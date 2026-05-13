<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Categoría: ') }} {{ $category->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <a href="{{ route('categorias.index') }}" class="text-blue-600 hover:underline mb-6 inline-block">← Volver a
                todas las categorías</a>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($productos as $producto)
                    {{-- Aquí pegamos el mismo diseño de tarjeta con animación que usamos en productos.index --}}
                    <div
                        class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full border border-gray-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                        <div class="h-48 w-full bg-gray-50 flex items-center justify-center p-2">
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}"
                                    class="max-h-full max-w-full object-contain">
                            @else
                                <span class="text-gray-300 uppercase text-xs">Sin imagen</span>
                            @endif
                        </div>
                        <div class="p-4 flex-grow flex flex-col">
                            <h4 class="font-bold text-gray-800 uppercase text-sm truncate">{{ $producto->nombre }}</h4>
                            <p class="text-2xl font-black text-gray-900 mt-2">${{ number_format($producto->precio, 2) }}</p>

                            {{-- SELECTOR DE CANTIDAD Y BOTÓN ESTILO AXON --}}
                            @auth
                                @if(Auth::user()->role === 'cliente')
                                    <form action="{{ route('cart.add', $producto->id) }}" method="POST" class="mt-4 space-y-3">
                                        @csrf

                                        <div class="flex items-center border border-black/10">
                                            {{-- Etiqueta visual --}}
                                            <span
                                                class="px-3 text-[10px] font-bold uppercase tracking-widest text-gray-400 border-r border-black/10">
                                                CANT
                                            </span>

                                            {{-- Input numérico estilizado --}}
                                            <input type="number" name="quantity" value="1" min="1" max="{{ $producto->stock }}"
                                                class="w-full border-none focus:ring-0 text-sm font-bold text-center bg-transparent"
                                                required>
                                        </div>

                                        <button type="submit"
                                            class="w-full bg-black text-white text-[10px] font-bold py-3 uppercase tracking-widest hover:bg-gray-800 transition-all duration-300">
                                            Añadir al Carrito
                                        </button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-10">No hay productos en esta categoría todavía.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>