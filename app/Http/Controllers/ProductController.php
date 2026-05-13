<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $productos = Product::with('category')->get();
        $categorias = Category::all(); // Las necesitamos para el formulario
        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
        'nombre' => 'required|max:255',
        'precio' => 'required|numeric',
        'stock' => 'required|integer',
        'category_id' => 'required|exists:categories,id',
        'imagen' => 'nullable|image|max:2048' // Cambiamos a nullable para que no sea obligatoria
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        Product::create($data);

        return redirect()->back()->with('success', 'Producto agregado correctamente.');
    }
    public function destroy(Product $producto)
    {
    // Si tiene imagen, la borramos del disco para no llenar espacio basura
    if ($producto->imagen) {
        Storage::disk('public')->delete($producto->imagen);
    }

    $producto->delete();
    return redirect()->back()->with('success', 'Producto eliminado correctamente.');
    }

    // 1. Mostrar el formulario de edición
public function edit(Product $producto)
{
    $categorias = \App\Models\Category::all();
    return view('productos.edit', compact('producto', 'categorias'));
}

    // Procesar los cambios
    public function update(Request $request, Product $producto)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            // Borramos la foto vieja si existe
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }
}