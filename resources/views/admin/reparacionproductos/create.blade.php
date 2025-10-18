<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Productos por Reparación</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-indigo-100 font-sans">
<div class="flex">
    <x-admin-nav />

    <main class="flex-1 p-6">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Crear Productos por Reparación</h1>
                <a href="{{ route('reparacionproductos.index') }}"
                   class="px-6 py-2 bg-gray-600 text-white font-bold rounded-md shadow-lg hover:bg-gray-700 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>

            <!-- Manejo de errores -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reparacionproductos.store') }}" method="POST" id="reparacionProductosForm">
                @csrf

                <!-- Selección de Reparación -->
                <div class="mb-6">
                    <label for="reparacion_id" class="block text-sm font-medium text-gray-700">Reparación</label>
                    <select name="reparacion_id" id="reparacion_id" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm">
                        <option value="">Seleccione una reparación...</option>
                        @foreach($reparaciones as $reparacion)
                            <option value="{{ $reparacion->id }}" {{ old('reparacion_id') == $reparacion->id ? 'selected' : '' }}>
                                #{{ $reparacion->id }} - {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }} | {{ $reparacion->equipo_descripcion }} | {{ $reparacion->codigo_unico }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <hr class="my-6 border-t border-gray-300">

                <!-- Productos dinámicos -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-inner mb-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4">Productos de la Reparación</h2>
                    <div id="productos-container"></div>

                    <div class="flex justify-end mt-4">
                        <button type="button" id="add-product-btn"
                                class="px-4 py-2 bg-green-500 text-white font-bold rounded-md hover:bg-green-600">
                            <i class="fas fa-plus mr-2"></i> Agregar Producto
                        </button>
                    </div>
                </div>

                <!-- Resumen total -->
                <div class="bg-gray-100 p-4 rounded-lg shadow mb-6 flex justify-between font-bold text-lg">
                    <span>Total:</span>
                    <span id="total-precio" class="text-indigo-600">$0.00</span>
                </div>

                <!-- Botón guardar -->
                <div class="mt-6">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-md hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i> Guardar Productos
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    let productIndex = 0;
    $('#reparacion_id').select2({ placeholder: "Seleccione una reparación", allowClear: true });

    function addProductRow() {
        const row = `
        <div class="product-row bg-white p-4 rounded-lg border border-gray-200 mb-4 grid grid-cols-12 gap-4 items-center">
            <!-- Producto -->
            <div class="col-span-3">
                <label class="block text-sm font-medium text-gray-700">Producto</label>
                <select name="productos[${productIndex}][producto_id]" class="product-select mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md sm:text-sm" style="width: 100%" required>
                    <option value="">Seleccione un producto...</option>
                    @foreach($productos as $producto)
                        <option value="{{ $producto->id }}" data-stock="{{ $producto->stock }}" data-subcategoria="{{ $producto->sub_categoria }}" data-precio="{{ $producto->precio }}">
                            {{ $producto->nombre }} | {{ $producto->sub_categoria }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Stock -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Stock</label>
                <input type="text" class="stock-info mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 sm:text-sm" disabled>
            </div>

            <!-- Subcategoría -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Subcategoría</label>
                <input type="text" class="subcat-info mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 sm:text-sm" disabled>
            </div>

            <!-- Cantidad -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" name="productos[${productIndex}][cantidad]" class="cantidad-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md sm:text-sm" required min="1">
            </div>

            <!-- Precio -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Precio</label>
                <input type="number" step="0.01" name="productos[${productIndex}][precio]" class="precio-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md sm:text-sm" min="0">
            </div>

            <!-- Botón eliminar -->
            <div class="col-span-1 flex justify-center">
                <button type="button" class="remove-product-btn px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
        `;
        $('#productos-container').append(row);
        $(`.product-select[name="productos[${productIndex}][producto_id]"]`).select2({ placeholder: "Seleccione un producto" });
        productIndex++;
    }

    function updateTotal() {
        let total = 0;
        $('.product-row').each(function() {
            const cantidad = parseFloat($(this).find('.cantidad-input').val()) || 0;
            const precio = parseFloat($(this).find('.precio-input').val()) || 0;
            total += cantidad * precio;
        });
        $('#total-precio').text(`$${total.toFixed(2)}`);
    }

    // Eventos
    $('#add-product-btn').click(() => { addProductRow(); updateTotal(); });
    $('#productos-container').on('click', '.remove-product-btn', function() { $(this).closest('.product-row').remove(); updateTotal(); });
    $('#productos-container').on('change keyup', '.cantidad-input, .precio-input', updateTotal);

    // Mostrar Stock/Subcat/Precio al seleccionar producto
    $('#productos-container').on('change', '.product-select', function() {
        const selected = $(this).find('option:selected');
        const row = $(this).closest('.product-row');
        row.find('.stock-info').val(selected.data('stock') || '');
        row.find('.subcat-info').val(selected.data('subcategoria') || '');
        row.find('.precio-input').val(selected.data('precio') || '');
        updateTotal();
    });

    // Fila inicial
    addProductRow();
});
</script>

</body>
</html>
