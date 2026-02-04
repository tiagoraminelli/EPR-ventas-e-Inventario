<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen">

<div class="flex">
    <x-admin-nav />

    <main class="flex-1 p-6">
        <div class="max-w-5xl mx-auto bg-white rounded-lg shadow p-6">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6 border-b pb-3">
                <h1 class="text-xl font-semibold text-gray-800">
                    Editar producto · <span class="text-blue-600">{{ $product->nombre }}</span>
                </h1>

                <a href="{{ route('products.index') }}"
                   class="text-sm text-black-600 hover:underline">
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

            <!-- Form -->
            <form action="{{ route('products.update', $product->id) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5">

                @csrf
                @method('PUT')

                <!-- Nombre / Subcategoría -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Nombre</label>
                        <input type="text" name="nombre"
                               value="{{ old('nombre', $product->nombre) }}"
                               class="w-full border rounded px-3 py-2 text-sm"
                               required>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Subcategoría</label>
                        <input type="text" name="sub_categoria"
                               value="{{ old('sub_categoria', $product->sub_categoria) }}"
                               class="w-full border rounded px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="text-sm text-gray-600">Descripción</label>
                    <textarea name="descripcion" rows="3"
                              class="w-full border rounded px-3 py-2 text-sm">{{ old('descripcion', $product->descripcion) }}</textarea>
                </div>

                <!-- Precios / Stock -->
                <div class="grid md:grid-cols-3 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Precio proveedor</label>
                        <input type="number" step="0.01" name="precio_proveedor"
                               value="{{ old('precio_proveedor', $product->precio_proveedor) }}"
                               class="w-full border rounded px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Precio venta</label>
                        <input type="number" step="0.01" name="precio"
                               value="{{ old('precio', $product->precio) }}"
                               class="w-full border rounded px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Stock</label>
                        <input type="number" name="stock"
                               value="{{ old('stock', $product->stock) }}"
                               class="w-full border rounded px-3 py-2 text-sm">
                    </div>
                </div>

                <!-- Categoría / Marca -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm text-gray-600">Categoría</label>
                        <select name="categoria_id" class="select2 w-full">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('categoria_id', $product->categoria_id) == $category->id)>
                                    {{ $category->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-sm text-gray-600">Marca</label>
                        <select name="marca_id" class="select2 w-full">
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id }}"
                                    @selected(old('marca_id', $product->marca_id) == $marca->id)>
                                    {{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Imagen -->
                <div>
                    <label class="text-sm text-gray-600">Imagen</label>
                    <input type="file" name="imagen" class="text-sm">
                </div>

                <!-- Visible -->
                <div class="flex items-center gap-2">
                    <input type="hidden" name="visible" value="0">
                    <input type="checkbox" name="visible" value="1"
                           @checked(old('visible', $product->visible))>
                    <span class="text-sm text-gray-700">Visible en tienda</span>
                </div>

                <!-- Acciones -->
                <div class="flex justify-end pt-4 border-t">
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                        Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $('.select2').select2({
        width: '100%'
    });
</script>

</body>
</html>
