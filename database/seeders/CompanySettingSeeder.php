<?php

namespace Database\Seeders;

use App\Enums\Setting as EnumsSetting;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Setting::insert([
            [
                'id' => uuid_create(),
                'key' => 'company_name',
                'value' => 'PT Rekber',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'company_address',
                'value' => 'Jl. Malabar no 19A',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => uuid_create(),
                'key' => 'app_name',
                'value' => 'Rekber App',
                'type' => EnumsSetting::General,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
