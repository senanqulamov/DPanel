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
        Schema::table('quotes', function (Blueprint $table) {
            $table->decimal('adjusted_total_price', 10, 2)->nullable()->after('total_amount');
            $table->timestamp('adjusted_at')->nullable()->after('adjusted_total_price');
            $table->foreignId('adjusted_by')->nullable()->after('adjusted_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['adjusted_by']);
            $table->dropColumn(['adjusted_total_price', 'adjusted_at', 'adjusted_by']);
        });
    }
};
