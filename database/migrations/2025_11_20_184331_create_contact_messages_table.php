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
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained()->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained()->onDelete('set null');
            $table->string('sender_name', 255);
            $table->string('sender_email', 255);
            $table->string('sender_phone', 20);
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index('distributor_id');
            $table->index('read_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_messages');
    }
};
