<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_id',
        'supplier_id',
        'supplier_invitation_id',
        'unit_price',
        'total_price',
        'total_amount',
        'adjusted_total_price',
        'adjusted_at',
        'adjusted_by',
        'currency',
        'valid_until',
        'notes',
        'terms_conditions',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
        'submitted_at' => 'datetime',
        'adjusted_at' => 'datetime',
        'total_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'adjusted_total_price' => 'decimal:2',
        'unit_price' => 'decimal:2',
    ];

    /**
     * Get the request that this quote belongs to.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }

    /**
     * Get the supplier (user) that provided this quote.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    /**
     * Get the supplier invitation this quote responds to.
     */
    public function supplierInvitation(): BelongsTo
    {
        return $this->belongsTo(SupplierInvitation::class, 'supplier_invitation_id');
    }

    /**
     * Get the quote items.
     */
    public function items()
    {
        return $this->hasMany(QuoteItem::class);
    }

    /**
     * Get the user who adjusted the quote prices.
     */
    public function adjustedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    /**
     * Calculate the adjusted total from quote items' new unit prices
     */
    public function getCalculatedAdjustedTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            $price = $item->new_unit_price ?? $item->unit_price;
            $subtotal = $item->quantity * $price;
            $tax = $subtotal * ($item->tax_rate / 100);
            return $subtotal + $tax;
        });
    }

    /**
     * Calculate the actual total from quote items (including tax)
     * This is the correct calculation based on items
     */
    public function getCalculatedTotalAttribute(): float
    {
        return $this->items->sum(function ($item) {
            return $item->total; // Uses the accessor from QuoteItem model
        });
    }

    /**
     * Accessor: get the formatted total price as money.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        $value = $this->total_price ?? 0;

        return number_format((float) $value, 2, '.', ' ');
    }

    /**
     * Accessor: get the formatted adjusted total price as money.
     */
    public function getFormattedAdjustedTotalPriceAttribute(): string
    {
        $value = $this->adjusted_total_price ?? 0;

        return number_format((float) $value, 2, '.', ' ');
    }
}
