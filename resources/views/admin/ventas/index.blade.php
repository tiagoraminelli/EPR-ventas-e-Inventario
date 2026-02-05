<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <div class="flex-shrink-0 w-64 bg-white shadow-lg">
            <x-admin-nav />
        </div>

        <!-- Contenido -->
        <main class="flex-1 p-6">

            <div class="bg-white p-6 shadow-lg">

                <!-- ================= HEADER ================= -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                        Gestión de Ventas
                    </h1>

                    <div class="flex items-center gap-3">
                        <div class="flex border border-gray-300">
                            <button id="btn-table" class="px-4 py-2 text-sm">
                                <i class="fas fa-list mr-1"></i> Tabla
                            </button>
                            <button id="btn-grid" class="px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-th mr-1"></i> Imágenes
                            </button>
                        </div>

                        <a href="{{ route('ventas.create') }}"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i> Nueva Venta
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- ================= FILTROS ================= -->
                <form action="{{ route('ventas.index') }}" method="GET" class="mb-6 border-b pb-4">
                    <div class="flex flex-wrap gap-3 items-center">

                        <input type="search" name="search" placeholder="Buscar por número, estado o cliente..."
                            value="{{ request('search') }}" class="flex-1 min-w-[220px] border px-3 py-2 shadow-sm">

                        <select name="cliente_id" id="cliente_id"
                            class="border px-3 py-2 shadow-sm min-w-[220px] max-w-[220px]">
                            <option value="">Todos los clientes</option>
                            @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->id }}"
                                    {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                    {{ $cliente->NombreCompleto }}
                                </option>
                            @endforeach
                        </select>

                        <select name="estado_venta" class="border px-3 py-2 shadow-sm">
                            <option value="">Todos los estados</option>
                            <option value="Pagada" {{ request('estado_venta') == 'Pagada' ? 'selected' : '' }}>Pagada
                            </option>
                            <option value="Pendiente" {{ request('estado_venta') == 'Pendiente' ? 'selected' : '' }}>
                                Pendiente</option>
                            <option value="Anulada" {{ request('estado_venta') == 'Anulada' ? 'selected' : '' }}>
                                Anulada</option>
                        </select>

                        <select name="fecha_venta" class="border px-3 py-2 shadow-sm">
                            <option value="">Todas las fechas</option>
                            <option value="Hoy" {{ request('fecha_venta') == 'Hoy' ? 'selected' : '' }}>Hoy</option>
                            <option value="Ayer" {{ request('fecha_venta') == 'Ayer' ? 'selected' : '' }}>Ayer
                            </option>
                            <option value="Esta semana"
                                {{ request('fecha_venta') == 'Esta semana' ? 'selected' : '' }}>Esta semana</option>
                            <option value="Este mes" {{ request('fecha_venta') == 'Este mes' ? 'selected' : '' }}>Este
                                mes</option>
                        </select>

                        <input type="date" name="fecha_venta_exacta" value="{{ request('fecha_venta_exacta') }}"
                            class="border px-3 py-2 shadow-sm">

                        <select name="condicion_pago" class="border px-3 py-2 shadow-sm">
                            <option value="">Todas las condiciones</option>
                            <option value="Contado Efectivo"
                                {{ request('condicion_pago') == 'Contado Efectivo' ? 'selected' : '' }}>
                                Contado Efectivo
                            </option>
                            <option value="Contado Transferencia"
                                {{ request('condicion_pago') == 'Contado Transferencia' ? 'selected' : '' }}>
                                Contado Transferencia
                            </option>
                            <option value="Pago Parcial"
                                {{ request('condicion_pago') == 'Pago Parcial' ? 'selected' : '' }}>
                                Pago Parcial
                            </option>
                            <option value="Cuenta Corriente"
                                {{ request('condicion_pago') == 'Cuenta Corriente' ? 'selected' : '' }}>
                                Cuenta Corriente
                            </option>
                        </select>

                        <select name="tipo_comprobante" class="border px-3 py-2 shadow-sm">
                            <option value="">Todos los tipos</option>
                            <option value="Factura" {{ request('tipo_comprobante') == 'Factura' ? 'selected' : '' }}>
                                Factura</option>
                            <option value="Presupuesto"
                                {{ request('tipo_comprobante') == 'Presupuesto' ? 'selected' : '' }}>Presupuesto
                            </option>
                            <option value="Recibo" {{ request('tipo_comprobante') == 'Recibo' ? 'selected' : '' }}>
                                Recibo</option>
                        </select>

                        <button type="submit" class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                            <i class="fas fa-search mr-1"></i> Buscar
                        </button>
                    </div>
                </form>

                <!-- ================= TABLA ================= -->
                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs uppercase">Cliente</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Tipo</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Número</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Condición</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Importe</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Estado</th>
                                <th class="px-4 py-2 text-left text-xs uppercase">Fecha</th>
                                <th class="px-4 py-2 text-center text-xs uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @foreach ($ventas as $venta)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2">
                                        {{ optional($venta->cliente)->NombreCompleto ?? 'Sin cliente' }}</td>
                                    <td class="px-4 py-2">{{ $venta->tipo_comprobante }}</td>
                                    <td class="px-4 py-2">{{ $venta->numero_comprobante }}</td>
                                    <td class="px-4 py-2">{{ $venta->condicion_pago }}</td>
                                    <td class="px-4 py-2">$ {{ number_format($venta->importe_total, 2, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-2">{{ $venta->estado_venta }}</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3">
                                            <a href="{{ route('ventas.show', $venta->id) }}"><i
                                                    class="fas fa-eye text-green-600"></i></a>
                                            <a href="{{ route('ventas.edit', $venta->id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            <button type="button"
                                                onclick="openConfirmModal('delete-venta-modal', () => {
                                                document.getElementById('delete-form-{{ $venta->id }}').submit();
                                            })">
                                                <i class="fas fa-trash text-red-600"></i>
                                            </button>
                                            <form id="delete-form-{{ $venta->id }}"
                                                action="{{ route('ventas.destroy', $venta->id) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ================= GRID ================= -->
                <div id="grid-view" class="hidden">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($ventas as $venta)
                            <div class="border bg-white shadow hover:shadow-md rounded overflow-hidden">

                                <!-- Imagen -->
                                <div class="w-full aspect-square">
                                    <img src="{{ asset('storage/ventas/ventas.png') }}" alt="Factura"
                                        class="w-full h-full object-cover">
                                </div>

                                <!-- Info -->
                                <div class="p-4 space-y-1">
                                    <h3 class="font-semibold truncate">
                                        {{ optional($venta->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                    </h3>

                                    <p class="text-sm text-gray-600">
                                        {{ $venta->tipo_comprobante }} · {{ $venta->numero_comprobante }}
                                    </p>

                                    <p class="text-sm font-medium">
                                        $ {{ number_format($venta->importe_total, 2, ',', '.') }}
                                    </p>

                                    <div class="flex justify-between items-center pt-3">
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                                        </span>

                                        <div class="flex gap-3">
                                            <a href="{{ route('ventas.show', $venta->id) }}">
                                                <i class="fas fa-eye text-green-600"></i>
                                            </a>

                                            <a href="{{ route('ventas.edit', $venta->id) }}">
                                                <i class="fas fa-edit text-blue-600"></i>
                                            </a>

                                            <button type="button"
                                                onclick="openConfirmModal('delete-venta-modal', () => {
                                    document.getElementById('delete-form-{{ $venta->id }}').submit();
                                })">
                                                <i class="fas fa-trash text-red-600"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>


                <div class="mt-6">
                    {{ $ventas->links() }}
                </div>

            </div>
        </main>
    </div>

    <x-confirm-modal id="delete-venta-modal" title="¿Eliminar venta?"
        message="Esta venta se eliminará definitivamente." confirmText="Eliminar" cancelText="Cancelar" />

    <!-- ================= JS VIEW TOGGLE ================= -->
    <script>
        const tableView = document.getElementById('table-view');
        const gridView = document.getElementById('grid-view');
        const btnTable = document.getElementById('btn-table');
        const btnGrid = document.getElementById('btn-grid');
        const VIEW_KEY = 'ventas_view';

        function setView(view) {
            if (view === 'grid') {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
                btnGrid.classList.add('bg-gray-900', 'text-white');
                btnTable.classList.remove('bg-gray-900', 'text-white');
            } else {
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
                btnTable.classList.add('bg-gray-900', 'text-white');
                btnGrid.classList.remove('bg-gray-900', 'text-white');
            }
            localStorage.setItem(VIEW_KEY, view);
        }

        btnTable.onclick = () => setView('table');
        btnGrid.onclick = () => setView('grid');

        document.addEventListener('DOMContentLoaded', () => {
            setView(localStorage.getItem(VIEW_KEY) || 'table');
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            $('#cliente_id').select2({
                placeholder: 'Seleccionar cliente',
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>

</body>

</html>
