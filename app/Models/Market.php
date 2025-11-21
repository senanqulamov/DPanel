<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'image_path',
    ];

    // Relationship to orders belonging to this market
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Access all order items via orders for product aggregation (legacy)
    public function orderItems()
    {
        return $this->hasManyThrough(OrderItem::class, Order::class, 'market_id', 'order_id');
    }

    // Direct products belonging to this market
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
