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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary()->unique();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->text('password');
            $table->text('address')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('postalcode')->nullable();
            $table->smallInteger('email_otp')->nullable();
            $table->smallInteger('phone_otp')->nullable();
            $table->json('total_saldo')->comment('json type with format: {_type_: _saldo, _type_: _saldo_}')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('kyc_verified_at')->nullable();
            $table->tinyInteger('status')->comment('1 for active, 2 for banned, 3 for unactive')->default(1);
            $table->timestamp('last_login_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
};
