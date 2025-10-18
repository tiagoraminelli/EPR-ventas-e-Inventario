<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios por Venta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .modal {
            transition: opacity 0.25s ease-in-out;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 40px;
        }
    </style>
    <style>
        /* Ajustar contenedor principal */
        .select2-container .select2-selection--single,
        .select2-container .select2-selection--multiple {
            @apply w-full px-4 py-2 border rounded-md shadow-sm text-gray-700;
            @apply focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
            min-height: 42px;
        }

        /* Texto dentro del select */
        .select2-selection__rendered {
            @apply text-sm text-gray-700;
            line-height: 2rem !important;
        }

        /* Flecha del select */
        .select2-selection__arrow {
            top: 8px !important;
            right: 8px !important;
        }

        /* Dropdown */
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
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Servicios por Venta</h1>
                    <a href="{{ route('serviciosventas.create') }}"
                        class="px-6 py-2 bg-blue-600 text-white font-bold rounded-md shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus-circle mr-2"></i> Nuevo Servicio
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-6 relative"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6 relative"
                        role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Formulario de búsqueda -->
                <form action="{{ route('serviciosventas.index') }}" method="GET" class="mb-6">
                    <div class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                        <input type="search" name="search" placeholder="Buscar por algo..."
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ request('search') }}">

                        <input type="text" name="numero_comprobante" placeholder="numero de comprobante..."
                            class="w-100 px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ request('numero_comprobante') }}">

                        <select name="venta" id="venta-select"
                            class="w-100 px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las ventas</option>
                            @foreach ($ventasFiltro as $ventaFiltroItem)
                                <option value="{{ $ventaFiltroItem->id }}"
                                    {{ request('venta') == $ventaFiltroItem->id ? 'selected' : '' }}>
                                    #{{ $ventaFiltroItem->id }} -
                                    {{ optional($ventaFiltroItem->cliente)->NombreCompleto ?? 'Sin cliente' }}

                                </option>
                            @endforeach
                        </select>


                        <select name="servicios[]" multiple="multiple" id="servicios-select"
                            class="px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 w-full">
                            @foreach ($serviciosFiltro as $servicio)
                                <option value="{{ $servicio->id }}" @if (in_array($servicio->id, (array) request('servicios'))) selected @endif>
                                    {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>


                        <button type="submit"
                            class="px-6 py-2 bg-gray-600 text-white font-bold rounded-md hover:bg-gray-700 transition duration-300">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- Tabla de servicios por venta -->
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Venta - Numero Comprobante</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Cliente</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Servicios</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($ventas as $venta)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $venta->id }} - {{ $venta->numero_comprobante }}
                                    </td>


                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ optional($venta->cliente)->NombreCompleto ?? 'Sin cliente' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <ul>
                                            @foreach ($venta->serviciosVentas as $sv)
                                                <li>{{ $sv->servicio->id ?? 'N/A' }}
                                                    {{ $sv->servicio->nombre ?? 'N/A' }} ({{ $sv->cantidad }}) -
                                                    ${{ number_format($sv->precio, 2, ',', '.') }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('serviciosventas.edit', $venta) }}"
                                            class="text-indigo-600 hover:text-indigo-900" title="Editar Venta Completa">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">No se encontraron
                                    ventas con servicios.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-6">
                    {{ $ventas->links() }}
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">¿Estás seguro?</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">Esta acción eliminará el registro. ¿Deseas continuar?</p>
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
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#servicios-select').select2({
                placeholder: "Filtrar por servicios",
                allowClear: true
            });

            // Select de ventas
            $('#venta-select').select2({
                placeholder: "Selecciona una venta",
                allowClear: true,
                width: 'resolve'
            });
        });

        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        function openDeleteModal(id) {
            deleteForm.action = `/admin/serviciosventas/${id}`;
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
    </script>
</body>

</html>
