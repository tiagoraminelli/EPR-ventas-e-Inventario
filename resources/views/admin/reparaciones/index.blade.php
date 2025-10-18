<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reparaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/select2.css') }}" rel="stylesheet"> <!-- Estilo personalizado Select2 -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-indigo-100 font-sans">
    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Gestión de Reparaciones</h1>
                    <a href="{{ route('reparaciones.create') }}"
                        class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 transition duration-300 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> Nueva Reparación
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6"
                        role="alert">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6" role="alert">
                        {{ session('error') }}</div>
                @endif

                <!-- FILTROS -->
                <form action="{{ route('reparaciones.index') }}" method="GET" class="mb-6">
                    <div class="flex flex-wrap md:flex-nowrap items-center space-y-4 md:space-y-0 md:space-x-4">
                        <!-- Búsqueda general -->
                        <input type="search" name="search" placeholder="Buscar por código, cliente o equipo..."
                            class="w-full md:w-auto flex-grow rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            value="{{ request('search') }}">

                        <!-- Cliente -->
                        <select name="cliente_id" id="cliente_id"
                            class="w-full md:w-auto flex-grow rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            <option value="">Todos los clientes</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->NombreCompleto ?? $cliente->RazonSocial }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Estado -->
                        <select name="estado_reparacion"
                            class="w-full md:w-auto flex-grow rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            <option value="">Todos los estados</option>
                            @foreach (['Pendiente', 'En proceso', 'Reparado', 'No reparable', 'Entregado'] as $estado)
                                <option value="{{ $estado }}"
                                    {{ request('estado_reparacion') == $estado ? 'selected' : '' }}>
                                    {{ $estado }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Reparable -->
                        <select name="reparable"
                            class="w-full md:w-auto flex-grow rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            <option value="">Situación</option>
                            <option value="1" {{ request('reparable') === '1' ? 'selected' : '' }}>Reparable
                            </option>
                            <option value="0" {{ request('reparable') === '0' ? 'selected' : '' }}>No reparable
                            </option>
                        </select>

                        <!-- Fecha ingreso -->
                        <input type="date" name="fecha_ingreso"
                            class="w-full md:w-auto flex-grow rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            value="{{ request('fecha_ingreso') }}">

                        <!-- Botón Filtrar -->
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white font-semibold rounded-full shadow-lg hover:bg-blue-600 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i> Filtrar
                        </button>
                    </div>
                </form>

                <!-- TABLA -->
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Código</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Cliente</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Equipo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Estado</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Fecha de Ingreso</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if (count($reparaciones) > 0)
                                @foreach ($reparaciones as $reparacion)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $reparacion->codigo_unico }}
                                            <span class="text-xs text-gray-500 ml-1">#{{ $reparacion->id }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $reparacion->equipo_descripcion }} - {{ $reparacion->equipo_marca }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @php
                                                $colors = [
                                                    'Pendiente' => 'yellow',
                                                    'En proceso' => 'blue',
                                                    'Reparado' => 'green',
                                                    'Entregado' => 'purple',
                                                    'No reparable' => 'red',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ $colors[$reparacion->estado_reparacion] ?? 'gray' }}-100 text-{{ $colors[$reparacion->estado_reparacion] ?? 'gray' }}-800">
                                                {{ $reparacion->estado_reparacion }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('reparaciones.show', $reparacion->id) }}"
                                                class="text-green-600 hover:text-green-900 mr-2" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('reparaciones.edit', $reparacion->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900 mr-2" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" onclick="openDeleteModal({{ $reparacion->id }})"
                                                class="text-red-600 hover:text-red-900" title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron reparaciones.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </main>
    </div>

    <!-- DELETE MODAL -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">¿Estás seguro?</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Esta acción eliminará la reparación. ¿Deseas continuar?</p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:w-auto sm:text-sm">
                            Eliminar
                        </button>
                    </form>
                    <button id="closeModal"
                        class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 mt-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        function openDeleteModal(id) {
            // Genera la URL usando route() con un placeholder :id
            deleteForm.action = "{{ route('reparaciones.destroy', ':id') }}".replace(':id', id);
            deleteModal.classList.remove('hidden');
        }

        document.getElementById('closeModal').addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        window.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });

        // Inicializar Select2 para Cliente
        $(document).ready(function() {
            $('#cliente_id').select2({
                placeholder: "Seleccione un cliente",
                allowClear: true,
                width: 'resolve',
                dropdownCssClass: 'bg-white border border-gray-300 rounded-md shadow-sm'
            });
        });
    </script>
</body>

</html>
