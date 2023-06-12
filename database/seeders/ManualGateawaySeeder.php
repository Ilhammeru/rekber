<?php

namespace Database\Seeders;

use App\Models\PaymentGateaway\PaymentGateawayDetail;
use App\Models\PaymentGateaway\PaymentGateawaySetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManualGateawaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentGateawaySetting::truncate();
        PaymentGateawayDetail::truncate();

        $bni = PaymentGateawaySetting::create([
            'name' => 'Transfer BNI',
            'type' => PaymentGateawaySetting::MANUAL_TYPE
        ]);

        $bni_detail = [
            'payment_gateaway_setting_id' => $bni->id,
            'currency' => 'IDR',
            'symbol' => 'Rp',
            'rate' => 1,
            'minimum_trx' => 5000,
            'maximum_trx' => 5000000,
            'fixed_charge' => 200,
            'percent_charge' => 10,
            'deposit_instruction' => '<p><bold>Silahkan transfer nominal yang diinginkan ke nomor rekening: </bold></p><br/><p>Atas Nama PT. Rekber: 000-000-000-000-000</p>',
            'user_field' => json_encode([
                [
                    'type' => 'file',
                    'label' => 'Bukti Transfer',
                    'is_required' => true,
                ],
                [
                    'type' => 'text',
                    'label' => 'Nama Pengirim',
                    'is_required' => false,
                ],
            ]),
        ];

        PaymentGateawayDetail::create($bni_detail);
    }
}
