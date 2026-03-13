<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\AuthController;
use App\Http\Controllers\Api\V1\Auth\TwoFactorController;
use App\Http\Controllers\Api\V1\Wallet\WalletController;
use App\Http\Controllers\Api\V1\Wallet\WithdrawalPinController;
use App\Http\Controllers\Api\V1\Betting\BetSlipController;
use App\Http\Controllers\Api\V1\Sports\SportsController;
use App\Http\Controllers\Api\V1\User\NotificationController;

Route::prefix('v1')->group(function () {
    // Authentication
    Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');

        Route::post('2fa/enable', [TwoFactorController::class, 'enable'])->middleware('auth:api');
        Route::post('2fa/verify', [TwoFactorController::class, 'verify']);
    });

    // User profile & notifications
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);

        Route::get('notifications', [NotificationController::class, 'index']);
        Route::post('notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
    });

    // Wallets
    Route::prefix('wallet')->middleware('auth:api')->group(function () {
        Route::get('/', [WalletController::class, 'index']);
        Route::get('/transactions', [WalletController::class, 'transactions']);

        Route::post('/deposit/initiate', [WalletController::class, 'initiateDeposit']);
        Route::post('/withdraw', [WalletController::class, 'withdraw']);

        Route::post('/pin/set', [WithdrawalPinController::class, 'setPin']);
        Route::post('/pin/verify', [WithdrawalPinController::class, 'verifyPin']);
    });

    // Sports & matches
    Route::prefix('sports')->group(function () {
        Route::get('/', [SportsController::class, 'sports']);
        Route::get('/leagues', [SportsController::class, 'leagues']);
        Route::get('/leagues/{league}/matches', [SportsController::class, 'leagueMatches']);
        Route::get('/matches/live', [SportsController::class, 'liveMatches']);
        Route::get('/matches/{match}', [SportsController::class, 'showMatch']);
        Route::get('/matches/{match}/markets', [SportsController::class, 'matchMarkets']);
    });

    // Betting
    Route::prefix('betting')->middleware('auth:api')->group(function () {
        Route::get('bet-slips', [BetSlipController::class, 'index']);
        Route::post('bet-slips', [BetSlipController::class, 'store']);
        Route::get('bet-slips/{betSlip}', [BetSlipController::class, 'show']);
        Route::get('bet-slips/{betSlip}/cashout-offer', [BetSlipController::class, 'cashoutOffer']);
        Route::post('bet-slips/{betSlip}/cashout', [BetSlipController::class, 'cashout']);
    });
});

