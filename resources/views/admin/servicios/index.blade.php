<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control de Servicios</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-indigo-100 font-sans">

    <!-- Layout principal -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-admin-nav />

        <!-- Contenido principal -->
        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Panel de Control de Servicios</h1>
                    <a href="{{ route('servicios.create') }}"
                        class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md shadow-sm hover:bg-green-600 transition duration-300 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> Nuevo Servicio
                    </a>
                </div>

                <!-- Formulario de Búsqueda -->
                <div class="mb-6 border-b pb-4">
                    <form action="{{ route('servicios.index') }}" method="GET"
                        class="flex flex-wrap md:flex-nowrap items-center space-y-4 md:space-y-0 md:space-x-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nombre o descripción"
                            class="w-full md:w-auto flex-grow rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">

                        <select name="activo"
                            class="w-full md:w-auto form-select border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            <option value="">Todos los estados</option>
                            <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activo</option>
                            <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
                        </select>

                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white font-semibold rounded-full shadow-lg hover:bg-blue-600 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i> Buscar
                        </button>
                    </form>
                </div>

                <!-- Tabla de Servicios -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">IVA Aplicable</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Activo</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Visible</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($servicios as $servicio)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $servicio->nombre }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $servicio->descripcion }}</td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-500">{{ $servicio->iva_aplicable ? 'Sí' : 'No' }}</td>
                                    <td class="px-6 py-4 text-sm text-center text-gray-500">${{ number_format($servicio->precio, 2) }}</td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $servicio->activo ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            {{ $servicio->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-center">
                                        <span class="px-2 inline-flex text-xs font-semibold rounded-full {{ $servicio->visible ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $servicio->visible ? 'Visible' : 'Oculto' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('servicios.edit', $servicio->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('servicios.destroy', $servicio->id) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $servicios->links() }}
                </div>
            </div>
        </main>
    </div>

</body>

</html>
