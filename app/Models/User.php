<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use  HasRoles;
    public const USER_TYPE_GENERAL_USER          = 'gu';
    public const USER_TYPE_SUPPLY_CHAIN_INCHARGE = 'sci';
    public const USER_TYPE_OPERATION_MANAGER     = 'om';
    public const USER_TYPE_MD                    = 'md';

    public const USER_TYPES = [
        self::USER_TYPE_GENERAL_USER          => 'General User',
        self::USER_TYPE_SUPPLY_CHAIN_INCHARGE => 'Supply Chain Incharge',
        self::USER_TYPE_OPERATION_MANAGER     => 'Operation Manager',
        self::USER_TYPE_MD                    => 'MD',
    ];
    protected $guarded = [];

    public function wings()
    {
        return $this->belongsToMany(Wing::class, 'user_wings')->withTimestamps();
    }

    /**
     * Human readable label for the user_type column.
     */
    public function getUserTypeLabelAttribute(): string
    {
        return self::USER_TYPES[$this->user_type] ?? '-';
    }
}
