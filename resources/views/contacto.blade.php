<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ponte en Contacto con Nosotros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">

                {{-- Encabezado Principal --}}
                <div class="text-center mb-12">
                    <h1 class="text-4xl font-extrabold text-gray-900">Ferretería Octavo</h1>
                    <p class="mt-4 text-lg text-gray-600 max-w-2xl mx-auto">
                        ¿Tienes dudas sobre una herramienta? ¿Necesitas un presupuesto para tu obra? Estamos aquí para
                        ayudarte. Contáctanos por cualquiera de nuestros medios o visítanos en nuestra sucursal.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">

                    {{-- Columna Derecha: Tarjetas de Información --}}
                    <div class="space-y-6">
                        {{-- Tarjeta: Dirección --}}
                        <div class="flex items-start bg-gray-50 p-6 rounded-xl shadow-sm border">
                            <div class="bg-orange-100 text-orange-600 p-3 rounded-lg mr-4">
                                {{-- Icono de Ubicación --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Visítanos en la Ferretería</h3>
                                <p class="text-gray-600 mt-1">Av. Tecnológico #123, Col. Centro</p>
                                <p class="text-gray-600">Ciudad Cuauhtémoc, Chihuahua. CP 31500</p>
                            </div>
                        </div>

                        {{-- Tarjeta: Teléfono / WhatsApp --}}
                        <div class="flex items-start bg-gray-50 p-6 rounded-xl shadow-sm border">
                            <div class="bg-green-100 text-green-600 p-3 rounded-lg mr-4">
                                {{-- Icono de Teléfono --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Llámanos o Envíanos un WhatsApp</h3>
                                <p class="text-gray-600 mt-1">Oficina: <span class="font-semibold">(625) 150-6705</span>
                                </p>
                                <a href="https://wa.me/526251506705" target="_blank"
                                    class="flex items-center text-green-700 mt-1 hover:underline">
                                    <span class="mr-1">WhatsApp Business</span>
                                    {{-- Pequeño icono de enlace externo --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>
                        </div>

                        {{-- Tarjeta: Correo --}}
                        <div class="flex items-start bg-gray-50 p-6 rounded-xl shadow-sm border">
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-lg mr-4">
                                {{-- Icono de Correo --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">Escríbenos por Correo</h3>
                                <p class="text-gray-600 mt-1">contacto@ferreteriaoctavo.com</p>
                                <p class="text-gray-600">ventas@ferreteriaoctavo.com</p>
                            </div>
                        </div>
                    </div>

                    {{-- Columna Izquierda: Mapa de Google (Embebido) --}}
                    <div class="bg-gray-100 p-2 rounded-xl border-4 border-dashed border-gray-200">
                        {{-- Aquí puse un mapa genérico, pero puedes reemplazar el 'src' por tu ubicación real en Google
                        Maps --}}
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d111624.97022137533!2d-106.91979435!3d28.406981450000003!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8697193d25816281%3A0xc319208f654b9d0e!2sCuauht%C39m%C3c%2C%20Chih.!5e0!3m2!1ses!2smx!4v1714421110595!5m2!1ses!2smx"
                            width="100%" height="100%" style="border:0; min-height: 400px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="rounded-lg shadow-inner">
                        </iframe>
                    </div>

                </div>

                {{-- Horarios de Atención --}}
                <div class="mt-16 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8">
                        {{-- Título con acento naranja --}}
                        <div class="flex items-center mb-8">
                            <div class="h-8 w-1.5 bg-orange-500 rounded-full mr-3"></div>
                            <h3 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Nuestros Horarios de
                                Atención</h3>
                        </div>

                        <div
                            class="grid grid-cols-1 md:grid-cols-3 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">
                            {{-- Lunes a Viernes --}}
                            <div class="py-6 md:px-8 flex flex-col items-center md:items-start">
                                <span
                                    class="text-orange-600 font-bold text-xs uppercase tracking-widest mb-3">Semana</span>
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-black text-gray-900">8:00</span>
                                    <span class="text-sm font-semibold text-gray-500 ml-1 uppercase">am</span>
                                    <span class="mx-2 text-gray-300">—</span>
                                    <span class="text-3xl font-black text-gray-900">6:00</span>
                                    <span class="text-sm font-semibold text-gray-500 ml-1 uppercase">pm</span>
                                </div>
                                <p class="text-gray-400 text-xs mt-2 font-medium">Lunes a Viernes</p>
                            </div>

                            {{-- Sábado --}}
                            <div class="py-6 md:px-8 flex flex-col items-center md:items-start">
                                <span class="text-orange-600 font-bold text-xs uppercase tracking-widest mb-3">Fin de
                                    Semana</span>
                                <div class="flex items-baseline">
                                    <span class="text-3xl font-black text-gray-900">9:00</span>
                                    <span class="text-sm font-semibold text-gray-500 ml-1 uppercase">am</span>
                                    <span class="mx-2 text-gray-300">—</span>
                                    <span class="text-3xl font-black text-gray-900">3:00</span>
                                    <span class="text-sm font-semibold text-gray-500 ml-1 uppercase">pm</span>
                                </div>
                                <p class="text-gray-400 text-xs mt-2 font-medium">Sábados</p>
                            </div>

                            {{-- Domingo --}}
                            <div class="py-6 md:px-8 flex flex-col items-center md:items-start bg-gray-50/50">
                                <span
                                    class="text-red-500 font-bold text-xs uppercase tracking-widest mb-3">Descanso</span>
                                <p class="text-2xl font-bold text-gray-400">Cerrado</p>
                                <p class="text-gray-400 text-xs mt-2 italic font-medium">Domingos y Días Festivos</p>
                            </div>
                        </div>
                    </div>

                    {{-- Pie de tarjeta sutil --}}
                    <div class="bg-gray-50 border-t border-gray-100 py-3 px-8 text-center md:text-left">
                        <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.2em]">
                            Servicio profesional de ferretería en Ciudad Cuauhtémoc
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>