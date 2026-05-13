<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. RUTAS TOTALMENTE PÚBLICAS (Sin Login)
// Esta es la que ve el cliente al entrar
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/productos', [ProductController::class, 'index'])->name('productos.index');
Route::get('/categorias/{category?}', [CategoryController::class, 'index'])->name('categorias.index');
Route::get('/contacto', [ContactController::class, 'index'])->name('contacto.index');


// 2. RUTAS PROTEGIDAS (Requieren Login)
Route::middleware('auth')->group(function () {

    // Quitamos la ruta 'dashboard' de aquí adentro para que no pida login

    Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Carrito
    Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrito/agregar/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/carrito/eliminar/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/carrito/update/{cartItem}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/mis-pedidos', [OrderHistoryController::class, 'index'])->name('orders.history');
    Route::post('/carrito/agregar/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::get('/carrito/confirmar', [CartController::class, 'checkoutView'])->name('cart.checkout.view');

    // 3. RUTAS EXCLUSIVAS DE ADMINISTRADOR
    Route::middleware('can:access-admin')->group(function () {
        Route::post('/categorias', [CategoryController::class, 'store'])->name('categorias.store');
        Route::post('/productos', [ProductController::class, 'store'])->name('productos.store');
        Route::get('/productos/{producto}/edit', [ProductController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}', [ProductController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}', [ProductController::class, 'destroy'])->name('productos.destroy');

        // Admin Dashboard
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::patch('/admin/orders/{order}/status', [AdminDashboardController::class, 'updateStatus'])->name('admin.orders.updateStatus');
        Route::patch('/admin/users/{user}/role', [AdminDashboardController::class, 'updateRole'])->name('admin.users.updateRole');
    });

    // Rutas compartidas entre Admin y Ventas
    Route::middleware('role:admin,ventas')->group(function () {
        Route::get('/ventas/crear', [VentaController::class, 'create'])->name('ventas.create');
        Route::post('/ventas/guardar', [VentaController::class, 'store'])->name('ventas.store');
        Route::get('/ventas/historial', [VentaController::class, 'index'])->name('ventas.index');
        Route::get('/ventas/{id}/ticket', [VentaController::class, 'generarTicket'])->name('ventas.ticket');
        // Ruta para actualizar el estado de la venta/orden
        Route::patch('/ventas/{order}/status', [VentaController::class, 'updateStatus'])->name('ventas.updateStatus');
    });
});

require __DIR__ . '/auth.php';