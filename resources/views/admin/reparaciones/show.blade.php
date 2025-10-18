<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Reparación #{{ $reparacion->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6 sm:p-10">

            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-8 sm:p-12">

                    <!-- Encabezado: Información de Cliente y Reparación -->
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-10">
                        <div class="flex items-center mb-6 sm:mb-0">
                            <img src="{{ asset('utils/RDM.jpg') }}" alt="Logo"
                                class="h-16 w-16 rounded-full mr-4 shadow-md">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800">RDM</h1>
                                <p class="text-gray-500 text-sm">Alvear 585, San Cristobal, Santa Fe</p>
                                <p class="text-gray-500 text-sm">CUIT:
                                    {{ optional($reparacion->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                            </div>
                        </div>


                        <div class="max-w-md text-left sm:text-right">
                            <h2 class="text-2xl font-extrabold text-blue-600 mb-2">DETALLE DE REPARACIÓN</h2>
                            <div class="bg-gray-100 p-4 rounded-xl">
                                <p class="text-gray-700 text-sm font-semibold mb-1">
                                    Fecha Ingreso: <span
                                        class="text-gray-900 font-bold text-base">{{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}</span>
                                    | Reparación n°: <span
                                        class="text-gray-900 font-bold text-base">{{ $reparacion->id }}</span>
                                </p>
                                <p class="text-gray-700 text-sm font-semibold mb-1">
                                    Estado: <span
                                        class="text-gray-900 font-bold text-base">{{ $reparacion->estado_reparacion }}</span>
                                    | Reparable: <span
                                        class="text-gray-900 font-bold text-base">{{ $reparacion->reparable ? 'Sí' : 'No' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Datos del Cliente -->
                    <div class="bg-gray-50 p-6 rounded-xl shadow-inner mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Datos del Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <p><strong>Razón Social:</strong>
                                    {{ optional($reparacion->cliente)->RazonSocial ?? 'Sin dato' }}</p>
                                <p><strong>Domicilio:</strong> {{ optional($reparacion->cliente)->Domicilio ?? '' }}</p>
                                <p><strong>Localidad:</strong> {{ optional($reparacion->cliente)->Localidad ?? '' }}
                                </p>
                            </div>
                            <div>
                                <p><strong>CUIT/DNI:</strong>
                                    {{ optional($reparacion->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                                <p><strong>Teléfono:</strong> {{ optional($reparacion->cliente)->Telefono ?? '' }}</p>
                                <p><strong>Tipo Cliente:</strong>
                                    {{ optional($reparacion->cliente)->TipoCliente ?? '' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Exportar -->
                    <div class="flex justify-end gap-4 mb-4">
                        <a href="{{ route('reparaciones.export.pdf', $reparacion->id) }}" target="_blank"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 flex items-center">
                            <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                        </a>

                        {{-- <a href="{{ route('ventas.export.excel', $venta->id) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 flex items-center">
                            <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                        </a> --}}
                    </div>

                    <!-- Sección de Detalles de la Reparación -->
                    <div class="bg-gray-50 p-6 rounded-xl shadow-inner mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles de la Reparación</h3>

                        <!-- Primera fila: Equipo, Marca y Modelo -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700 mb-4">
                            <div>
                                <p><strong>Descripción del Equipo:</strong> {{ $reparacion->equipo_descripcion }}</p>
                                <p><strong>Marca:</strong> {{ $reparacion->equipo_marca ?? 'Sin dato' }}</p>
                                <p><strong>Modelo:</strong> {{ $reparacion->equipo_modelo ?? 'Sin dato' }}</p>
                            </div>
                        </div>

                        <!-- Segunda fila: Daño y Solución (ocupan todo el ancho) -->
                        <div class="text-sm text-gray-700">
                            <p class="mb-2"><strong>Descripción del Daño:</strong>
                                {{ $reparacion->descripcion_danio }}</p>
                            <p><strong>Solución Aplicada:</strong> {{ $reparacion->solucion_aplicada ?? 'Sin dato' }}
                            </p>
                        </div>
                    </div>


                    <!-- Detalle de Servicios -->
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Servicios Aplicados</h3>
                        <div class="overflow-x-auto rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-600 text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Servicio</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Cantidad</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Precio Unitario</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($reparacion->reparacionServicios as $rs)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $rs->servicio->nombre ?? 'Sin Servicio' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $rs->cantidad }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ${{ number_format($rs->precio, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ${{ number_format($rs->cantidad * $rs->precio, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay
                                                servicios asociados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Detalle de Productos -->
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Productos Utilizados</h3>
                        <div class="overflow-x-auto rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-600 text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Producto</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Cantidad</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Precio Unitario</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($reparacion->reparacionProductos as $rp)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                {{ $rp->producto->nombre ?? 'Sin Producto' }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ $rp->cantidad }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ${{ number_format($rp->precio, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                ${{ number_format($rp->cantidad * $rp->precio, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay
                                                productos asociados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totales -->
                    @php
                        $subtotalProductos = $reparacion->reparacionProductos->sum(
                            fn($rp) => $rp->cantidad * $rp->precio,
                        );
                        $subtotalServicios = $reparacion->reparacionServicios->sum(
                            fn($rs) => $rs->cantidad * $rs->precio,
                        );
                        $total = $subtotalProductos + $subtotalServicios;
                    @endphp

                    <div class="flex justify-end mt-8">
                        <div class="w-full sm:w-1/2">
                            <div class="flex justify-between mb-2"><span class="text-gray-700">Subtotal
                                    Productos:</span><span
                                    class="text-gray-900 font-medium">${{ number_format($subtotalProductos, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between mb-2"><span class="text-gray-700">Subtotal
                                    Servicios:</span><span
                                    class="text-gray-900 font-medium">${{ number_format($subtotalServicios, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-4 border-t-2 border-b-2 border-gray-200 mt-4"><span
                                    class="text-xl font-bold text-gray-800">Total a Pagar:</span><span
                                    class="text-2xl font-extrabold text-green-600">${{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

</body>

</html>
