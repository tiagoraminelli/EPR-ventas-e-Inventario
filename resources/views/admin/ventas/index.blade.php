<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ventas</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">

        <div class="w-64 bg-white shadow-lg">
            <x-admin-nav />
        </div>

        <main class="flex-1 p-6">

            <div class="bg-white p-6 shadow-lg">

                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                        Gestión de Ventas
                    </h1>

                    <div class="flex gap-2">
                        <!-- Toggle -->
                        <button onclick="showTable()" class="px-3 py-2 border hover:bg-gray-100" title="Vista tabla">
                            <i class="fas fa-table"></i>
                        </button>

                        <button onclick="showGrid()" class="px-3 py-2 border hover:bg-gray-100" title="Vista grilla">
                            <i class="fas fa-th"></i>
                        </button>

                        <a href="{{ route('ventas.create') }}"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i> Nueva Venta
                        </a>
                    </div>
                </div>

                <!-- Mensajes -->
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

                        <select name="estado_venta" class="border px-3 py-2 shadow-sm">
                            <option value="">Todos los estados</option>
                            <option value="Pagada" {{ request('estado_venta') == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                            <option value="Pendiente" {{ request('estado_venta') == 'Pendiente' ? 'selected' : '' }}>Pendiente
                            </option>
                            <option value="Anulada" {{ request('estado_venta') == 'Anulada' ? 'selected' : '' }}>Anulada
                            </option>
                        </select>

                        <select name="fecha_venta" class="border px-3 py-2 shadow-sm">
                            <option value="">Todas las fechas</option>
                            <option value="Hoy" {{ request('fecha_venta') == 'Hoy' ? 'selected' : '' }}>Hoy</option>
                            <option value="Ayer" {{ request('fecha_venta') == 'Ayer' ? 'selected' : '' }}>Ayer</option>
                            <option value="Esta semana" {{ request('fecha_venta') == 'Esta semana' ? 'selected' : '' }}>Esta
                                semana</option>
                            <option value="Este mes" {{ request('fecha_venta') == 'Este mes' ? 'selected' : '' }}>Este mes
                            </option>
                        </select>

                        <input type="date" name="fecha_venta_exacta" value="{{ request('fecha_venta_exacta') }}"
                            class="border px-3 py-2 shadow-sm">

                        <select name="condicion_pago" class="border px-3 py-2 shadow-sm">
                            <option value="">Todas las condiciones</option>
                            <option value="Contado Efectivo"
                                {{ request('condicion_pago') == 'Contado Efectivo' ? 'selected' : '' }}>Contado Efectivo
                            </option>
                            <option value="Contado Transferencia"
                                {{ request('condicion_pago') == 'Contado Transferencia' ? 'selected' : '' }}>Contado
                                Transferencia</option>
                            <option value="Pago Parcial" {{ request('condicion_pago') == 'Pago Parcial' ? 'selected' : '' }}>
                                Pago Parcial</option>
                            <option value="Cuenta Corriente"
                                {{ request('condicion_pago') == 'Cuenta Corriente' ? 'selected' : '' }}>Cuenta Corriente
                            </option>
                        </select>

                        <select name="tipo_comprobante" class="border px-3 py-2 shadow-sm">
                            <option value="">Todos los tipos</option>
                            <option value="Factura" {{ request('tipo_comprobante') == 'Factura' ? 'selected' : '' }}>Factura
                            </option>
                            <option value="Presupuesto" {{ request('tipo_comprobante') == 'Presupuesto' ? 'selected' : '' }}>
                                Presupuesto</option>
                            <option value="Recibo" {{ request('tipo_comprobante') == 'Recibo' ? 'selected' : '' }}>Recibo
                            </option>
                        </select>

                        <button type="submit" class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                            <i class="fas fa-search mr-1"></i> Buscar
                        </button>
                    </div>
                </form>


                <!-- ================= TABLA ================= -->
                <div id="tableView">
                    <div class="overflow-x-auto">
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
                                        <td class="px-4 py-2 text-sm">
                                            {{ optional($venta->cliente)->NombreCompleto ?? (optional($venta->cliente)->RazonSocial ?? 'Sin cliente') }}
                                        </td>

                                        <td class="px-4 py-2 text-sm">{{ $venta->tipo_comprobante }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $venta->numero_comprobante }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $venta->condicion_pago }}</td>
                                        <td class="px-4 py-2 text-sm">$
                                            {{ number_format($venta->importe_total, 2, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-sm">{{ $venta->estado_venta }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td>

                                        <td class="px-4 py-2 text-center">
                                            <div class="flex justify-center gap-3">
                                                <a href="{{ route('ventas.show', $venta->id) }}"><i
                                                        class="fas fa-eye text-green-600"></i></a>
                                                <a href="{{ route('ventas.edit', $venta->id) }}"><i
                                                        class="fas fa-edit"></i></a>
                                                <button onclick="openDeleteModal({{ $venta->id }})">
                                                    <i class="fas fa-trash text-red-600"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ================= GRID ================= -->
                <div id="gridView" class="hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6">

                        @foreach ($ventas as $venta)
                            <div class="border bg-white shadow-sm hover:shadow-md transition">

                                <img src="{{ asset('storage/ventas/ventas.png') }}" class="w-full h-40 object-cover">

                                <div class="p-4 space-y-1">
                                    <h3 class="font-semibold text-gray-800 truncate">
                                        {{ optional($venta->cliente)->NombreCompleto ?? (optional($venta->cliente)->RazonSocial ?? 'Sin cliente') }}
                                    </h3>

                                    <p class="text-sm text-gray-600">
                                        {{ $venta->tipo_comprobante }} · {{ $venta->numero_comprobante }}
                                    </p>

                                    <p class="text-sm">
                                        $ {{ number_format($venta->importe_total, 2, ',', '.') }}
                                    </p>

                                    <div class="flex justify-between items-center pt-2">
                                        <span class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                                        </span>

                                        <div class="flex gap-3">
                                            <a href="{{ route('ventas.show', $venta->id) }}"><i
                                                    class="fas fa-eye text-green-600"></i></a>
                                            <a href="{{ route('ventas.edit', $venta->id) }}"><i
                                                    class="fas fa-edit"></i></a>
                                            <button onclick="openDeleteModal({{ $venta->id }})">
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

    <!-- MODAL -->
    <div id="deleteModal" class="fixed inset-0 bg-black/50 hidden z-50">
        <div class="relative top-20 mx-auto p-5 w-96 bg-white">
            <h3 class="text-lg font-semibold text-center">¿Eliminar venta?</h3>

            <form id="deleteForm" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button class="w-full bg-black text-white py-2">Eliminar</button>
            </form>

            <button onclick="closeDeleteModal()" class="w-full mt-2 py-2 bg-gray-200">
                Cancelar
            </button>
        </div>
    </div>

    <script>
        function showGrid() {
            document.getElementById('gridView').classList.remove('hidden')
            document.getElementById('tableView').classList.add('hidden')
        }

        function showTable() {
            document.getElementById('tableView').classList.remove('hidden')
            document.getElementById('gridView').classList.add('hidden')
        }

        function openDeleteModal(id) {
            document.getElementById('deleteForm').action = `/admin/ventas/${id}`
            document.getElementById('deleteModal').classList.remove('hidden')
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden')
        }
    </script>

</body>

</html>
