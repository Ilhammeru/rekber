<?php

namespace App\Models\PaymentGateaway;

use App\Models\Charge;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentGateawayDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'payment_gateaway_details';

    protected $fillable = [
        'name',
        'payment_gateaway_setting_id',
        'currency',
        'symbol',
        'rate',
        'minimum_trx',
        'maximum_trx',
        'fixed_charge',
        'percent_charge',
        'deposit_instruction',
        'user_field',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(PaymentGateawaySetting::class, 'payment_gateaway_setting_id');
    }

    public function charge(): HasOne
    {
        return $this->hasOne(Charge::class, 'payment_gateaway_id', 'id');
    }
}
