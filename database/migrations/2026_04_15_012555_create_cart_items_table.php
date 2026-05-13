<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            // Relación con el usuario (quién es el dueño del carrito)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Relación con el producto (qué está comprando)
            // Asegúrate que tu tabla anterior se llame 'products'
            $table->foreignId('product_id')->constrained()->onDelete('cascade');

            // Cantidad de ese mismo producto
            $table->integer('quantity')->default(1);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
