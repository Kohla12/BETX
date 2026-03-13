<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public const STATUS_PENDING_VERIFICATION = 'pending_verification';
    public const STATUS_ACTIVE = 'active';
    public const STATUS_SUSPENDED = 'suspended';
    public const STATUS_BANNED = 'banned';

    protected $fillable = [
        'email',
        'phone',
        'password',
        'first_name',
        'last_name',
        'country_code',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function wallets()
    {
        return $this->hasMany(Wallet::class);
    }

    public function betSlips()
    {
        return $this->hasMany(BetSlip::class);
    }
}

