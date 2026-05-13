<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nombre', 'descripcion'];

    //Una categoría tiene muchos productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}