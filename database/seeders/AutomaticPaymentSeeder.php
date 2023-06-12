<?php

namespace Database\Seeders;

use App\Models\PaymentGateaway\PaymentGateawayDetail;
use App\Models\PaymentGateaway\PaymentGateawaySetting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AutomaticPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {
            $model = PaymentGateawaySetting::create([
                'name' => 'Tripay',
                'type' => PaymentGateawaySetting::AUTO_TYPE,
                'status' => true,
                'is_have_channel' => true,
                'configuration' => json_encode([
                    [
                        'type' => 'text',
                        'label' => 'Api Key',
                        'slug' => 'api_key',
                        'value' => '',
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Callback Url',
                        'slug' => 'callback_url',
                        'value' => 'https://rekber.doordash.my.id/tripay',
                    ],
                    [
                        'type' => 'text',
                        'label' => 'Secret Key',
                        'slug' => 'secret_key',
                        'value' => '',
                    ],
                ]),
            ]);

            $payload_currency = [
                [
                    'payment_gateaway_setting_id' => $model->id,
                    'name' => 'Tripay USD',
                    'minimum_trx' => 10000,
                    'maximum_trx' => 2000000,
                    'fixed_charge' => 2000,
                    'percent_charge' => 2,
                    'currency' => 'USD',
                    'symbol' => '$',
                    'rate' => 14500,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
                [
                    'payment_gateaway_setting_id' => $model->id,
                    'name' => 'Tripay IDR',
                    'minimum_trx' => 10000,
                    'maximum_trx' => 2000000,
                    'fixed_charge' => 2000,
                    'percent_charge' => 2,
                    'currency' => 'IDR',
                    'symbol' => 'Rp',
                    'rate' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ],
            ];
            foreach ($payload_currency as $payload) {
                PaymentGateawayDetail::create($payload);
            }
            DB::commit();

            $this->command->info('success run automatic gateaway seeder');

        } catch (\Throwable $th) {
            DB::rollBack();

            $this->command->info('Error running automatic gateaway seeder: ');
            $this->command->error($th->getMessage() . $th->getLine() . $th->getFile());
        }
    }
}
