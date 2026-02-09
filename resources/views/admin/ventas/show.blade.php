<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta #{{ $venta->id }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 text-gray-800">

    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="max-w-4xl mx-auto bg-white border border-gray-200">

                <div class="p-8">

                    <!-- HEADER -->
                    <div class="flex flex-col sm:flex-row justify-between gap-6 mb-10">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('utils/RDM.jpg') }}" class="h-14 w-14 rounded-full border">
                            <div>
                                <h1 class="text-xl font-semibold">RDM</h1>
                                <p class="text-sm text-gray-500">Alvear 585 · San Cristóbal · Santa Fe</p>
                                <p class="text-sm text-gray-500">
                                    CUIT: {{ optional($venta->cliente)->cuit_dni ?? '—' }}
                                </p>
                            </div>
                        </div>

                        <div class="text-sm text-right">
                            <p class="font-semibold text-gray-900">
                                Venta #{{ $venta->id }}
                            </p>
                            <p class="text-gray-500">
                                {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}
                            </p>
                            <p class="text-gray-500">
                                {{ $venta->tipo_comprobante }} · {{ $venta->numero_comprobante }}
                            </p>
                            <p class="text-gray-500">
                                {{ $venta->condicion_pago }} · {{ $venta->estado_venta }}
                            </p>
                        </div>
                    </div>

                    <!-- CLIENTE -->
                    <div class="border-t border-b py-6 mb-10">
                        <h3 class="text-sm font-semibold uppercase text-gray-500 mb-4">
                            Datos del cliente
                        </h3>

                        <div class="grid md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p><span class="font-medium">Razón Social:</span>
                                    {{ optional($venta->cliente)->RazonSocial ?? '—' }}</p>
                                <p><span class="font-medium">Domicilio:</span>
                                    {{ optional($venta->cliente)->Domicilio ?? '—' }}</p>
                                <p><span class="font-medium">Localidad:</span>
                                    {{ optional($venta->cliente)->Localidad ?? '—' }}</p>
                            </div>
                            <div>
                                <p><span class="font-medium">CUIT/DNI:</span>
                                    {{ optional($venta->cliente)->cuit_dni ?? '—' }}</p>
                                <p><span class="font-medium">Teléfono:</span>
                                    {{ optional($venta->cliente)->Telefono ?? '—' }}</p>
                                <p><span class="font-medium">Tipo:</span>
                                    {{ optional($venta->cliente)->TipoCliente ?? '—' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- EXPORT -->
                    <div class="flex justify-end mb-6">
                        <a href="{{ route('ventas.export.pdf', $venta->id) }}" target="_blank"
                            class="text-sm px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            Exportar PDF
                        </a>
                    </div>

                    <!-- PRODUCTOS -->
                    <div class="mb-10">
                        <h3 class="text-sm font-semibold uppercase text-gray-500 mb-3">
                            Productos
                        </h3>

                        <table class="w-full text-sm border-t">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">Descripción</th>
                                    <th class="py-2">Cant.</th>
                                    <th class="py-2">Precio</th>
                                    <th class="py-2">Desc.</th>
                                    <th class="py-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse ($venta->detalleVentas as $detalle)
                                    <tr>
                                        <td class="py-2">
                                            {{ \Illuminate\Support\Str::limit(optional($detalle->producto)->nombre ?? 'Sin producto', 40) }}
                                        </td>
                                        <td class="py-2">{{ $detalle->cantidad }}</td>
                                        <td class="py-2">$
                                            {{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                                        <td class="py-2">
                                            {{ $detalle->descuento > 0 ? '$ ' . number_format($detalle->descuento, 2, ',', '.') : '—' }}
                                        </td>
                                        <td class="py-2 text-right">
                                            $ {{ number_format($detalle->subtotal, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-4 text-center text-gray-500">
                                            No hay productos asociados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- SERVICIOS -->
                    <div class="mb-10">
                        <h3 class="text-sm font-semibold uppercase text-gray-500 mb-3">
                            Servicios
                        </h3>

                        <table class="w-full text-sm border-t">
                            <thead class="text-left text-gray-500">
                                <tr>
                                    <th class="py-2">Servicio</th>
                                    <th class="py-2">Cant.</th>
                                    <th class="py-2">Precio</th>
                                    <th class="py-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @forelse ($venta->serviciosVentas as $sv)
                                    <tr>
                                        <td class="py-2">{{ $sv->servicio->nombre ?? '—' }}</td>
                                        <td class="py-2">{{ $sv->cantidad }}</td>
                                        <td class="py-2">$ {{ number_format($sv->precio, 2, ',', '.') }}</td>
                                        <td class="py-2 text-right">
                                            $ {{ number_format($sv->cantidad * $sv->precio, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">
                                            No hay servicios asociados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @php
                        $subtotalProductos = $venta->detalleVentas->sum('subtotal');

                        $subtotalServicios = $venta->serviciosVentas->sum(function ($sv) {
                            return $sv->cantidad * $sv->precio;
                        });

                        $importeTotal = $subtotalProductos + $subtotalServicios;
                    @endphp

                    <!-- TOTALES -->
                    <div class="flex justify-end border-t pt-6">
                        <div class="w-full sm:w-1/2 text-sm space-y-2">
                            <div class="flex justify-between">
                                <span>Subtotal Productos</span>
                                <span>$ {{ number_format($subtotalProductos, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Subtotal Servicios</span>
                                <span>$ {{ number_format($subtotalServicios, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between font-semibold border-t pt-3">
                                <span>Total</span>
                                <span class="text-lg">
                                    $ {{ number_format($importeTotal, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="mt-10 text-center text-xs text-gray-400">
                        <p>Documento no válido como factura.</p>
                        <p>Gracias por su compra.</p>
                    </div>

                </div>
            </div>
        </main>
    </div>

</body>

</html>
