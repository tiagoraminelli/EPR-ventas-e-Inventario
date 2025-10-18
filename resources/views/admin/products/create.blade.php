<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .modal {
            transition: opacity 0.25s ease-in-out;
        }

        .select2-container .select2-selection--single {
            height: 42px; /* Aumenta la altura para que coincida con los inputs */
            display: flex;
            align-items: center;
            border-radius: 0.375rem; /* Esquinas redondeadas */
            border-color: #d1d5db; /* Color de borde */
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* Sombra sutil */
        }
    </style>
</head>

<body class="bg-gray-100 p-4 font-sans">

    <!-- Layout principal -->
    <div class="flex min-h-screen">
        <x-admin-nav />

        <div class="flex-1 container mx-auto bg-white p-8 rounded-xl shadow-lg">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800">Crear Nuevo Producto</h1>
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 text-blue-600 bg-blue-100 rounded-lg font-semibold hover:bg-blue-200 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al Dashboard
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-bold">¡Error!</p>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="productForm" action="{{ route('products.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="sub_categoria" class="block text-sm font-semibold text-gray-700 mb-1">Sub
                            Categoría</label>
                        <input type="text" name="sub_categoria" id="sub_categoria"
                            value="{{ old('sub_categoria') }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                    </div>
                </div>

                <div>
                    <label for="descripcion" class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">{{ old('descripcion') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label for="precio_proveedor" class="block text-sm font-semibold text-gray-700 mb-1">Precio
                            Proveedor ($)</label>
                        <input type="number" name="precio_proveedor" id="precio_proveedor" step="0.01"
                            value="{{ old('precio_proveedor') }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="precio" class="block text-sm font-semibold text-gray-700 mb-1">Precio de Venta ($)</label>
                        <input type="number" name="precio" id="precio" step="0.01" value="{{ old('precio') }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-1">Stock</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock') }}"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                    </div>
                    <div>
                        <label for="visible"
                            class="block text-sm font-semibold text-gray-700 mb-1">Visible</label>
                        <select name="visible" id="visible"
                            class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                            <option value="1" {{ old('visible') == '1' ? 'selected' : '' }}>Sí</option>
                            <option value="0" {{ old('visible') == '0' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="categoria_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            Categoría
                        </label>
                        <select name="categoria_id" id="categoria_id"
                            class="select2 w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                            <option value="">Selecciona una categoría</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('categoria_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="marca_id" class="block text-sm font-semibold text-gray-700 mb-1">
                            Marca
                        </label>
                        <select name="marca_id" id="marca_id"
                            class="select2 w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            required>
                            <option value="">Selecciona una marca</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}"
                                    {{ old('marca_id') == $marca->id ? 'selected' : '' }}>{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="url_imagen" class="block text-sm font-semibold text-gray-700 mb-1">URL de la
                        Imagen</label>
                    <input type="text" name="url_imagen" id="url_imagen" value="{{ old('url_imagen') }}"
                        class="w-full rounded-md border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i> Guardar Producto
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Selecciona una opción",
                allowClear: true,
                width: '100%'
            });
        });
    </script>
</body>
</html>
