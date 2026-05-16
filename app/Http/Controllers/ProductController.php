<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function index()
    {
        $productos = Product::with('category')->get();
        $categorias = Category::all();
        return view('productos.index', compact('productos', 'categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $data = $request->all();

        if ($request->hasFile('imagen')) {
            $data['imagen'] = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                ['folder' => 'productos']
            )->getSecurePath();
        }

        Product::create($data);
        return redirect()->back()->with('success', 'Producto agregado correctamente.');
    }

    public function destroy(Product $producto)
    {
        if ($producto->imagen) {
            // Extraer public_id de la URL de Cloudinary y borrar
            $publicId = 'productos/' . pathinfo(basename($producto->imagen), PATHINFO_FILENAME);
            Cloudinary::destroy($publicId);
        }

        $producto->delete();
        return redirect()->back()->with('success', 'Producto eliminado correctamente.');
    }

    public function edit(Product $producto)
    {
        $categorias = Category::all();
        return view('productos.edit', compact('producto', 'categorias'));
    }

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
            if ($producto->imagen) {
                $publicId = 'productos/' . pathinfo(basename($producto->imagen), PATHINFO_FILENAME);
                Cloudinary::destroy($publicId);
            }
            $data['imagen'] = Cloudinary::upload(
                $request->file('imagen')->getRealPath(),
                ['folder' => 'productos']
            )->getSecurePath();
        }

        $producto->update($data);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }
}