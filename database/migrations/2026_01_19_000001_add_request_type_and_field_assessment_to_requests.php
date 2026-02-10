<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Request type (public tender vs internal)
            $table->enum('request_type', ['public', 'internal'])
                ->default('internal')
                ->after('status');

            // Field assessment fields
            $table->boolean('requires_field_assessment')->default(false)->after('request_type');
            $table->foreignId('assigned_to_field_evaluator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->after('requires_field_assessment');
            $table->enum('field_assessment_status', [
                'not_required',
                'pending',
                'in_progress',
                'completed',
                'rejected'
            ])->default('not_required')->after('assigned_to_field_evaluator_id');
            $table->timestamp('field_assessment_completed_at')->nullable()->after('field_assessment_status');

            // Delivery information
            $table->string('delivery_location')->nullable()->after('description');
            $table->text('delivery_address')->nullable()->after('delivery_location');
            $table->text('special_instructions')->nullable()->after('delivery_address');

            // Indexes
            $table->index('request_type');
            $table->index('requires_field_assessment');
            $table->index('field_assessment_status');
            $table->index('assigned_to_field_evaluator_id');
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropIndex(['request_type']);
            $table->dropIndex(['requires_field_assessment']);
            $table->dropIndex(['field_assessment_status']);
            $table->dropIndex(['assigned_to_field_evaluator_id']);

            $table->dropForeign(['assigned_to_field_evaluator_id']);

            $table->dropColumn([
                'request_type',
                'requires_field_assessment',
                'assigned_to_field_evaluator_id',
                'field_assessment_status',
                'field_assessment_completed_at',
                'delivery_location',
                'delivery_address',
                'special_instructions',
            ]);
        });
    }
};
