<?php

// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'description',
        'purchase_price',
        'selling_price',
        'stock',
        'image_path',
        'category_id',
        'supplier_id',
        'sku',
        'active',
    ];

    protected $appends = ['profit', 'profit_percentage'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function getProfitAttribute()
    {
        return $this->selling_price - $this->purchase_price;
    }

    public function getProfitPercentageAttribute()
    {
        if ($this->purchase_price == 0) return 0;
        return round(($this->selling_price - $this->purchase_price) / $this->purchase_price * 100, 2);
    }
}