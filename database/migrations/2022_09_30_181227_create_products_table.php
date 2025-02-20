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
        Schema::create('products', function(Blueprint $table){
            $table -> id();
            $table -> string('title');
            $table -> string('content')-> nullable();
            $table -> double('price') -> nullable();
            $table -> string('estado') -> nullable();
            $table -> boolean('toSend') -> nullable();
            $table -> boolean('isDeleted') -> nullable();
            $table -> boolean('isBoost') -> nullable();
            $table -> integer('dimensionX') -> nullable();
            $table -> integer('dimensionY') -> nullable();
            $table -> string('marca') -> nullable();
            $table -> string('color') -> nullable();
            

            $table -> timestamps();
        });
    
        
        Schema::create('usersOpinion', function(Blueprint $table){
            $table -> id();
            $table -> string('title');
            $table -> string('destription');
            $table -> integer('calification'); // dese 1 - 5
            $table -> unsignedBigInteger('product_id');
            $table -> unsignedBigInteger('user_id');
            $table -> foreign('product_id') -> references('id') -> on('products');
            $table -> foreign('user_id') -> references('id') -> on('users');
            $table -> timestamps();
    
            
        });
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
            $table -> timestamps();
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
        Schema::dropIfExists('usersOpinion');
        Schema::dropIfExists('transactions');
    }
};
