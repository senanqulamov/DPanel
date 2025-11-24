<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'total',
        'status',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Order $order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        return 'ORD-'.strtoupper(uniqid());
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')
            ->withPivot(['quantity', 'unit_price', 'subtotal', 'market_id'])
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all markets involved in this order
     */
    public function markets()
    {
        return $this->belongsToMany(Market::class, 'order_items')
            ->distinct();
    }

    /**
     * Get items grouped by market
     */
    public function itemsByMarket()
    {
        return $this->items()
            ->with(['product', 'market'])
            ->get()
            ->groupBy('market_id');
    }

    public function recalcTotal(): void
    {
        $total = $this->items()->sum('subtotal');
        if ((float) $this->total !== (float) $total) {
            $this->total = $total;
            $this->saveQuietly();
        }
    }
}
