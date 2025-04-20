<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function(Blueprint $table){
            $table -> id();
            $table -> unsignedBigInteger('userSeller_id');
            $table -> unsignedBigInteger('userBuyer_id');
            $table -> unsignedBigInteger('product_id');
            $table -> foreign('userSeller_id') -> references('id') -> on('users');
            $table -> foreign('userBuyer_id') -> references('id') -> on('users');
            $table -> foreign('product_id') -> references('id') -> on('products');
            $table -> double('initialPrice');
            $table -> double('finalPrice');
            $table -> boolean('isToSend') -> default(0);
            $table -> boolean('isRegated') -> default(0);
            $table -> string('session_id') -> nullable();
            $table -> enum('delivery_type', ['in_person', 'home_delivery', 'pickup_point'])->default('in_person');  // in_person || home_delivery || pickup_point
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
