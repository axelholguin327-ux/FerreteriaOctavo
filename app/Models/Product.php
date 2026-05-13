<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock', 'imagen', 'category_id'];

    // Relación: Un producto pertenece a una categoría
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orderItems()
    {
        // Un producto puede aparecer en muchos detalles de órdenes
        return $this->hasMany(OrderItem::class);
    }
}