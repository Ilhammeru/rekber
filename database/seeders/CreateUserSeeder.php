<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class CreateUserSeeder extends Seeder
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
            $userRole = Role::findByName('user');

            $buyer = new User();
            $buyer->id = uuid_create();
            $buyer->username = 'Roy';
            $buyer->email = 'roy@gmail.com';
            $buyer->password = Hash::make('Ilham..123');
            $buyer->first_name = 'Roy';
            $buyer->last_name = 'Suryo';
            $buyer->phone = '6285795327333';
            $buyer->save();
            $buyer->assignRole($userRole);

            $seller = new User();
            $seller->id = uuid_create();
            $seller->username = 'yono';
            $seller->email = 'yono@gmail.com';
            $seller->password = Hash::make('Ilham..123');
            $seller->first_name = 'Yono';
            $seller->last_name = 'Bakrie';
            $seller->phone = '628976076029';
            $seller->save();
            $seller->assignRole($userRole);

            $general = new User();
            $general->id = uuid_create();
            $general->username = 'rany';
            $general->email = 'rany@gmail.com';
            $general->password = Hash::make('Ilham..123');
            $general->first_name = 'Rany';
            $general->last_name = 'Desy';
            $general->phone = '6289760760999';
            $general->save();
            $general->assignRole($userRole);

            DB::commit();

            $this->command->info('Success');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->command->info($th->getMessage());
        }
    }
}
