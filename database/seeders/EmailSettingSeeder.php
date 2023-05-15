<?php

namespace Database\Seeders;

use App\Enums\Setting as EnumsSetting;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => uuid_create(),
                'key' => 'email_host',
                'value' => 'sandbox.smtp.mailtrap.io',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'email_port',
                'value' => '465',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'email_encryption',
                'value' => 'TLS',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'email_username',
                'value' => env('MAIL_USERNAME'),
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'email_password',
                'value' => env('MAIL_PASSWORD'),
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'email_sender_name',
                'value' => 'Rekber Admin',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'email_sender_email',
                'value' => 'admin@rekber.id',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        Setting::insert($data);
    }
}
