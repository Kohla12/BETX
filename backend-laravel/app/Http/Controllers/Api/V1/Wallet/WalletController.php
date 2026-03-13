<?php

namespace App\Http\Controllers\Api\V1\Wallet;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $wallets = $user->wallets()->get();

        return response()->json($wallets);
    }

    public function transactions(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $transactions = Transaction::query()
            ->whereIn('wallet_id', $user->wallets()->pluck('id'))
            ->orderByDesc('created_at')
            ->limit(200)
            ->get();

        return response()->json($transactions);
    }

    public function initiateDeposit(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'channel' => 'required|string', // e.g. momo, card
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $wallet = $user->wallets()
            ->where('type', Wallet::TYPE_BETTING)
            ->firstOrCreate([
                'type' => Wallet::TYPE_BETTING,
                'currency' => strtoupper($data['currency']),
            ], [
                'balance' => 0,
            ]);

        $reference = 'DEP-' . now()->format('YmdHis') . '-' . $user->id;

        $transaction = Transaction::create([
            'wallet_id' => $wallet->id,
            'type' => Transaction::TYPE_DEPOSIT,
            'amount' => $data['amount'],
            'balance_before' => $wallet->balance,
            'balance_after' => $wallet->balance,
            'reference' => $reference,
            'status' => 'pending',
            'meta' => [
                'channel' => $data['channel'],
            ],
        ]);

        // TODO: hand off to PaymentService to initiate real payment

        return response()->json([
            'transaction' => $transaction,
            'payment' => [
                'redirect_url' => null,
                'instructions' => 'Use the reference to complete payment via the configured gateway.',
            ],
        ], 201);
    }

    public function withdraw(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'channel' => 'required|string',
            'withdrawal_pin' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        // TODO: validate withdrawal PIN securely

        return DB::transaction(function () use ($user, $data) {
            $wallet = $user->wallets()
                ->where('type', Wallet::TYPE_BETTING)
                ->lockForUpdate()
                ->firstOrFail();

            if ($wallet->balance < $data['amount']) {
                return response()->json(['message' => 'Insufficient balance'], 422);
            }

            $before = $wallet->balance;
            $after = $before - $data['amount'];
            $wallet->balance = $after;
            $wallet->save();

            $reference = 'WD-' . now()->format('YmdHis') . '-' . $user->id;

            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => Transaction::TYPE_WITHDRAWAL,
                'amount' => $data['amount'],
                'balance_before' => $before,
                'balance_after' => $after,
                'reference' => $reference,
                'status' => 'pending', // will be updated by payout webhook/admin
                'meta' => [
                    'channel' => $data['channel'],
                ],
            ]);

            return response()->json([
                'wallet' => $wallet,
                'transaction' => $transaction,
            ]);
        });
    }
}

