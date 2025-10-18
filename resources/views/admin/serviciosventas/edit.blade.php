<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicios por Venta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Estilo para Select2 */
        .select2-container .select2-selection--single {
            height: 42px !important;
            border-radius: 0.375rem !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #6B7280 transparent transparent transparent;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
        }
    </style>
</head>

<body class="bg-gray-100 antialiased">
    <div class="flex">
        <!-- Sidebar -->
        <x-admin-nav />

        <main class="flex-1 p-6 lg:p-12">
            <div class="bg-white p-6 lg:p-10 rounded-xl shadow-lg border border-gray-200">

                <!-- Header de la página -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">
                        Editar Detalles de Servicios por Venta #{{ $venta->id }}
                    </h1>
                    <a href="{{ route('serviciosventas.index') }}"
                        class="mt-4 md:mt-0 px-6 py-2 bg-gray-700 text-white font-semibold rounded-full shadow-md hover:bg-gray-800 transition duration-300">
                        <i class="fas fa-arrow-left mr-2"></i> Volver al listado
                    </a>
                </div>

                <!-- Contenedor principal para la factura -->
                <div class="border border-gray-300 rounded-xl overflow-hidden shadow-2xl">
                    <form action="{{ route('serviciosventas.update', $venta) }}" method="POST"
                        id="editServiciosVentasForm">
                        @csrf
                        @method('PUT')

                        <!-- Información de la Factura y Cliente combinada -->
                        <div class="bg-gray-50 p-6 lg:p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Info de la Venta -->
                            <div class="bg-gray-100 p-6 rounded-xl shadow flex flex-col justify-between">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-300">DOC. NO
                                    VALIDADO COMO FACTURA</h3>
                                <div class="text-sm text-gray-700 space-y-2">
                                    <p><strong>Fecha:</strong> <span
                                            class="font-semibold">{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</span>
                                        | <strong>Venta N°:</strong> <span
                                            class="font-semibold">{{ $venta->id }}</span></p>
                                    <p><strong>Tipo de Comprobante:</strong> <span
                                            class="font-semibold">{{ $venta->tipo_comprobante }}</span> |
                                        <strong>Condición de Pago:</strong> <span
                                            class="font-semibold">{{ $venta->condicion_pago }}</span></p>
                                    <p><strong>N° Comprobante:</strong> <span
                                            class="font-semibold">{{ $venta->numero_comprobante }}</span> |
                                        <strong>Estado:</strong> <span
                                            class="font-semibold">{{ $venta->estado_venta }}</span></p>
                                </div>
                            </div>

                            <!-- Información del Cliente -->
                            <div class="bg-gray-100 p-6 rounded-xl shadow flex flex-col justify-between">
                                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-300">Datos del
                                    Cliente</h3>
                                <div class="grid grid-cols-1 gap-y-2 text-sm text-gray-700">
                                    <p><strong>Razón Social:</strong>
                                        {{ optional($venta->cliente)->RazonSocial ?? 'Sin dato' }}</p>
                                    <p><strong>CUIT/DNI:</strong>
                                        {{ optional($venta->cliente)->cuit_dni ?? 'Sin dato' }}</p>
                                    <p><strong>Domicilio:</strong> {{ optional($venta->cliente)->Domicilio ?? '' }}</p>
                                    <p><strong>Teléfono:</strong> {{ optional($venta->cliente)->Telefono ?? '' }}</p>
                                    <p><strong>Localidad:</strong> {{ optional($venta->cliente)->Localidad ?? '' }}</p>
                                    <p><strong>Tipo Cliente:</strong>
                                        {{ optional($venta->cliente)->TipoCliente ?? '' }}</p>
                                </div>
                            </div>

                        </div>



                        <!-- Detalle de Servicios (Tabla) -->
                        <div class="p-6 lg:p-8 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-300">
                                Detalle de Servicios
                            </h3>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-semibold">
                                        <tr>
                                            <th class="py-3 px-4 rounded-tl-lg">Servicio</th>
                                            <th class="py-3 px-4">Cantidad</th>
                                            <th class="py-3 px-4">Precio Unitario</th>
                                            <th class="py-3 px-4">Precio Referencia</th>
                                            <th class="py-3 px-4">Activo</th>
                                            <th class="py-3 px-4 text-right rounded-tr-lg">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="servicios-container" class="bg-white divide-y divide-gray-200">
                                        @foreach ($venta->serviciosVentas as $index => $sv)
                                            <tr
                                                class="service-row group hover:bg-gray-50 transition-colors duration-200">
                                                <input type="hidden" name="servicios[{{ $index }}][id]"
                                                    value="{{ $sv->id }}">
                                                <td class="py-4 px-4">
                                                    <select name="servicios[{{ $index }}][servicio_id]"
                                                        class="service-select w-full border border-gray-300 rounded-md shadow-sm text-sm"
                                                        required>
                                                        @foreach ($servicios as $servicio)
                                                            <option value="{{ $servicio->id }}"
                                                                data-precio="{{ $servicio->precio }}"
                                                                data-activo="{{ $servicio->activo ? 'Sí' : 'No' }}"
                                                                @if ($sv->servicio_id == $servicio->id) selected @endif>
                                                                {{ $servicio->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <input type="number"
                                                        name="servicios[{{ $index }}][cantidad]"
                                                        value="{{ $sv->cantidad }}" min="1" required
                                                        class="cantidad-input w-24 border border-gray-300 rounded-md shadow-sm text-sm text-center">
                                                </td>
                                                <td class="py-4 px-4">
                                                    <input type="number" step="0.01"
                                                        name="servicios[{{ $index }}][precio]"
                                                        value="{{ $sv->precio }}"
                                                        class="precio-input w-32 border border-gray-300 rounded-md shadow-sm text-sm">
                                                </td>
                                                <td class="py-4 px-4">
                                                    <input type="text"
                                                        class="precio-servicio w-32 bg-gray-100 border border-gray-300 rounded-md text-sm cursor-not-allowed"
                                                        value="{{ $sv->servicio->precio }}" disabled>
                                                </td>
                                                <td class="py-4 px-4">
                                                    <input type="text"
                                                        class="activo-servicio w-16 bg-gray-100 border border-gray-300 rounded-md text-sm text-center cursor-not-allowed"
                                                        value="{{ $sv->servicio->activo ? 'Sí' : 'No' }}" disabled>
                                                </td>
                                                <td class="py-4 px-4 text-right">
                                                    <button type="button"
                                                        class="remove-service-btn text-gray-500 hover:text-red-600 transition duration-200">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div
                            class="p-6 lg:p-8 flex flex-col sm:flex-row justify-between items-center gap-4 border-t border-gray-200 mt-4">
                            <button type="button" id="add-service-btn"
                                class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white font-bold rounded-lg shadow-md hover:bg-green-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                <i class="fas fa-plus mr-2"></i> Agregar Servicio
                            </button>
                            <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-save mr-2"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            let serviceIndex = {{ $venta->serviciosVentas->count() }};

            // Inicializar select2
            $('.service-select').select2();

            // Cambiar precio y activo al seleccionar servicio
            $('#servicios-container').on('change', '.service-select', function() {
                const selected = $(this).find('option:selected');
                const row = $(this).closest('.service-row');
                row.find('.precio-servicio').val(selected.data('precio') || '');
                row.find('.activo-servicio').val(selected.data('activo') || '');
            });

            // Agregar servicio nuevo
            $('#add-service-btn').click(function() {
                const serviciosData = @json($servicios);
                let options = '';
                serviciosData.forEach(s => {
                    options +=
                        `<option value="${s.id}" data-precio="${s.precio}" data-activo="${s.activo ? 'Sí' : 'No'}">${s.nombre}</option>`;
                });

                const row = $(`
                    <tr class="service-row group hover:bg-gray-50 transition-colors duration-200">
                        <td class="py-4 px-4">
                            <select name="servicios[${serviceIndex}][servicio_id]" class="service-select w-full border border-gray-300 rounded-md shadow-sm text-sm" required>${options}</select>
                        </td>
                        <td class="py-4 px-4">
                            <input type="number" name="servicios[${serviceIndex}][cantidad]" value="1" min="1" required class="cantidad-input w-24 border border-gray-300 rounded-md shadow-sm text-sm text-center">
                        </td>
                        <td class="py-4 px-4">
                            <input type="number" step="0.01" name="servicios[${serviceIndex}][precio]" class="precio-input w-32 border border-gray-300 rounded-md shadow-sm text-sm">
                        </td>
                        <td class="py-4 px-4">
                            <input type="text" class="precio-servicio w-32 bg-gray-100 border border-gray-300 rounded-md text-sm cursor-not-allowed" disabled>
                        </td>
                        <td class="py-4 px-4">
                            <input type="text" class="activo-servicio w-16 bg-gray-100 border border-gray-300 rounded-md text-sm text-center cursor-not-allowed" disabled>
                        </td>
                        <td class="py-4 px-4 text-right">
                            <button type="button" class="remove-service-btn text-gray-500 hover:text-red-600 transition duration-200">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                `);

                $('#servicios-container').append(row);
                row.find('.service-select').select2();
                serviceIndex++;
            });

            // Eliminar fila
            $('#servicios-container').on('click', '.remove-service-btn', function() {
                $(this).closest('tr').remove();
            });
        });
    </script>
</body>

</html>
