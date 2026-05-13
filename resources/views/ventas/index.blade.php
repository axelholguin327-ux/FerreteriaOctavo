<x-app-layout>
    <div x-data="ventaModal()" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alertas --}}
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-md">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800 border-b-2 border-orange-500 pb-1">Historial de Ventas
                    </h3>
                    <button @click="openModal = true"
                        class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg transition-all transform hover:scale-105">
                        + Nueva Venta
                    </button>
                </div>

                {{-- Tabla de historial --}}
                @if($ventas->isEmpty())
                    <div class="text-center py-10">
                        <p class="text-gray-500">Aún no se han registrado ventas el día de hoy.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Folio</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Cliente</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Entrega</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Metodo de Pago</th>
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Detalles</th>          
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($ventas as $venta)
                                                            <tr class="hover:bg-gray-50 transition">
                                                                {{-- FOLIO --}}
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-600">
                                                                    #{{ $venta->id }}
                                                                </td>

                                                                {{-- CLIENTE: Intentamos usar la relación de usuario, si no, el nombre manual --}}
                                                                <td class="px-6 py-4 whitespace-nowrap">
                                                                    <div class="text-sm font-bold text-gray-900">
                                                                        {{-- Si el nombre en la BD no es nulo, usa ese. Si es nulo, usa el del usuario de la cuenta --}}
                                                                        {{ $venta->cliente_nombre ?? ($venta->user->name ?? 'Cliente Desconocido') }}
                                                                    </div>
                                                                </td>

                                                                {{-- TOTAL --}}
                                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-black text-gray-900">
                                                                    ${{ number_format($venta->total, 2) }}
                                                                </td>

                                                                {{-- ACCIONES (Status y Ticket) --}}
                                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                    <div class="flex flex-col items-center space-y-2">
                                                                        {{-- Cambio de Status --}}
                                                                        <form action="{{ route('ventas.updateStatus', $venta->id) }}" method="POST">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <select name="status" onchange="this.form.submit()"
                                                                                class="appearance-none block w-full px-2 py-1 pr-7 text-[10px] font-bold uppercase border-gray-200 focus:ring-black focus:border-black rounded shadow-sm bg-white 
                                    {{ in_array($venta->status, ['Entregado', 'Pagado']) ? 'text-green-600' : ($venta->status == 'Pendiente' ? 'text-orange-500' : ($venta->status == 'Enviado' ? 'text-blue-600' : 'text-red-600')) }}"
                                                                                style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23666%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 0.5rem center; background-size: 0.6em;">

                                                                                {{-- Comparamos con la primera letra Mayúscula para que coincida con el
                                                                                Admin --}}
                                                                                <option value="Pendiente" {{ strtolower(trim($venta->status)) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                                                <option value="Pagado" {{ strtolower(trim($venta->status)) == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                                                                <option value="Enviado" {{ strtolower(trim($venta->status)) == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                                                                <option value="Entregado" {{ strtolower(trim($venta->status)) == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                                                                <option value="Cancelar" {{ strtolower(trim($venta->status)) == 'cancelar' ? 'selected' : '' }}>Cancelar</option>
                                                                            </select>
                                                                        </form>

                                                                        {{-- Botón Ticket --}}
                                                                        <a href="{{ route('ventas.ticket', $venta->id) }}" target="_blank"
                                                                            class="text-gray-400 hover:text-orange-600 transition flex items-center text-[10px] font-bold uppercase">
                                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                                                </path>
                                                                            </svg>
                                                                            Imprimir
                                                                        </a>
                                                                    </div>
                                                                </td>

                                                                {{-- MÉTODO / DIRECCIÓN --}}
                                                                <td class="px-6 py-4 text-center">
                                                                    @if($venta->metodo_entrega === 'Envío')
                                                                        <span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded-full">
                                                                            A DOMICILIO
                                                                        </span>
                                                                        <div class="text-[10px] text-gray-500 mt-1 italic">
                                                                            {{ $venta->direccion_envio }}
                                                                        </div>
                                                                    @else
                                                                        <span class="px-2 py-1 text-xs font-bold bg-gray-100 text-gray-800 rounded-full">
                                                                            RECOGER EN LOCAL
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                               <td class="px-6 py-4">
                                                                    <div class="flex items-center justify-center text-sm font-medium text-gray-900">
                                                                        @if($venta->metodo_pago === 'Tarjeta') 💳 
                                                                        @elseif($venta->metodo_pago === 'Transferencia') 🏦 
                                                                        @else 💵 @endif
                                                                        <span class="ml-2 uppercase">{{ $venta->metodo_pago }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                    <button @click="$dispatch('open-detalle', { 
                                                                            items: {{ $venta->orderItems ? $venta->orderItems->toJson() : '[]' }}, 
                                                                            total: '{{ $venta->total }}', 
                                                                            cliente: '{{ $venta->cliente_nombre ?? ($venta->user->name ?? 'Sin Nombre') }}' 
                                                                        })"
                                                                        class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-3 py-1 rounded-full text-xs font-bold transition-colors border border-blue-200">
                                                                        📦 Ver Productos
                                                                    </button>
                                                                </td>
                                                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- MODAL --}}
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="openModal = false">
                </div>

                <div
                    class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <form action="{{ route('ventas.store') }}" method="POST">
                        @csrf
                        <div class="bg-white px-6 py-6">
                            <h2 class="text-2xl font-black text-gray-800 mb-4 tracking-tight">Registrar Nueva Venta</h2>

                            {{-- Datos del Cliente y Entrega --}}
                            <div x-data="{ metodo: 'Recoger' }" class="mb-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700">Nombre del Cliente</label>
                                        <input type="text" name="cliente_nombre" required placeholder="Ej. Juan Pérez"
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700">Método de Entrega</label>
                                        <select name="metodo_entrega" x-model="metodo" required 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                            <option value="Recoger">Pasar a recoger (Local)</option>
                                            <option value="Envío">Envío a domicilio</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700">Método de Pago</label>
                                        <select name="metodo_pago" required 
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500">
                                            <option value="Efectivo">Efectivo</option>
                                            <option value="Tarjeta">Tarjeta</option>
                                            <option value="Transferencia">Transferencia</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Campo de Dirección (Solo aparece si elige Envío) --}}
                                <div x-show="metodo === 'Envío'" x-transition class="mt-4">
                                    <label class="block text-sm font-bold text-gray-700">Dirección de Envío</label>
                                    <textarea name="direccion_envio" rows="2" :required="metodo === 'Envío'"
                                        placeholder="Calle, Número, Colonia y Referencias..."
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500"></textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Selector de Productos con Buscador --}}
                                <div x-data="{ prodSearch: '' }" class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                    <h4 class="font-bold text-gray-600 mb-3">Seleccionar Productos</h4>
                                    
                                    {{-- Input de búsqueda rápida --}}
                                    <div class="mb-3">
                                        <input type="text" x-model="prodSearch" placeholder="Buscar producto..." 
                                            class="w-full p-2 text-sm border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500">
                                    </div>

                                    <div class="space-y-2 max-h-60 overflow-y-auto">
                                        @foreach($productos as $producto)
                                            {{-- El x-show filtra mientras escribes --}}
                                            <div x-show="`{{ strtolower($producto->nombre) }}`.includes(prodSearch.toLowerCase())"
                                                class="flex items-center justify-between bg-white p-2 rounded shadow-sm">
                                                
                                                <span class="text-sm font-medium">
                                                    {{ $producto->nombre }} - 
                                                    <span class="text-orange-600 font-bold">${{ $producto->precio }}</span>
                                                    <span class="text-[10px] text-gray-400 block">Stock: {{ $producto->stock }}</span>
                                                </span>

                                                <button type="button"
                                                    @click="addItem({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio }}, {{ $producto->stock }})"
                                                    class="bg-green-100 text-green-600 hover:bg-green-600 hover:text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors font-bold text-xl">
                                                    +
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Carrito de la Venta --}}
                                <div class="bg-orange-50 p-4 rounded-lg border border-orange-100">
                                    <h4 class="font-bold text-orange-800 mb-3">Detalle de la Orden</h4>
                                    <template x-for="(item, index) in items" :key="index">
                                        <div class="flex items-center justify-between mb-2 text-sm">
                                            <input type="hidden" :name="'items['+index+'][product_id]'"
                                                :value="item.id">
                                            <span class="w-1/2" x-text="item.nombre"></span>
                                            <input type="number" :name="'items['+index+'][cantidad]'"
                                                x-model="item.cantidad" class="w-16 p-1 border rounded text-center"
                                                min="1" :max="item.stock">
                                            <button type="button" @click="removeItem(index)"
                                                class="text-red-500 ml-2">✕</button>
                                        </div>
                                    </template>
                                    <div class="mt-4 border-t border-orange-200 pt-2 text-right">
                                        <span class="text-lg font-black">Total: $<span
                                                x-text="calculateTotal()"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button type="button" @click="openModal = false"
                                class="text-gray-600 font-bold px-4 py-2 hover:underline">Cancelar</button>
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-bold px-6 py-2 rounded-lg shadow">Finalizar
                                Venta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function ventaModal() {
            return {
                openModal: false,
                items: [],
                addItem(id, nombre, precio, stock) {
                    const existing = this.items.find(i => i.id === id);
                    if (existing) {
                        if (existing.cantidad < stock) existing.cantidad++;
                    } else {
                        this.items.push({ id, nombre, precio, cantidad: 1, stock });
                    }
                },
                removeItem(index) { this.items.splice(index, 1); },
                calculateTotal() {
                    return this.items.reduce((t, i) => t + (i.precio * i.cantidad), 0).toFixed(2);
                }
            }
        }

    </script>
        {{-- MODAL DE DETALLES DE PRODUCTOS --}}
    <div x-data="{ open: false, detalle: { items: [], total: 0, cliente: '' } }" 
        @open-detalle.window="open = true; detalle = $event.detail"
        x-show="open" 
        class="fixed inset-0 z-[60] overflow-y-auto" x-cloak>
        
        <div class="flex items-center justify-center min-h-screen px-4">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

            {{-- Contenido del Modal --}}
            <div class="bg-white rounded-xl shadow-2xl transform transition-all sm:max-w-lg sm:w-full z-10 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Detalle de la Venta</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                </div>

                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Cliente: <span class="font-bold text-gray-900" x-text="detalle.cliente"></span></p>
                    
                    <div class="space-y-3 max-h-64 overflow-y-auto pr-2">
                        <template x-for="item in detalle.items" :key="item.id">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div>
                                    <p class="font-bold text-sm text-gray-800" x-text="item.product.nombre"></p>
                                    <p class="text-xs text-gray-500" x-text="'Cantidad: ' + item.cantidad"></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-orange-600" 
                                    x-text="'$' + (parseFloat(item.precio_unitario || 0) * item.cantidad).toFixed(2)">
                                    </p>
                                    
                                    <p class="text-[10px] text-gray-400" 
                                    x-text="'Unitario: $' + (item.precio_unitario || '0.00')">
                                    </p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-center">
                        <span class="text-gray-600 font-bold uppercase text-xs tracking-widest">Total Pagado</span>
                        <span class="text-2xl font-black text-gray-900" x-text="'$' + detalle.total"></span>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button @click="open = false" class="bg-gray-800 text-white px-6 py-2 rounded-lg font-bold text-sm hover:bg-gray-700">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>