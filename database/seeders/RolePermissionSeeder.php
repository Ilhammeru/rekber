<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('cache:forget spatie.permission.cache');

        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        $update_payment_gateaway = Permission::create(['name' => 'Update Payment Gateaway']);
        $admin->givePermissionTo($update_payment_gateaway);

        $do_deposit = Permission::create(['name' => 'Do Deposit']);
        $do_withdrawal = Permission::create(['name' => 'Do Withdrawals']);
        $create_escrow = Permission::create(['name' => 'Create Escrow']);
        $delete_escrow = Permission::create(['name' => 'Delete Escrow']);
        $user->givePermissionTo($do_deposit);
        $user->givePermissionTo($do_withdrawal);
        $user->givePermissionTo($create_escrow);
        $user->givePermissionTo($delete_escrow);
    }
}
