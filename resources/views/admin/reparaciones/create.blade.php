<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Crear Reparación</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Estilos personalizados -->
    <style>
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            height: 2.75rem;
            padding: 0.5rem 0.75rem;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.75rem;
            padding-left: 0;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 2.75rem;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }

        .select2-container {
            width: 100% !important;
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

        /* Estilos responsivos */
        @media screen and (max-width: 1024px) {
            #productos-table, #servicios-table {
                display: block;
                overflow-x: auto;
            }
            #productos-table thead, #servicios-table thead {
                display: none;
            }
            #productos-table tr, #servicios-table tr {
                margin-bottom: 10px;
                display: block;
                border: 1px solid #ccc;
                border-radius: 8px;
            }
            #productos-table td, #servicios-table td {
                display: block;
                text-align: right;
                border: none;
                position: relative;
                padding-left: 50%;
            }
            #productos-table td::before, #servicios-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 45%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
            }
            #productos-table td:last-child, #servicios-table td:last-child {
                text-align: center;
                padding-left: 10px;
                padding-right: 10px;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <div class="flex">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="max-w-7xl mx-auto bg-white rounded-lg shadow p-6">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6 border-b pb-3">
                    <div>
                        <h1 class="text-xl font-semibold text-gray-800">Crear Nueva Reparación</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Complete la información requerida para registrar una nueva reparación</p>
                    </div>
                    <a href="{{ route('reparaciones.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <i class="fas fa-arrow-left mr-1"></i> Volver
                    </a>
                </div>

                <!-- Errores -->
                @if ($errors->any())
                    <div class="mb-4 text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('reparaciones.store') }}" method="POST" id="reparacion-form" class="space-y-6">
                    @csrf

                    <!-- Información general -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Información de la Reparación</h2>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Código Único -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Código Único</label>
                                <input type="text" name="codigo_unico" id="codigo_unico"
                                    value="{{ old('codigo_unico') }}"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    placeholder="Ej: REP-2025-0001">
                            </div>

                            <!-- Cliente -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Cliente</label>
                                <select name="cliente_id" id="cliente_id" class="select2 w-full" required>
                                    <option value="">Selecciona un cliente</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}"
                                            {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->NombreCompleto ?? $cliente->RazonSocial }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Descripción del Equipo -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Descripción del Equipo</label>
                                <input type="text" name="equipo_descripcion" id="equipo_descripcion"
                                    value="{{ old('equipo_descripcion') }}"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    placeholder="Ej: Laptop, Impresora, Celular"
                                    required>
                            </div>

                            <!-- Marca del Equipo -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Marca del Equipo</label>
                                <input type="text" name="equipo_marca" id="equipo_marca"
                                    value="{{ old('equipo_marca') }}"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    placeholder="Ej: HP, Samsung, Dell"
                                    required>
                            </div>

                            <!-- Modelo del Equipo -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Modelo del Equipo</label>
                                <input type="text" name="equipo_modelo" id="equipo_modelo"
                                    value="{{ old('equipo_modelo') }}"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    placeholder="Ej: Pavilion, Galaxy S21, Latitude"
                                    required>
                            </div>

                            <!-- Reparable -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Reparable</label>
                                <select name="reparable" id="reparable"
                                    class="w-full border rounded px-3 py-2 text-sm" required>
                                    <option value="1" {{ old('reparable') == '1' ? 'selected' : '' }}>Sí</option>
                                    <option value="0" {{ old('reparable') == '0' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <!-- Fecha de Ingreso -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Fecha de Ingreso</label>
                                <input type="date" name="fecha_ingreso" id="fecha_ingreso"
                                    value="{{ old('fecha_ingreso', date('Y-m-d')) }}"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    required>
                            </div>

                            <!-- Fecha de Egreso -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Fecha de Egreso</label>
                                <input type="date" name="fecha_egreso" id="fecha_egreso"
                                    value="{{ old('fecha_egreso') }}"
                                    class="w-full border rounded px-3 py-2 text-sm">
                            </div>

                            <!-- Estado de Reparación -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Estado de la Reparación</label>
                                <select name="estado_reparacion" id="estado_reparacion"
                                    class="w-full border rounded px-3 py-2 text-sm" required>
                                    <option value="">Selecciona un estado</option>
                                    @foreach(['Pendiente', 'En proceso', 'Reparado', 'No reparable', 'Entregado'] as $estado)
                                        <option value="{{ $estado }}" {{ old('estado_reparacion') == $estado ? 'selected' : '' }}>
                                            {{ $estado }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Descripción del Daño -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Detalles del Problema</h2>
                        </div>
                        <div class="p-5">
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Descripción del Daño</label>
                                <textarea name="descripcion_danio" id="descripcion_danio" rows="3"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    placeholder="Describa detalladamente el problema o daño del equipo..."
                                    required>{{ old('descripcion_danio') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Solución Aplicada -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Solución</h2>
                        </div>
                        <div class="p-5">
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Solución Aplicada</label>
                                <textarea name="solucion_aplicada" id="solucion_aplicada" rows="3"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    placeholder="Describa la solución aplicada (si ya fue reparado)...">{{ old('solucion_aplicada') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Productos Utilizados</h2>
                                <span id="productos-count" class="text-xs text-gray-500">0 productos</span>
                            </div>
                            <button type="button" id="btn-agregar-producto"
                                class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 uppercase tracking-wider font-medium">
                                <i class="fas fa-plus mr-1"></i> Agregar Producto
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="productos-table" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Producto</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cant.</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio Unit.</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Dto %</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productos-table-body" class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Servicios -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Servicios Realizados</h2>
                                <span id="servicios-count" class="text-xs text-gray-500">0 servicios</span>
                            </div>
                            <button type="button" id="btn-agregar-servicio"
                                class="px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 uppercase tracking-wider font-medium">
                                <i class="fas fa-plus mr-1"></i> Agregar Servicio
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table id="servicios-table" class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cant.</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Precio Unit.</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="servicios-table-body" class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Resumen de Costos</h2>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded border">
                                <span class="block text-xs text-gray-600 uppercase font-medium">Costo Total</span>
                                <p id="costo-total" class="text-lg font-bold text-gray-900">$0.00</p>
                                <input type="hidden" name="costo_total" id="costo-total-input" value="0.00">
                            </div>
                            <div class="bg-blue-50 p-3 rounded border border-blue-200">
                                <span class="block text-xs text-blue-700 uppercase font-medium">IVA 21%</span>
                                <p id="importe-iva" class="text-lg font-bold text-blue-800">$0.00</p>
                                <input type="hidden" name="importe_iva" id="importe-iva-input" value="0.00">
                            </div>
                        </div>
                    </div>

                    <!-- Acciones finales -->
                    <div class="flex justify-between pt-2 border-t">
                        <button type="button" id="btn-limpiar"
                                class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                            <i class="fas fa-eraser mr-1"></i> Limpiar Formulario
                        </button>
                        <button type="submit" id="submit-btn"
                                class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 flex items-center">
                            <i class="fas fa-save mr-2"></i> Guardar Reparación
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- Modal de duplicado -->
    <div id="duplicateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow overflow-hidden w-96">
            <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Elemento duplicado</h3>
                <button id="closeDuplicateModal" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            </div>
            <div class="p-5">
                <p id="duplicateMessage" class="text-sm text-gray-600"></p>
            </div>
            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200 flex justify-end">
                <button id="duplicateModalOk"
                        class="px-4 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 uppercase tracking-wider font-medium">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <!-- Dependencias JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        (function() {
            'use strict';

            // Constantes
            const IVA_RATE = 0.21;
            const STOCK_MINIMO = 10;

            // Estado global
            const state = {
                productoIndex: 0,
                servicioIndex: 0,
                productos: @json($productos ?? []),
                servicios: @json($servicios ?? [])
            };

            // Referencias DOM
            const $productosBody = $('#productos-table-body');
            const $serviciosBody = $('#servicios-table-body');
            const $duplicateModal = $('#duplicateModal');

            // Utilidades
            const formatMoney = (value) => '$' + parseFloat(value).toFixed(2);
            const parseNumber = (value) => parseFloat(value) || 0;

            // Inicialización
            $(document).ready(function() {
                // Inicializar Select2
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Selecciona un cliente',
                    allowClear: true
                });

                calculateTotals();
                bindEvents();
            });

            // Bind de eventos
            function bindEvents() {
                // Botones principales
                $('#btn-agregar-producto').on('click', () => addRow('producto'));
                $('#btn-agregar-servicio').on('click', () => addRow('servicio'));
                $('#btn-limpiar').on('click', clearForm);

                // Modal
                $('#closeDuplicateModal, #duplicateModalOk').on('click', () => $duplicateModal.addClass('hidden'));

                // Formulario
                $('#reparacion-form').on('submit', handleSubmit);

                // Eventos de input
                $(document).on('input', '.cantidad-input, .precio-input, .descuento-input', function() {
                    calculateTotals();
                    if ($(this).hasClass('cantidad-input') && $(this).closest('.detalle-producto-row').length) {
                        validateStock($(this).closest('.detalle-producto-row'));
                    }
                });
            }

            // Agregar fila
            function addRow(type) {
                const isProduct = type === 'producto';
                const $target = isProduct ? $productosBody : $serviciosBody;
                const index = isProduct ? state.productoIndex++ : state.servicioIndex++;

                const rowHtml = generateRowHtml(type, index);
                $target.append(rowHtml);

                const $newRow = $target.children().last();

                if (isProduct) {
                    const $select = $newRow.find('.product-select');
                    initProductSelect($select);
                } else {
                    const $select = $newRow.find('.service-select');
                    initServiceSelect($select);
                }

                calculateTotals();
            }

            // Generar HTML de fila
            function generateRowHtml(type, index) {
                const isProduct = type === 'producto';

                if (isProduct) {
                    return `<tr class="detalle-producto-row">
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Producto">
                            <select name="productos[${index}][id]" class="product-select w-full border rounded px-2 py-1.5 text-sm" required>
                                <option value="">Selecciona un producto</option>
                                @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}"
                                        data-precio="{{ $producto->precio }}"
                                        data-subcategoria="{{ $producto->sub_categoria }}"
                                        data-marca="{{ $producto->marca->nombre ?? '' }}"
                                        data-stock="{{ $producto->stock }}">
                                    {{ $producto->nombre }} | ${{ number_format($producto->precio, 2) }}
                                    @if($producto->stock <= 0) (SIN STOCK) @endif
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Cantidad">
                            <input type="number" name="productos[${index}][cantidad]" min="1" value="1"
                                   class="cantidad-input w-20 border rounded px-2 py-1.5 text-sm" required>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Precio Unit.">
                            <input type="number" step="0.01" name="productos[${index}][precio_unitario]"
                                   class="precio-input w-24 border rounded px-2 py-1.5 text-sm" required>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Dto %">
                            <input type="number" step="0.01" name="productos[${index}][descuento]" value="0"
                                   class="descuento-input w-16 border rounded px-2 py-1.5 text-sm" min="0" max="100">
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap subtotal-cell font-medium text-sm" data-label="Subtotal">
                            $0.00
                            <input type="hidden" name="productos[${index}][subtotal]" class="subtotal-input" value="0">
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Stock">
                            <input type="text" name="productos[${index}][stock]"
                                   class="stock-field w-16 border rounded px-2 py-1.5 bg-gray-50 text-sm" disabled>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm" data-label="Acciones">
                            <button type="button" class="btn-eliminar text-red-600 hover:text-red-900 text-xs uppercase font-medium">
                                <i class="fas fa-trash mr-1"></i> Eliminar
                            </button>
                        </td>
                    </tr>`;
                }

                return `<tr class="detalle-servicio-row">
                    <td class="px-3 py-2 whitespace-nowrap" data-label="Servicio">
                        <select name="servicios[${index}][servicio_id]" class="service-select w-full border rounded px-2 py-1.5 text-sm" required>
                            <option value="">Selecciona un servicio</option>
                            @foreach ($servicios as $servicio)
                            <option value="{{ $servicio->id }}" data-precio="{{ $servicio->precio }}">
                                {{ $servicio->nombre }} | ${{ number_format($servicio->precio, 2) }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap" data-label="Cantidad">
                        <input type="number" name="servicios[${index}][cantidad]" min="1" value="1"
                               class="cantidad-input w-20 border rounded px-2 py-1.5 text-sm" required>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap" data-label="Precio Unit.">
                        <input type="number" step="0.01" name="servicios[${index}][precio]"
                               class="precio-input w-24 border rounded px-2 py-1.5 text-sm" required>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap subtotal-cell font-medium text-sm" data-label="Subtotal">
                        $0.00
                        <input type="hidden" name="servicios[${index}][subtotal]" class="subtotal-input" value="0">
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm" data-label="Acciones">
                        <button type="button" class="btn-eliminar text-red-600 hover:text-red-900 text-xs uppercase font-medium">
                            <i class="fas fa-trash mr-1"></i> Eliminar
                        </button>
                    </td>
                </tr>`;
            }

            // Inicializar select de productos
            function initProductSelect($select) {
                $select.select2({
                        width: '100%',
                        placeholder: 'Selecciona un producto'
                    })
                    .on('change', function() {
                        const selectedValue = $(this).val();
                        const $currentSelect = $(this);

                        // Validar duplicados
                        const duplicate = $('#productos-table-body .product-select')
                            .not($currentSelect).filter((_, el) => $(el).val() === selectedValue &&
                                selectedValue !== '').length > 0;

                        if (duplicate) {
                            showDuplicateModal('producto', $currentSelect.find('option:selected').text());
                            $currentSelect.val('').trigger('change');
                            return;
                        }

                        updateProductFields(this);
                    });
            }

            // Inicializar select de servicios
            function initServiceSelect($select) {
                $select.select2({
                        width: '100%',
                        placeholder: 'Selecciona un servicio'
                    })
                    .on('change', function() {
                        const selectedValue = $(this).val();
                        const $currentSelect = $(this);

                        // Validar duplicados
                        const duplicate = $('#servicios-table-body .service-select')
                            .not($currentSelect).filter((_, el) => $(el).val() === selectedValue &&
                                selectedValue !== '').length > 0;

                        if (duplicate) {
                            showDuplicateModal('servicio', $currentSelect.find('option:selected').text());
                            $currentSelect.val('').trigger('change');
                            return;
                        }

                        updateServiceFields(this);
                    });
            }

            // Actualizar campos del producto
            function updateProductFields(select) {
                const $row = $(select).closest('.detalle-producto-row');
                const $option = $(select).find('option:selected');

                if (!$option.val()) return;

                $row.find('.precio-input').val($option.data('precio'));

                const stock = parseInt($option.data('stock')) || 0;
                $row.find('.stock-field').val(stock);

                // Actualizar clase de stock
                const $stockField = $row.find('.stock-field');
                if (stock < STOCK_MINIMO) {
                    $stockField.addClass('stock-low').removeClass('stock-ok');
                } else {
                    $stockField.addClass('stock-ok').removeClass('stock-low');
                }

                calculateTotals();
                validateStock($row);
            }

            // Actualizar campos del servicio
            function updateServiceFields(select) {
                const $row = $(select).closest('.detalle-servicio-row');
                const $option = $(select).find('option:selected');

                if ($option.val()) {
                    $row.find('.precio-input').val($option.data('precio'));
                    calculateTotals();
                }
            }

            // Validar stock
            function validateStock($row) {
                const cantidad = parseInt($row.find('.cantidad-input').val()) || 0;
                const stock = parseInt($row.find('.stock-field').val()) || 0;
                const $cantidadInput = $row.find('.cantidad-input');

                if (cantidad > stock) {
                    $cantidadInput.addClass('invalid-stock');
                    showStockWarning($row, stock);
                } else {
                    $cantidadInput.removeClass('invalid-stock');
                    hideStockWarning($row);
                }
            }

            function showStockWarning($row, stockDisponible) {
                let $warning = $row.find('.stock-warning');
                if (!$warning.length) {
                    $warning = $('<div class="stock-warning text-xs mt-0.5">');
                    $row.find('.cantidad-input').parent().append($warning);
                }
                $warning.text(`Stock insuficiente. Disponible: ${stockDisponible}`);
            }

            function hideStockWarning($row) {
                $row.find('.stock-warning').remove();
            }

            // Cálculo de subtotales
            function calculateProductSubtotal($row) {
                const cantidad = parseNumber($row.find('.cantidad-input').val());
                const precio = parseNumber($row.find('.precio-input').val());
                const descuento = parseNumber($row.find('.descuento-input').val());

                const subtotal = cantidad * precio;
                const descuentoMonto = subtotal * (descuento / 100);
                return Math.max(0, subtotal - descuentoMonto);
            }

            function calculateServiceSubtotal($row) {
                const cantidad = parseNumber($row.find('.cantidad-input').val());
                const precio = parseNumber($row.find('.precio-input').val());
                return cantidad * precio;
            }

            // Calcular totales generales
            function calculateTotals() {
                let costoTotal = 0;

                // Productos
                $('.detalle-producto-row').each(function() {
                    const $row = $(this);
                    const subtotal = calculateProductSubtotal($row);
                    $row.find('.subtotal-cell').text(formatMoney(subtotal));
                    $row.find('.subtotal-input').val(subtotal.toFixed(2));
                    costoTotal += subtotal;
                });

                // Servicios
                $('.detalle-servicio-row').each(function() {
                    const $row = $(this);
                    const subtotal = calculateServiceSubtotal($row);
                    $row.find('.subtotal-cell').text(formatMoney(subtotal));
                    $row.find('.subtotal-input').val(subtotal.toFixed(2));
                    costoTotal += subtotal;
                });

                const totalIva = costoTotal * IVA_RATE;

                $('#costo-total').text(formatMoney(costoTotal));
                $('#costo-total-input').val(costoTotal.toFixed(2));
                $('#importe-iva').text(formatMoney(totalIva));
                $('#importe-iva-input').val(totalIva.toFixed(2));

                updateCounters();
            }

            // Actualizar contadores
            function updateCounters() {
                const productosCount = $('.detalle-producto-row').length;
                const serviciosCount = $('.detalle-servicio-row').length;

                $('#productos-count').text(`${productosCount} ${productosCount === 1 ? 'producto' : 'productos'}`);
                $('#servicios-count').text(`${serviciosCount} ${serviciosCount === 1 ? 'servicio' : 'servicios'}`);
            }

            // Eliminar fila
            $(document).on('click', '.btn-eliminar', function() {
                if (confirm('¿Está seguro de que desea eliminar este elemento?')) {
                    $(this).closest('tr').remove();
                    calculateTotals();
                }
            });

            // Modal de duplicado
            function showDuplicateModal(tipo, nombre) {
                $('#duplicateMessage').text(`El ${tipo} "${nombre}" ya fue agregado.`);
                $duplicateModal.removeClass('hidden');
            }

            // Limpiar formulario
            function clearForm() {
                if (confirm('¿Está seguro de que desea limpiar todo el formulario?')) {
                    $('#reparacion-form')[0].reset();
                    $('.select2').val('').trigger('change');
                    $productosBody.empty();
                    $serviciosBody.empty();
                    state.productoIndex = 0;
                    state.servicioIndex = 0;

                    // Establecer fecha actual por defecto
                    $('#fecha_ingreso').val(new Date().toISOString().split('T')[0]);

                    calculateTotals();
                }
            }

            // Validación del formulario
            function validateForm() {
                const errors = [];

                // Validar campos requeridos
                if (!$('#cliente_id').val()) {
                    errors.push('El cliente es obligatorio.');
                }

                if (!$('#equipo_descripcion').val().trim()) {
                    errors.push('La descripción del equipo es obligatoria.');
                }

                if (!$('#equipo_marca').val().trim()) {
                    errors.push('La marca del equipo es obligatoria.');
                }

                if (!$('#equipo_modelo').val().trim()) {
                    errors.push('El modelo del equipo es obligatorio.');
                }

                if (!$('#descripcion_danio').val().trim()) {
                    errors.push('La descripción del daño es obligatoria.');
                }

                if (!$('#fecha_ingreso').val()) {
                    errors.push('La fecha de ingreso es obligatoria.');
                }

                if (!$('#estado_reparacion').val()) {
                    errors.push('El estado de la reparación es obligatorio.');
                }

                // Validar productos
                $('.detalle-producto-row').each(function(index) {
                    const $row = $(this);

                    if (!$row.find('.product-select').val()) {
                        errors.push(`El producto en la fila ${index + 1} es obligatorio`);
                    }

                    const cantidad = parseInt($row.find('.cantidad-input').val());
                    if (!cantidad || cantidad < 1) {
                        errors.push(`La cantidad del producto en la fila ${index + 1} debe ser al menos 1`);
                    }

                    const precio = parseFloat($row.find('.precio-input').val());
                    if (!precio || precio < 0) {
                        errors.push(`El precio del producto en la fila ${index + 1} es inválido`);
                    }

                    const stock = parseInt($row.find('.stock-field').val()) || 0;
                    if (cantidad > stock) {
                        errors.push(
                            `Stock insuficiente para el producto "${$row.find('.product-select option:selected').text()}".`
                        );
                    }
                });

                // Validar servicios
                $('.detalle-servicio-row').each(function(index) {
                    const $row = $(this);

                    if (!$row.find('.service-select').val()) {
                        errors.push(`El servicio en la fila ${index + 1} es obligatorio`);
                    }

                    const cantidad = parseInt($row.find('.cantidad-input').val());
                    if (!cantidad || cantidad < 1) {
                        errors.push(`La cantidad del servicio en la fila ${index + 1} debe ser al menos 1`);
                    }

                    const precio = parseFloat($row.find('.precio-input').val());
                    if (!precio || precio < 0) {
                        errors.push(`El precio del servicio en la fila ${index + 1} es inválido`);
                    }
                });

                if (errors.length) {
                    showErrors(errors);
                    return false;
                }

                return true;
            }

            // Mostrar errores
            function showErrors(errors) {
                $('.validation-errors').remove();

                const errorHtml = `
                    <div class="validation-errors mb-4 text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            ${errors.map(error => `<li>${error}</li>`).join('')}
                        </ul>
                    </div>
                `;

                $('form').prepend(errorHtml);
                $('html, body').animate({
                    scrollTop: $('.validation-errors').offset().top - 20
                }, 300);
            }

            // Manejar envío del formulario
            function handleSubmit(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }

                const $btn = $('#submit-btn');
                $btn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin mr-2"></i> Guardando...')
                    .removeClass('bg-blue-600 hover:bg-blue-700')
                    .addClass('bg-blue-400');

                return true;
            }
        })();
    </script>
</body>

</html>
