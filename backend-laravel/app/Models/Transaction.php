<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_WITHDRAWAL = 'withdrawal';
    public const TYPE_BET_STAKE = 'bet_stake';
    public const TYPE_PAYOUT = 'payout';
    public const TYPE_BONUS_CREDIT = 'bonus_credit';
    public const TYPE_ADJUSTMENT = 'adjustment';
    public const TYPE_CASHOUT = 'cashout';

    protected $fillable = [
        'wallet_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'reference',
        'external_reference',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}

