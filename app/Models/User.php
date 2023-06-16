<?php

namespace App\Models;

use App\Traits\HasTransaction;
use App\Traits\HasWallet;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasUuids, HasWallet, HasRoles, HasTransaction;

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
        'is_two_factor',
    ];

    public function fullname(): Attribute
    {
        $res = '-';
        if ($this->first_name) {
            $res = $this->first_name;

            if ($this->last_name) {
                $res = $res . ' ' . $this->last_name;
            }
        }
        return Attribute::make(
            get: fn() => $res,
        );
    }

    public function balance(): Attribute
    {
        return Attribute::make(
            get: fn() => number_format($this->currentBalance()) ?? 0,
        );
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function state():BelongsTo
    {
        return $this->belongsTo(Province::class, 'state_id', 'id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'city_id', 'id');
    }

    public function ban(): HasOne
    {
        return $this->hasOne(UserBan::class);
    }

    public function depositManual(): HasMany
    {
        return $this->hasMany(DepositManual::class, 'user_id');
    }

}
