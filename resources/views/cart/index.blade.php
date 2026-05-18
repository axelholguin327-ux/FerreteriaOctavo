<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tu Carrito de Compras</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow">
                @if(count($cartItems) > 0)
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b">
                                <th class="pb-4">Producto</th>
                                <th class="pb-4">Precio</th>
                                <th class="pb-4">Cantidad</th>
                                <th class="pb-4">Subtotal</th>
                                <th class="pb-4">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                                <tr class="border-b">
                                    <td class="py-4 flex items-center">
                                        <img src="{{ Str::startsWith($item->product->imagen, 'http') ? $item->product->imagen : asset('storage/' . $item->product->imagen) }}"
                                            class="w-12 h-12 object-contain mr-4">
                                        {{ $item->product->nombre }}
                                    </td>
                                    <td>${{ number_format($item->product->precio, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>
                                        ${{ number_format($item->product->precio * $item->quantity, 2) }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">

                                            {{-- 1. FORMULARIO PARA ACTUALIZAR CANTIDAD --}}
                                            <form action="{{ route('cart.update', $item->id) }}" method="POST"
                                                class="flex items-center">
                                                @csrf
                                                @method('PATCH')
                                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="1"
                                                    onchange="this.form.submit()"
                                                    class="w-16 border-gray-200 text-sm text-center focus:ring-black focus:border-black">
                                            </form>

                                            {{-- 2. NUEVO FORMULARIO PARA ELIMINAR --}}
                                            <form action="{{ route('cart.remove', $item->product->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="flex items-center text-gray-400 hover:text-red-600 transition-colors duration-300"
                                                    title="Eliminar producto">
                                                    <span class="material-symbols-outlined text-xl">delete</span>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{-- BOTÓN DE CHECKOUT ESTILO AXON --}}
                    <div class="mt-10 border-t border-black pt-8">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-xs font-bold uppercase tracking-widest text-gray-400">Total Estimado</span>
                            <span class="text-2xl font-black tracking-tighter">${{ number_format($total, 2) }}</span>
                        </div>

                        <a href="{{ route('cart.checkout.view') }}"
                            class="block w-full bg-black text-white text-center text-[10px] font-bold py-4 uppercase tracking-[0.2em] hover:bg-gray-800 transition-all duration-300 shadow-lg">
                            Continuar al Pago
                        </a>
                    </div>
                @else
                    <p class="text-center text-gray-500 py-10">Tu carrito está vacío.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
