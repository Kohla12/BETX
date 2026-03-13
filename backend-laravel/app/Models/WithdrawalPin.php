<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WithdrawalPin extends Model
{
    protected $fillable = [
        'user_id',
        'pin_hash',
        'failed_attempts',
        'locked_until',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
    ];
}

