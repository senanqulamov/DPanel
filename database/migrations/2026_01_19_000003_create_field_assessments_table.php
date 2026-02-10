<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('field_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('requests')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('users')->cascadeOnDelete(); // Changed from evaluator_id

            // Location and site info
            $table->string('site_location')->nullable();
            $table->text('accessibility_notes')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();

            // Condition assessment
            $table->text('current_condition')->nullable();

            // Technical assessment
            $table->text('technical_feasibility')->nullable();
            $table->text('technical_compliance')->nullable();
            $table->integer('estimated_duration')->nullable();
            $table->enum('duration_unit', ['hours', 'days', 'weeks'])->default('days');

            // Price recommendation
            $table->decimal('recommended_price', 15, 2)->nullable();
            $table->decimal('recommended_price_min', 15, 2)->nullable();
            $table->decimal('recommended_price_max', 15, 2)->nullable();
            $table->string('currency', 3)->default('AZN');
            $table->text('price_justification')->nullable();

            // Risks
            $table->text('risks_identified')->nullable();
            $table->text('mitigation_recommendations')->nullable();

            // Media and notes
            $table->json('photos')->nullable(); // Array of file paths
            $table->text('notes')->nullable();
            $table->text('field_notes')->nullable();

            // Status tracking
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'submitted',
                'approved',
                'rejected'
            ])->default('pending');

            // Timestamps for KPI calculation
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('request_id');
            $table->index('supplier_id');
            $table->index('status');
            $table->unique(['request_id', 'supplier_id']); // One assessment per supplier per RFQ
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('field_assessments');
    }
};
