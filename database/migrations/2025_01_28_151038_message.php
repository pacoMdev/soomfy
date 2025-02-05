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
        Schema::create('message', function(Blueprint $table){
            $table->id();
            $table->string('fullMessage');
            $table->unsignedBigInteger('userDestination_id');
            $table->unsignedBigInteger('userRemitent_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('userDestination_id')->references('id')->on('users');
            $table->foreign('userRemitent_id')->references('id')->on('users');
            $table->foreign('post_id')->references('id')->on('post');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message');
    }
};
