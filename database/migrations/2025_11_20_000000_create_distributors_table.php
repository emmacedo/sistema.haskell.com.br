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
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('company_name', 255);
            $table->string('trade_name', 255);
            $table->string('cnpj', 18)->unique();
            $table->string('email', 255);
            $table->string('phone', 20);
            $table->string('whatsapp', 20)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('address')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_code', 10)->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
