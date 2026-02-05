<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Select2 CSS básico -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Estilos personalizados para Select2 con Tailwind */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            height: 2.5rem;
            padding: 0.5rem 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.5rem;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 2.5rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }

        .stock-ok {
            color: #10b981;
            font-weight: 600;
        }

        .stock-low {
            color: #ef4444;
            font-weight: 600;
        }

        .invalid-stock {
            border-color: #ef4444 !important;
            background-color: #fef2f2;
        }

        .stock-warning {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
    </style>
    <title>Crear Venta</title>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <x-admin-nav />

        <div class="flex-1 p-6">
            <div class="max-w-7xl mx-auto">
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Crear Nueva Venta</h1>
                    <p class="text-gray-600 mt-2">Complete la información requerida para registrar una nueva venta</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">¡Error!</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('ventas.store') }}" method="POST" id="venta-form" class="space-y-8">
                    @csrf

                    <!-- Información general -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Información de la Venta</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Cliente -->
                            <div>
                                <label for="cliente_id"
                                    class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                                <select name="cliente_id" id="cliente_id"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"
                                            {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->RazonSocial }} {{ $cliente->NombreCompleto }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Fecha de venta -->
                            <div>
                                <label for="fecha_venta" class="block text-sm font-medium text-gray-700 mb-1">Fecha de
                                    Venta</label>
                                <input type="date" name="fecha_venta" id="fecha_venta"
                                    value="{{ old('fecha_venta', date('Y-m-d')) }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                            </div>

                            <!-- Tipo Comprobante -->
                            <div>
                                <label for="tipo_comprobante" class="block text-sm font-medium text-gray-700 mb-1">Tipo
                                    Comprobante</label>
                                <select name="tipo_comprobante" id="tipo_comprobante"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    <option value="">Selecciona un tipo</option>
                                    <option value="Factura"
                                        {{ old('tipo_comprobante') == 'Factura' ? 'selected' : '' }}>Factura</option>
                                    <option value="Presupuesto"
                                        {{ old('tipo_comprobante') == 'Presupuesto' ? 'selected' : '' }}>Presupuesto
                                    </option>
                                    <option value="Nota de Crédito"
                                        {{ old('tipo_comprobante') == 'Nota de Crédito' ? 'selected' : '' }}>Nota de
                                        Crédito</option>
                                    <option value="Nota de Débito"
                                        {{ old('tipo_comprobante') == 'Nota de Débito' ? 'selected' : '' }}>Nota de
                                        Débito</option>
                                </select>
                            </div>

                            <!-- Condición de Pago -->
                            <div>
                                <label for="condicion_pago"
                                    class="block text-sm font-medium text-gray-700 mb-1">Condición de Pago</label>
                                <select name="condicion_pago" id="condicion_pago"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    <option value="">Selecciona</option>
                                    <option value="Contado Efectivo"
                                        {{ old('condicion_pago') == 'Contado Efectivo' ? 'selected' : '' }}>Contado
                                        Efectivo</option>
                                    <option value="Contado Transferencia"
                                        {{ old('condicion_pago') == 'Contado Transferencia' ? 'selected' : '' }}>
                                        Contado Transferencia</option>
                                    <option value="Pago Parcial"
                                        {{ old('condicion_pago') == 'Pago Parcial' ? 'selected' : '' }}>Pago Parcial
                                    </option>
                                    <option value="Cuenta Corriente"
                                        {{ old('condicion_pago') == 'Cuenta Corriente' ? 'selected' : '' }}>Cuenta
                                        Corriente</option>
                                </select>
                            </div>

                            <!-- Estado de venta -->
                            <div>
                                <label for="estado_venta" class="block text-sm font-medium text-gray-700 mb-1">Estado de
                                    la Venta</label>
                                <select name="estado_venta" id="estado_venta"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    <option value="Pagada" {{ old('estado_venta') == 'Pagada' ? 'selected' : '' }}>
                                        Pagada</option>
                                    <option value="Pendiente"
                                        {{ old('estado_venta') == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                </select>
                            </div>

                            <!-- Observaciones -->
                            <div class="md:col-span-2">
                                <label for="observaciones"
                                    class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" rows="3"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('observaciones') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Productos del Detalle</h2>
                                <span id="productos-count" class="text-sm text-gray-600">0 productos</span>
                            </div>
                            <button type="button" onclick="addRowProducto()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Agregar Producto
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="productos-table" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Producto</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cantidad</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Precio Unitario</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Descuento %</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subtotal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subcategoría</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Marca</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Stock</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productos-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Las filas se agregarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Servicios -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800">Servicios del Detalle</h2>
                                <span id="servicios-count" class="text-sm text-gray-600">0 servicios</span>
                            </div>
                            <button type="button" onclick="addRowServicio()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Agregar Servicio
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="servicios-table" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Servicio</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Cantidad</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Precio Unitario</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Subtotal</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="servicios-table-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Las filas se agregarán dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-800">Resumen de Totales</h2>
                        </div>
                        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="block text-sm font-medium text-gray-500">Importe Neto</span>
                                <p id="importe-neto" class="text-2xl font-bold text-gray-900">$0.00</p>
                                <input type="hidden" name="importe_neto" id="importe-neto-input" value="0.00">
                            </div>

                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="block text-sm font-medium text-gray-500">IVA 21%</span>
                                <p id="importe-iva" class="text-2xl font-bold text-gray-900">$0.00</p>
                                <input type="hidden" name="importe_iva" id="importe-iva-input" value="0.00">
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <span class="block text-sm font-medium text-blue-700">Importe Total</span>
                                <p id="importe-total" class="text-2xl font-bold text-blue-800">$0.00</p>
                                <input type="hidden" name="importe_total" id="importe-total-input" value="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between pt-6">
                        <button type="button" onclick="clearForm()"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Limpiar Formulario
                        </button>
                        <button type="submit" id="submit-btn"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Guardar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de duplicado -->
    <div id="duplicateModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white shadow rounded-lg overflow-hidden w-96">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold">Elemento duplicado</h3>
                <button id="closeDuplicateModal" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="p-6">
                <p id="duplicateMessage" class="text-gray-700"></p>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                <button id="duplicateModalOk"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Cerrar</button>
            </div>
        </div>
    </div>





    <!-- Dependencias JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function showDuplicateModal(tipo, nombre = '') {
            const modal = document.getElementById('duplicateModal');
            const message = document.getElementById('duplicateMessage');
            message.textContent = `El ${tipo} "${nombre}" ya fue agregado.`;
            modal.classList.remove('hidden');
        }

        document.getElementById('closeDuplicateModal').addEventListener('click', () => {
            document.getElementById('duplicateModal').classList.add('hidden');
        });
        document.getElementById('duplicateModalOk').addEventListener('click', () => {
            document.getElementById('duplicateModal').classList.add('hidden');
        });
    </script>

    <script>
        // Variables globales y referencias
        const productosTableBody = document.getElementById('productos-table-body');
        const serviciosTableBody = document.getElementById('servicios-table-body');
        const IVA_RATE = 0.21;
        let productoRowIndex = 0;
        let servicioRowIndex = 0;

        // Document ready
        $(document).ready(function() {
            // Inicializar Select2 para cliente
            $('#cliente_id').select2({
                width: '100%',
                placeholder: "Selecciona un cliente",
                allowClear: true
            });

            // Agregar primera fila de producto y servicio
            addRowProducto();
            addRowServicio();

            // Calcular totales iniciales y actualizar contadores
            calculateTotals();
            updateCounters();

            // Validación al enviar formulario
            $('#venta-form').on('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
                setLoadingState(true);
            });
        });

        // Inicializa Select2 para selects de producto añadidos dinámicamente
        function initProductSelect($select) {
            $select.select2({
                width: '100%',
                placeholder: "Selecciona un producto",
                allowClear: true
            }).on('change', function() {
                const selectedValue = $(this).val();

                // Si ya existe el producto seleccionado en otra fila → revertir
                const duplicate = $('#productos-table-body .product-select').not(this).filter(function() {
                    return $(this).val() === selectedValue && selectedValue !== '';
                }).length > 0;

                if (duplicate) {
                    const selectedText = $(this).find('option:selected').text();
                    showDuplicateModal('producto', selectedText);
                    $(this).val('').trigger('change');
                    return;
                }

                updateProductFields(this);
            });
        }

        // Inicializa Select2 para selects de servicio añadidos dinámicamente
        function initServiceSelect($select) {
            $select.select2({
                width: '100%',
                placeholder: "Selecciona un servicio",
                allowClear: true
            }).on('change', function() {
                const selectedValue = $(this).val();

                // Si ya existe el servicio seleccionado en otra fila → revertir
                const duplicate = $('#servicios-table-body .service-select').not(this).filter(function() {
                    return $(this).val() === selectedValue && selectedValue !== '';
                }).length > 0;

                if (duplicate) {
                    const selectedText = $(this).find('option:selected').text();
                    showDuplicateModal('servicio', selectedText);
                    $(this).val('').trigger('change');
                    return;
                }

                updateServiceFields(this);
            });
        }

        // Actualiza campos al seleccionar producto
        function updateProductFields(select) {
            const option = select.options[select.selectedIndex];
            const row = select.closest('.detalle-producto-row');

            if (!row) return;

            if (option && option.value) {
                const precio = parseFloat(option.getAttribute('data-precio')) || 0;
                const stock = parseInt(option.getAttribute('data-stock')) || 0;

                const precioInput = row.querySelector('.precio-input');
                const subcategoriaField = row.querySelector('.subcategoria-field');
                const marcaField = row.querySelector('.marca-field');
                const stockField = row.querySelector('.stock-field');

                if (precioInput) precioInput.value = precio.toFixed(2);
                if (subcategoriaField) subcategoriaField.value = option.getAttribute('data-subcategoria') || '';
                if (marcaField) marcaField.value = option.getAttribute('data-marca') || '';
                if (stockField) stockField.value = stock;

                if (stockField) {
                    if (stock < 10) {
                        stockField.classList.add('stock-low');
                        stockField.classList.remove('stock-ok');
                    } else {
                        stockField.classList.add('stock-ok');
                        stockField.classList.remove('stock-low');
                    }
                }

                validateStock(row);
            }

            calculateTotals();
        }

        // Actualiza precio al seleccionar servicio
        function updateServiceFields(select) {
            const option = select.options[select.selectedIndex];
            const row = select.closest('.detalle-servicio-row');

            if (!row) return;

            if (option && option.value) {
                const precio = parseFloat(option.getAttribute('data-precio')) || 0;
                const precioInput = row.querySelector('.precio-input');
                if (precioInput) precioInput.value = precio.toFixed(2);
            }

            calculateTotals();
        }

        // Validar stock para una fila
        function validateStock(row) {
            const cantidadInput = row.querySelector('.cantidad-input');
            const cantidad = parseInt(cantidadInput ? cantidadInput.value : 0) || 0;
            const stock = parseInt(row.querySelector('.stock-field') ? row.querySelector('.stock-field').value : 0) || 0;

            if (!cantidadInput) return;

            if (cantidad > stock) {
                cantidadInput.classList.add('invalid-stock');
                showStockWarning(row, stock);
            } else {
                cantidadInput.classList.remove('invalid-stock');
                hideStockWarning(row);
            }
        }

        // Mostrar advertencia de stock
        function showStockWarning(row, stockDisponible) {
            let warning = row.querySelector('.stock-warning');
            if (!warning) {
                warning = document.createElement('div');
                warning.className = 'stock-warning';
                row.querySelector('.cantidad-input').parentNode.appendChild(warning);
            }
            warning.textContent = `Stock insuficiente. Disponible: ${stockDisponible}`;
        }

        function hideStockWarning(row) {
            const warning = row.querySelector('.stock-warning');
            if (warning) warning.remove();
        }

        // Calcula subtotal para producto
        function calculateProductSubtotal(row) {
            const cantidad = parseFloat(row.querySelector('.cantidad-input') ? row.querySelector('.cantidad-input').value :
                0) || 0;
            const precio = parseFloat(row.querySelector('.precio-input') ? row.querySelector('.precio-input').value : 0) ||
                0;
            const descuento = parseFloat(row.querySelector('.descuento-input') ? row.querySelector('.descuento-input')
                .value : 0) || 0;

            const subtotalSinDescuento = cantidad * precio;
            const montoDescuento = (subtotalSinDescuento * descuento) / 100;
            const subtotalConDescuento = subtotalSinDescuento - montoDescuento;

            return Math.max(0, subtotalConDescuento);
        }

        // Calcula subtotal para servicio
        function calculateServiceSubtotal(row) {
            const cantidad = parseFloat(row.querySelector('.cantidad-input') ? row.querySelector('.cantidad-input').value :
                0) || 0;
            const precio = parseFloat(row.querySelector('.precio-input') ? row.querySelector('.precio-input').value : 0) ||
                0;
            return cantidad * precio;
        }

        // Calcula totales generales
        function calculateTotals() {
            let totalNeto = 0;

            // Productos
            productosTableBody.querySelectorAll('.detalle-producto-row').forEach(row => {
                const subtotal = calculateProductSubtotal(row);
                const subtotalCell = row.querySelector('.subtotal-cell');
                const subtotalInput = row.querySelector('.subtotal-input');
                if (subtotalCell) subtotalCell.textContent = '$' + subtotal.toFixed(2);
                if (subtotalInput) subtotalInput.value = subtotal.toFixed(2);
                totalNeto += subtotal;
            });

            // Servicios
            serviciosTableBody.querySelectorAll('.detalle-servicio-row').forEach(row => {
                const subtotal = calculateServiceSubtotal(row);
                const subtotalCell = row.querySelector('.subtotal-cell');
                const subtotalInput = row.querySelector('.subtotal-input');
                if (subtotalCell) subtotalCell.textContent = '$' + subtotal.toFixed(2);
                if (subtotalInput) subtotalInput.value = subtotal.toFixed(2);
                totalNeto += subtotal;
            });

            const totalIva = totalNeto * IVA_RATE;
            const totalFinal = totalNeto + totalIva;

            document.getElementById('importe-neto').textContent = '$' + totalNeto.toFixed(2);
            document.getElementById('importe-iva').textContent = '$' + totalIva.toFixed(2);
            document.getElementById('importe-total').textContent = '$' + totalFinal.toFixed(2);

            document.getElementById('importe-neto-input').value = totalNeto.toFixed(2);
            document.getElementById('importe-iva-input').value = totalIva.toFixed(2);
            document.getElementById('importe-total-input').value = totalFinal.toFixed(2);

            updateCounters();
        }

        // Actualizar contadores
        function updateCounters() {
            const productosCount = productosTableBody.querySelectorAll('.detalle-producto-row').length;
            const serviciosCount = serviciosTableBody.querySelectorAll('.detalle-servicio-row').length;

            document.getElementById('productos-count').textContent =
                `${productosCount} ${productosCount === 1 ? 'producto' : 'productos'}`;
            document.getElementById('servicios-count').textContent =
                `${serviciosCount} ${serviciosCount === 1 ? 'servicio' : 'servicios'}`;
        }

        // Agregar fila de producto
        function addRowProducto() {
            // Verificar si hay productos disponibles
            const existingProducts = $('#productos-table-body .product-select').map(function() {
                return $(this).val();
            }).get();

            if (existingProducts.length >= @json(count($productos))) {
                alert('Ya agregaste todos los productos disponibles.');
                return;
            }

            const newRowHtml = `
    <tr class="detalle-producto-row">
        <td class="px-6 py-4 whitespace-nowrap">
            <select name="productos[${productoRowIndex}][id]" class="product-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="">Selecciona un producto</option>
                @foreach ($productos as $producto)
                <option value="{{ $producto->id }}"
                        data-precio="{{ $producto->precio }}"
                        data-subcategoria="{{ $producto->sub_categoria }}"
                        data-marca="{{ $producto->marca->nombre ?? '' }}"
                        data-stock="{{ $producto->stock }}">
                    {{ $producto->nombre }} | {{ $producto->sub_categoria }} | ${{ number_format($producto->precio, 2) }}
                </option>
                @endforeach
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="productos[${productoRowIndex}][cantidad]" min="1" value="1"
                   class="cantidad-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" step="0.01" name="productos[${productoRowIndex}][precio_unitario]"
                   class="precio-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" step="0.01" name="productos[${productoRowIndex}][descuento]" value="0"
                   class="descuento-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" min="0" max="100">
        </td>
        <td class="px-6 py-4 whitespace-nowrap subtotal-cell font-medium">$0.00
            <input type="hidden" name="productos[${productoRowIndex}][subtotal]" class="subtotal-input" value="0">
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="text" name="productos[${productoRowIndex}][subcategoria]" class="subcategoria-field w-full rounded-md border-gray-300 bg-gray-50" disabled>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="text" name="productos[${productoRowIndex}][marca]" class="marca-field w-full rounded-md border-gray-300 bg-gray-50" disabled>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="text" name="productos[${productoRowIndex}][stock]" class="stock-field w-full rounded-md border-gray-300 bg-gray-50" disabled>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button type="button" onclick="confirmRemoveRow(this)"
                    class="text-red-600 hover:text-red-900 focus:outline-none">
                Eliminar
            </button>
        </td>
    </tr>`;

            productosTableBody.insertAdjacentHTML('beforeend', newRowHtml);
            const lastSelect = $(productosTableBody.lastElementChild).find('select.product-select');
            initProductSelect(lastSelect);
            productoRowIndex++;
        }

        // Agregar fila de servicio
        function addRowServicio() {
            const existingServices = $('#servicios-table-body .service-select').map(function() {
                return $(this).val();
            }).get();

            if (existingServices.length >= @json(count($servicios))) {
                alert('Ya agregaste todos los servicios disponibles.');
                return;
            }

            const newRowHtml = `
    <tr class="detalle-servicio-row">
        <td class="px-6 py-4 whitespace-nowrap">
            <select name="servicios[${servicioRowIndex}][servicio_id]" class="service-select w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                <option value="">Selecciona un servicio</option>
                @foreach ($servicios as $servicio)
                <option value="{{ $servicio->id }}" data-precio="{{ $servicio->precio }}">
                    {{ $servicio->nombre }}
                </option>
                @endforeach
            </select>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" name="servicios[${servicioRowIndex}][cantidad]" min="1" value="1"
                   class="cantidad-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <input type="number" step="0.01" name="servicios[${servicioRowIndex}][precio]"
                   class="precio-input w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
        </td>
        <td class="px-6 py-4 whitespace-nowrap subtotal-cell font-medium">$0.00
            <input type="hidden" name="servicios[${servicioRowIndex}][subtotal]" class="subtotal-input" value="0">
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
            <button type="button" onclick="confirmRemoveRow(this)"
                    class="text-red-600 hover:text-red-900 focus:outline-none">
                Eliminar
            </button>
        </td>
    </tr>`;

            serviciosTableBody.insertAdjacentHTML('beforeend', newRowHtml);
            const lastSelect = $(serviciosTableBody.lastElementChild).find('select.service-select');
            initServiceSelect(lastSelect);
            servicioRowIndex++;
        }

        // Confirmar eliminación de fila
        function confirmRemoveRow(button) {
            const tableBody = button.closest('tbody');
            const rowCount = tableBody.querySelectorAll('tr').length;

            if (rowCount <= 1) {
                alert('Debe haber al menos un elemento');
                return;
            }

            if (confirm('¿Está seguro de que desea eliminar este elemento?')) {
                removeRow(button);
            }
        }

        // Eliminar fila
        function removeRow(button) {
            button.closest('tr').remove();
            calculateTotals();
        }

        // Validación del formulario
        function validateForm() {
            let isValid = true;
            const errors = [];

            // Cliente
            if (!$('#cliente_id').val()) {
                errors.push('El cliente es obligatorio.');
                isValid = false;
            }

            // Al menos un producto o servicio
            const hasProducts = $('#productos-table-body .detalle-producto-row').length > 0;
            const hasServices = $('#servicios-table-body .detalle-servicio-row').length > 0;

            if (!hasProducts && !hasServices) {
                errors.push('Debe agregar al menos un producto o servicio');
                isValid = false;
            }

            // Validar productos
            $('#productos-table-body .detalle-producto-row').each(function(index) {
                const productoSelect = $(this).find('select.product-select');
                const cantidadInput = $(this).find('.cantidad-input');
                const precioInput = $(this).find('.precio-input');

                if (!productoSelect.val()) {
                    errors.push(`El producto en la fila ${index + 1} es obligatorio`);
                    isValid = false;
                }

                if (!cantidadInput.val() || parseInt(cantidadInput.val()) < 1) {
                    errors.push(
                        `La cantidad del producto en la fila ${index + 1} es obligatoria y debe ser al menos 1`);
                    isValid = false;
                }

                if (!precioInput.val() || parseFloat(precioInput.val()) < 0) {
                    errors.push(
                        `El precio del producto en la fila ${index + 1} es obligatorio y no puede ser negativo`);
                    isValid = false;
                }

                // Stock
                const cantidad = parseInt(cantidadInput.val()) || 0;
                const stock = parseInt($(this).find('.stock-field').val()) || 0;
                const productoNombre = productoSelect.find('option:selected').text();

                if (cantidad > stock) {
                    errors.push(
                        `Stock insuficiente para el producto "${productoNombre}". Disponible: ${stock}, solicitado: ${cantidad}.`
                    );
                    isValid = false;
                }
            });

            // Validar servicios
            $('#servicios-table-body .detalle-servicio-row').each(function(index) {
                const servicioSelect = $(this).find('select.service-select');
                const cantidadInput = $(this).find('.cantidad-input');
                const precioInput = $(this).find('.precio-input');

                if (!servicioSelect.val()) {
                    errors.push(`El servicio en la fila ${index + 1} es obligatorio`);
                    isValid = false;
                }

                if (!cantidadInput.val() || parseInt(cantidadInput.val()) < 1) {
                    errors.push(
                        `La cantidad del servicio en la fila ${index + 1} es obligatoria y debe ser al menos 1`);
                    isValid = false;
                }

                if (!precioInput.val() || parseFloat(precioInput.val()) < 0) {
                    errors.push(
                        `El precio del servicio en la fila ${index + 1} es obligatorio y no puede ser negativo`);
                    isValid = false;
                }
            });

            if (!isValid) {
                showErrors(errors);
            }

            return isValid;
        }

        // Mostrar errores (inserta un bloque simple sin estilos)
        function showErrors(errors) {
            // Remover anteriores
            $('.validation-errors').remove();

            const errorHtml = `
                <div class="validation-errors bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">¡Error de validación!</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    ${errors.map(error => `<li>${error}</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            // Insertar después del título
            $('h1').after(errorHtml);

            // Scroll to errors (si existe posición)
            if ($('.validation-errors').length) {
                $('html, body').animate({
                    scrollTop: $('.validation-errors').offset().top - 20
                }, 300);
            }
        }

        // Estado de carga del botón enviar
        function setLoadingState(loading) {
            const submitBtn = document.getElementById('submit-btn');
            if (!submitBtn) return;

            if (loading) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Guardando...';
                submitBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                submitBtn.classList.add('bg-blue-400');
            } else {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Guardar Venta';
                submitBtn.classList.remove('bg-blue-400');
                submitBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }
        }

        // Limpiar formulario
        function clearForm() {
            if (confirm('¿Está seguro de que desea limpiar todo el formulario? Se perderán todos los datos.')) {
                document.getElementById('venta-form').reset();
                productosTableBody.innerHTML = '';
                serviciosTableBody.innerHTML = '';
                productoRowIndex = 0;
                servicioRowIndex = 0;
                addRowProducto();
                addRowServicio();
                calculateTotals();
            }
        }

        // Escuchar inputs para cálculos en tiempo real
        document.addEventListener('input', e => {
            if (e.target.matches('.cantidad-input, .precio-input, .descuento-input')) {
                calculateTotals();

                if (e.target.matches('.cantidad-input')) {
                    const row = e.target.closest('.detalle-producto-row');
                    if (row) validateStock(row);
                }
            }
        });
    </script>
</body>

</html>
