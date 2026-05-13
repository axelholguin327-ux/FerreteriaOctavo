<?php

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/producto/{codigo}', function ($codigo) {
    // Buscamos el producto por su código de barras
    $producto = Producto::where('codigo_barras', $codigo)->first();

    if ($producto) {
        return response()->json($producto);
    }

    return response()->json(['error' => 'Producto no encontrado'], 404);
});
