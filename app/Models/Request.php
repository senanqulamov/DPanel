<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Request extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'buyer_id',
        'title',
        'description',
        'deadline',
        'status',
        // Request type and field assessment
        'request_type',
        'requires_field_assessment',
        'assigned_to_field_evaluator_id',
        'field_assessment_status',
        'field_assessment_completed_at',
        // Delivery information
        'delivery_location',
        'delivery_address',
        'special_instructions',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'deadline' => 'datetime',
        'field_assessment_completed_at' => 'datetime',
        'status' => 'string',
        'request_type' => 'string',
        'field_assessment_status' => 'string',
        'requires_field_assessment' => 'boolean',
    ];

    /**
     * Get the buyer (user) that owns the request.
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the field evaluator assigned to this request.
     */
    public function fieldEvaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_field_evaluator_id');
    }

    /**
     * Get the items for this request.
     */
    public function items(): HasMany
    {
        return $this->hasMany(RequestItem::class);
    }

    /**
     * Get the quotes for this request.
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * Get the supplier invitations for this request.
     */
    public function supplierInvitations(): HasMany
    {
        return $this->hasMany(SupplierInvitation::class);
    }

    /**
     * Get the workflow events for this request.
     */
    public function workflowEvents()
    {
        return $this->morphMany(WorkflowEvent::class, 'eventable');
    }

    /**
     * Get the field assessment for this request.
     */
    public function fieldAssessment(): HasMany
    {
        return $this->hasMany(FieldAssessment::class);
    }

    /**
     * Get the latest field assessment for this request.
     */
    public function latestFieldAssessment()
    {
        return $this->hasOne(FieldAssessment::class)->latestOfMany();
    }
}
