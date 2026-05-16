<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::check() && Auth::user()->role === 'admin' ? 'Inventario de Ferretería' : 'Catálogo de Productos' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- MENSAJES DE ESTADO --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORMULARIO SOLO PARA ADMIN (Protegido con @auth) --}}
            @auth
                @if(Auth::user()->role === 'admin')
                    <div class="bg-white p-6 rounded-lg shadow mb-8 border-t-4 border-orange-500">
                        <h3 class="font-bold text-lg mb-4 text-gray-700 uppercase tracking-wider">Registrar Nuevo Producto</h3>
                        <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input type="text" name="nombre" placeholder="Nombre del producto"
                                    class="rounded border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    required>
                                <input type="number" name="precio" placeholder="Precio" step="0.01"
                                    class="rounded border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    required>
                                <input type="number" name="stock" placeholder="Stock"
                                    class="rounded border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    required>

                                <select name="category_id"
                                    class="rounded border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>

                                <input type="file" name="imagen"
                                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">

                                <x-primary-button class="justify-center bg-orange-600 hover:bg-orange-700 transition">Guardar
                                    Producto</x-primary-button>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth

            <div x-data="{ search: '' }">
                {{-- Barra de Búsqueda --}}
                <div class="mb-6">
                    <input type="text" x-model="search" placeholder="Buscar herramienta, material..."
                        class="w-full p-3 rounded-xl border-gray-200 shadow-sm focus:ring-orange-500 focus:border-orange-500">
                </div>

                {{-- LISTADO DE PRODUCTOS (GRID) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($productos as $producto)
                            <div x-show="`{{ strtolower($producto->nombre) }}`.includes(search.toLowerCase())"
                            x-transition
                            class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col h-full border border-gray-100 transition-all duration-300 hover:scale-105 hover:shadow-2xl">

                                {{-- Contenedor de la imagen --}}
                                <div
                                    class="h-48 w-full bg-gray-50 flex items-center justify-center border-b border-gray-100 p-2">
                                    @if($producto->imagen)
                                        <img src="{{ Str::startsWith($producto->imagen, 'http') ? $producto->imagen : asset('storage/' . $producto->imagen) }}"
                                            class="max-h-full max-w-full object-contain" alt="{{ $producto->nombre }}">
                                    @else
                                        <div class="flex flex-col items-center text-gray-300">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span class="text-xs mt-2 uppercase">Sin imagen</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Detalles del Producto --}}
                                <div class="p-4 flex-grow flex flex-col">
                                    <h4 class="font-bold text-gray-800 uppercase text-sm truncate"
                                        title="{{ $producto->nombre }}">
                                        {{ $producto->nombre }}
                                    </h4>
                                    <p class="text-[10px] text-orange-600 font-bold mb-2 tracking-widest">
                                        {{ $producto->category->nombre }}</p>

                                    <div class="mt-auto">
                                        <p class="text-2xl font-black text-gray-900 mb-1">
                                            ${{ number_format($producto->precio, 2) }}</p>

                                        <p
                                            class="text-[10px] uppercase tracking-widest {{ $producto->stock < 5 ? 'text-red-500 font-bold' : 'text-gray-400' }}">
                                            Stock: {{ $producto->stock }}
                                        </p>

                                        {{-- SECCIÓN DE BOTONES --}}
                                        @auth
                                            @if(Auth::user()->role === 'cliente')
                                                <form action="{{ route('cart.add', $producto->id) }}" method="POST" class="mt-4">
                                                    @csrf
                                                    <div class="flex items-center mb-3">
                                                        <label for="quantity"
                                                            class="mr-2 text-sm font-semibold text-gray-700">Cant:</label>
                                                        <input type="number" name="quantity" value="1" min="1"
                                                            max="{{ $producto->stock }}"
                                                            class="w-20 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    </div>

                                                    <button type="submit"
                                                        class="w-full bg-black text-white text-[10px] font-bold py-3 uppercase tracking-widest hover:bg-gray-800 transition-all duration-300"">
                                                                AÑADIR AL CARRITO
                                                            </button> 
                                                        </form>
                                            @endif

                                                {{-- BOTONES PARA ADMIN (Ahora protegidos) --}}
                                                @if(Auth::user()->role === 'admin')
                                                                <div class=" mt-4 flex gap-2 border-t pt-4 border-gray-50">
                                                                <a href="{{ route('productos.edit', $producto) }}"
                                                                    class="flex-1 text-center bg-gray-100 text-gray-700 text-[10px] font-bold py-2 rounded hover:bg-yellow-400 hover:text-white transition uppercase">
                                                                    Editar
                                                                </a>
                                                                <form action="{{ route('productos.destroy', $producto) }}" method="POST"
                                                                    class="flex-1">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" onclick="return confirm('¿Seguro?')"
                                                                        class="w-full bg-gray-100 text-gray-400 text-[10px] font-bold py-2 rounded hover:bg-red-600 hover:text-white transition uppercase">
                                                                        Borrar
                                                                    </button>
                                                                </form>
                                                    </div>
                                                @endif
                                        @else
                                        {{-- Botón para Invitados --}}
                                        <a href="{{ route('login') }}"
                                            class="w-full mt-4 block text-center bg-gray-200 text-gray-700 text-xs font-bold py-2 rounded shadow hover:bg-gray-300 transition uppercase">
                                            Inicia sesión para comprar
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>            
            </div>
        </div>
    </div>
</x-app-layout>