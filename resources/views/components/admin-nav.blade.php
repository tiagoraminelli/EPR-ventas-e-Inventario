<!-- Sidebar lateral -->
<aside class="w-64 h-screen bg-white border-r flex flex-col">

    <!-- Logo / Título -->
    <a href="{{ route('dashboard') }}" class="border-b">
        <div class="flex items-center gap-2 px-5 py-4">
            <svg xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-indigo-600"
                fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>

            <span class="text-base font-semibold text-gray-700">
                Acceso Rapido
            </span>
        </div>
    </a>

    <!-- Navegación -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1 text-sm">

        <!-- Productos -->
        <a href="{{ route('products.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md transition
           {{ request()->routeIs('products.*')
                ? 'bg-indigo-50 text-black-700 font-medium'
                : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
            </svg>
            Productos
        </a>

        <!-- Clientes -->
        <a href="{{ route('clientes.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md transition
           {{ request()->routeIs('clientes.*')
                ? 'bg-indigo-50 text-black-700 font-medium'
                : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Clientes
        </a>

        <!-- Ventas -->
        <a href="{{ route('ventas.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md transition
           {{ request()->routeIs('ventas.*')
                ? 'bg-indigo-50 text-black-700 font-medium'
                : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 17v-6h13M9 5v2m0 4v2m0 4v2" />
            </svg>
            Cuentas / C
        </a>

        <!-- Gráficos -->
        <a href="{{ route('admin.graficos') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md transition
           {{ request()->routeIs('admin.graficos')
                ? 'bg-indigo-50 text-black-700 font-medium'
                : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 19V6a1 1 0 011-1h7m-8 6h8m-8 6h5" />
            </svg>
            Gráficos
        </a>

        <!-- Reparaciones -->
        <a href="{{ route('reparaciones.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-md transition
           {{ request()->routeIs('reparaciones.*')
                ? 'bg-indigo-50 text-black-700 font-medium'
                : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6V4m0 2a1.5 1.5 0 01-1.5-1.5M12 6a1.5 1.5 0 00-1.5 1.5M12 6v2m0 0h-2m2 0h2" />
            </svg>
            Reparaciones
        </a>

        <!-- Servicios -->
        <div x-data="{ open: false }" class="pt-2">
            <button @click="open = !open"
                class="flex items-center w-full gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Servicios
                <svg class="ml-auto w-4 h-4 transition-transform"
                     :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" class="mt-1 space-y-1 pl-10 text-gray-600">
                <a href="{{ route('serviciosventas.index') }}" class="block py-1 hover:text-gray-900">
                    Servicios en C/C
                </a>
                <a href="{{ route('reparacionservicios.index') }}" class="block py-1 hover:text-gray-900">
                    Servicios en Reparaciones
                </a>
                <a href="{{ route('reparacionproductos.index') }}" class="block py-1 hover:text-gray-900">
                    Productos en Reparaciones
                </a>
            </div>
        </div>

        <!-- Complementos -->
        <div x-data="{ open: false }" class="pt-2">
            <button @click="open = !open"
                class="flex items-center w-full gap-3 px-3 py-2 rounded-md text-gray-600 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Complementos
                <svg class="ml-auto w-4 h-4 transition-transform"
                     :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" class="mt-1 space-y-1 pl-10 text-gray-600">
                <a href="{{ route('marcas.index') }}" class="block py-1 hover:text-gray-900">
                    Marcas
                </a>
                <a href="{{ route('categorias.index') }}" class="block py-1 hover:text-gray-900">
                    Categorías
                </a>
                <a href="{{ route('servicios.index') }}" class="block py-1 hover:text-gray-900">
                    Servicios
                </a>
            </div>
        </div>

    </nav>
</aside>
