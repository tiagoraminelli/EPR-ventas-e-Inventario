<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Venta</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Estilos personalizados para la tabla y Select2 para armonizar con Tailwind */
        .select2-container--default .select2-selection--single {
            border: 1px solid rgb(209 213 219) !important;
            /* border-gray-300 */
            border-radius: 0.375rem !important;
            /* rounded-md */
            height: 38px !important;
            padding: 5px 0 0 8px;
            width: 100%;
        }

        .select2-container {
            width: 100% !important;
        }

        /* Estilo para inputs deshabilitados */
        input[disabled] {
            background-color: #e9ecef !important;
            cursor: not-allowed;
            color: #6c757d;
        }

        /* Asegura que los inputs en las celdas de tabla sean responsivos */
        #productosTable input,
        #serviciosTable input {
            width: 100%;
            padding: 4px;
            font-size: 0.875rem;
            /* text-sm */
            border-radius: 0.25rem;
            /* rounded-sm */
            border: 1px solid #d1d5db;
            /* border-gray-300 */
        }

        /* Responsive table behavior */
        @media screen and (max-width: 1024px) {

            #productosTable,
            #serviciosTable {
                display: block;
                overflow-x: auto;
            }

            #productosTable thead,
            #serviciosTable thead {
                display: none;
            }

            #productosTable tr,
            #serviciosTable tr {
                margin-bottom: 10px;
                display: block;
                border: 1px solid #ccc;
                border-radius: 8px;
            }

            #productosTable td,
            #serviciosTable td {
                display: block;
                text-align: right;
                border: none;
                position: relative;
                padding-left: 50%;
            }

            #productosTable td::before,
            #serviciosTable td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
            }

            /* Override for remove button, make it full width */
            #productosTable td:last-child,
            #serviciosTable td:last-child {
                text-align: center;
                padding-left: 10px;
                padding-right: 10px;
            }

            /* Force Select2 to be inside the td content area */
            .producto-select,
            .servicio-select {
                max-width: 100%;
            }
        }
    </style>
</head>

