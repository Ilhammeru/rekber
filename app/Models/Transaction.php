<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Transaction.
 *
 * @property string      $payable_type
 * @property int|string  $payable_id
 * @property int         $wallet_id
 * @property string      $uuid
 * @property string      $type
 * @property string      $amount
 * @property int         $amountInt
 * @property string      $amountFloat
 * @property bool        $confirmed
 * @property array       $meta
 * @property Wallet      $payable
 * @property WalletModel $wallet
 *
 * @method int getKey()
 */
class Transaction extends Model
{
    public const TYPE_DEPOSIT = 'deposit';
    public const TYPE_DEBIT = 'debit';
    public const TYPE_CREDIT = 'credit';
    public const TYPE_WITHDRAW = 'withdraw';
    public const SUCCESS = 1;
    public const PENDING = 2;
    public const FAILED = 3;

    /**
     * @var string[]
     */
    protected $fillable = [
        'payable_type',
        'payable_id',
        'wallet_id',
        'description',
        'uuid',
        'type',
        'amount',
        'confirmed',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payable_id');
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(Charge::class, 'uuid', 'trx_id');
    }

    public function depositManual(): HasOne
    {
        return $this->hasOne(DepositManual::class, 'trx_id', 'uuid');
    }

    public function depositAutomatic(): HasOne
    {
        return $this->hasOne(AutomaticDeposit::class, 'trx_id', 'uuid');
    }

    // /**
    //  * @var array<string, string>
    //  */
    // protected $casts = [
    //     'wallet_id' => 'int',
    //     'confirmed' => 'bool',
    //     'meta' => 'json',
    // ];

    // public function getTable(): string
    // {
    //     if ((string) $this->table === '') {
    //         $this->table = config('wallet.transaction.table', 'transactions');
    //     }

    //     return parent::getTable();
    // }

    // public function payable(): MorphTo
    // {
    //     return $this->morphTo();
    // }

    // public function wallet(): BelongsTo
    // {
    //     return $this->belongsTo(config('wallet.wallet.model', WalletModel::class));
    // }

    // public function getAmountIntAttribute(): int
    // {
    //     return (int) $this->amount;
    // }

    // public function getAmountFloatAttribute(): string
    // {
    //     $math = app(MathServiceInterface::class);
    //     $decimalPlacesValue = app(CastServiceInterface::class)
    //         ->getWallet($this->wallet)
    //         ->decimal_places;
    //     $decimalPlaces = $math->powTen($decimalPlacesValue);

    //     return $math->div($this->amount, $decimalPlaces, $decimalPlacesValue);
    // }

    // public function setAmountFloatAttribute(float|int|string $amount): void
    // {
    //     $math = app(MathServiceInterface::class);
    //     $decimalPlacesValue = app(CastServiceInterface::class)
    //         ->getWallet($this->wallet)
    //         ->decimal_places;
    //     $decimalPlaces = $math->powTen($decimalPlacesValue);

    //     $this->amount = $math->round($math->mul($amount, $decimalPlaces));
    // }
}
