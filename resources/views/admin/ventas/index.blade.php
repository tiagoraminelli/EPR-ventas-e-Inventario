<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .modal {
            transition: opacity 0.25s ease-in-out;
        }
    </style>
</head>

<body class="bg-indigo-100 font-sans">
    <!-- Layout principal -->
    <div class="flex">
        <!-- Sidebar -->
        <x-admin-nav />

        <!-- Contenido principal -->
        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Gestión de Ventas</h1>
                    <a href="{{ route('ventas.create') }}"
                        class="px-6 py-2 bg-blue-600 text-white font-bold rounded-md shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-plus-circle mr-2"></i> Nueva Venta
                    </a>
                </div>

                <!-- Mensaje de éxito o error -->
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
                <form action="{{ route('ventas.index') }}" method="GET" class="mb-6">
                    <div class="flex items-center space-x-4">
                        <!-- Buscador -->
                        <input type="search" name="search" placeholder="Buscar por número, estado, o cliente..."
                            class="w-full px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ request('search') }}">

                        <!-- Select Estado -->
                        <select name="estado_venta"
                            class="px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los estados</option>
                            <option value="Pagada" {{ request('estado_venta') == 'Pagada' ? 'selected' : '' }}>Pagada
                            </option>
                            <option value="Pendiente" {{ request('estado_venta') == 'Pendiente' ? 'selected' : '' }}>
                                Pendiente</option>
                            <option value="Anulada" {{ request('estado_venta') == 'Anulada' ? 'selected' : '' }}>Anulada
                            </option>
                        </select>

                        <!-- Filtro de fecha -->
                        <div class="flex space-x-2">
                            <select name="fecha_venta"
                                class="px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Todas las fechas</option>
                                <option value="Hoy" {{ request('fecha_venta') == 'Hoy' ? 'selected' : '' }}>Hoy
                                </option>
                                <option value="Ayer" {{ request('fecha_venta') == 'Ayer' ? 'selected' : '' }}>Ayer
                                </option>
                                <option value="Esta semana"
                                    {{ request('fecha_venta') == 'Esta semana' ? 'selected' : '' }}>Esta semana
                                </option>
                                <option value="Este mes" {{ request('fecha_venta') == 'Este mes' ? 'selected' : '' }}>
                                    Este mes</option>
                            </select>

                            <!-- Fecha exacta -->
                            <input type="date" name="fecha_venta_exacta" value="{{ request('fecha_venta_exacta') }}"
                                class="px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Fecha exacta">
                        </div>


                        <!-- Select de Condicion de Pago -->
                        <select name="condicion_pago"
                            class="px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todas las condiciones</option>
                            <option value="Contado Efectivo"
                                {{ request('condicion_pago') == 'Contado Efectivo' ? 'selected' : '' }}>Contado
                                Efectivo</option>
                            <option value="Contado Transferencia"
                                {{ request('condicion_pago') == 'Contado Transferencia' ? 'selected' : '' }}>Contado
                                Transferencia</option>
                            <option value="Pago Parcial"
                                {{ request('condicion_pago') == 'Pago Parcial' ? 'selected' : '' }}>Pago Parcial
                            </option>
                            <option value="Cuenta Corriente"
                                {{ request('condicion_pago') == 'Cuenta Corriente' ? 'selected' : '' }}>Cuenta
                                Corriente</option>
                        </select>

                        <!-- Select Tipo de Comprobante -->
                        <select name="tipo_comprobante"
                            class="px-4 py-2 border rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Todos los tipos</option>
                            <option value="Factura" {{ request('tipo_comprobante') == 'Factura' ? 'selected' : '' }}>
                                Factura</option>
                            <option value="Presupuesto"
                                {{ request('tipo_comprobante') == 'Presupuesto' ? 'selected' : '' }}>Presupuesto
                            </option>
                            <option value="Recibo" {{ request('tipo_comprobante') == 'Recibo' ? 'selected' : '' }}>
                                Recibo</option>
                        </select>

                        <button type="submit"
                            class="px-6 py-2 bg-gray-600 text-white font-bold rounded-md hover:bg-gray-700 transition duration-300">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>


                <!-- Tabla de ventas -->
                <div class="overflow-x-auto bg-white rounded-lg shadow-md border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider ">
                                    Tipo Comprobante
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Número
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Condicion de Pago
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Importe Total
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>

                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($ventas as $venta)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Illuminate\Support\Str::limit(optional($venta->cliente)->NombreCompleto ?? (optional($venta->cliente)->RazonSocial ?? 'Sin cliente'), 12) }}
                                        @if (optional($venta->cliente)->id)
                                            <span
                                                class="text-xs text-gray-500 ml-1">#{{ optional($venta->cliente)->id }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($venta->tipo_comprobante == 'Factura')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $venta->tipo_comprobante }}
                                            </span>
                                        @elseif ($venta->tipo_comprobante == 'Presupuesto')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                                {{ $venta->tipo_comprobante }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $venta->tipo_comprobante }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $venta->numero_comprobante }}
                                        <span class="text-xs text-gray-500 ml-1">#{{ $venta->id }}</span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $venta->condicion_pago }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        $ {{ number_format($venta->importe_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if ($venta->estado_venta === 'Pagada')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $venta->estado_venta }}
                                            </span>
                                        @elseif ($venta->estado_venta === 'Pendiente')
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $venta->estado_venta }}
                                            </span>
                                        @else
                                            <span
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $venta->estado_venta }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('ventas.show', $venta->id) }}"
                                            class="text-green-600 hover:text-green-900 mr-2" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('ventas.edit', $venta->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 mr-2" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="openDeleteModal({{ $venta->id }})"
                                            class="text-red-600 hover:text-red-900" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No se encontraron ventas.
                                    </td>
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
                    <p class="text-sm text-gray-500">
                        Esta acción marcará la venta como no visible. ¿Deseas continuar?
                    </p>
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

    <script>
        const deleteModal = document.getElementById('deleteModal');
        const deleteForm = document.getElementById('deleteForm');

        function openDeleteModal(id) {
            deleteForm.action = `/admin/ventas/${id}`; // Ruta dinámica para la eliminación
            deleteModal.classList.remove('hidden');
        }

        document.getElementById('closeModal').addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });

        // Opcional: Cerrar el modal haciendo clic fuera de él
        window.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
