<?php

namespace App\Models;

use App\Models\PaymentGateaway\PaymentGateawayDetail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepositManual extends Model
{
    use HasFactory, HasUuids;

    const APPROVE = 1;
    const PENDING = 0;
    const WAITING_UPLOAD = 2;
    const DECLINE = 3;

    protected $fillable = [
        'user_id',
        'trx_id',
        'amount',
        'payment_gateaway_id',
        'status',
        'field_value',
        'reason'
    ];

    public function gateaway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateawayDetail::class, 'payment_gateaway_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'trx_id', 'uuid');
    }
}
