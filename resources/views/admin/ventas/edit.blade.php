<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Venta</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Estilos personalizados para Select2 -->
    <style>
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
                        <h1 class="text-xl font-semibold text-gray-800">Editar Venta #{{ $venta->id }}</h1>
                        <p class="text-xs text-gray-500 mt-0.5">Modifique la información requerida para actualizar la venta</p>
                    </div>
                    <a href="{{ route('ventas.index') }}" class="text-sm text-black-600 hover:underline">
                        ← Volver
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

                <form action="{{ route('ventas.update', $venta->id) }}" method="POST" id="venta-form" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Información general -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Información de la Venta</h2>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Cliente -->
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-600 uppercase font-medium">Cliente</label>
                                <input type="hidden" name="cliente_id" value="{{ $venta->cliente_id }}">
                                <input type="text"
                                       value="{{ $venta->cliente->RazonSocial }} | {{ $venta->cliente->NombreCompleto }}"
                                       class="w-full border rounded px-3 py-2 text-sm bg-gray-50"
                                       disabled>
                            </div>

                            <!-- Fecha de venta -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Fecha de Venta</label>
                                <input type="date" name="fecha_venta" id="fecha_venta"
                                    value="{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m-d') }}"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    required>
                            </div>

                            <!-- Tipo Comprobante -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Tipo Comprobante</label>
                                <select name="tipo_comprobante" id="tipo_comprobante"
                                    class="w-full border rounded px-3 py-2 text-sm" required>
                                    <option value="">Selecciona un tipo</option>
                                    <option value="Factura" {{ $venta->tipo_comprobante == 'Factura' ? 'selected' : '' }}>Factura</option>
                                    <option value="Presupuesto" {{ $venta->tipo_comprobante == 'Presupuesto' ? 'selected' : '' }}>Presupuesto</option>
                                    <option value="Nota de Crédito" {{ $venta->tipo_comprobante == 'Nota de Crédito' ? 'selected' : '' }}>Nota de Crédito</option>
                                    <option value="Nota de Débito" {{ $venta->tipo_comprobante == 'Nota de Débito' ? 'selected' : '' }}>Nota de Débito</option>
                                </select>
                            </div>

                            <!-- Número Comprobante -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Número Comprobante</label>
                                <input type="text" name="numero_comprobante" id="numero_comprobante"
                                    value="{{ $venta->numero_comprobante }}"
                                    class="w-full border rounded px-3 py-2 text-sm"
                                    required>
                            </div>

                            <!-- Condición de Pago -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Condición de Pago</label>
                                <select name="condicion_pago" id="condicion_pago"
                                    class="w-full border rounded px-3 py-2 text-sm" required>
                                    <option value="">Selecciona</option>
                                    <option value="Contado Efectivo" {{ $venta->condicion_pago == 'Contado Efectivo' ? 'selected' : '' }}>Contado Efectivo</option>
                                    <option value="Contado Transferencia" {{ $venta->condicion_pago == 'Contado Transferencia' ? 'selected' : '' }}>Contado Transferencia</option>
                                    <option value="Pago Parcial" {{ $venta->condicion_pago == 'Pago Parcial' ? 'selected' : '' }}>Pago Parcial</option>
                                    <option value="Cuenta Corriente" {{ $venta->condicion_pago == 'Cuenta Corriente' ? 'selected' : '' }}>Cuenta Corriente</option>
                                </select>
                            </div>

                            <!-- Estado de venta -->
                            <div>
                                <label class="text-xs text-gray-600 uppercase font-medium">Estado de la Venta</label>
                                <select name="estado_venta" id="estado_venta"
                                    class="w-full border rounded px-3 py-2 text-sm" required>
                                    <option value="Pagada" {{ $venta->estado_venta == 'Pagada' ? 'selected' : '' }}>Pagada</option>
                                    <option value="Pendiente" {{ $venta->estado_venta == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                </select>
                            </div>

                            <!-- Visible -->
                            <div class="flex items-center mt-2">
                                <input type="checkbox" id="visible" name="visible" value="1"
                                    {{ $venta->visible ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="visible" class="ml-2 block text-xs text-gray-600 uppercase font-medium">
                                    Visible
                                </label>
                            </div>

                            <!-- Observaciones -->
                            <div class="md:col-span-2">
                                <label class="text-xs text-gray-600 uppercase font-medium">Observaciones</label>
                                <textarea name="observaciones" id="observaciones" rows="2"
                                    class="w-full border rounded px-3 py-2 text-sm">{{ $venta->observaciones }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Productos -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Productos del Detalle</h2>
                                <span id="productos-count" class="text-xs text-gray-500">{{ $venta->detalleVentas->count() }} productos</span>
                            </div>
                            <button type="button" id="btn-agregar-producto"
                                class="px-3 py-1.5 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 uppercase tracking-wider font-medium">
                                + Agregar Producto
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
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Subcategoría</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Marca</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="productos-table-body" class="bg-white divide-y divide-gray-200">
                                    @foreach ($venta->detalleVentas as $index => $detalle)
                                    <tr class="detalle-producto-row">
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Producto">
                                            <input type="hidden" name="productos[{{ $index }}][detalle_id]" value="{{ $detalle->id }}">
                                            <select name="productos[{{ $index }}][id]" class="product-select w-full border rounded px-2 py-1.5 text-sm" required>
                                                <option value="">Selecciona un producto</option>
                                                @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}"
                                                        data-precio="{{ $producto->precio }}"
                                                        data-subcategoria="{{ $producto->sub_categoria }}"
                                                        data-marca="{{ $producto->marca->nombre ?? '' }}"
                                                        data-stock="{{ $producto->stock }}"
                                                        {{ $detalle->producto_id == $producto->id ? 'selected' : '' }}>
                                                    {{ $producto->nombre }} | {{ $producto->sub_categoria }} | ${{ number_format($producto->precio, 2) }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Cantidad">
                                            <input type="number" name="productos[{{ $index }}][cantidad]" min="1"
                                                   value="{{ $detalle->cantidad }}"
                                                   class="cantidad-input w-20 border rounded px-2 py-1.5 text-sm" required>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Precio Unit.">
                                            <input type="number" step="0.01" name="productos[{{ $index }}][precio_unitario]"
                                                   value="{{ $detalle->precio_unitario }}"
                                                   class="precio-input w-24 border rounded px-2 py-1.5 text-sm" required>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Dto %">
                                            <input type="number" step="0.01" name="productos[{{ $index }}][descuento]"
                                                   value="{{ $detalle->descuento }}"
                                                   class="descuento-input w-16 border rounded px-2 py-1.5 text-sm" min="0" max="100">
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap subtotal-cell font-medium text-sm" data-label="Subtotal">
                                            ${{ number_format($detalle->cantidad * $detalle->precio_unitario - $detalle->descuento, 2) }}
                                            <input type="hidden" name="productos[{{ $index }}][subtotal]"
                                                   class="subtotal-input"
                                                   value="{{ $detalle->cantidad * $detalle->precio_unitario - $detalle->descuento }}">
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Subcategoría">
                                            <input type="text" name="productos[{{ $index }}][subcategoria]"
                                                   class="subcategoria-field w-24 border rounded px-2 py-1.5 bg-gray-50 text-sm"
                                                   value="{{ $detalle->producto->sub_categoria ?? '' }}"
                                                   disabled>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Marca">
                                            <input type="text" name="productos[{{ $index }}][marca]"
                                                   class="marca-field w-24 border rounded px-2 py-1.5 bg-gray-50 text-sm"
                                                   value="{{ $detalle->producto->marca->nombre ?? '' }}"
                                                   disabled>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Stock">
                                            <input type="text" name="productos[{{ $index }}][stock]"
                                                   class="stock-field w-16 border rounded px-2 py-1.5 bg-gray-50 text-sm"
                                                   value="{{ $detalle->producto->stock ?? 0 }}"
                                                   disabled>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm" data-label="Acciones">
                                            <button type="button" class="btn-eliminar text-red-600 hover:text-red-900 text-xs uppercase font-medium">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Servicios -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Servicios del Detalle</h2>
                                <span id="servicios-count" class="text-xs text-gray-500">{{ $venta->servicios->count() }} servicios</span>
                            </div>
                            <button type="button" id="btn-agregar-servicio"
                                class="px-3 py-1.5 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 uppercase tracking-wider font-medium">
                                + Agregar Servicio
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
                                <tbody id="servicios-table-body" class="bg-white divide-y divide-gray-200">
                                    @foreach ($venta->servicios as $index => $servicio)
                                    <tr class="detalle-servicio-row">
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Servicio">
                                            <input type="hidden" name="servicios[{{ $index }}][detalle_id]" value="{{ $servicio->pivot->id ?? '' }}">
                                            <select name="servicios[{{ $index }}][servicio_id]" class="service-select w-full border rounded px-2 py-1.5 text-sm" required>
                                                <option value="">Selecciona un servicio</option>
                                                @foreach ($servicios as $s)
                                                <option value="{{ $s->id }}"
                                                        data-precio="{{ $s->precio }}"
                                                        {{ $servicio->id == $s->id ? 'selected' : '' }}>
                                                    {{ $s->nombre }} | ${{ number_format($s->precio, 2) }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Cantidad">
                                            <input type="number" name="servicios[{{ $index }}][cantidad]" min="1"
                                                   value="{{ $servicio->pivot->cantidad ?? 1 }}"
                                                   class="cantidad-input w-20 border rounded px-2 py-1.5 text-sm" required>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap" data-label="Precio Unit.">
                                            <input type="number" step="0.01" name="servicios[{{ $index }}][precio]"
                                                   value="{{ $servicio->precio }}"
                                                   class="precio-input w-24 border rounded px-2 py-1.5 text-sm" required>
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap subtotal-cell font-medium text-sm" data-label="Subtotal">
                                            ${{ number_format(($servicio->pivot->cantidad ?? 1) * $servicio->precio, 2) }}
                                            <input type="hidden" name="servicios[{{ $index }}][subtotal]"
                                                   class="subtotal-input"
                                                   value="{{ ($servicio->pivot->cantidad ?? 1) * $servicio->precio }}">
                                        </td>
                                        <td class="px-3 py-2 whitespace-nowrap text-sm" data-label="Acciones">
                                            <button type="button" class="btn-eliminar text-red-600 hover:text-red-900 text-xs uppercase font-medium">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Totales -->
                    <div class="bg-white border rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-200">
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Resumen de Totales</h2>
                        </div>
                        <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4">
                            @php
                                $totalNeto = 0;
                                foreach($venta->detalleVentas as $detalle) {
                                    $totalNeto += ($detalle->cantidad * $detalle->precio_unitario) - $detalle->descuento;
                                }
                                foreach($venta->servicios as $servicio) {
                                    $totalNeto += ($servicio->pivot->cantidad ?? 1) * $servicio->precio;
                                }
                                $totalIva = $totalNeto * 0.21;
                                $totalFinal = $totalNeto + $totalIva;
                            @endphp
                            <div class="bg-gray-50 p-3 rounded border">
                                <span class="block text-xs text-gray-600 uppercase font-medium">Importe Neto</span>
                                <p id="importe-neto" class="text-lg font-bold text-gray-900">${{ number_format($totalNeto, 2) }}</p>
                                <input type="hidden" name="importe_neto" id="importe-neto-input" value="{{ $totalNeto }}">
                            </div>
                            <div class="bg-gray-50 p-3 rounded border">
                                <span class="block text-xs text-gray-600 uppercase font-medium">IVA 21%</span>
                                <p id="importe-iva" class="text-lg font-bold text-gray-900">${{ number_format($totalIva, 2) }}</p>
                                <input type="hidden" name="importe_iva" id="importe-iva-input" value="{{ $totalIva }}">
                            </div>
                            <div class="bg-blue-50 p-3 rounded border border-blue-200">
                                <span class="block text-xs text-blue-700 uppercase font-medium">Importe Total</span>
                                <p id="importe-total" class="text-lg font-bold text-blue-800">${{ number_format($totalFinal, 2) }}</p>
                                <input type="hidden" name="importe_total" id="importe-total-input" value="{{ $totalFinal }}">
                            </div>
                        </div>
                    </div>

                    <!-- Acciones finales -->
                    <div class="flex justify-between pt-2 border-t">
                        <a href="{{ route('ventas.index') }}"
                           class="px-4 py-2 border border-gray-300 text-sm rounded hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" id="submit-btn"
                                class="px-5 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">
                            Actualizar Venta
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
                productoIndex: {{ $venta->detalleVentas->count() }},
                servicioIndex: {{ $venta->servicios->count() }},
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
                $('.product-select, .service-select, .select2').select2({
                    width: '100%'
                });

                calculateTotals();
                bindEvents();

                // Validar stock en filas existentes
                $('.detalle-producto-row').each(function() {
                    validateStock($(this));
                });
            });

            // Bind de eventos
            function bindEvents() {
                // Botones principales
                $('#btn-agregar-producto').on('click', () => addRow('producto'));
                $('#btn-agregar-servicio').on('click', () => addRow('servicio'));

                // Modal
                $('#closeDuplicateModal, #duplicateModalOk').on('click', () => $duplicateModal.addClass('hidden'));

                // Formulario
                $('#venta-form').on('submit', handleSubmit);

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
                                    {{ $producto->nombre }} | {{ $producto->sub_categoria }} | ${{ number_format($producto->precio, 2) }}
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
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Subcategoría">
                            <input type="text" name="productos[${index}][subcategoria]"
                                   class="subcategoria-field w-24 border rounded px-2 py-1.5 bg-gray-50 text-sm" disabled>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Marca">
                            <input type="text" name="productos[${index}][marca]"
                                   class="marca-field w-24 border rounded px-2 py-1.5 bg-gray-50 text-sm" disabled>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap" data-label="Stock">
                            <input type="text" name="productos[${index}][stock]"
                                   class="stock-field w-16 border rounded px-2 py-1.5 bg-gray-50 text-sm" disabled>
                        </td>
                        <td class="px-3 py-2 whitespace-nowrap text-sm" data-label="Acciones">
                            <button type="button" class="btn-eliminar text-red-600 hover:text-red-900 text-xs uppercase font-medium">
                                Eliminar
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
                            Eliminar
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
                $row.find('.subcategoria-field').val($option.data('subcategoria'));
                $row.find('.marca-field').val($option.data('marca'));

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
                let totalNeto = 0;

                // Productos
                $('.detalle-producto-row').each(function() {
                    const $row = $(this);
                    const subtotal = calculateProductSubtotal($row);
                    $row.find('.subtotal-cell').text(formatMoney(subtotal));
                    $row.find('.subtotal-input').val(subtotal.toFixed(2));
                    totalNeto += subtotal;
                });

                // Servicios
                $('.detalle-servicio-row').each(function() {
                    const $row = $(this);
                    const subtotal = calculateServiceSubtotal($row);
                    $row.find('.subtotal-cell').text(formatMoney(subtotal));
                    $row.find('.subtotal-input').val(subtotal.toFixed(2));
                    totalNeto += subtotal;
                });

                const totalIva = totalNeto * IVA_RATE;
                const totalFinal = totalNeto + totalIva;

                $('#importe-neto').text(formatMoney(totalNeto));
                $('#importe-neto-input').val(totalNeto.toFixed(2));
                $('#importe-iva').text(formatMoney(totalIva));
                $('#importe-iva-input').val(totalIva.toFixed(2));
                $('#importe-total').text(formatMoney(totalFinal));
                $('#importe-total-input').val(totalFinal.toFixed(2));

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

            // Validación del formulario
            function validateForm() {
                const errors = [];

                if (!$('input[name="cliente_id"]').val()) {
                    errors.push('El cliente es obligatorio.');
                }

                // Validar fechas
                const fechaVenta = $('#fecha_venta').val();
                if (!fechaVenta) {
                    errors.push('La fecha de venta es obligatoria.');
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
                $btn.prop('disabled', true).text('Actualizando...').removeClass('bg-gray-600 hover:bg-gray-700').addClass('bg-gray-400');
                return true;
            }
        })();
    </script>
</body>

</html>
