<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FieldAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'supplier_id',
        'site_location',
        'accessibility_notes',
        'latitude',
        'longitude',
        'current_condition',
        'technical_feasibility',
        'technical_compliance',
        'estimated_duration',
        'duration_unit',
        'recommended_price',
        'recommended_price_min',
        'recommended_price_max',
        'currency',
        'price_justification',
        'risks_identified',
        'mitigation_recommendations',
        'photos',
        'notes',
        'field_notes',
        'status',
        'assigned_at',
        'started_at',
        'completed_at',
        'submitted_at',
    ];

    protected $casts = [
        'photos' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'recommended_price' => 'decimal:2',
        'recommended_price_min' => 'decimal:2',
        'recommended_price_max' => 'decimal:2',
        'assigned_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function canSubmit(): bool
    {
        return in_array($this->status, ['pending', 'in_progress']);
    }

    public function isCompleted(): bool
    {
        return in_array($this->status, ['completed', 'submitted', 'approved']);
    }
}
