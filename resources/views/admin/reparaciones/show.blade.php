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

<body class="bg-gray-200">

    <div class="flex min-h-screen">

        <!-- SIDEBAR -->
        <div class="no-print">
            <x-admin-nav />
        </div>

        <!-- CONTENIDO -->
        <main class="flex-1 flex justify-center py-10">

            <div class="w-full max-w-[210mm]">

                <!-- BOTONES -->
                <div class="flex justify-end gap-3 mb-6 no-print">

                    <a href="{{ route('reparaciones.export.pdf', $reparacion->id) }}" target="_blank"
                        class="px-4 py-2 text-sm bg-black text-white rounded hover:bg-gray-800 transition">
                        Exportar PDF
                    </a>

                    <a href="{{ route('reparaciones.edit', $reparacion->id) }}"
                        class="px-4 py-2 text-sm bg-gray-700 text-white rounded hover:bg-gray-900 transition">
                        Editar Reparación
                    </a>

                </div>


                <!-- HOJA A4 -->
                <div class="a4 shadow-xl px-16 py-14 text-gray-800 bg-white">

                    <!-- HEADER EMPRESA -->
                    <div class="flex justify-between items-start border-b pb-6 mb-8">
                        <div>
                            <h1 class="text-2xl font-bold tracking-tight">RDM</h1>
                            <p class="text-sm text-gray-500">Alvear 585 - San Cristóbal</p>
                            <p class="text-sm text-gray-500">
                                CUIT: {{ optional($reparacion->cliente)->cuit_dni ?? '-' }}
                            </p>
                        </div>

                        <div class="text-right">
                            <h2 class="text-lg font-semibold uppercase tracking-wide">
                                Orden de Reparación
                            </h2>
                            <p class="text-sm mt-2">
                                Nº {{ $reparacion->id }}
                            </p>
                            <p class="text-sm">
                                Código: {{ $reparacion->codigo_unico }}
                            </p>
                            <p class="text-sm">
                                Fecha: {{ \Carbon\Carbon::parse($reparacion->fecha_ingreso)->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <!-- CLIENTE -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold uppercase tracking-wide mb-3 border-b pb-2">
                            Datos del Cliente
                        </h3>

                        <div class="grid grid-cols-2 gap-6 text-sm">
                            <div>
                                <p><strong>Razón Social:</strong>
                                    {{ optional($reparacion->cliente)->RazonSocial ?? 'Sin dato' }}
                                </p>

                                <p><strong>Nombre:</strong>
                                    {{ optional($reparacion->cliente)->nombreCompleto ?? 'Sin dato' }}
                                </p>

                                <p><strong>Tipo Cliente:</strong>
                                    {{ optional($reparacion->cliente)->TipoCliente ?? 'Sin dato' }}
                                </p>

                                <p><strong>CUIT/DNI:</strong>
                                    {{ optional($reparacion->cliente)->cuit_dni ?? 'Sin dato' }}
                                </p>
                            </div>

                            <div>
                                <p><strong>Localidad:</strong>
                                    {{ optional($reparacion->cliente)->Localidad ?? 'Sin dato' }}
                                </p>

                                <p><strong>Domicilio:</strong>
                                    {{ optional($reparacion->cliente)->Domicilio ?? '-' }}
                                </p>
                                <p><strong>Teléfono:</strong>
                                    {{ optional($reparacion->cliente)->Telefono ?? '-' }}
                                </p>
                                <p><strong>Email:</strong>
                                    {{ optional($reparacion->cliente)->Email ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- EQUIPO -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold uppercase tracking-wide mb-3 border-b pb-2">
                            Datos del Equipo
                        </h3>

                        <div class="text-sm space-y-2">
                            <p><strong>Descripción:</strong> {{ $reparacion->equipo_descripcion }}</p>
                            <p><strong>Marca:</strong> {{ $reparacion->equipo_marca ?? '-' }}</p>
                            <p><strong>Modelo:</strong> {{ $reparacion->equipo_modelo ?? '-' }}</p>
                            <p><strong>Estado:</strong> {{ $reparacion->estado_reparacion }}</p>
                            <p><strong>Reparable:</strong> {{ $reparacion->reparable ? 'Sí' : 'No' }}</p>
                        </div>
                    </div>

                    <!-- DIAGNÓSTICO -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold uppercase tracking-wide mb-3 border-b pb-2">
                            Diagnóstico
                        </h3>

                        <div class="text-sm space-y-4">
                            <div>
                                <p class="font-semibold mb-1">Descripción del Daño</p>
                                <p class="border p-3 rounded bg-gray-50">
                                    {{ $reparacion->descripcion_danio }}
                                </p>
                            </div>

                            <div>
                                <p class="font-semibold mb-1">Solución Aplicada</p>
                                <p class="border p-3 rounded bg-gray-50">
                                    {{ $reparacion->solucion_aplicada ?? 'Sin dato' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- PRODUCTOS -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold uppercase tracking-wide mb-3 border-b pb-2">
                            Productos Utilizados
                        </h3>

                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Producto</th>
                                    <th class="text-right py-2">Cant.</th>
                                    <th class="text-right py-2">Precio</th>
                                    <th class="text-right py-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reparacion->reparacionProductos as $rp)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $rp->producto->nombre ?? '-' }}</td>
                                        <td class="text-right">{{ $rp->cantidad }}</td>
                                        <td class="text-right">${{ number_format($rp->precio, 2, ',', '.') }}</td>
                                        <td class="text-right">
                                            ${{ number_format($rp->cantidad * $rp->precio, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-gray-400">
                                            Sin productos
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- SERVICIOS -->
                    <div class="mb-8">
                        <h3 class="text-sm font-semibold uppercase tracking-wide mb-3 border-b pb-2">
                            Servicios Aplicados
                        </h3>

                        <table class="w-full text-sm border-collapse">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-2">Servicio</th>
                                    <th class="text-right py-2">Cant.</th>
                                    <th class="text-right py-2">Precio</th>
                                    <th class="text-right py-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($reparacion->reparacionServicios as $rs)
                                    <tr class="border-b">
                                        <td class="py-2">{{ $rs->servicio->nombre ?? '-' }}</td>
                                        <td class="text-right">{{ $rs->cantidad }}</td>
                                        <td class="text-right">${{ number_format($rs->precio, 2, ',', '.') }}</td>
                                        <td class="text-right">
                                            ${{ number_format($rs->cantidad * $rs->precio, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-gray-400">
                                            Sin servicios
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- TOTALES -->
                    @php
                        $subtotalProductos = $reparacion->reparacionProductos->sum(
                            fn($rp) => $rp->cantidad * $rp->precio,
                        );
                        $subtotalServicios = $reparacion->reparacionServicios->sum(
                            fn($rs) => $rs->cantidad * $rs->precio,
                        );
                        $total = $subtotalProductos + $subtotalServicios;
                    @endphp

                    <div class="flex justify-end mt-10">
                        <div class="w-64 text-sm">
                            <div class="flex justify-between mb-2">
                                <span>Subtotal Productos</span>
                                <span>${{ number_format($subtotalProductos, 2, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between mb-2">
                                <span>Subtotal Servicios</span>
                                <span>${{ number_format($subtotalServicios, 2, ',', '.') }}</span>
                            </div>

                            <div class="flex justify-between border-t pt-3 font-semibold text-base">
                                <span>Total</span>
                                <span>${{ number_format($total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </main>
    </div>

</body>


</html>
