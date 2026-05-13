<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Administración - Ferretería') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 shadow-md" role="alert">
                    <p class="font-bold">¡Logrado!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            {{-- 1. Tarjetas de Estadísticas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <p class="text-gray-500 text-sm uppercase font-bold">Total Ventas</p>
                    <p class="text-3xl font-bold text-gray-800">${{ number_format($totalVentas, 2) }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <p class="text-gray-500 text-sm uppercase font-bold">Pedidos Realizados</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPedidos }}</p>
                </div>
            </div>

            {{-- 2. Gráfica de Ventas --}}
            <div class="bg-white p-6 rounded-lg shadow mb-8">
                <h3 class="text-lg font-bold mb-4 text-gray-800">Tendencia de Ventas (Últimos 7 días)</h3>
                <div style="height: 300px;">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                {{-- 3. Alertas de Stock (Columna Izquierda) --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-bold mb-4 text-red-600">Alertas de Stock Bajo</h3>
                    @if($stockBajo->isEmpty())
                        <p class="text-gray-500 italic">Todo el inventario está en orden.</p>
                    @else
                        <table class="min-w-full">
                            <thead>
                                <tr class="text-left border-b">
                                    <th class="pb-2">Producto</th>
                                    <th class="pb-2 text-center">Stock Actual</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockBajo as $prod)
                                    <tr class="border-b last:border-0">
                                        <td class="py-2 text-gray-700">{{ $prod->nombre }}</td>
                                        <td class="py-2 text-center font-bold text-red-500">{{ $prod->stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- 4. Gestión de Usuarios y Roles (Columna Derecha) --}}
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <h3 class="text-lg font-bold text-gray-800">Usuarios y Roles</h3>
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="relative w-full md:w-48">
                            <input type="text" name="buscar_usuario" value="{{ request('buscar_usuario') }}" 
                                placeholder="Buscar..." 
                                class="w-full pl-3 pr-10 py-1 text-sm border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-lg shadow-sm">
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                    <div class="overflow-y-auto h-[300px] custom-scrollbar">
                        <table class="min-w-full text-sm">
                            <thead class="sticky top-0 bg-white z-10">
                                <tr class="border-b text-gray-600 uppercase text-xs">
                                    <th class="text-left py-3 px-2">Nombre</th>
                                    <th class="text-left py-3 px-2">Rol</th>
                                    <th class="text-left py-3 px-2">Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $user)
                                <tr class="border-b hover:bg-gray-50 transition">
                                    <td class="py-3 px-2">
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $user->email }}</div>
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="px-2 py-1 rounded-full text-[9px] font-bold uppercase 
                                            {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-700' : ($user->role == 'ventas' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                                            {{ $user->role ?? 'cliente' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2">
                                        <form action="{{ route('admin.users.updateRole', $user) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" onchange="this.form.submit()" 
                                                    class="text-[10px] border-gray-300 rounded-md py-0 focus:ring-orange-500 focus:border-orange-500">
                                                @foreach($rolesDisponibles as $rol)
                                                    <option value="{{ $rol }}" {{ $user->role == $rol ? 'selected' : '' }}>
                                                        {{ ucfirst($rol) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- 5. Gestión de Pedidos Recientes (Ancho Completo) --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <h3 class="text-lg font-bold text-gray-800">Gestión de Pedidos Recientes</h3>
                    
                    <div class="flex flex-wrap items-center gap-4">
                        {{-- Filtros de Tiempo --}}
                        <div class="flex gap-1 bg-gray-100 p-1 rounded-lg">
                            @foreach(['24hr' => '24h', 'semana' => 'Semana', 'mes' => '30 días'] as $key => $label)
                                <a href="{{ route('admin.dashboard', ['filtro' => $key, 'buscar' => request('buscar')]) }}" 
                                class="px-3 py-1 text-[11px] font-bold rounded-md transition {{ request('filtro') == $key ? 'bg-orange-500 text-white shadow' : 'text-gray-600 hover:bg-gray-200' }}">
                                    {{ $label }}
                                </a>
                            @endforeach
                            <a href="{{ route('admin.dashboard', ['buscar' => request('buscar')]) }}" 
                               class="px-3 py-1 text-[11px] font-bold rounded-md transition {{ !request('filtro') ? 'bg-gray-800 text-white shadow' : 'text-gray-600 hover:bg-gray-200' }}">
                                Todo
                            </a>
                        </div>

                        {{-- Buscador de Pedidos --}}
                        <form action="{{ route('admin.dashboard') }}" method="GET" class="relative w-full md:w-64">
                            @if(request('filtro'))
                                <input type="hidden" name="filtro" value="{{ request('filtro') }}">
                            @endif
                            <input type="text" name="buscar" value="{{ request('buscar') }}" 
                                placeholder="Buscar cliente por nombre..." 
                                class="w-full pl-3 pr-10 py-1 text-sm border-gray-300 focus:border-orange-500 focus:ring-orange-500 rounded-lg shadow-sm">
                            <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-orange-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-gray-600 uppercase text-xs">
                                <th class="text-left py-3 px-2">ID</th>
                                <th class="text-left py-3 px-2">Cliente</th>
                                <th class="text-left py-3 px-2">Fecha</th>
                                <th class="text-left py-3 px-2">Total</th>
                                <th class="text-left py-3 px-2">Estado</th>
                                <th class="text-left py-3 px-2">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ultimosPedidos as $ped)
                                <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                                    <td class="py-3 px-2 font-bold text-orange-600">#{{ $ped->id }}</td>
                                    <td class="py-3 px-2 font-medium">{{ $ped->user->name }}</td>
                                    <td class="py-3 px-2 text-gray-500">
                                        <div class="text-gray-900">{{ $ped->created_at->format('d/m/Y') }}</div>
                                        <div class="text-[10px]">{{ $ped->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="py-3 px-2 font-semibold text-gray-800">${{ number_format($ped->total, 2) }}</td>
                                    <td class="py-3 px-2">
                                        @php
                                            $statusClasses = [
                                                'entregado' => 'bg-green-100 text-green-700',
                                                'pendiente' => 'bg-yellow-100 text-yellow-700',
                                                'cancelado' => 'bg-red-100 text-red-700',
                                                'pagado'    => 'bg-blue-100 text-blue-700',
                                                'enviado'   => 'bg-indigo-100 text-indigo-700'
                                            ];
                                            $currentStatus = strtolower(trim($ped->status));
                                        @endphp
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusClasses[$currentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ $ped->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2">
                                        <form action="{{ route('admin.orders.updateStatus', $ped) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-[11px] border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 py-1">
                                                @foreach(['Pendiente', 'Pagado', 'Enviado', 'Entregado', 'Cancelado'] as $opt)
                                                    <option value="{{ $opt }}" {{ ucfirst($currentStatus) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts y Estilos --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('ventasChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Ventas ($)',
                    data: {!! json_encode($data) !!},
                    borderWidth: 3,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { callback: value => '$' + value } }
                }
            }
        });
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #ea580c; }
    </style>
</x-app-layout>