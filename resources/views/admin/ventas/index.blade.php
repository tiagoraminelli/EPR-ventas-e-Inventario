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

    <!-- ================= MODAL ELIMINAR ================= -->
    <x-confirm-modal id="delete-venta-modal" title="¿Estás seguro?"
        message="¿Seguro que deseas eliminar esta venta?" confirm-text="Eliminar" cancel-text="Cancelar" />

    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="bg-white p-6 shadow">

                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 border border-green-200">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- HEADER -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">

                    <h1 class="text-2xl font-semibold">
                        Panel de Control de Ventas
                    </h1>

                    <div class="flex gap-3 items-center">
                        <a href="{{ route('ventas.create') }}"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i> Nueva Venta
                        </a>

                        <div class="flex border border-gray-300">
                            <button id="btn-table" class="px-4 py-2 bg-gray-900 text-white text-sm">
                                <i class="fas fa-list mr-1"></i> Tabla
                            </button>
                            <button id="btn-grid" class="px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-th mr-1"></i> Tarjetas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ================= FILTROS ================= -->
                <form action="{{ route('ventas.index') }}" method="GET" class="mb-6 border-b pb-4">
                    <div class="flex flex-wrap gap-3 items-center">

                        <!-- Buscador -->
                        <input type="search" name="search" placeholder="Buscar por número, estado o cliente..."
                            value="{{ request('search') }}" class="flex-1 min-w-[220px] border px-3 py-2 shadow-sm">

                        <!-- Cliente (Select2) -->
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

                        <!-- Estado Venta -->
                        <select name="estado_venta" class="border px-3 py-2 shadow-sm">
                            <option value="">Estado</option>
                            <option value="Pagada" {{ request('estado_venta') == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                            <option value="Pendiente" {{ request('estado_venta') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Anulada" {{ request('estado_venta') == 'Anulada' ? 'selected' : '' }}>Anulada</option>
                        </select>

                        <!-- Condición de Pago -->
                        <select name="condicion_pago" class="border px-3 py-2 shadow-sm">
                            <option value="">Condición</option>
                            <option value="Contado Efectivo" {{ request('condicion_pago') == 'Contado Efectivo' ? 'selected' : '' }}>
                                Contado Efectivo
                            </option>
                            <option value="Contado Transferencia" {{ request('condicion_pago') == 'Contado Transferencia' ? 'selected' : '' }}>
                                Contado Transferencia
                            </option>
                            <option value="Pago Parcial" {{ request('condicion_pago') == 'Pago Parcial' ? 'selected' : '' }}>
                                Pago Parcial
                            </option>
                            <option value="Cuenta Corriente" {{ request('condicion_pago') == 'Cuenta Corriente' ? 'selected' : '' }}>
                                Cuenta Corriente
                            </option>
                        </select>

                        <!-- Tipo Comprobante -->
                        <select name="tipo_comprobante" class="border px-3 py-2 shadow-sm">
                            <option value="">Tipo</option>
                            <option value="Factura" {{ request('tipo_comprobante') == 'Factura' ? 'selected' : '' }}>Factura</option>
                            <option value="Presupuesto" {{ request('tipo_comprobante') == 'Presupuesto' ? 'selected' : '' }}>Presupuesto</option>
                            <option value="Recibo" {{ request('tipo_comprobante') == 'Recibo' ? 'selected' : '' }}>Recibo</option>
                        </select>

                        <!-- Filtro rápido de fecha -->
                        <select name="fecha_venta" class="border px-3 py-2 shadow-sm">
                            <option value="">Período</option>
                            <option value="Hoy" {{ request('fecha_venta') == 'Hoy' ? 'selected' : '' }}>Hoy</option>
                            <option value="Ayer" {{ request('fecha_venta') == 'Ayer' ? 'selected' : '' }}>Ayer</option>
                            <option value="Esta semana" {{ request('fecha_venta') == 'Esta semana' ? 'selected' : '' }}>Esta semana</option>
                            <option value="Este mes" {{ request('fecha_venta') == 'Este mes' ? 'selected' : '' }}>Este mes</option>
                        </select>

                        <!-- Fecha exacta -->
                        <input type="date" name="fecha_venta_exacta" value="{{ request('fecha_venta_exacta') }}"
                            class="border px-3 py-2 shadow-sm">

                        <!-- Botones -->
                        <button type="submit" class="px-4 py-2 border hover:bg-gray-100">
                            <i class="fas fa-search mr-1"></i> Filtrar
                        </button>

                        <a href="{{ route('ventas.index') }}"
                            class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-100">
                            Limpiar
                        </a>
                    </div>
                </form>

                <!-- ================= TABLA ================= -->
                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Cliente</th>
                                <th class="px-4 py-2 text-left">Tipo</th>
                                <th class="px-4 py-2 text-left">Número</th>
                                <th class="px-4 py-2 text-left">Condición</th>
                                <th class="px-4 py-2 text-left">Importe</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                                <th class="px-4 py-2 text-left">Fecha</th>
                                <th class="px-4 py-2 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($ventas as $venta)
                                <tr class="hover:bg-gray-50 cursor-pointer"
                                    onclick="window.open('{{ route('ventas.show', $venta->id) }}', '_blank')">

                                    <td class="px-4 py-2">
                                        {{ optional($venta->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ $venta->tipo_comprobante }}
                                    </td>

                                    <td class="px-4 py-2 font-medium">
                                        {{ $venta->numero_comprobante }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ $venta->condicion_pago }}
                                    </td>

                                    <td class="px-4 py-2">
                                        $ {{ number_format($venta->importe_total, 2, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ $venta->estado_venta }}
                                    </td>

                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3 text-gray-600">

                                            <!-- VER -->
                                            <a href="{{ route('ventas.show', $venta->id) }}"
                                                onclick="event.stopPropagation()" title="Ver detalles">
                                                <i class="fas fa-eye text-black-600"></i>
                                            </a>

                                            <!-- EDITAR -->
                                            <a href="{{ route('ventas.edit', $venta->id) }}"
                                                onclick="event.stopPropagation()" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- FORM DELETE -->
                                            <form id="delete-form-{{ $venta->id }}"
                                                action="{{ route('ventas.destroy', $venta->id) }}"
                                                method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>

                                            <!-- ELIMINAR -->
                                            <button type="button"
                                                onclick="event.stopPropagation(); openConfirmModal('delete-venta-modal', () => {
                                                    document.getElementById('delete-form-{{ $venta->id }}').submit();
                                                })"
                                                class="text-red-600 hover:text-red-800" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- ================= GRID ================= -->
                <div id="grid-view" class="hidden">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

                        @foreach ($ventas as $venta)
                            <div class="border shadow-sm hover:shadow transition bg-white cursor-pointer"
                                onclick="window.open('{{ route('ventas.show', $venta->id) }}', '_blank')">

                                <div class="h-32 bg-gray-100 flex items-center justify-center">
                                    <i class="fas fa-file-invoice text-4xl text-gray-400"></i>
                                </div>

                                <div class="p-4 text-sm space-y-2">
                                    <h3 class="font-semibold truncate">
                                        {{ $venta->numero_comprobante }}
                                    </h3>

                                    <p class="text-gray-600 truncate">
                                        {{ optional($venta->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                    </p>

                                    <p class="text-xs text-gray-500 truncate">
                                        {{ $venta->tipo_comprobante }} · {{ $venta->condicion_pago }}
                                    </p>

                                    <p class="text-xs text-gray-600">
                                        Estado: {{ $venta->estado_venta }}
                                    </p>

                                    <p class="text-xs text-gray-600">
                                        Fecha: {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                                    </p>

                                    <p class="text-sm font-semibold text-gray-800">
                                        $ {{ number_format($venta->importe_total, 2, ',', '.') }}
                                    </p>

                                    <div class="flex gap-4 mt-3 text-gray-600">

                                        <!-- VER -->
                                        <a href="{{ route('ventas.show', $venta->id) }}"
                                            onclick="event.stopPropagation()" title="Ver detalles">
                                            <i class="fas fa-eye text-green-600"></i>
                                        </a>

                                        <!-- EDITAR -->
                                        <a href="{{ route('ventas.edit', $venta->id) }}"
                                            onclick="event.stopPropagation()" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- FORM DELETE -->
                                        <form id="delete-form-{{ $venta->id }}"
                                            action="{{ route('ventas.destroy', $venta->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                        <!-- ELIMINAR -->
                                        <button type="button"
                                            onclick="event.stopPropagation(); openConfirmModal('delete-venta-modal', () => {
                                                document.getElementById('delete-form-{{ $venta->id }}').submit();
                                            })"
                                            class="text-red-600 hover:text-red-800" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    {{ $ventas->links() }}
                </div>

            </div>
        </main>
    </div>

    <!-- ================= JS VIEW TOGGLE ================= -->
    <script>
        const btnTable = document.getElementById('btn-table');
        const btnGrid = document.getElementById('btn-grid');
        const tableView = document.getElementById('table-view');
        const gridView = document.getElementById('grid-view');
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
