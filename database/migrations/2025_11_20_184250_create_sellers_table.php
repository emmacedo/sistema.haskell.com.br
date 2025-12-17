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
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained()->onDelete('cascade');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 20);
            $table->string('whatsapp', 20)->nullable();
            $table->string('position', 100)->nullable();
            $table->timestamps();

            $table->index('distributor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
