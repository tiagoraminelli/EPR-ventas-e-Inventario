<!-- Sidebar lateral -->
<aside class="w-64 h-screen bg-white shadow-lg rounded-r-xl flex flex-col">

    <!-- Logo o título -->
    <a href="{{ route('dashboard') }}">
        <div class="flex items-center space-x-2 p-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            <span class="text-lg font-bold text-gray-700">Dashboard</span>
        </div>
    </a>

    <!-- Navegación con scroll si excede la altura -->
    <nav class="flex-1 overflow-y-auto px-4 space-y-2">

        <!-- Links principales -->
        <a href="{{ route('products.index') }}"
            class="flex items-center px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('products.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
            </svg>
            Productos
        </a>

        <a href="{{ route('clientes.index') }}"
            class="flex items-center px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('clientes.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87M16 3.13a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            Clientes
        </a>

        <a href="{{ route('ventas.index') }}"
            class="flex items-center px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('ventas.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6h13M9 5v2m0 4v2m0 4v2" />
            </svg>
            Cuentas /C
        </a>

        <a href="{{ route('admin.graficos') }}"
            class="flex items-center px-3 py-2 rounded-lg transition
                  {{ request()->routeIs('admin.graficos') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11 19V6a1 1 0 011-1h7m-8 6h8m-8 6h5" />
            </svg>
            Gráficos
        </a>

        <a href="{{ route('reparaciones.index') }}"
            class="flex items-center px-3 py-2 rounded-lg transition
           {{ request()->routeIs('reparaciones.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'hover:bg-gray-100 text-gray-600' }}">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 6V4m0 2a1.5 1.5 0 01-1.5-1.5M12 6a1.5 1.5 0 00-1.5 1.5M12 6v2m0 0h-2m2 0h2m-2 0a1 1 0 01-1-1h-1a1 1 0 01-1-1v-2a1 1 0 011-1h1a1 1 0 011 1v2a1 1 0 01-1 1z" />
            </svg>
            Reparaciones
        </a>
        <!-- Sección complementaria desplegable -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center w-full px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Servicios
                <svg :class="{ 'transform rotate-180': open }" class="ml-auto w-4 h-4 transition-transform"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" class="mt-1 space-y-1 pl-8">
                <a href="{{ route('serviciosventas.index') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                    Detalle de Servicios en C/C
                </a>
                <a href="{{ route('reparacionservicios.index') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                    Detalle de Servicios en Reparaciones
                </a>
                <a href="{{ route('reparacionproductos.index') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                    Detalle de Productos en Reparaciones
                </a>
            </div>
        </div>

        <!-- Sección complementaria desplegable -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open"
                class="flex items-center w-full px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                Complementos Necesarios
                <svg :class="{ 'transform rotate-180': open }" class="ml-auto w-4 h-4 transition-transform"
                    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="open" class="mt-1 space-y-1 pl-8">
                <a href="{{ route('marcas.index') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                    Marcas de productos
                </a>
                <a href="{{ route('categorias.index') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                    Categorías de productos
                </a>
                <a href="{{ route('servicios.index') }}"
                    class="flex items-center px-3 py-2 rounded-lg transition hover:bg-gray-100 text-gray-600">
                    Servicios de la empresa
                </a>
            </div>
        </div>

    </nav>

</aside>
