<?php

namespace Database\Seeders;

use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::truncate();

        $list = \Monarobase\CountryList\CountryListFacade::getList('en', 'php');
        $data = [];
        foreach ($list as $code => $l) {
            $data[] = [
                'code' => $code,
                'name' => $l,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        Country::insert($data);

        // get indonesia data
        $indo = \Monarobase\CountryList\CountryListFacade::getOne('ID', 'en');
        $indo = Country::select("id")
            ->where('name', $indo)
            ->first();

        if ($indo) {
            // update province table
            DB::table('provinces')
                ->where('id', '>', 0)
                ->update([
                    'country_id' => $indo->id,
                ]);
        }
    }
}
