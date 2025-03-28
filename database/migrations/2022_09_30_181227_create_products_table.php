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
            $table -> boolean('toSend') -> nullable();
            $table -> boolean('isDeleted') -> nullable();
            $table -> boolean('isBoost') -> nullable();
            $table -> integer('dimensionX') -> nullable();
            $table -> integer('dimensionY') -> nullable();
            $table -> string('marca') -> nullable();
            $table -> string('color') -> nullable();

            // AGREGADO - Agregar el campo user_id como clave foránea
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            // AGREGADO - Agregar el campo category_id como clave foránea
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')->on('categories')
                ->onDelete('cascade');
            // AGREGADO - Agregar el campo estado_id como clave foránea
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->foreign('estado_id')
                ->references('id')->on('estados')
                ->onDelete('cascade');

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
        Schema::dropIfExists('products');
    }
};
