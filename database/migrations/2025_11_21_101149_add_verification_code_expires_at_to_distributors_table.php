<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adiciona campo para controlar a expiração do código de verificação de email.
     * Códigos são válidos por 24 horas após a geração, aumentando a segurança.
     */
    public function up(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Campo para armazenar quando o código de verificação expira
            // Posicionado após verification_code para manter organização lógica
            $table->timestamp('verification_code_expires_at')->nullable()->after('verification_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Remove o campo de expiração do código de verificação
            $table->dropColumn('verification_code_expires_at');
        });
    }
};
