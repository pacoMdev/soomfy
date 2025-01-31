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
        Schema::create('post', function(Blueprint $table){
            $table -> id();
            $table -> string('title');
            $table -> string('description');
            $table -> double('price');
            $table -> string('estado');
            $table -> string('location');
            $table -> boolean('toSend');
            $table -> boolean('isUpdated');
            $table -> string('category_id');
            $table -> foreign('category_id')->references('category_id')->on('category');
            $table -> timestamps();
        });

        Schema::create('category', function(Blueprint $table){
            $table->id('category_id');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
        
        Schema::create('usersOpinion', function(Blueprint $table){
            $table->id();
            $table->string('title');
            $table->string('destription');
            $table->integer('calification');
            $table->foreign('post_id')->references('id')->on('post');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timespamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post');
        Schema::dropIfExists('category');
        Schema::dropIfExists('userOpinion');
    }
};
