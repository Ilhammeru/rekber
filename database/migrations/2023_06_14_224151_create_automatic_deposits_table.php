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
        Schema::create('automatic_deposits', function (Blueprint $table) {
            $table->id();
            $table->uuid('trx_id');
            $table->uuid('payment_gateaway_id');
            $table->string('channel_code');
            $table->string('channel_name')->nullable();
            $table->json('channel_detail')->nullable();
            $table->uuid('user_id');
            $table->tinyInteger('status')
                ->comment('1 for success, 2 for waiting payment, 3 for failed');
            $table->float('amount');
            $table->json('request_transaction_response')->nullable();
            $table->json('callback_response')->nullable();
            $table->json('request_transaction_payload')->nullable();
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
        Schema::dropIfExists('automatic_deposits');
    }
};
