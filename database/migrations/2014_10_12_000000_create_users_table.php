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
            $table->id();
            $table->string('name');
            $table->string('surname1')->nullable();
            $table->string('surname2')->nullable();
            $table->string('alias')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table -> decimal('latitude', 10, 8) -> nullable();
            $table -> decimal('longitude', 11, 8) -> nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('imgProfile')->nullable();
            $table->string('imgBanner')->nullable()->default('default.webp');
            $table->string('location')->nullable();
            $table->string('google_id')->nullable();
            $table->rememberToken();
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
