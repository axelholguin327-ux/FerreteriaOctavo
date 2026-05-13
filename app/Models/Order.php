<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'cliente_nombre',
        'total',
        'status',
        'metodo_entrega',
        'metodo_pago',
        'direccion_envio'
    ];  // Relación con el usuario (quién hizo el pedido)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
