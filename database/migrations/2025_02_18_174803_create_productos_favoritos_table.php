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
        Schema::create('productos_favoritos', function (Blueprint $table) {
            $table->id();
            // Clave foránea para 'user_id'
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Clave foránea para 'product_id'
            $table->foreignId('product_id')->constrained('seed_products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos_favoritos');
    }
};
