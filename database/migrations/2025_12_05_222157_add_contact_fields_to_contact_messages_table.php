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
        Schema::table('contact_messages', function (Blueprint $table) {
            // Cidade e estado do remetente (texto livre, pois pode não estar cadastrada)
            $table->string('sender_city', 100)->nullable()->after('sender_phone');
            $table->string('sender_state', 2)->nullable()->after('sender_city'); // UF

            // Produto de interesse (FK para tabela products)
            $table->foreignId('product_id')->nullable()->after('sender_state')
                  ->constrained('products')->nullOnDelete();

            // Vendedor que recebeu o contato (FK para sellers)
            $table->foreignId('seller_id')->nullable()->after('product_id')
                  ->constrained('sellers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropForeign(['product_id']);
            $table->dropColumn(['sender_city', 'sender_state', 'product_id', 'seller_id']);
        });
    }
};
