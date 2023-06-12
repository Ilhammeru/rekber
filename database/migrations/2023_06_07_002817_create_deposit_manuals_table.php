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
        Schema::create('deposit_manuals', function (Blueprint $table) {
            $table->uuid('id')->unique()->primary();
            $table->uuid('trx_id')->unique();
            $table->uuid('user_id');
            $table->decimal('amount', 64, 0);
            $table->uuid('payment_gateaway_id');
            $table->boolean('status')->default(false);
            $table->json('field_value')->nullable();
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
        Schema::dropIfExists('deposit_manuals');
    }
};
