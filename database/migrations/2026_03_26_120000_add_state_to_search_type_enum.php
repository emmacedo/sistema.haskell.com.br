<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Adiciona 'state' ao ENUM search_type da tabela search_statistics.
     * Necessário porque o sistema detecta buscas por UF (ex: "MG", "SP")
     * e registra como 'state', mas o ENUM original só permitia 'cep' e 'city'.
     * Isso causava "Data truncated for column 'search_type'" em produção.
     */
    public function up(): void
    {
        // Altera o ENUM para incluir 'state' como tipo de busca válido
        // 'cep' = busca por CEP, 'city' = busca por nome da cidade, 'state' = busca por UF (2 letras)
        DB::statement("ALTER TABLE search_statistics MODIFY COLUMN search_type ENUM('cep', 'city', 'state') NOT NULL");
    }

    /**
     * Reverte para o ENUM original sem 'state'.
     * Registros com search_type='state' serão truncados ao reverter.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE search_statistics MODIFY COLUMN search_type ENUM('cep', 'city') NOT NULL");
    }
};
