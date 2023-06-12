<?php

namespace App\Models\PaymentGateaway;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PaymentGateawaySetting extends Model
{
    use HasFactory, HasUuids;

    const MANUAL_TYPE = 'manual';
    const AUTO_TYPE = 'automatic';
    const TEXT_TYPE = 1;
    const FILE_TYPE = 2;
    const TEXTAREA_TYPE = 3;

    protected $fillable = ['name', 'type', 'configuration', 'status', 'is_have_channel', 'channel'];

    public static function getAllType()
    {
        return [
            self::TEXT_TYPE => 'Text',
            self::FILE_TYPE => 'File',
            self::TEXTAREA_TYPE => 'Textarea',
        ];
    }

    public function details(): HasMany
    {
        return $this->hasMany(PaymentGateawayDetail::class, 'payment_gateaway_setting_id', 'id');
    }

    public function detail(): HasOne
    {
        return $this->hasOne(PaymentGateawayDetail::class, 'payment_gateaway_setting_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
