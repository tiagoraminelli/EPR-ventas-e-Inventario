<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Clientes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-indigo-100 font-sans">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="flex-shrink-0 w-64 bg-white shadow-lg">
            <x-admin-nav />
        </div>

        <!-- Contenido principal -->
        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Panel de Control de Clientes</h1>
                    <a href="{{ route('clientes.create') }}"
                        class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md shadow-sm hover:bg-green-600 transition duration-300 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> Nuevo Cliente
                    </a>
                </div>

                <!-- Formulario de búsqueda -->
                <div class="mb-6 border-b pb-4">
                    <form action="{{ route('clientes.index') }}" method="GET" class="flex space-x-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nombre, razón social, CUIT/DNI o email..."
                            class="flex-grow rounded-md border shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-500 text-white font-semibold rounded-md shadow-sm hover:bg-blue-600 transition duration-300 ease-in-out">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </form>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Tabla de clientes -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Razón Social</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    CUIT/DNI</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tipo Cliente</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Detalle</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($clientes as $cliente)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Illuminate\Support\Str::limit($cliente->NombreCompleto, 12) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cliente->RazonSocial }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cliente->cuit_dni }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cliente->TipoCliente }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cliente->Email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cliente->Detalle }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>

                                            <!---llamar al telofono whatsapp-->
                                            <a href="https://wa.me/{{ $cliente->Telefono }}" target="_blank" class="text-green-600 hover:text-green-900" title="Contactar por WhatsApp">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $clientes->appends(request()->query())->links() }}
                </div>
            </div>
        </main>
    </div>

</body>
</html>
