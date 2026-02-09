<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
<link rel="stylesheet" href="{{ asset('css/panel.css') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray text-gray-800">

<div class="min-h-screen p-6">

    <!-- Header -->
    <header class="max-w-6xl mx-auto mb-12">
        <h1 class="text-3xl font-semibold text-gray-900">
            Panel de Control
        </h1>
        <p class="mt-2 text-sm text-gray-500">
            Gestión general del sistema
        </p>
    </header>

    <main class="max-w-6xl mx-auto space-y-12">

        <!-- ================= VISTAS ================= -->
        <section>
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-4">
                Vistas generales
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('products.index') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-boxes text-gray-400 mb-3"></i>
                    <p class="font-medium">Productos</p>
                </a>

                <a href="{{ route('ventas.index') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-shopping-cart text-gray-400 mb-3"></i>
                    <p class="font-medium">Ventas</p>
                </a>

                <a href="{{ route('clientes.index') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-user text-gray-400 mb-3"></i>
                    <p class="font-medium">Clientes</p>
                </a>

                <a href="{{ route('marcas.index') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-tags text-gray-400 mb-3"></i>
                    <p class="font-medium">Marcas</p>
                </a>

                <a href="{{ route('categorias.index') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-list text-gray-400 mb-3"></i>
                    <p class="font-medium">Categorías</p>
                </a>

                <a href="{{ route('admin.graficos') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-chart-line text-gray-400 mb-3"></i>
                    <p class="font-medium">Gráficos</p>
                </a>
            </div>
        </section>

        <!-- ================= CREAR ================= -->
        <section>
            <h2 class="text-sm font-semibold text-gray-500 uppercase mb-4">
                Crear nuevo
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <a href="{{ route('products.create') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-box text-gray-400 mb-3"></i>
                    <p class="font-medium">Nuevo producto</p>
                </a>

                <a href="{{ route('ventas.create') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-cart-plus text-gray-400 mb-3"></i>
                    <p class="font-medium">Nueva venta</p>
                </a>

                <a href="{{ route('clientes.create') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-user-plus text-gray-400 mb-3"></i>
                    <p class="font-medium">Nuevo cliente</p>
                </a>

                <a href="{{ route('marcas.create') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-plus text-gray-400 mb-3"></i>
                    <p class="font-medium">Nueva marca</p>
                </a>

                <a href="{{ route('categorias.create') }}" class="border rounded-lg p-5 hover:bg-gray-100 transition">
                    <i class="fas fa-folder-plus text-gray-400 mb-3"></i>
                    <p class="font-medium">Nueva categoría</p>
                </a>
            </div>
        </section>

    </main>
</div>

</body>
</html>