<body class="bg-gray-50 p-4 min-h-screen">
    <div class="flex">


        <x-admin-nav class="mb-6" />
        <div class="max-w-5xl mx-auto bg-white p-6 md:p-10 rounded-xl shadow-2xl">

            <h1 class="text-3xl font-extrabold mb-2 text-indigo-700 border-b pb-2">
                Editar Venta #{{ $venta->id }}
            </h1>
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6">
                    <strong class="font-bold">¡Atención! Se encontraron errores:</strong>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('ventas.update', $venta->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8 border-b pb-4">
                    <div class="lg:col-span-3">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Información del Cliente</h3>
                        <label class="block text-sm font-medium text-gray-700">Cliente (Razón Social):</label>
                        <input type="hidden" name="cliente_id" value="{{ $venta->cliente->id }}">
                        <input type="text" value="{{ $venta->cliente->RazonSocial }}" disabled
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">

                        <label class="block text-sm font-medium text-gray-700 mt-2">Cliente (Nombre Completo |
                            ID):</label>
                        <input type="text" value="{{ $venta->cliente->NombreCompleto }} | {{ $venta->cliente->id }}"
                            disabled class="mt-1 block w-full rounded-md border-gray-300 shadow-sm p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tipo Comprobante:</label>
                        <input type="text" name="tipo_comprobante" value="{{ $venta->tipo_comprobante }}" required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Número Comprobante:</label>
                        <input type="text" name="numero_comprobante" value="{{ $venta->numero_comprobante }}"
                            required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha Venta:</label>
                        <input type="date" name="fecha_venta"
                            value="{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m-d') }}" required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Condición de Pago:</label>
                        <input type="text" name="condicion_pago" value="{{ $venta->condicion_pago }}" required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado de Venta:</label>
                        <input type="text" name="estado_venta" value="{{ $venta->estado_venta }}" required
                            class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2">
                    </div>

                    <div class="flex items-center mt-6">
                        <input type="checkbox" id="visible_checkbox" name="visible" value="1"
                            {{ $venta->visible ? 'checked' : '' }}
                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="visible_checkbox"
                            class="ml-2 block text-sm font-medium text-gray-700">Visible</label>
                    </div>
                </div>

                <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones:</label>
                <textarea name="observaciones" rows="3"
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 p-2 mb-8">{{ $venta->observaciones }}</textarea>


                <h3 class="text-xl font-semibold mt-4 mb-4 border-b pb-2 text-gray-700">Detalle de Productos</h3>
                <div class="overflow-x-auto shadow-md rounded-lg mb-6">
                    <table id="productosTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 hidden lg:table-header-group">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Producto</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Cantidad</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Precio Unitario</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 bg-gray-100 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Precio en Lista</th> <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Descuento </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Subtotal </th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Marca</th> <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Stock</th> <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($venta->detalleVentas as $i => $detalle)
                                <tr
                                    class="producto-row transition duration-150 ease-in-out hover:bg-yellow-50 lg:table-row">
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Producto">
                                        <input type="hidden" name="productos[{{ $i }}][detalle_id]"
                                            value="{{ $detalle->id }}">
                                        <select class="producto-select" name="productos[{{ $i }}][id]"
                                            required>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}"
                                                    data-subcategoria="{{ $producto->sub_categoria ?? '' }}"
                                                    data-marca="{{ $producto->marca->nombre ?? '' }}"
                                                    data-stock="{{ $producto->stock ?? 0 }}"
                                                    data-precio="{{ $producto->precio ?? 0 }}"
                                                    {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                                                    {{ $producto->nombre }} | {{ $producto->sub_categoria }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Cantidad"><input
                                            type="number" name="productos[{{ $i }}][cantidad]"
                                            value="{{ $detalle->cantidad }}" min="1" class="cantidad"></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Precio Unitario"><input
                                            type="number" name="productos[{{ $i }}][precio_unitario]"
                                            value="{{ $detalle->precio_unitario }}" min="0" step="0.01"
                                            class="precio"></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Precio en Lista"><input
                                            type="text" class="precio_prod"
                                            value="{{ $detalle->producto->precio ?? '' }}" readonly></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Descuento %"><input
                                            type="number" name="productos[{{ $i }}][descuento]"
                                            value="{{ $detalle->descuento }}" min="0" step="0.01"
                                            class="descuento"></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Subtotal"><input
                                            type="text" name="productos[{{ $i }}][subtotal]"
                                            value="{{ number_format($detalle->cantidad * $detalle->precio_unitario - $detalle->descuento, 2) }}"
                                            readonly class="subtotal"></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Marca"><input
                                            type="text" class="marca"
                                            value="{{ $detalle->producto->marca->nombre ?? '' }}" readonly></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Stock"><input
                                            type="text" class="stock"
                                            value="{{ $detalle->producto->stock ?? 0 }}" readonly></td>
                                    <td class="px-3 py-2 text-center lg:border" data-label="Acciones">
                                        <button type="button" class="remove-btn w-full lg:w-auto">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" id="addProducto"
                    class="add-btn bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">+
                    Agregar Producto</button>

                <h3 class="text-xl font-semibold mt-8 mb-4 border-b pb-2 text-gray-700">Detalle de Servicios</h3>
                <div class="overflow-x-auto shadow-md rounded-lg mb-8">
                    <table id="serviciosTable" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 hidden lg:table-header-group">
                            <tr>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Servicio</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Cantidad</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Precio Unitario</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-300"
                                    scope="col">Subtotal</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($venta->servicios as $i => $servicio)
                                <tr
                                    class="servicio-row transition duration-150 ease-in-out hover:bg-yellow-50 lg:table-row">
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Servicio">
                                        <input type="hidden" name="servicios[{{ $i }}][detalle_id]"
                                            value="{{ $servicio->pivot->id }}">
                                        <select class="servicio-select"
                                            name="servicios[{{ $i }}][servicio_id]" required>
                                            @foreach ($servicios as $s)
                                                <option value="{{ $s->id }}"
                                                    data-precio="{{ $s->precio ?? 0 }}"
                                                    {{ $servicio->id == $s->id ? 'selected' : '' }}>
                                                    {{ $s->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Cantidad"><input
                                            type="number" name="servicios[{{ $i }}][cantidad]"
                                            value="{{ $servicio->pivot->cantidad }}" min="1"
                                            class="cantidad"></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Precio Unitario"><input
                                            type="number" name="servicios[{{ $i }}][precio]"
                                            value="{{ $servicio->precio }}" min="0" step="0.01"
                                            class="precio"></td>
                                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="Subtotal"><input
                                            type="text" name="servicios[{{ $i }}][subtotal]"
                                            value="{{ number_format($servicio->pivot->cantidad * $servicio->precio, 2) }}"
                                            readonly class="subtotal"></td>
                                    <td class="px-3 py-2 text-center lg:border" data-label="Acciones">
                                        <button type="button" class="remove-btn w-full lg:w-auto">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" id="addServicio"
                    class="add-btn bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-150 ease-in-out">+
                    Agregar Servicio</button>

                <div class="mt-10 text-center">
                    <button type="submit"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition duration-150 ease-in-out transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-green-500 focus:ring-opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Actualizar Venta
                    </button>
                </div>
            </form>
        </div>
    </div> <script>
        // Clase Tailwind para botones de eliminar
        const removeBtnClass =
            "bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm transition duration-150 ease-in-out w-full";

        // Inicializar botones de eliminar existentes
        $('.remove-btn').addClass(removeBtnClass);

        $(function() {
            // Inicialización de Select2 para elementos existentes
            $('.producto-select, .servicio-select').select2();

            // Contador global para asegurar índices únicos al agregar filas
            let productoIndex = {{ $venta->detalleVentas->count() }};
            let servicioIndex = {{ $venta->servicios->count() }};

            function getNextIndex(tableId) {
                if (tableId === 'productosTable') {
                    return productoIndex++;
                }
                if (tableId === 'serviciosTable') {
                    return servicioIndex++;
                }
                return 0;
            }

            function calcularSubtotal(row) {
                let isProduct = row.hasClass('producto-row');
                let cantidad = parseFloat(row.find('.cantidad').val()) || 0;
                let precio = parseFloat(row.find('.precio').val()) || 0;
                let descuento = isProduct ? (parseFloat(row.find('.descuento').val()) || 0) : 0;

                let subtotal = (cantidad * precio) - descuento;

                row.find('.subtotal').val(subtotal.toFixed(2));
            }

            // Inicializar subtotales (ya se realiza en el Blade para filas existentes,
            // pero se mantiene la lógica JS para asegurar cálculos correctos después de interacciones).
            $('.producto-row, .servicio-row').each(function() {
                calcularSubtotal($(this));
            });

            $('#productosTable').on('input change', '.cantidad, .precio, .descuento', function() {
                calcularSubtotal($(this).closest('tr'));
            });

            $('#serviciosTable').on('input change', '.cantidad, .precio', function() {
                let row = $(this).closest('tr');
                let cantidad = parseFloat(row.find('.cantidad').val()) || 0;
                let precio = parseFloat(row.find('.precio').val()) || 0;
                // No hay descuento en servicios según la estructura HTML
                row.find('.subtotal').val((cantidad * precio).toFixed(2));
            });

            // Eliminar filas
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('tr').remove();
            });

            // Función para obtener la etiqueta de datos para modo responsivo
            function getLabel(name) {
                const labels = {
                    'id': 'Producto',
                    'servicio_id': 'Servicio',
                    'cantidad': 'Cantidad',
                    'precio_unitario': 'Precio Unitario',
                    'precio': 'Precio Unitario',
                    'descuento': 'Descuento %',
                    'subtotal': 'Subtotal',
                    'precio_prod': 'Precio en Lista',
                    'marca': 'Marca',
                    'stock': 'Stock'
                };
                return labels[name] || name.charAt(0).toUpperCase() + name.slice(1);
            }

            // Agregar producto
            $('#addProducto').click(function() {
                let index = getNextIndex('productosTable'); // Obtener índice único
                let row = `
                <tr class="producto-row transition duration-150 ease-in-out hover:bg-yellow-50 lg:table-row">
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('id')}">
                        <select class="producto-select" name="productos[${index}][id]" required>
                            <option value="" selected disabled>Seleccione Producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}"
                                    data-subcategoria="{{ $producto->sub_categoria ?? '' }}"
                                    data-marca="{{ $producto->marca->nombre ?? '' }}"
                                    data-stock="{{ $producto->stock ?? 0 }}"
                                    data-precio="{{ $producto->precio ?? 0 }}">
                                    {{ $producto->nombre }} | {{ $producto->sub_categoria }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('cantidad')}"><input type="number" name="productos[${index}][cantidad]" value="1" min="1" class="cantidad"></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('precio_unitario')}"><input type="number" name="productos[${index}][precio_unitario]" value="0.00" min="0" step="0.01" class="precio"></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('precio_prod')}"><input type="text" class="precio_prod" value="0.00" readonly></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('descuento')}"><input type="number" name="productos[${index}][descuento]" value="0.00" min="0" step="0.01" class="descuento"></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('subtotal')}"><input type="text" name="productos[${index}][subtotal]" value="0.00" readonly class="subtotal"></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('marca')}"><input type="text" class="marca" value="" readonly></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('stock')}"><input type="text" class="stock" value="0" readonly></td>
                    <td class="px-3 py-2 text-center lg:border" data-label="Acciones"><button type="button" class="remove-btn w-full lg:w-auto">Eliminar</button></td>
                </tr>`;
                $('#productosTable tbody').append(row);
                // Aplicar estilos y Select2 a los nuevos elementos
                let newRow = $('#productosTable tbody tr:last-child');
                newRow.find('.remove-btn').addClass(removeBtnClass);
                newRow.find('input').addClass('w-full text-sm p-1 border rounded-sm border-gray-300');
                newRow.find('.producto-select').select2();
                calcularSubtotal(newRow); // Calcular subtotal inicial (0.00)
            });

            // Agregar servicio
            $('#addServicio').click(function() {
                let index = getNextIndex('serviciosTable'); // Obtener índice único
                let row = `
                <tr class="servicio-row transition duration-150 ease-in-out hover:bg-yellow-50 lg:table-row">
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('servicio_id')}">
                        <select class="servicio-select" name="servicios[${index}][servicio_id]" required>
                            <option value="" selected disabled>Seleccione Servicio</option>
                            @foreach ($servicios as $s)
                                <option value="{{ $s->id }}" data-precio="{{ $s->precio ?? 0 }}">{{ $s->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('cantidad')}"><input type="number" name="servicios[${index}][cantidad]" value="1" min="1" class="cantidad"></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('precio_unitario')}"><input type="number" name="servicios[${index}][precio]" value="0.00" min="0" step="0.01" class="precio"></td>
                    <td class="px-3 py-2 text-gray-900 lg:border" data-label="${getLabel('subtotal')}"><input type="text" name="servicios[${index}][subtotal]" value="0.00" readonly class="subtotal"></td>
                    <td class="px-3 py-2 text-center lg:border" data-label="Acciones"><button type="button" class="remove-btn w-full lg:w-auto">Eliminar</button></td>
                </tr>`;
                $('#serviciosTable tbody').append(row);
                // Aplicar estilos y Select2 a los nuevos elementos
                let newRow = $('#serviciosTable tbody tr:last-child');
                newRow.find('.remove-btn').addClass(removeBtnClass);
                newRow.find('input').addClass('w-full text-sm p-1 border rounded-sm border-gray-300');
                newRow.find('.servicio-select').select2();
                calcularSubtotal(newRow); // Calcular subtotal inicial (0.00)
            });

            // Autocompletar datos del producto al elegir producto
            $(document).on('change', '.producto-select', function() {
                let option = $(this).find(':selected');
                let row = $(this).closest('tr');
                let precioProd = option.data('precio') || 0; // Obtener precio de la data attribute

                // Rellenar campos
                row.find('.precio_prod').val(parseFloat(precioProd).toFixed(2));
                row.find('.marca').val(option.data('marca'));
                row.find('.stock').val(option.data('stock'));

                // Opcional: Establecer el precio unitario al precio de lista si el input está a 0 (solo al cambiar por primera vez)
                // if (parseFloat(row.find('.precio').val()) === 0) {
                //     row.find('.precio').val(parseFloat(precioProd).toFixed(2)).trigger('change');
                // }

                // Recalcular subtotal
                calcularSubtotal(row);
            });

            // Autocompletar datos del servicio al elegir servicio (solo el precio, ya que no hay otros datos)
            $(document).on('change', '.servicio-select', function() {
                let option = $(this).find(':selected');
                let row = $(this).closest('tr');
                let precioServicio = option.data('precio') || 0;

                // Establecer el precio unitario del servicio
                row.find('.precio').val(parseFloat(precioServicio).toFixed(2));

                // Recalcular subtotal
                calcularSubtotal(row);
            });

            // Inicializar datos de filas existentes (necesario si la vista asume que estos campos están poblados desde JS)
            $('.producto-select').trigger('change');
        });
    </script>
</body>

</html>
