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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('metodo_entrega')->default('recoger'); // recoger o envio
            $table->string('metodo_pago')->nullable(); // tarjeta, efectivo, transferencia
            $table->text('direccion_envio')->nullable(); // Calle, número, colonia, CP
            $table->string('estado_pago')->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
