<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuids;

    const ACTIVE = 1;
    const BANNED = 2;
    const INACTIVE = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'postalcode',
        'email_otp',
        'phone_otp',
        'total_saldo',
        'email_verified_at',
        'phone_verified_at',
        'kyc_verified_at',
        'status',
        'last_login_at',
        'deleted_at',
    ];

}
