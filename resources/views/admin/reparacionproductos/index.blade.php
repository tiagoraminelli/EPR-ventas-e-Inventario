<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos por Reparación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            @apply w-full px-4 py-2 border rounded-md shadow-sm text-gray-700;
            @apply focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
            min-height: 42px;
        }

        .select2-selection__rendered {
            @apply text-sm text-gray-700;
            line-height: 2rem !important;
        }

        .select2-selection__arrow {
            top: 8px !important;
            right: 8px !important;
        }

        .select2-dropdown {
            @apply border border-gray-300 rounded-md shadow-lg;
        }

        .select2-results__option {
            @apply px-4 py-2 text-sm text-gray-700 hover:bg-blue-100 cursor-pointer;
        }

        .select2-results__option--highlighted {
            @apply bg-blue-500 text-white;
        }
    </style>
</head>

<body class="bg-indigo-100 font-sans">
    <div class="flex">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Productos por Reparación</h1>
                    <a href="{{ route('reparacionproductos.create') }}"
                        class="px-6 py-2 bg-blue-600 text-white font-bold rounded-md shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus-circle mr-2"></i> Nuevo Registro
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6">
                        {{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6">
                        {{ session('error') }}</div>
                @endif

                <!-- Formulario de búsqueda -->
                <form action="{{ route('reparacionproductos.index') }}" method="GET" class="mb-6">
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                        <input type="search" name="search" placeholder="Buscar por producto..."
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ request('search') }}">

                          <input type="text" name="codigo_unico" placeholder="código único..."
                            class="max-w-xs px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ request('codigo_unico') }}">

                        <select name="reparacion" id="reparacion-select"
                            class="px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                            <option value="">Todas las reparaciones</option>
                            @foreach ($reparacionesFiltro as $repFiltro)
                                <option value="{{ $repFiltro->id }}"
                                    {{ request('reparacion') == $repFiltro->id ? 'selected' : '' }}>
                                    #{{ $repFiltro->id }} -
                                    {{ optional($repFiltro->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                            class="px-6 py-2 bg-gray-600 text-white font-bold rounded-md hover:bg-gray-700 transition duration-300">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Tabla de reparaciones y productos -->
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Reparación | Código
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Equipo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Productos
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($reparaciones as $reparacion)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        #{{ $reparacion->id }} | {{ $reparacion->codigo_unico }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $reparacion->equipo_descripcion }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <ul>
                                            @foreach ($reparacion->productos as $producto)
                                                <li>
                                                    {{ $producto->nombre ?? 'N/A' }}
                                                    ({{ $producto->pivot->cantidad ?? 1 }})
                                                    -
                                                    ${{ number_format($producto->pivot->precio ?? 0, 2, ',', '.') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('reparacionproductos.edit', $reparacion->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <button onclick="openDeleteModal({{ $reparacion->id }}, this)"
                                            data-url="{{ route('reparacionproductos.destroy', $reparacion->id) }}"
                                            class="text-red-600 hover:text-red-900 ml-2">
                                            <i class="fas fa-trash"></i> Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron registros.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>

                <div class="mt-6">
                    {{ $reparaciones->links() }}
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">¿Estás seguro?</h3>
                <p class="mt-2 text-sm text-gray-500">Esta acción eliminará el registro. ¿Deseas continuar?</p>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md w-full hover:bg-red-700">Eliminar</button>
                    </form>
                    <button id="closeModal"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md w-full mt-2 hover:bg-gray-300">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reparacion-select').select2({
                placeholder: "Selecciona una reparación",
                allowClear: true,
                width: 'resolve'
            });
        });

        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        function openDeleteModal(id, el) {
            const url = el.getAttribute('data-url');
            deleteForm.action = url;
            deleteModal.classList.remove('hidden');
        }

        document.getElementById('closeModal').addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        window.addEventListener('click', (event) => {
            if (event.target === deleteModal) deleteModal.classList.add('hidden');
        });
    </script>

</body>

</html>
