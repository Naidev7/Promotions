<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'sku';
    public $incrementing = false;

    protected $fillable = [
        'sku',
        'name',
        'category',
        'price',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    public function scopeCategory($query, $category)
    {
        if ($category) {
            return $query->whereRaw('LOWER(category) = ?', [strtolower($category)]);
        }
        return $query;
    }


    public function scopePriceLessThan($query, $price)
    {
        if ($price !== null) {
            return $query->where('price', '<=', (int) $price);
        }
        return $query;
    }

    public function calculateMaxDiscount()
    {
        $discounts = [];

        if ($this->category === 'boots') {
            $discounts[] = 30;
        }

        if ($this->sku === '000003') {
            $discounts[] = 15;
        }

        // Retorna el descuento más alto si existe, o null
        return !empty($discounts) ? max($discounts) : null;
    }

    // Método para aplicar el descuento al precio original
    public function applyDiscount($discountPercentage)
    {
        $originalPrice = $this->price;
        $discountAmount = $originalPrice * ($discountPercentage / 100);
        $finalPrice = intval(round($originalPrice - $discountAmount));
        return $finalPrice;
    }
}

