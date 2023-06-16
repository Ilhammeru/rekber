<?php

namespace App\Models;

use App\Models\PaymentGateaway\PaymentGateawayDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutomaticDeposit extends Model
{
    use HasFactory;

    const SUCCESS = 1;
    const WAITING_PAYMENT = 2;
    const FAILED = 3;

    protected $fillable = [
        'trx_id',
        'payment_gateaway_id',
        'channel_code',
        'channel_name',
        'channel_detail',
        'user_id',
        'status',
        'amount',
        'request_transaction_response',
        'callback_response',
        'request_transaction_payload',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'trx_id', 'uuid');
    }

    public function gateaway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateawayDetail::class, 'payment_gateaway_id', 'id');
    }
}
