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
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->string('color')->nullable()->after('calculated_price');
            $table->string('cuadricula_type')->nullable()->after('color');
            $table->json('inputs')->nullable()->after('cuadricula_type');
            $table->decimal('total', 10, 2)->nullable()->after('inputs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn(['color', 'cuadricula_type', 'inputs', 'total']);
        });
    }
};
