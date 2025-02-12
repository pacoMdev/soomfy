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
        Schema::create('posts', function(Blueprint $table){
            $table -> id();
            $table -> string('title');
            $table -> string('description');
            $table -> double('price');
            $table -> string('estado');
            $table -> decimal('latitude', 10, 8) -> nullable();
            $table -> decimal('longitude', 11, 8) -> nullable();
            $table -> boolean('toSend');
            $table -> boolean('isDeleted');
            $table -> boolean('isBoost');
            $table -> integer('dimensionX');
            $table -> integer('dimensionY');
            $table -> string('marca');
            $table -> string('color');
            $table -> timestamps();
        });
    
        
        Schema::create('usersOpinion', function(Blueprint $table){
            $table -> id();
            $table -> string('title');
            $table -> string('destription');
            $table -> integer('calification'); // dese 1 - 5
            $table -> unsignedBigInteger('post_id');
            $table -> unsignedBigInteger('user_id');
            $table -> foreign('post_id') -> references('id') -> on('posts');
            $table -> foreign('user_id') -> references('id') -> on('users');
            $table -> timestamps();
    
            
        });
        Schema::create('transactions', function(Blueprint $table){
            $table -> id();
            $table -> unsignedBigInteger('userSeller_id');
            $table -> unsignedBigInteger('userBuyer_id');
            $table -> unsignedBigInteger('post_id');
            $table -> foreign('userSeller_id') -> references('id') -> on('users');
            $table -> foreign('userBuyer_id') -> references('id') -> on('users');
            $table -> foreign('post_id') -> references('id') -> on('posts');
            $table -> double('initialPrice');
            $table -> double('finalPrice');
            $table -> boolean('isToSend') -> default(0);
            $table -> boolean('isRegated') -> default(0);
            $table -> timestamps();
        });
        Schema::create('post_image', function(Blueprint $table){
            $table -> id();
            $table -> string('title');
            $table -> unsignedBigInteger('post_id');
            $table -> foreign('post_id') -> references('id') -> on('posts');
            $table ->timestamps();
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
        Schema::dropIfExists('post_image');
    }
};
