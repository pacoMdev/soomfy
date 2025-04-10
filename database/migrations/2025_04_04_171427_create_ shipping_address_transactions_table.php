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
        Schema::create('shipping_address_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transactions_id');
            $table->unsignedBigInteger('shipping_address_id');
            $table->foreign('transactions_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_address')->onDelete('cascade');

            $table->string('status')->default('Pendiente'); // "Pendiente", "Enviado", "Entregado"
            $table->string('tracking_number')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_address_transactions');
    }
};
