<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::truncate();

        Category::insert([
            ['name' => 'Real Estate', 'created_at' => now_time(), 'updated_at' => now_time(), 'id' => uuid_create()],
            ['name' => 'Auction', 'created_at' => now_time(), 'updated_at' => now_time(), 'id' => uuid_create()],
            ['name' => 'Buying and Selling Car', 'created_at' => now_time(), 'updated_at' => now_time(), 'id' => uuid_create()],
            ['name' => 'Domain and hosting', 'created_at' => now_time(), 'updated_at' => now_time(), 'id' => uuid_create()],
            ['name' => 'Vendor third party', 'created_at' => now_time(), 'updated_at' => now_time(), 'id' => uuid_create()],
        ]);
    }
}
