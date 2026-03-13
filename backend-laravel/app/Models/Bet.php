<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    public const RESULT_PENDING = 'pending';
    public const RESULT_WON = 'won';
    public const RESULT_LOST = 'lost';
    public const RESULT_VOID = 'void';

    protected $fillable = [
        'bet_slip_id',
        'match_id',
        'market_id',
        'odd_id',
        'selection',
        'odds_at_placement',
        'result',
        'settled_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'settled_at' => 'datetime',
    ];

    public function betSlip()
    {
        return $this->belongsTo(BetSlip::class);
    }

    public function match()
    {
        return $this->belongsTo(MatchModel::class, 'match_id');
    }
}

