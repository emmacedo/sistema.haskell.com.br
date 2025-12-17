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
        Schema::table('distributors', function (Blueprint $table) {
            // Adicionar phone2
            $table->string('phone2', 20)->nullable()->after('phone');

            // Remover campo address antigo
            $table->dropColumn('address');

            // Adicionar campos de endereço detalhados
            $table->string('cep', 10)->nullable()->after('whatsapp');
            $table->string('logradouro', 255)->nullable()->after('cep');
            $table->string('numero', 20)->nullable()->after('logradouro');
            $table->string('complemento', 255)->nullable()->after('numero');
            $table->string('bairro', 100)->nullable()->after('complemento');
            $table->string('cidade', 100)->nullable()->after('bairro');
            $table->string('estado', 2)->nullable()->after('cidade');

            // Tornar email único
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            // Remover phone2
            $table->dropColumn('phone2');

            // Remover campos de endereço detalhados
            $table->dropColumn(['cep', 'logradouro', 'numero', 'complemento', 'bairro', 'cidade', 'estado']);

            // Restaurar campo address
            $table->text('address')->nullable();

            // Remover unique do email
            $table->dropUnique(['email']);
        });
    }
};
