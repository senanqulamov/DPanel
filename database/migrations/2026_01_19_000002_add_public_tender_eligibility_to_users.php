<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Flag to indicate if supplier is eligible for public tenders
            $table->boolean('is_public_tender_eligible')
                ->default(false)
                ->after('is_supplier');

            $table->index('is_public_tender_eligible');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_public_tender_eligible']);
            $table->dropColumn('is_public_tender_eligible');
        });
    }
};
