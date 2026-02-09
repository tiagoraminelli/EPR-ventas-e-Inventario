<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Productos</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">

</head>

<body class="bg-gray-100 font-sans">

    <!-- ================= MODAL ELIMINAR ================= -->
    <x-confirm-modal id="delete-product-modal" title="¿Estás seguro?"
        message="¿Seguro que deseas eliminar este producto?" confirm-text="Eliminar" cancel-text="Cancelar" />


    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6">
            <div class="bg-white p-6 shadow">

                <!-- HEADER -->
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <h1 class="text-2xl font-semibold">Panel de Control de Productos</h1>

                    <div class="flex gap-3 items-center">
                        <a href="{{ route('products.create') }}"
                            class="px-4 py-2 border border-gray-300 hover:bg-gray-100">
                            <i class="fas fa-plus mr-2"></i> Nuevo Producto
                        </a>

                        <div class="flex border border-gray-300">
                            <button id="btn-table" class="px-4 py-2 bg-gray-900 text-white text-sm">
                                <i class="fas fa-list mr-1"></i> Tabla
                            </button>
                            <button id="btn-grid" class="px-4 py-2 text-sm hover:bg-gray-100">
                                <i class="fas fa-th mr-1"></i> Imágenes
                            </button>
                        </div>
                    </div>
                </div>

                <form method="GET" class="mb-6 flex flex-wrap gap-3 items-center">

                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar producto"
                        class="flex-grow border border-gray-300 px-3 py-2">

                    <!-- Productos eliminados -->
                    <label class="flex items-center gap-1 text-gray-600">
                        <input type="checkbox" name="with_trashed" {{ request('with_trashed') ? 'checked' : '' }}>
                        Incluir eliminados
                    </label>

                    <!-- CATEGORÍA -->
                    <select name="categoria" class="select2 w-56 border border-gray-300 px-3 py-2">
                        <option value="">Todas las categorías</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <!-- MARCA -->
                    <select name="marca" class="select2 w-56 border border-gray-300 px-3 py-2">
                        <option value="">Todas las marcas</option>
                        @foreach ($marcas as $m)
                            <option value="{{ $m->id }}" {{ request('marca') == $m->id ? 'selected' : '' }}>
                                {{ $m->nombre }}
                            </option>
                        @endforeach
                    </select>

                    <input type="number" name="stock" value="{{ request('stock') }}" placeholder="Stock"
                        class="w-24 border border-gray-300 px-3 py-2">

                    <select name="stock_type" class="border border-gray-300 px-3 py-2">
                        <option value="=" {{ request('stock_type') == '=' ? 'selected' : '' }}>=</option>
                        <option value=">=" {{ request('stock_type') == '>=' ? 'selected' : '' }}>&ge;</option>
                        <option value="<=" {{ request('stock_type') == '<=' ? 'selected' : '' }}>&le;</option>
                        <option value=">" {{ request('stock_type') == '>' ? 'selected' : '' }}>&gt;</option>
                        <option value="<" {{ request('stock_type') == '<' ? 'selected' : '' }}>&lt;</option>
                    </select>

                    <input type="hidden" name="view" value="{{ request('view', 'table') }}">

                    <button type="submit" class="px-4 py-2 border border-gray-400 hover:bg-gray-100">
                        <i class="fas fa-search mr-1"></i> Buscar
                    </button>
                </form>


                <!-- TABLA -->
                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Producto</th>
                                <th class="px-4 py-2 text-left">Stock</th>
                                <th class="px-4 py-2 text-left">Marca</th>
                                <th class="px-4 py-2 text-left">Categoría</th>
                                <th class="px-4 py-2 text-right">Subcategoría</th>
                                {{-- <th class="px-4 py-2 text-right">Precio Prov.</th> --}}
                                <th class="px-4 py-2 text-right">Precio Venta</th>
                                <th class="px-4 py-2 text-right">Ganancia %</th>
                                <th class="px-4 py-2 text-right">Beneficio $</th>
                                <th class="px-4 py-2 text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @foreach ($products as $product)
                                @php
                                    $ganancia =
                                        $product->precio_proveedor > 0
                                            ? (($product->precio - $product->precio_proveedor) /
                                                    $product->precio_proveedor) *
                                                100
                                            : 0;

                                    $beneficioNeto = $product->precio - $product->precio_proveedor;
                                @endphp

                                <tr class="hover:bg-gray-50">
                                    <!-- Producto -->
                                    <td class="px-4 py-2">
                                        {{ $product->nombre }}
                                    </td>

                                    <!-- Stock -->
                                    <td class="px-4 py-2">
                                        {{ $product->stock }}
                                    </td>

                                    <!-- Marca -->
                                    <td class="px-4 py-2">
                                        {{ $product->marca->nombre ?? 'N/A' }}
                                    </td>

                                    <!-- Categoría -->
                                    <td class="px-4 py-2">
                                        {{ $product->categoria->nombre ?? 'N/A' }}
                                    </td>

                                    <!-- Subcategoría -->
                                    <td class="px-4 py-2 text-right">
                                        {{ $product->sub_categoria }}
                                    </td>

                                    <!-- Precio Proveedor -->
                                    {{-- <td class="px-4 py-2 text-right">
                                        ${{ number_format($product->precio_proveedor, 2) }}
                                    </td> --}}

                                    <!-- Precio Venta -->
                                    <td class="px-4 py-2 text-right font-medium">
                                        ${{ number_format($product->precio, 2) }}
                                    </td>

                                    <!-- Ganancia % -->
                                    <td
                                        class="px-4 py-2 text-right {{ $ganancia >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        %{{ number_format($ganancia, 2) }}
                                    </td>

                                    <!-- Beneficio $ -->
                                    <td
                                        class="px-4 py-2 text-right {{ $beneficioNeto >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        ${{ number_format($beneficioNeto, 2) }}
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center gap-3 text-gray-600">
                                            <a href="{{ route('products.edit', $product->id) }}" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <form id="delete-form-{{ $product->id }}"
                                                action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <button type="button"
                                                onclick="openConfirmModal('delete-product-modal', () => {
        document.getElementById('delete-form-{{ $product->id }}').submit();
    })"
                                                class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

                <!-- GRID -->
                <div id="grid-view" class="hidden">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($products as $product)
                            <div class="border shadow-sm hover:shadow transition">
                                <div class="h-40 bg-gray-100 overflow-hidden">
                                    @if ($product->url_imagen)
                                        <img src="{{ asset('storage/' . $product->url_imagen) }}"
                                            class="object-cover h-full w-full">
                                    @endif
                                </div>
                                <div class="p-4 text-sm space-y-2">
                                    <h3 class="font-semibold truncate">{{ $product->nombre }}</h3>
                                    <p class="text-gray-500">{{ $product->marca->nombre ?? 'N/A' }}</p>

                                    @php
                                        $ganancia =
                                            $product->precio_proveedor > 0
                                                ? (($product->precio - $product->precio_proveedor) /
                                                        $product->precio_proveedor) *
                                                    100
                                                : 0;

                                        $beneficioNeto = $product->precio - $product->precio_proveedor;
                                    @endphp

                                    <!-- DATOS EN 2 GRILLAS -->
                                    <div class="grid grid-cols-2 gap-x-3 gap-y-1 text-xs mt-2">
                                        <div>
                                            <span class="text-gray-400">Categoría</span>
                                            <p class="font-medium">{{ $product->categoria->nombre ?? 'N/A' }}</p>
                                        </div>

                                        <div>
                                            <span class="text-gray-400">Subcategoría</span>
                                            <p class="font-medium">{{ $product->sub_categoria }}</p>
                                        </div>

                                        <div>
                                            <span class="text-gray-400">Precio</span>
                                            <p class="font-semibold">${{ number_format($product->precio, 2) }}</p>
                                        </div>

                                        <div>
                                            <span class="text-gray-400">Ganancia</span>
                                            <p class="{{ $ganancia >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                %{{ number_format($ganancia, 2) }}
                                            </p>
                                        </div>

                                        <div class="col-span-2">
                                            <span class="text-gray-400">Beneficio</span>
                                            <p
                                                class="{{ $beneficioNeto >= 0 ? 'text-green-600' : 'text-red-600' }} font-medium">
                                                ${{ number_format($beneficioNeto, 2) }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- ACCIONES -->
                                    <div class="flex gap-4 mt-3 text-gray-600">
                                        <a href="{{ route('products.edit', $product->id) }}" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button"
                                            onclick="openConfirmModal('delete-product-modal', () => {
        document.getElementById('delete-form-{{ $product->id }}').submit();
    })"
                                            class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>

            </div>
        </main>
    </div>

    <script>
        const btnTable = document.getElementById('btn-table');
        const btnGrid = document.getElementById('btn-grid');
        const tableView = document.getElementById('table-view');
        const gridView = document.getElementById('grid-view');

        const urlParams = new URLSearchParams(window.location.search);
        const currentView = urlParams.get('view') || 'table';

        function setView(view) {
            if (view === 'grid') {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');

                btnGrid.classList.add('bg-gray-900', 'text-white');
                btnTable.classList.remove('bg-gray-900', 'text-white');
            } else {
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');

                btnTable.classList.add('bg-gray-900', 'text-white');
                btnGrid.classList.remove('bg-gray-900', 'text-white');
            }

            urlParams.set('view', view);
            const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
            window.history.replaceState({}, '', newUrl);
        }

        // Estado inicial (al cargar página o cambiar de página)
        setView(currentView);

        btnTable.addEventListener('click', () => setView('table'));
        btnGrid.addEventListener('click', () => setView('grid'));
    </script>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Seleccionar',
                allowClear: true,
                width: 'resolve'
            });
        });
    </script>

</body>

</html>
