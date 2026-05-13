<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::check() && Auth::user()->role === 'admin' ? __('Gestión de Categorías') : __('Nuestras Categorías') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- SOLO EL ADMIN VE EL FORMULARIO --}}
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-bold mb-4">Agregar Nueva Sección</h3>
                        <form action="{{ route('categorias.store') }}" method="POST" class="flex items-end gap-4">
                            @csrf
                            <div class="flex-1">
                                <x-input-label for="nombre" :value="__('Nombre')" />
                                <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" required />
                            </div>
                            <x-primary-button>{{ __('Guardar') }}</x-primary-button>
                        </form>
                    </div>
                @endif

                {{-- TODOS VEN LA LISTA (Pero con estilos diferentes) --}}
                <h3 class="font-bold text-lg mb-4 text-orange-600">Explorar Pasillos</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @forelse ($categorias as $categoria)
                        <a href="{{ route('categorias.index', $categoria->id) }}"
                            class="border p-4 rounded-lg hover:bg-orange-50 transition shadow-sm flex justify-between items-center cursor-pointer group">
                            <span
                                class="font-medium text-gray-800 uppercase group-hover:text-orange-600">{{ $categoria->nombre }}</span>
                            <svg class="w-5 h-5 text-gray-400 group-hover:text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </a>
                    @empty
                        <p class="text-gray-500">No hay categorías disponibles.</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
</x-app-layout>