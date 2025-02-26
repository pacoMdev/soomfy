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
        Schema::create('categories', function (Blueprint $table) {
            $table -> id();
            $table -> string('name');

            // Campo 'categoria_id': Relaciona una categoría con su categoría principal (padre).
            // Si el valor es NUll, esta categoría es una categoría principal.
            // Si contiene un ID, esta categoría es una subcategoría de la categoría cuyo ID corresponde con 'categoria_id'.
                        $table->unsignedBigInteger('categoria_id')->nullable();

            // Foreign Key: 'categoria_id' referencia al campo 'id' de la tabla 'categories'.
            // Esto asegura que 'categoria_id' siempre contenga un ID válido de una categoría existente.
            // onDelete('cascade'): Si una categoría principal es eliminada, también se eliminarán todas las subcategorías asociadas a ella automáticamente.
                        $table->foreign('categoria_id')->references('id')->on('categories')->onDelete('cascade');



            $table -> string('nameImg'); // Nombre de la imagen guardada
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
        Schema::dropIfExists('categories');
    }
};
