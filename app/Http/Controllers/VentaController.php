<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Order::with(['orderItems.product', 'user'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        $productos = Product::where('stock', '>', 0)
            ->orderBy('nombre', 'asc')
            ->get();

        return view('ventas.index', compact('ventas', 'productos'));
    }

    public function create()
    {
        $productos = Product::where('stock', '>', 0)->get();
        return view('ventas.create', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'required|string|max:100',
            'metodo_entrega' => 'required|in:Recoger,Envío', // Validamos el nuevo campo
            'metodo_pago' => 'required|in:Efectivo,Tarjeta,Transferencia',
            'direccion_envio' => 'required_if:metodo_entrega,Envío|nullable|string|max:255', // Validar solo si es Envío
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $total = 0;

                // 1. VALIDACIÓN PREVIA Y CÁLCULO
                foreach ($request->items as $item) {
                    $p = Product::findOrFail($item['product_id']);

                    if ($p->stock < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para: {$p->nombre} (Disponible: {$p->stock})");
                    }

                    $total += $p->precio * $item['cantidad'];
                }

                // 2. CREACIÓN DE LA ORDEN (Aquí es donde agregamos lo que pediste)
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'cliente_nombre' => $request->cliente_nombre, // Guarda el nombre del modal
                    'metodo_entrega' => $request->metodo_entrega, // Guarda si es Envío o Recoger
                    'metodo_pago' => $request->metodo_pago,
                    'direccion_envio' => $request->metodo_entrega === 'Envío' ? $request->direccion_envio : 'Entrega en local', // Guardamos la dirección o aviso de local
                    'total' => $total,
                    'status' => 'Pendiente', // Forzamos que siempre sea Pendiente al inicio
                ]);

                // 3. DESCUENTO DE STOCK Y REGISTRO DE ITEMS
                foreach ($request->items as $item) {
                    $producto = Product::findOrFail($item['product_id']);
                    $producto->decrement('stock', $item['cantidad']);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $producto->id,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $producto->precio,
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Venta registrada con éxito.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
    public function generarTicket($id)
    {
        $venta = Order::with(['orderItems.product', 'user'])->findOrFail($id);

        // Configuramos el papel para que parezca ticket de 80mm (típico de térmica)
        // 227pt de ancho es aprox 80mm. El alto es variable (800pt)
        $pdf = Pdf::loadView('ventas.ticket', compact('venta'))
            ->setPaper([0, 0, 227, 800], 'portrait');

        return $pdf->stream('ticket_venta_' . $venta->id . '.pdf');
    }
    // En VentaController.php
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            // Ahora validamos contra los 5 estados reales
            'status' => 'required|in:Pendiente,Pagado,Enviado,Entregado,Cancelar'
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Estado actualizado');
    }

}