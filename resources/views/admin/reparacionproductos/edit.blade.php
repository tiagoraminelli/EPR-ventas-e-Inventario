<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Productos de Reparación</title>
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
                <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">
                    Editar Productos de Reparación #{{ $reparacion->id }}
                </h1>
                <a href="{{ route('reparacionproductos.index') }}"
                   class="px-6 py-2 bg-gray-600 text-white font-bold rounded-md shadow-lg hover:bg-gray-700 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Volver
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('reparacionproductos.update', $reparacion) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Reparación (solo info) -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700">Reparación</label>
                    <input type="text"
                           value="#{{ $reparacion->id }} - {{ optional($reparacion->cliente)->NombreCompleto ?? 'Sin cliente' }} | {{ $reparacion->equipo_descripcion }} | {{ $reparacion->codigo_unico }}"
                           disabled
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm bg-gray-100">
                </div>

                <hr class="my-6 border-t border-gray-300">

                <!-- Productos dinámicos -->
                <div class="bg-gray-50 p-4 rounded-lg shadow-inner mb-6">
                    <h2 class="text-xl font-bold text-gray-700 mb-4">Productos de la Reparación</h2>
                    <div id="productos-container">
                        @foreach ($reparacion->reparacionProductos as $index => $repProd)
                            <div class="product-row border border-gray-200 p-4 rounded-lg mb-4 bg-white flex flex-col md:flex-row gap-4 items-center"
                                 data-index="{{ $index }}">
                                <input type="hidden" name="productos[{{ $index }}][id]" value="{{ $repProd->id }}">

                                <div class="w-full md:w-2/5">
                                    <label class="block text-sm font-medium text-gray-700">Producto</label>
                                    <select name="productos[{{ $index }}][producto_id]"
                                            class="product-select mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                                            required>
                                        <option value="">Seleccione un producto...</option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}" {{ $repProd->producto_id == $producto->id ? 'selected' : '' }}>
                                                {{ $producto->nombre }} | {{ $producto->sub_categoria }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-full md:w-1/5">
                                    <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                                    <input type="number" name="productos[{{ $index }}][cantidad]"
                                           value="{{ $repProd->cantidad }}"
                                           class="cantidad-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                                           required min="1">
                                </div>

                                <div class="w-full md:w-1/5">
                                    <label class="block text-sm font-medium text-gray-700">Precio</label>
                                    <input type="number" step="0.01" name="productos[{{ $index }}][precio]"
                                           value="{{ $repProd->precio }}"
                                           class="precio-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm"
                                           min="0">
                                </div>

                                <div class="w-full md:w-1/5 flex justify-end md:justify-center mt-4 md:mt-0">
                                    <button type="button"
                                            class="remove-product-btn px-4 py-2 bg-red-500 text-white font-bold rounded-md shadow-lg hover:bg-red-600">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="button" id="add-product-btn"
                                class="px-4 py-2 bg-green-500 text-white font-bold rounded-md shadow-lg hover:bg-green-600">
                            <i class="fas fa-plus mr-2"></i> Agregar Producto
                        </button>
                    </div>
                </div>

                <!-- Total -->
                <div class="bg-gray-100 p-4 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center text-lg font-bold">
                        <span>Total:</span>
                        <span id="total-precio" class="text-indigo-600">$0.00</span>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full px-6 py-3 bg-indigo-600 text-white font-bold rounded-md shadow-lg hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i> Guardar Productos
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    let productIndex = {{ $reparacion->reparacionProductos->count() }};

    $('#productos-container .product-select').select2({ placeholder: "Seleccione un producto" });

    function addProductRow() {
        const row = `
        <div class="product-row border border-gray-200 p-4 rounded-lg mb-4 bg-white flex flex-col md:flex-row gap-4 items-center" data-index="${productIndex}">
            <div class="w-full md:w-2/5">
                <label class="block text-sm font-medium text-gray-700">Producto</label>
                <select name="productos[${productIndex}][producto_id]" class="product-select mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required>
                    <option value="">Seleccione un producto...</option>
                    @foreach ($productos as $producto)
                        <option value="{{ $producto->id }}">{{ $producto->nombre }} | {{ $producto->sub_categoria }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-1/5">
                <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" name="productos[${productIndex}][cantidad]" class="cantidad-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" required min="1">
            </div>
            <div class="w-full md:w-1/5">
                <label class="block text-sm font-medium text-gray-700">Precio</label>
                <input type="number" step="0.01" name="productos[${productIndex}][precio]" class="precio-input mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm sm:text-sm" min="0">
            </div>
            <div class="w-full md:w-1/5 flex justify-end md:justify-center mt-4 md:mt-0">
                <button type="button" class="remove-product-btn px-4 py-2 bg-red-500 text-white font-bold rounded-md shadow-lg hover:bg-red-600">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
        </div>
        `;
        $('#productos-container').append(row);
        $(`.product-select[name="productos[${productIndex}][producto_id]"]`).select2({ placeholder: "Seleccione un producto" });
        productIndex++;
    }

    $('#add-product-btn').on('click', addProductRow);

    $('#productos-container').on('click', '.remove-product-btn', function() {
        $(this).closest('.product-row').remove();
        updateTotal();
    });

    $('#productos-container').on('change keyup', '.cantidad-input, .precio-input', updateTotal);

    function updateTotal() {
        let total = 0;
        $('.product-row').each(function() {
            const qty = parseFloat($(this).find('.cantidad-input').val()) || 0;
            const price = parseFloat($(this).find('.precio-input').val()) || 0;
            total += qty * price;
        });
        $('#total-precio').text(`$${total.toFixed(2)}`);
    }

    updateTotal();
});
</script>
</body>
</html>
