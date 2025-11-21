<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'category',
        'market_id', // added
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function market()
    {
        return $this->belongsTo(Market::class);
    }
}
