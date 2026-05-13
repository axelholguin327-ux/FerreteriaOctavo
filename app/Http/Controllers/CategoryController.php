<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product; 
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Mostrar la lista y el formulario
    public function index(Category $category = null)
{
    $categorias = Category::all();

    // Si el usuario hizo clic en una categoría específica:
    if ($category) {
        $productos = Product::where('category_id', $category->id)->get();
        return view('categorias.show', compact('categorias', 'category', 'productos'));
    }

    // Si no, muestra la lista normal de categorías
    return view('categorias.index', compact('categorias'));
}

    // Guardar una nueva categoría
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
        ]);

        Category::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('categorias.index')->with('success', 'Categoría guardada con éxito');
    }
}
