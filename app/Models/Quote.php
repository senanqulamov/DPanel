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
        'unit_price',
        'total_price',
        'notes',
        'status',
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
     * Accessor: get the formatted total price as money.
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        $value = $this->total_price ?? 0;

        return number_format((float) $value, 2, '.', ' ');
    }
}
