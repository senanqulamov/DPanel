<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'request_id',
        'product_name',
        'quantity',
        'specifications',
    ];

    /**
     * Get the request that owns this item.
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(Request::class);
    }
}
