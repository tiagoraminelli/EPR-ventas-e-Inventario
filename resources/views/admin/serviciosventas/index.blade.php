<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicios por Venta</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 font-sans">

<div class="flex min-h-screen">
    <x-admin-nav />

    <main class="flex-1 p-6">
        <div class="bg-white p-6 shadow">

            <!-- ================= HEADER ================= -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <h1 class="text-2xl font-semibold">Panel de Servicios por Venta</h1>

                <a href="{{ route('serviciosventas.create') }}"
                   class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                    <i class="fas fa-plus mr-2"></i> Nuevo Servicio
                </a>
            </div>

            <!-- ================= ALERTAS ================= -->
            @if (session('success'))
                <div class="mb-6 px-4 py-3 border border-green-300 bg-green-50 text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 px-4 py-3 border border-red-300 bg-red-50 text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- ================= FILTROS ================= -->
            <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Buscar..."
                       class="flex-grow border border-gray-300 px-3 py-2">

                <input type="text"
                       name="numero_comprobante"
                       value="{{ request('numero_comprobante') }}"
                       placeholder="N° Comprobante"
                       class="w-48 border border-gray-300 px-3 py-2">

                <select name="venta"
                        id="venta-select"
                        class="select2 w-56 border border-gray-300 px-3 py-2">
                    <option value="">Todas las ventas</option>
                    @foreach ($ventasFiltro as $ventaFiltroItem)
                        <option value="{{ $ventaFiltroItem->id }}"
                            {{ request('venta') == $ventaFiltroItem->id ? 'selected' : '' }}>
                            #{{ $ventaFiltroItem->id }} -
                            {{ optional($ventaFiltroItem->cliente)->NombreCompleto ?? 'Sin cliente' }}
                        </option>
                    @endforeach
                </select>

                <select name="servicios[]"
                        multiple
                        id="servicios-select"
                        class="select2 w-64 border border-gray-300 px-3 py-2">
                    @foreach ($serviciosFiltro as $servicio)
                        <option value="{{ $servicio->id }}"
                            @if(in_array($servicio->id, (array) request('servicios'))) selected @endif>
                            {{ $servicio->nombre }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                    <i class="fas fa-search mr-1"></i> Buscar
                </button>
            </form>

            <!-- ================= TABLA ================= -->
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left">Venta</th>
                            <th class="px-4 py-2 text-left">Cliente</th>
                            <th class="px-4 py-2 text-left">Servicios</th>
                            <th class="px-4 py-2 text-right">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($ventas as $venta)
                            <tr class="hover:bg-gray-50">

                                <!-- Venta -->
                                <td class="px-4 py-2">
                                    <div class="font-medium">
                                        #{{ $venta->id }}
                                    </div>
                                    <div class="text-gray-500 text-xs">
                                        {{ $venta->numero_comprobante }}
                                    </div>
                                </td>

                                <!-- Cliente -->
                                <td class="px-4 py-2">
                                    {{ optional($venta->cliente)->NombreCompleto ?? 'Sin cliente' }}
                                </td>

                                <!-- Servicios -->
                                <td class="px-4 py-2">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($venta->serviciosVentas as $sv)
                                            <div class="border border-gray-200 px-2 py-1 text-xs bg-gray-50">
                                                {{ $sv->servicio->nombre ?? 'N/A' }}
                                                (x{{ $sv->cantidad }})
                                                - ${{ number_format($sv->precio, 2, ',', '.') }}
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Acciones -->
                                <td class="px-4 py-2 text-right">
                                    <div class="flex justify-end gap-3 text-gray-600">
                                        <a href="{{ route('serviciosventas.edit', $venta) }}"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                    No se encontraron ventas con servicios.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- ================= PAGINACIÓN ================= -->
            <div class="mt-4">
                {{ $ventas->links() }}
            </div>

        </div>
    </main>
</div>

<!-- ================= SCRIPTS ================= -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: 'Seleccionar',
            allowClear: true,
            width: 'resolve'
        });
    });
</script>

</body>
</html>
