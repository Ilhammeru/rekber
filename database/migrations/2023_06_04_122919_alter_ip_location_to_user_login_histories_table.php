<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_login_histories', function (Blueprint $table) {
            $table->string('ip_address')->nullable();
            $table->string('location')->nullable();
            $table->string('device')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_login_histories', function (Blueprint $table) {
            $table->dropColumn('ip_address');
            $table->dropColumn('location');
            $table->dropColumn('device');
        });
    }
};
