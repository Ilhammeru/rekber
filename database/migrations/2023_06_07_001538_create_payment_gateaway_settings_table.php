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
        Schema::create('payment_gateaway_settings', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->enum('type', ['manual', 'automatic']);
            $table->string('name');
            $table->json('configuration')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('payment_gateaway_details', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('payment_gateaway_setting_id');
            $table->string('currency', 10);
            $table->string('symbol', 3)->nullable();
            $table->float('rate', 64,2);
            $table->float('minimum_trx', 64,2);
            $table->float('maximum_trx', 64,2);
            $table->float('fixed_charge', 64,2);
            $table->tinyInteger('percent_charge');
            $table->text('deposit_instruction')->nullable();
            $table->json('user_field')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateaway_settings');
        Schema::dropIfExists('payment_gateaway_details');
    }
};
