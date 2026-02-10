<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Categorías</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200">
            <x-admin-nav />
        </aside>

        <!-- Contenido principal -->
        <main class="flex-1 p-6">
            <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow">

                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
                    <h1 class="text-2xl font-semibold text-gray-800">
                        Categorías
                    </h1>

                    <a href="{{ route('categorias.create') }}"
                        class="mt-4 md:mt-0 inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                        <i class="fas fa-plus"></i>
                        Nueva categoría
                    </a>
                </div>

                <!-- Buscador -->
                <form action="{{ route('categorias.index') }}" method="GET"
                    class="mb-6 flex flex-col sm:flex-row gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por nombre o descripción..."
                        class="flex-1 rounded-lg border-gray-300 text-sm focus:ring-gray-800 focus:border-gray-800">

                    <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white text-sm rounded-lg hover:bg-gray-800 transition">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </form>

                <!-- Mensaje éxito -->
                @if (session('success'))
                    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabla -->
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full bg-white text-sm">
                        <thead class="bg-gray-50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Nombre</th>
                                <th class="px-6 py-3 text-left font-semibold text-gray-600">Descripción</th>
                                <th class="px-6 py-3 text-center font-semibold text-gray-600">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse ($categorias as $categoria)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 text-gray-800">
                                        {{ $categoria->nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500">
                                        {{ $categoria->descripcion }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-3">

                                            <a href="{{ route('categorias.edit', $categoria->id) }}"
                                                class="text-gray-600 hover:text-gray-900 transition"
                                                title="Editar">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form action="{{ route('categorias.destroy', $categoria->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('¿Eliminar esta categoría?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 transition"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-6 text-center text-gray-500">
                                        No hay categorías registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $categorias->links() }}
                </div>

            </div>
        </main>

    </div>

</body>
</html>
