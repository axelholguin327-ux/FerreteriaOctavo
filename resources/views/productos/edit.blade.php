<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Producto: ') }} {{ $producto->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow border-t-4 border-yellow-400">
                <form action="{{ route('productos.update', $producto) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- ¡IMPORTANTE! Para actualizar usamos PUT --}}

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Nombre --}}
                        <div>
                            <x-input-label for="nombre" :value="__('Nombre del Producto')" />
                            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full"
                                :value="old('nombre', $producto->nombre)" required />
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <x-input-label for="category_id" :value="__('Categoría')" />
                            <select name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" {{ $producto->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Precio --}}
                        <div>
                            <x-input-label for="precio" :value="__('Precio')" />
                            <x-text-input id="precio" name="precio" type="number" step="0.01" class="mt-1 block w-full"
                                :value="old('precio', $producto->precio)" required />
                        </div>

                        {{-- Stock --}}
                        <div>
                            <x-input-label for="stock" :value="__('Stock')" />
                            <x-text-input id="stock" name="stock" type="number" class="mt-1 block w-full"
                                :value="old('stock', $producto->stock)" required />
                        </div>

                        {{-- Imagen actual y nueva --}}
                        <div class="md:col-span-2">
                            <x-input-label :value="__('Imagen del Producto (Opcional)')" />
                            @if($producto->imagen)
                                <div class="mb-2">
                                    <img src="{{ Str::startsWith($producto->imagen, 'http') ? $producto->imagen : asset('storage/' . $producto->imagen) }}"
                                        class="h-20 w-20 object-contain border rounded">
                                    <p class="text-xs text-gray-500 italic">Imagen actual</p>
                                </div>
                            @endif
                            <input type="file" name="imagen" class="mt-1 block w-full text-sm">
                        </div>
                    </div>

                    <div class="mt-6 flex gap-4">
                        <x-primary-button class="bg-yellow-500 hover:bg-yellow-600">Actualizar
                            Producto</x-primary-button>
                        <a href="{{ route('productos.index') }}"
                            class="text-gray-600 text-sm py-2 px-4 hover:underline">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
