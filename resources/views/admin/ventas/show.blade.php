<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Venta #{{ $venta->id }}</title>
    <!-- Carga Tailwind CSS para un diseño moderno y responsive -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Usa la fuente Inter para un aspecto profesional -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex min-h-screen">
        <x-admin-nav />
        <!-- Contenido principal de la factura -->
        <main class="flex-1 p-6 sm:p-10">

            <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
                <!-- Contenedor principal de la factura -->
                <div class="p-8 sm:p-12">

                    <!-- Sección de Encabezado: Información de la Empresa e Identificación de la Factura -->
                    <div class="flex flex-col sm:flex-row justify-between items-start mb-10">
                        <!-- Información de la Empresa -->
                        <div class="flex items-center mb-6 sm:mb-0">
                            <!-- Reemplaza la imagen con el logo de tu empresa -->
                            <img src="{{ asset('utils/RDM.jpg') }}" alt="Logo de la Empresa"
                                class="h-16 w-16 rounded-full mr-4 shadow-md">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-800">RDM </h1>
                                <p class="text-gray-500 text-sm">Alvear 585, San Cristobal, Santa fe</p>
                                <p class="text-gray-500 text-sm">CUIT:
                                    {{ optional($venta->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                            </div>
                        </div>

                        <!-- Información de la Factura -->
                        <div class="max-w-md text-left sm:text-right">
                            <h2 class="text-2xl font-extrabold text-blue-600 mb-2">DOC. NO VALIDADO COMO FACTURA</h2>
                            <div class="bg-gray-100 p-4 rounded-xl">
                                <!-- Información de la Venta y fecha-->
                                <p class="text-gray-700 text-sm font-semibold mb-1">
                                    Fecha: <span
                                        class="text-gray-900 font-bold text-base">{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</span>
                                    | Venta n°: <span
                                        class="text-gray-900 font-bold text-base">{{ $venta->id }}</span>
                                </p>
                                <!-- Tipo de Comprobante y condicion de pago-->
                                <p class="text-gray-700 text-sm font-semibold mb-1">
                                    Tipo de Comprobante: <span
                                        class="text-gray-900 font-bold text-base">{{ $venta->tipo_comprobante }}</span>
                                    | Condición de Pago: <span
                                        class="text-gray-900 font-bold text-base">{{ $venta->condicion_pago }}</span>
                                </p>
                                <!-- Número de Comprobante y estado de la venta-->
                                <p class="text-gray-700 text-sm font-semibold mb-1">
                                    N° Comprobante: <span
                                        class="text-gray-900 font-bold text-base">{{ $venta->numero_comprobante }}</span>
                                    | Estado: <span
                                        class="text-gray-900 font-bold text-base">{{ $venta->estado_venta }}</span>
                                </p>

                            </div>
                        </div>
                    </div>

                    <!-- Sección de Clientes: Información del Cliente -->
                    <div class="bg-gray-50 p-6 rounded-xl shadow-inner mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Datos del Cliente</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-700">
                            <div>
                                <p><strong>Razón Social:</strong>
                                    {{ optional($venta->cliente)->RazonSocial ?? 'Sin dato' }}</p>
                                <p><strong>Domicilio:</strong> {{ optional($venta->cliente)->Domicilio ?? '' }}</p>
                                <p><strong>Localidad:</strong> {{ optional($venta->cliente)->Localidad ?? '' }}</p>
                            </div>
                            <div>
                                <p><strong>CUIT/DNI:</strong> {{ optional($venta->cliente)->cuit_dni ?? 'Sin dato' }}
                                </p>
                                <p><strong>Teléfono:</strong> {{ optional($venta->cliente)->Telefono ?? '' }}</p>
                                <p><strong>Tipo Cliente:</strong> {{ optional($venta->cliente)->TipoCliente ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Exportar -->
                    <div class="flex justify-end gap-4 mb-4">
                        <a href="{{ route('ventas.export.pdf', $venta->id) }} "
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition duration-300 flex items-center" target="_blank">
                            <i class="fas fa-file-pdf mr-2"></i> Exportar PDF
                        </a>
                        {{-- <a href="{{ route('ventas.export.excel', $venta->id) }}"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 flex items-center">
                            <i class="fas fa-file-excel mr-2"></i> Exportar Excel
                        </a> --}}
                    </div>


                    <!-- Sección de Detalles: Tabla de Productos -->
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalle de Productos</h3>
                        <div class="overflow-x-auto rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-600 text-white">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Descripción</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Cantidad</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Precio Unitario</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Descuento</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($venta->detalleVentas as $detalle)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ \Illuminate\Support\Str::limit(optional($detalle->producto)->nombre ?? 'Sin producto', 40) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $detalle->cantidad }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$
                                                {{ number_format($detalle->precio_unitario, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if ($detalle->descuento > 0)
                                                    $ {{ number_format($detalle->descuento, 2, ',', '.') }}
                                                @else
                                                    Sin descuento
                                                @endif
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$
                                                {{ number_format($detalle->subtotal, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No hay
                                                productos asociados a esta venta.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Sección de Detalles: Tabla de Servicios -->
                    <div class="mb-10">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalle de Servicios</h3>
                        <div class="overflow-x-auto rounded-lg shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-blue-600 text-white">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Servicio</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Cantidad</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Precio Unitario</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse ($venta->serviciosVentas as $sv)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sv->servicio->nombre ?? 'Servicio no disponible' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $sv->cantidad }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $ {{ number_format($sv->precio, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                $ {{ number_format($sv->cantidad * $sv->precio, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                                No hay servicios asociados a esta venta.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>


                    @php
                        // Subtotales
                        $subtotalProductos = $venta->detalleVentas->sum('subtotal');
                        $subtotalServicios = $venta->serviciosVentas->sum(function ($sv) {
                            return $sv->cantidad * $sv->precio;
                        });

                        $importeNeto = $subtotalProductos + $subtotalServicios;
                        // $importeIva = $importeNeto * 0.21;
                        $importeTotal = $importeNeto;
                    @endphp

                    <div class="flex justify-end mt-8">
                        <div class="w-full sm:w-1/2">
                            <!-- Subtotal Productos -->
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Subtotal Productos:</span>
                                <span class="text-gray-900 font-medium">
                                    $ {{ number_format($subtotalProductos, 2, ',', '.') }}
                                </span>
                            </div>

                            <!-- Subtotal Servicios -->
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Subtotal Servicios:</span>
                                <span class="text-gray-900 font-medium">
                                    $ {{ number_format($subtotalServicios, 2, ',', '.') }}
                                </span>
                            </div>

                            <!-- Total Neto -->
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-700">Subtotal General:</span>
                                <span class="text-gray-900 font-medium">
                                    $ {{ number_format($importeNeto, 2, ',', '.') }}
                                </span>
                            </div>

                            @if (($venta->cliente->TipoCliente ?? '') === 'I.V.A RESPONSABLE INSCRIPTO')
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-700">Impuestos (IVA 21%):</span>
                                    <span class="text-gray-900 font-medium">
                                        $ {{ number_format($importeIva, 2, ',', '.') }}
                                    </span>
                                </div>
                            @endif

                            <!-- Total Final -->
                            <div
                                class="flex justify-between items-center py-4 border-t-2 border-b-2 border-gray-200 mt-4">
                                <span class="text-xl font-bold text-gray-800">Total a Pagar:</span>
                                <span class="text-2xl font-extrabold text-green-600">
                                    $ {{ number_format($importeTotal, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>


                    <!-- Sección de Notas o Mensaje de Agradecimiento -->
                    <div class="mt-12 text-center text-gray-500 text-sm">
                        <p>Los precios, la disponibilidad y los servicios ofrecidos son sujetos a cambios sin previo aviso.</p>
                        <p>Gracias por tu compra. Si tienes alguna pregunta sobre esta factura, contáctanos.</p>
                        <p>Teléfono: [03408-15675519] | Email: [info@rdm.com]</p>
                    </div>

                </div>
            </div>
        </main>
    </div>

</body>

</html>
