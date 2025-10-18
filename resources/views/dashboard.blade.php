<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .dashboard-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-gray-900">
    <!-- Contenedor principal -->
    <div class="min-h-screen flex flex-col items-center justify-center p-6 bg-gray-900 text-gray-200">
        <header class="text-center mb-10 md:mb-16">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight">Panel de Control de Gestión de RDM</h1>
            <p class="mt-4 text-sm sm:text-base text-gray-400 max-w-xl mx-auto">
                Accede rápidamente a las secciones clave para gestionar productos, ventas, clientes y más.
            </p>
        </header>

        <main class="w-full max-w-6xl mx-auto">
            <!-- Sección de Vistas Generales -->
            <div class="mb-12">
                <h2 class="text-2xl font-semibold text-gray-100 mb-4">Vistas Generales</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Tarjeta de Productos -->
                    <a href="{{ route('products.index') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-boxes text-3xl mb-3 text-blue-400"></i>
                        <span class="font-medium text-lg text-white">Productos</span>
                    </a>
                    <!-- Tarjeta de Ventas -->
                    <a href="{{ route('ventas.index') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-shopping-cart text-3xl mb-3 text-green-400"></i>
                        <span class="font-medium text-lg text-white">Ventas</span>
                    </a>
                    <!-- Tarjeta de Clientes -->
                    <a href="{{ route('clientes.index') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-user-friends text-3xl mb-3 text-yellow-400"></i>
                        <span class="font-medium text-lg text-white">Clientes</span>
                    </a>
                    <!-- Tarjeta de Marcas -->
                    <a href="{{ route('marcas.index') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-tags text-3xl mb-3 text-purple-400"></i>
                        <span class="font-medium text-lg text-white">Marcas</span>
                    </a>
                    <!-- Tarjeta de Categorías -->
                    <a href="{{ route('categorias.index') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-list-alt text-3xl mb-3 text-red-400"></i>
                        <span class="font-medium text-lg text-white">Categorías</span>
                    </a>
                    <!-- Tarjeta de Gráficos -->
                    <a href="{{ route('admin.graficos') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-chart-line text-3xl mb-3 text-indigo-400"></i>
                        <span class="font-medium text-lg text-white">Gráficos</span>
                    </a>
                </div>
            </div>

            <!-- Sección de Acciones de Creación -->
            <div>
                <h2 class="text-2xl font-semibold text-gray-100 mb-4">Crear Nuevo</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Tarjeta de Crear Producto -->
                    <a href="{{ route('products.create') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-box text-3xl mb-3 text-blue-400"></i>
                        <span class="font-medium text-lg text-white">Crear Producto</span>
                    </a>
                    <!-- Tarjeta de Crear Venta -->
                    <a href="{{ route('ventas.create') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-cart-plus text-3xl mb-3 text-green-400"></i>
                        <span class="font-medium text-lg text-white">Crear Venta</span>
                    </a>
                    <!-- Tarjeta de Crear Cliente -->
                    <a href="{{ route('clientes.create') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-user-plus text-3xl mb-3 text-yellow-400"></i>
                        <span class="font-medium text-lg text-white">Crear Cliente</span>
                    </a>
                    <!-- Tarjeta de Crear Marca -->
                    <a href="{{ route('marcas.create') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-plus-circle text-3xl mb-3 text-purple-400"></i>
                        <span class="font-medium text-lg text-white">Crear Marca</span>
                    </a>
                    <!-- Tarjeta de Crear Categoría -->
                    <a href="{{ route('categorias.create') }}"
                       class="dashboard-card flex flex-col items-center p-6 bg-slate-800 rounded-2xl shadow-xl hover:bg-slate-700">
                        <i class="fas fa-folder-plus text-3xl mb-3 text-red-400"></i>
                        <span class="font-medium text-lg text-white">Crear Categoría</span>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
