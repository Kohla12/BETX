<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BetSlip extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_WON = 'won';
    public const STATUS_LOST = 'lost';
    public const STATUS_CASHED_OUT = 'cashed_out';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'user_id',
        'stake',
        'potential_payout',
        'total_odds',
        'type',
        'status',
        'cashout_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }
}

