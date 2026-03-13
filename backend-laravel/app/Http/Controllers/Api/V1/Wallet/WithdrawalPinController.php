<?php

namespace App\Http\Controllers\Api\V1\Wallet;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalPin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WithdrawalPinController extends Controller
{
    public function setPin(Request $request)
    {
        $data = $request->validate([
            'pin' => 'required|string|min:4|max:6',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $pin = WithdrawalPin::updateOrCreate(
            ['user_id' => $user->id],
            [
                'pin_hash' => Hash::make($data['pin']),
                'failed_attempts' => 0,
                'locked_until' => null,
            ]
        );

        return response()->json([
            'message' => 'Withdrawal PIN set.',
        ]);
    }

    public function verifyPin(Request $request)
    {
        $data = $request->validate([
            'pin' => 'required|string',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $pin = WithdrawalPin::where('user_id', $user->id)->first();

        if (!$pin) {
            return response()->json(['message' => 'PIN not set.'], 404);
        }

        if ($pin->locked_until && now()->lessThan($pin->locked_until)) {
            return response()->json(['message' => 'PIN is locked. Try again later.'], 423);
        }

        if (!Hash::check($data['pin'], $pin->pin_hash)) {
            $pin->failed_attempts += 1;
            if ($pin->failed_attempts >= 5) {
                $pin->locked_until = now()->addMinutes(15);
            }
            $pin->save();

            return response()->json(['message' => 'Invalid PIN.'], 422);
        }

        $pin->failed_attempts = 0;
        $pin->locked_until = null;
        $pin->save();

        return response()->json(['message' => 'PIN verified.']);
    }
}

