<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Múltiples Servicios por Venta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-indigo-100 font-sans">
    <div class="flex">
        <!-- Sidebar -->
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <!-- Header -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b pb-4">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Crear Servicios por Venta</h1>
                    <a href="{{ route('serviciosventas.index') }}"
                        class="px-6 py-2 bg-gray-600 text-white font-bold rounded-md shadow-lg hover:bg-gray-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                </div>

                <!-- Errores -->
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6" role="alert">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('serviciosventas.store') }}" method="POST" id="serviciosVentasForm">
                    @csrf

                    <!-- Venta -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="venta_id" class="block text-sm font-medium text-gray-700">Venta</label>
                            <select name="venta_id" id="venta_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Seleccione una venta...</option>
                                @foreach($ventas as $venta)
                                    <option value="{{ $venta->id }}"
                                        {{ old('venta_id') == $venta->id ? 'selected' : '' }}>
                                        #{{ $venta->id }} -
                                        {{ optional($venta->cliente)->NombreCompleto ?? 'Sin cliente' }} |
                                        {{ $venta->fecha_venta }} | {{ $venta->numero_comprobante }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <hr class="my-6 border-t border-gray-300">

                    <!-- Servicios dinámicos -->
                    <div class="bg-gray-50 p-4 rounded-lg shadow-inner mb-6">
                        <h2 class="text-xl font-bold text-gray-700 mb-4">Servicios de la Venta</h2>
                        <div id="servicios-container" class="space-y-4"></div>

                        <div class="flex justify-end mt-4">
                            <button type="button" id="add-service-btn"
                                class="px-4 py-2 bg-green-500 text-white font-bold rounded-md shadow-lg hover:bg-green-600 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <i class="fas fa-plus mr-2"></i> Agregar Servicio
                            </button>
                        </div>
                    </div>

                    <!-- Resumen de totales -->
                    <div class="bg-gray-100 p-4 rounded-lg shadow mb-6">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span>Total:</span>
                            <span id="total-precio" class="text-indigo-600">$0.00</span>
                        </div>
                    </div>

                    <!-- Botón Guardar -->
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-md shadow-lg hover:bg-indigo-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-save mr-2"></i> Guardar Todos los Servicios
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- jQuery y Select2 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            let serviceIndex = 0;

            $('#venta_id').select2();

            function addServiceRow() {
                const row = `
                <div class="service-row border border-gray-200 p-4 rounded-lg bg-white flex flex-wrap gap-4 items-end md:items-center">

                    <!-- Servicio -->
                    <div class="w-full md:w-1/4">
                        <label class="block text-sm font-medium text-gray-700">Servicio</label>
                        <select name="servicios[${serviceIndex}][servicio_id]" class="service-select mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                            <option value="">Seleccione un servicio...</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->id }}"
                                    data-precio="{{ $servicio->precio }}"
                                    data-activo="{{ $servicio->activo ? 'Sí' : 'No' }}">
                                    {{ $servicio->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Cantidad -->
                    <div class="w-full md:w-1/6">
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" name="servicios[${serviceIndex}][cantidad]" class="cantidad-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required min="1">
                    </div>

                    <!-- Precio editable -->
                    <div class="w-full md:w-1/6">
                        <label class="block text-sm font-medium text-gray-700">Precio</label>
                        <input type="number" step="0.01" name="servicios[${serviceIndex}][precio]" class="precio-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" min="0">
                    </div>

                    <!-- Precio Servicio (disabled) -->
                    <div class="w-full md:w-1/6">
                        <label class="block text-sm font-medium text-gray-700">Precio Servicio</label>
                        <input type="text" class="precio-servicio mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm bg-gray-100" disabled>
                    </div>

                    <!-- Activo (disabled) -->
                    <div class="w-full md:w-1/12">
                        <label class="block text-sm font-medium text-gray-700">Activo</label>
                        <input type="text" class="activo-servicio mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm bg-gray-100 text-center" disabled>
                    </div>

                    <!-- Botón eliminar -->
                    <div class="w-full md:w-1/12 flex justify-end">
                        <button type="button" class="remove-service-btn px-3 py-2 bg-red-500 text-white font-bold rounded-md shadow-lg hover:bg-red-600 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </div>
                `;
                $('#servicios-container').append(row);
                $(`.service-select[name="servicios[${serviceIndex}][servicio_id]"]`).select2();
                serviceIndex++;
            }

            // Actualizar precios y activo cuando cambie servicio
            $('#servicios-container').on('change', '.service-select', function() {
                const selected = $(this).find('option:selected');
                const row = $(this).closest('.service-row');
                row.find('.precio-servicio').val(selected.data('precio') || '');
                row.find('.activo-servicio').val(selected.data('activo') || '');
            });

            // Actualizar total
            function updateTotal() {
                let total = 0;
                $('.service-row').each(function() {
                    const cantidad = parseFloat($(this).find('.cantidad-input').val()) || 0;
                    const precio = parseFloat($(this).find('.precio-input').val()) || 0;
                    total += cantidad * precio;
                });
                $('#total-precio').text(`$${total.toFixed(2)}`);
            }

            // Eventos
            $('#add-service-btn').on('click', function() { addServiceRow(); updateTotal(); });
            $('#servicios-container').on('click', '.remove-service-btn', function() { $(this).closest('.service-row').remove(); updateTotal(); });
            $('#servicios-container').on('change keyup', '.cantidad-input, .precio-input', function() { updateTotal(); });

            // Agregar la primera fila
            addServiceRow();
        });
    </script>
</body>

</html>
