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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100); // Nome do produto de interesse (ex: Shampoo, Condicionador, Máscara)
            $table->boolean('active')->default(true); // Se o produto está ativo para seleção
            $table->integer('order')->default(0); // Ordem de exibição no select
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
