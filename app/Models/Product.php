<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
}

