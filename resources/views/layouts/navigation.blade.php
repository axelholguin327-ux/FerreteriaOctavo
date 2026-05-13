<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                {{-- Logo --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                {{-- Enlaces de Navegación --}}
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    {{-- 1. Dashboard de ADMIN (Solo si es admin) --}}
                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Panel Admin') }}
                        </x-nav-link>
                    @endif
                    {{-- Enlaces para VENTAS (Solo si es ventas o admin) --}}
                    @if(Auth::check() && (Auth::user()->role === 'ventas' || Auth::user()->role === 'admin'))
                        <x-nav-link :href="route('ventas.index')" :active="request()->routeIs('ventas.*')">
                            {{ __('Ventas') }}
                        </x-nav-link>
                    @endif
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('categorias.index')" :active="request()->routeIs('categorias.*')">
                        {{ __('Categorías') }}
                    </x-nav-link>
                    <x-nav-link :href="route('productos.index')" :active="request()->routeIs('productos.*')">
                        {{ __('Productos') }}
                    </x-nav-link>
                    <x-nav-link :href="url('/contacto')" :active="request()->is('contacto')">
                        {{ __('Contacto') }}
                    </x-nav-link>

                    {{-- NUEVO: Mis Compras (Solo para logueados) --}}
                    @auth
                        <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')">
                            {{ __('Mis Compras') }}
                        </x-nav-link>
                    @endauth
                </div>
            </div>

            {{-- LADO DERECHO DEL MENÚ --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6 space-x-4">

                @auth {{-- SI EL USUARIO ESTÁ LOGUEADO --}}
                    {{-- Carrito --}}
                    <a href="{{ route('cart.index') }}"
                        class="relative p-2 text-gray-400 hover:text-orange-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if($cartCount > 0)
                            <span
                                class="absolute top-0 right-0 flex h-5 w-5 items-center justify-center rounded-full bg-orange-600 text-[10px] font-bold text-white shadow-sm transform translate-x-1/4 -translate-y-1/4 animate-bounce">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Dropdown de Usuario --}}
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Perfil') }}</x-dropdown-link>
                            {{-- Opción de Compras en el menú del nombre --}}
                            <x-dropdown-link :href="route('orders.history')">
                                {{ __('Mis Pedidos') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Cerrar Sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>

                @else {{-- SI ES UN INVITADO (NO LOGUEADO) --}}
                    <a href="{{ route('login') }}"
                        class="text-sm text-gray-700 font-bold hover:text-orange-600 transition">INICIAR SESIÓN</a>
                    <a href="{{ route('register') }}"
                        class="text-sm bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 transition">REGISTRARSE</a>
                @endauth
            </div>

            {{-- Botón de Hamburguesa (Móvil) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menú Responsive (Móvil) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Panel Admin') }}
                </x-responsive-nav-link>
            @endif
            @if(Auth::check() && (Auth::user()->role === 'ventas' || Auth::user()->role === 'admin'))
                        <x-responsive-nav-link :href="route('ventas.index')" :active="request()->routeIs('ventas.*')">
                            {{ __('Ventas') }}
                        </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link :href="route('dashboard')"
                :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('categorias.index')"
                :active="request()->routeIs('categorias.*')">{{ __('Categorías') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('productos.index')"
                :active="request()->routeIs('productos.*')">{{ __('Productos') }}</x-responsive-nav-link>

            @auth
                <x-responsive-nav-link :href="route('cart.index')" :active="request()->routeIs('cart.index')">
                    {{ __('Carrito') }} ({{ $cartCount }})
                </x-responsive-nav-link>
            @endauth
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Perfil') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Cerrar Sesión') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-2 space-y-1">
                    <x-responsive-nav-link :href="route('login')">{{ __('Iniciar Sesión') }}</x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')">{{ __('Registrarse') }}</x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>