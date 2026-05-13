<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Iniciamos la consulta limpia
        $query = Order::with('user');

        if ($request->has('buscar') && $request->buscar != '') {
            $buscar = $request->buscar;
            $query->whereHas('user', function ($q) use ($buscar) {
                $q->where('name', 'LIKE', "%{$buscar}%");
            });
        }

        // 2. Aplicamos filtros según el request
        if ($request->filtro == '24hr') {
            $query->where('created_at', '>=', now()->subDay());
        } elseif ($request->filtro == 'semana') {
            $query->where('created_at', '>=', now()->subWeek());
        } elseif ($request->filtro == 'mes') {
            $query->where('created_at', '>=', now()->subMonth());
        }

        // 3. Lógica de cantidad a mostrar
        if ($request->has('filtro') && $request->filtro !== 'todo') {
            // Para 24hr, Semana o Mes
            $ultimosPedidos = $query->latest()->get();
        } else {
            // Si es "Todo" o no hay filtro (carga inicial)
            // QUITAMOS el take(20) para que muestre el historial completo
            $ultimosPedidos = $query->latest()->get();
        }
        $ultimosPedidos = $query->latest()->get();
        $totalVentas = Order::sum('total');
        $totalPedidos = Order::count();
        $stockBajo = Product::where('stock', '<', 5)->get();
        //$ultimosPedidos = Order::with('user')->latest()->take(5)->get();

        // --- LÓGICA DE LA GRÁFICA ---
        // Obtenemos ventas de los últimos 7 días
        $ventasSemanales = Order::select(
            DB::raw('DATE(created_at) as fecha'),
            DB::raw('SUM(total) as total')
        )
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('fecha')
            ->orderBy('fecha', 'asc')
            ->get();

        // Formateamos los datos para JS
        $labels = [];
        $data = [];

        // Rellenamos días si no hubo ventas para que la gráfica no se vea cortada
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('d M'); // Ej: 24 Abr

            $ventaDia = $ventasSemanales->firstWhere('fecha', $date);
            $data[] = $ventaDia ? $ventaDia->total : 0;
        }

        $userQuery = \App\Models\User::query();

        // Buscador de usuarios
        if ($request->has('buscar_usuario') && $request->buscar_usuario != '') {
            $buscarUser = $request->buscar_usuario;
            $userQuery->where(function ($q) use ($buscarUser) {
                $q->where('name', 'LIKE', "%{$buscarUser}%")
                    ->orWhere('email', 'LIKE', "%{$buscarUser}%");
            });
        }

        $usuarios = $userQuery->orderBy('name', 'asc')->get();

        $rolesDisponibles = ['admin', 'ventas', 'cliente'];

        return view('admin.dashboard', compact(
            'totalVentas',
            'totalPedidos',
            'stockBajo',
            'ultimosPedidos',
            'labels',
            'data',
            'usuarios',
            'rolesDisponibles'
        ));
    }
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:Pendiente,Pagado,Enviado,Entregado,Cancelar'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Estado del pedido #' . $order->id . ' actualizado.');
    }

    public function updateRole(Request $request, \App\Models\User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,ventas,cliente'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'Rol de ' . $user->name . ' actualizado a ' . $request->role);
    }
}