<x-app-layout>
    <div class="bg-white min-h-screen">

        {{-- HERO MODERNO --}}
        <header class="relative min-h-[80vh] flex items-center justify-center overflow-hidden">

            {{-- Fondo sutil --}}
            <div class="absolute inset-0 bg-gradient-to-b from-white via-gray-100 to-white"></div>

            <div class="relative z-10 text-center px-4">
                <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tight text-black">
                    Ferretería <span class="text-gray-400">Octavo</span>
                </h1>

                <p class="mt-6 text-gray-500 italic tracking-wide">
                    — Precisión, calidad y confianza en cada herramienta.
                </p>

                {{-- BOTÓN PROTAGONISTA --}}
                <div class="mt-12">
                    <a href="#catalogo" class="inline-block bg-black text-white px-12 py-4 text-sm font-bold uppercase tracking-widest 
                              hover:bg-white hover:text-black border-2 border-black transition-all duration-300
                              shadow-md hover:shadow-xl">
                        Ver Catálogo
                    </a>
                </div>
            </div>
        </header>


        {{-- SEPARADOR VISUAL (CLAVE PARA EL SALTO) --}}
        <div class="w-full flex justify-center py-10">
            <div class="h-[1px] w-32 bg-gradient-to-r from-transparent via-black to-transparent"></div>
        </div>


        {{-- BENEFICIOS REFINADOS --}}
        <section class="py-12 border-y border-gray-100">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    @php
                        $beneficios = [
                            ['icon' => 'local_shipping', 'titulo' => 'Envíos Nacionales', 'desc' => 'Logística optimizada'],
                            ['icon' => 'verified', 'titulo' => 'Calidad Octavo', 'desc' => 'Garantía de precisión'],
                            ['icon' => 'support_agent', 'titulo' => 'Soporte Técnico', 'desc' => 'Atención especializada'],
                            ['icon' => 'payments', 'titulo' => 'Pago Seguro', 'desc' => 'Transacciones cifradas'],
                        ];
                    @endphp

                    @foreach($beneficios as $b)
                        <div class="flex items-start gap-4 group">
                            <span
                                class="material-symbols-outlined text-black text-3xl group-hover:scale-110 transition">{{ $b['icon'] }}</span>
                            <div>
                                <h4 class="text-xs font-extrabold uppercase tracking-widest text-black">{{ $b['titulo'] }}
                                </h4>
                                <p class="text-[11px] text-gray-400 mt-1 uppercase">{{ $b['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        {{-- SEPARADOR MÁS FUERTE PARA EL SALTO --}}
        <div class="w-full py-16 bg-gray-50 text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Explora</p>
            <h2 class="text-3xl font-black tracking-widest text-black mt-2">
                Nuestra Colección
            </h2>
        </div>


        {{-- CATÁLOGO --}}
        <section id="catalogo" class="py-20 bg-white">
            <div class="container mx-auto px-4">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                    @foreach($productos as $producto)

                        <div class="group">

                            {{-- CARD --}}
                            <div class="group border-none">
                                <div class="relative overflow-hidden bg-[#f3f3f3] transition-all duration-500">
                                    {{-- IMAGEN --}}
                                    <div class="aspect-square flex items-center justify-center p-12">
                                        @if($producto->imagen)
                                            <img src="{{ asset('storage/' . $producto->imagen) }}"
                                                class="w-full h-full object-contain grayscale group-hover:grayscale-0 transition-all duration-700 group-hover:scale-105">
                                        @else
                                            <span
                                                class="text-gray-200 text-2xl font-black uppercase tracking-tighter">Octavo</span>
                                        @endif
                                    </div>

                                    {{-- BOTÓN OVERLAY CUADRADO --}}
                                    <div
                                        class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                        <a href="{{ route('productos.index') }}"
                                            class="bg-black text-white px-8 py-3 text-[10px] font-bold uppercase tracking-[0.2em] transition-transform translate-y-4 group-hover:translate-y-0">
                                            Ver Detalles
                                        </a>
                                    </div>
                                </div>

                                {{-- INFO ALINEADA AL ESTILO AXON --}}
                                <div class="mt-6 flex justify-between items-start">
                                    <div class="max-w-[70%]">
                                        <h3 class="text-[10px] text-gray-400 font-bold tracking-[0.2em] uppercase">
                                            {{ $producto->categoria->nombre ?? 'Herramienta' }}
                                        </h3>
                                        <p
                                            class="text-sm font-extrabold text-black uppercase mt-1 leading-tight tracking-tighter">
                                            {{ $producto->nombre }}
                                        </p>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900 tracking-tighter">
                                        ${{ number_format($producto->precio, 2) }}
                                    </p>
                                </div>
                            </div>

                            {{-- INFO --}}
                            <div class="mt-4 flex justify-between items-start uppercase">
                                <div>
                                    <h3 class="text-[10px] text-gray-400 tracking-widest">
                                        {{ $producto->categoria->nombre ?? 'General' }}
                                    </h3>
                                    <p class="text-sm font-black text-black">
                                        {{ $producto->nombre }}
                                    </p>
                                </div>

                                <p class="text-sm font-light text-black">
                                    ${{ number_format($producto->precio, 2) }}
                                </p>
                            </div>

                        </div>

                    @endforeach
                </div>
            </div>
        </section>

    </div>
</x-app-layout>
