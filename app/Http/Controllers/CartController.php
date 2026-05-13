<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem; // Importamos el modelo nuevo
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Ver el carrito
    public function index()
    {
        // Obtenemos los items del carrito del usuario autenticado
        // Usamos with('product') para cargar los datos del nombre, precio, etc.
        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->product->precio * $item->quantity;
        }

        return view('cart.index', compact('cartItems', 'total'));
    }

    // Agregar producto
    public function add(Request $request, Product $product)
    {
        // Validamos que la cantidad sea un número y no exceda el stock
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock
        ]);

        $quantity = $request->input('quantity');

        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Si ya existe, sumamos la nueva cantidad
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }

        return redirect()->back()->with('success', "Se añadieron {$quantity} unidades al carrito.");
    }

    // Quitar producto
    public function remove($id)
    {
        // Buscamos el registro en la BD y lo eliminamos
        $cartItem = CartItem::where('user_id', auth()->id())
            ->where('product_id', $id)
            ->first();

        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }

    public function checkout(Request $request)
    {
        // 1. Verificación de seguridad
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Obtener items del carrito desde la BD
        $cartItems = CartItem::where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        if (!$request->has('entrega')) {
            return redirect()->route('cart.index');
        }
        try {
            DB::beginTransaction();

            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item->product->precio * $item->quantity;
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'status' => 'pendiente',
                'metodo_entrega' => $request->input('entrega'),
                'metodo_pago' => $request->input('pago'),
                // Concatenamos todo lo que no tiene columna propia en direccion_envio
                'direccion_envio' => $request->input('direccion') .
                    ' Col. ' . $request->input('colonia') .
                    ' CP: ' . $request->input('cp'),
            ]);

            // 4. Crear Detalles y actualizar Inventario
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'cantidad' => $item->quantity,
                    'precio_unitario' => $item->product->precio,
                ]);

                // Descontar del stock físico
                $item->product->decrement('stock', $item->quantity);
            }

            // 5. Limpiar carrito de la BD
            CartItem::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('productos.index')->with('success', 'Pedido realizado');
        } catch (\Exception $e) {
            DB::rollBack();
            return dd($e->getMessage());
        }
    }

    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        // Si la cantidad es mayor al stock, podrías validar aquí también
        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        return redirect()->back()->with('success', 'Cantidad actualizada.');
    }

    public function checkoutView()
    {
        $cartItems = CartItem::where('user_id', auth()->id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->product->precio * $item->quantity;
        });

        return view('cart.checkout', compact('cartItems', 'total'));
    }
}