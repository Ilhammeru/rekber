<?php

namespace App\Models;

use App\Models\PaymentGateaway\PaymentGateawayDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charge extends Model
{
    use HasFactory;

    protected $fillable = [
        'trx_id',
        'payment_gateaway_id',
        'fixed_charge',
        'percent_charge',
        'total_charge',
    ];

    public function gateaway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateawayDetail::class, 'payment_gateaway_id');
    }
}
