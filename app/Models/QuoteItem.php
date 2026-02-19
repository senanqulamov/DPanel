<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quote_id',
        'request_item_id',
        'description',
        'quantity',
        'unit_price',
        'new_unit_price',
        'tax_rate',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'new_unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    /**
     * Get the quote this item belongs to.
     */
    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    /**
     * Get the request item this quote item responds to.
     */
    public function requestItem(): BelongsTo
    {
        return $this->belongsTo(RequestItem::class, 'request_item_id');
    }

    /**
     * Calculate the subtotal for this item.
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Calculate the tax amount for this item.
     */
    public function getTaxAmountAttribute(): float
    {
        return $this->subtotal * ($this->tax_rate / 100);
    }

    /**
     * Calculate the total for this item (subtotal + tax).
     */
    public function getTotalAttribute(): float
    {
        return $this->subtotal + $this->tax_amount;
    }

    /**
     * Calculate the adjusted subtotal using new_unit_price if available.
     */
    public function getAdjustedSubtotalAttribute(): float
    {
        $price = $this->new_unit_price ?? $this->unit_price;
        return $this->quantity * $price;
    }

    /**
     * Calculate the adjusted tax amount based on adjusted subtotal.
     */
    public function getAdjustedTaxAmountAttribute(): float
    {
        return $this->adjusted_subtotal * ($this->tax_rate / 100);
    }

    /**
     * Calculate the adjusted total (adjusted subtotal + tax).
     */
    public function getAdjustedTotalAttribute(): float
    {
        return $this->adjusted_subtotal + $this->adjusted_tax_amount;
    }
}
