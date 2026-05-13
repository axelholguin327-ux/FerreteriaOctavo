<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Buscamos los 3 productos con más cantidad vendida sumada
        $productos = Product::where('stock', '>', 0)
            ->withCount([
                'orderItems as total_vendido' => function ($query) {
                    $query->select(DB::raw('sum(cantidad)'));
                }
            ])
            ->orderByDesc('total_vendido')
            ->take(3)
            ->get();

        return view('dashboard', compact('productos'));
    }
}