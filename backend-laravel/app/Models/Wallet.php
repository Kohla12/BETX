<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    public const TYPE_BETTING = 'betting';
    public const TYPE_BONUS = 'bonus';

    protected $fillable = [
        'user_id',
        'type',
        'balance',
        'currency',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

