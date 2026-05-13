<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    // Esto permite usar CartItem::create()
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    // Relación: Un item del carrito pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}