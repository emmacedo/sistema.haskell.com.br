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
        Schema::create('search_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->string('search_term', 255);
            $table->enum('search_type', ['cep', 'city']);
            $table->boolean('has_result')->default(false);
            $table->integer('results_count')->default(0);
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('city_id');
            $table->index('has_result');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_statistics');
    }
};
