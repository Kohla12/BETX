<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'email' => 'required_without:phone|email|unique:users,email',
            'phone' => 'required_without:email|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'country_code' => 'required|string|max:4',
            'device_fingerprint' => 'required|string|max:255',
        ]);

        $user = User::create([
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'country_code' => $data['country_code'],
            'status' => User::STATUS_PENDING_VERIFICATION,
        ]);

        // TODO: generate OTP and send via SMS/Email, store in cache or DB

        return response()->json([
            'message' => 'Verification code sent',
            'verification_token' => base64_encode($user->id . '|' . now()->timestamp),
        ], 201);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'verification_token' => 'required|string',
            'otp_code' => 'required|string',
            'device_fingerprint' => 'required|string',
        ]);

        // TODO: lookup OTP and validate

        // Simplified: decode user ID from token (DO NOT use in production without signing)
        $decoded = base64_decode($request->input('verification_token'), true);
        [$userId] = explode('|', $decoded);

        /** @var \App\Models\User $user */
        $user = User::findOrFail($userId);
        $user->status = User::STATUS_ACTIVE;
        $user->save();

        UserDevice::firstOrCreate(
            [
                'user_id' => $user->id,
                'device_fingerprint' => $request->input('device_fingerprint'),
            ],
            [
                'trusted' => true,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'device_type' => 'web',
            ]
        );

        // TODO: issue JWT access/refresh tokens

        return response()->json([
            'message' => 'Account verified',
            'user' => $user,
            'tokens' => [
                'access' => 'ACCESS_TOKEN_PLACEHOLDER',
                'refresh' => 'REFRESH_TOKEN_PLACEHOLDER',
            ],
        ]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'sometimes|email',
            'phone' => 'sometimes|string',
            'password' => 'required|string',
            'device_fingerprint' => 'required|string',
        ]);

        $userQuery = User::query();

        if (!empty($data['email'])) {
            $userQuery->where('email', $data['email']);
        } elseif (!empty($data['phone'])) {
            $userQuery->where('phone', $data['phone']);
        } else {
            throw ValidationException::withMessages([
                'identifier' => ['Email or phone is required.'],
            ]);
        }

        /** @var \App\Models\User|null $user */
        $user = $userQuery->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['The provided credentials are incorrect.'],
            ]);
        }

        // TODO: IP monitoring, device trust, 2FA challenge

        UserDevice::updateOrCreate(
            [
                'user_id' => $user->id,
                'device_fingerprint' => $data['device_fingerprint'],
            ],
            [
                'trusted' => true,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'device_type' => 'web',
            ]
        );

        // TODO: issue real JWT tokens

        return response()->json([
            'user' => $user,
            'tokens' => [
                'access' => 'ACCESS_TOKEN_PLACEHOLDER',
                'refresh' => 'REFRESH_TOKEN_PLACEHOLDER',
            ],
        ]);
    }

    public function refresh(Request $request)
    {
        // TODO: implement refresh token rotation with blacklist
        return response()->json([
            'access' => 'NEW_ACCESS_TOKEN_PLACEHOLDER',
        ]);
    }

    public function logout(Request $request)
    {
        // TODO: revoke JWT (store jti in blacklist)
        return response()->json([
            'message' => 'Logged out',
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}

