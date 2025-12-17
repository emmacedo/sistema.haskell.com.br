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
        Schema::create('city_distributor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['distributor_id', 'city_id']);
            $table->index('city_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('city_distributor');
    }
};
