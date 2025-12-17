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
        if (Schema::hasTable('distributors') && Schema::hasColumn('distributors', 'rejection_reason')) {
            Schema::table('distributors', function (Blueprint $table) {
                $table->dropColumn('rejection_reason');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('distributors', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable();
        });
    }
};
