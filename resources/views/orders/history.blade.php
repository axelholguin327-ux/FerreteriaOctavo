<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Compras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($orders->isEmpty())
                    <p class="text-gray-500 text-center">Aún no has realizado ninguna compra.</p>
                    <div class="mt-4 text-center">
                        <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Ir a la tienda</a>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($orders as $order)
                                        <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                                            <div class="flex justify-between items-center border-b pb-2 mb-4">
                                                <div>
                                                    <span class="text-sm text-gray-500">Pedido #{{ $order->id }}</span>
                                                    <p class="text-xs text-gray-400">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase
                            {{ $order->status === 'entregado' ? 'bg-green-100 text-green-700' :
                                ($order->status === 'pendiente' ? 'bg-yellow-100 text-yellow-700' :
                                    ($order->status === 'cancelado' ? 'bg-red-100 text-red-700' :
                                        ($order->status === 'pagado' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700'))) }}">
                                                        {{ $order->status }}
                                                    </span>
                                                    <p class="font-bold text-lg text-gray-800 mt-1">${{ number_format($order->total, 2) }}
                                                    </p>
                                                </div>
                                            </div>

                                            {{-- Detalle de productos --}}
                                            <ul class="divide-y divide-gray-100">
                                                @foreach($order->orderItems as $item)
                                                    <li class="py-2 flex justify-between">
                                                        <span>{{ $item->product ? $item->product->nombre : 'Producto eliminado' }}
                                                            (x{{ $item->cantidad }})</span>
                                                        <span
                                                            class="text-gray-600">${{ number_format($item->precio_unitario * $item->cantidad, 2) }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>