<?php

namespace App\Http\Controllers\Api\V1\Betting;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetSlip;
use App\Models\MatchModel;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BetSlipController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $slips = BetSlip::where('user_id', $user->id)
            ->with('bets')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return response()->json($slips);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'stake' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'selections' => 'required|array|min:1',
            'selections.*.match_id' => 'required|integer|exists:matches,id',
            'selections.*.odds' => 'required|numeric|min:1',
            'selections.*.selection' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        return DB::transaction(function () use ($user, $data) {
            $wallet = $user->wallets()
                ->where('type', Wallet::TYPE_BETTING)
                ->lockForUpdate()
                ->firstOrFail();

            if ($wallet->balance < $data['stake']) {
                return response()->json(['message' => 'Insufficient balance.'], 422);
            }

            $totalOdds = 1.0;
            foreach ($data['selections'] as $sel) {
                $totalOdds *= $sel['odds'];
            }

            $potentialPayout = $data['stake'] * $totalOdds;

            $before = $wallet->balance;
            $after = $before - $data['stake'];
            $wallet->balance = $after;
            $wallet->save();

            $slip = BetSlip::create([
                'user_id' => $user->id,
                'stake' => $data['stake'],
                'potential_payout' => $potentialPayout,
                'total_odds' => $totalOdds,
                'type' => count($data['selections']) > 1 ? 'multi' : 'single',
                'status' => BetSlip::STATUS_PENDING,
            ]);

            foreach ($data['selections'] as $sel) {
                Bet::create([
                    'bet_slip_id' => $slip->id,
                    'match_id' => $sel['match_id'],
                    'market_id' => null,
                    'odd_id' => null,
                    'selection' => $sel['selection'],
                    'odds_at_placement' => $sel['odds'],
                    'result' => Bet::RESULT_PENDING,
                ]);
            }

            Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => Transaction::TYPE_BET_STAKE,
                'amount' => $data['stake'],
                'balance_before' => $before,
                'balance_after' => $after,
                'reference' => 'BET-' . $slip->id,
                'status' => 'completed',
            ]);

            // TODO: publish bet_placed event to Redis/WebSocket

            return response()->json($slip->load('bets'), 201);
        });
    }

    public function show(BetSlip $betSlip, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        abort_unless($betSlip->user_id === $user->id, 403);

        return response()->json($betSlip->load('bets'));
    }

    public function cashoutOffer(BetSlip $betSlip, Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        abort_unless($betSlip->user_id === $user->id, 403);

        // Simplified: cashout offer as fraction of potential payout
        $progressFactor = 0.5;

        $offer = $betSlip->potential_payout * $progressFactor;

        return response()->json([
            'cashout_offer' => $offer,
            'expires_at' => now()->addSeconds(30),
        ]);
    }

    public function cashout(BetSlip $betSlip, Request $request)
    {
        $data = $request->validate([
            'offer_amount' => 'required|numeric|min:0',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        abort_unless($betSlip->user_id === $user->id, 403);

        if ($betSlip->status !== BetSlip::STATUS_PENDING) {
            return response()->json(['message' => 'Cashout not available.'], 422);
        }

        return DB::transaction(function () use ($user, $betSlip, $data) {
            $wallet = $user->wallets()
                ->where('type', Wallet::TYPE_BETTING)
                ->lockForUpdate()
                ->firstOrFail();

            $before = $wallet->balance;
            $after = $before + $data['offer_amount'];
            $wallet->balance = $after;
            $wallet->save();

            $betSlip->status = BetSlip::STATUS_CASHED_OUT;
            $betSlip->cashout_amount = $data['offer_amount'];
            $betSlip->save();

            Transaction::create([
                'wallet_id' => $wallet->id,
                'type' => Transaction::TYPE_CASHOUT,
                'amount' => $data['offer_amount'],
                'balance_before' => $before,
                'balance_after' => $after,
                'reference' => 'CASHOUT-' . $betSlip->id,
                'status' => 'completed',
            ]);

            // TODO: publish bet_cashed_out event

            return response()->json([
                'wallet' => $wallet,
                'bet_slip' => $betSlip->load('bets'),
            ]);
        });
    }
}

