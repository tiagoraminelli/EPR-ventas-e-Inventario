<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Productos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Estilos personalizados-->
    <link href="{{ asset('css/productos_index.css') }}" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Estilos personalizados para Select2 -->

    <link href="{{ asset('css/select2.css') }}" rel="stylesheet">
    <!-- siempre recordar que la ruta asset es dentro de la carpeta public -->
</head>

<body class="bg-indigo-100 font-sans">
    <!-- Modal de confirmación -->
    <div id="confirm-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">¿Estás seguro?</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500" id="confirm-message">¿Seguro que deseas eliminar este producto?</p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="confirm-btn"
                        class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Eliminar
                    </button>
                    <button id="cancel-btn"
                        class="mt-3 px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Layout principal -->
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-admin-nav />

        <!-- Contenido principal -->
        <main class="flex-1 p-6">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-4 md:mb-0">Panel de Control de Productos</h1>
                    <a href="{{ route('products.create') }}"
                        class="px-4 py-2 bg-green-500 text-white font-semibold rounded-md shadow-sm hover:bg-green-600 transition duration-300 ease-in-out">
                        <i class="fas fa-plus mr-2"></i> Nuevo Producto
                    </a>
                </div>

                <!-- Formulario de Búsqueda -->
                <div class="mb-6 border-b pb-4">
                    <form action="{{ route('products.index') }}" method="GET"
                        class="flex flex-wrap md:flex-nowrap items-center space-y-4 md:space-y-0 md:space-x-4">
                        <!-- Buscador -->
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscador sensible a mayúsculas y minúsculas, uso de puntos y comas"
                            id="search"
                            class="w-full md:w-auto flex-grow rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">

                        <!-- Filtro de Categoría -->
                        <select name="categoria" id="categoria-select"
                            class="w-full md:w-auto form-select border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            style="max-width: 200px">
                            <option value="">Todas las Categorías</option>
                            @foreach ($categorias as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Filtro de Marca -->
                        <select name="marca" id="marca-select"
                            class="w-full md:w-auto form-select border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                            <option value="">Todas las Marcas</option>
                            @foreach ($marcas as $m)
                                <option value="{{ $m->id }}" {{ request('marca') == $m->id ? 'selected' : '' }}>
                                    {{ $m->nombre }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Filtro de cantidad de stock -->
                        <div class="flex gap-2 items-center">
                            <input type="number" name="stock" value="{{ request('stock') }}" placeholder="Stock"
                                class="w-full md:w-auto rounded-full border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                                style="max-width: 100px;">

                            <select name="stock_type"
                                class="rounded-full w-full md:w-auto form-select border border-gray-300 shadow-sm p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300">
                                <option value="=" {{ request('stock_type') == '=' ? 'selected' : '' }}>Igual a
                                </option>
                                <option value=">=" {{ request('stock_type') == '>=' ? 'selected' : '' }}>Mayor o
                                    igual</option>
                                <option value="<=" {{ request('stock_type') == '<=' ? 'selected' : '' }}>Menor o
                                    igual</option>
                                <option value=">" {{ request('stock_type') == '>' ? 'selected' : '' }}>Mayor que
                                </option>
                                <option value="<" {{ request('stock_type') == '<' ? 'selected' : '' }}>Menor que
                                </option>
                            </select>
                        </div>

                        <!-- Botón Buscar -->
                        <button type="submit"
                            class="w-full md:w-auto px-6 py-3 bg-blue-500 text-white font-semibold rounded-full shadow-lg hover:bg-blue-600 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i> Buscar
                        </button>
                    </form>
                </div>



                <!-- Tabla de Productos -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Producto</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Stock</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Marca</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Categoria</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sub Categoría</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio Proveedor</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio Venta</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ganancia</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Beneficio Neto</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($products as $product)
                                @php
                                    $ganancia =
                                        $product->precio_proveedor > 0
                                            ? (($product->precio - $product->precio_proveedor) /
                                                    $product->precio_proveedor) *
                                                100
                                            : 0;

                                    $beneficioNeto = $product->precio - $product->precio_proveedor;

                                    $gananciaColor = $ganancia >= 0 ? 'text-green-600' : 'text-red-600';
                                    $beneficioColor = $beneficioNeto >= 0 ? 'text-green-600' : 'text-red-600';

                                    // Lógica para cambiar color según la sub_categoria
                                    $productNameColorClass = 'text-gray-900'; // por defecto
                                    if (!empty($product->sub_categoria)) {
                                        $colorName = strtolower(trim($product->sub_categoria));
                                        switch ($colorName) {
                                            case 'black':
                                                $productNameColorClass = 'text-black-force';
                                                break;
                                            case 'cyan':
                                                $productNameColorClass = 'text-cyan-600';
                                                break;
                                            case 'magenta':
                                                $productNameColorClass = 'text-fuchsia-600';
                                                break;
                                            case 'yellow':
                                                $productNameColorClass = 'text-yellow-600';
                                                break;
                                            case 'light cyan':
                                                $productNameColorClass = 'text-sky-400';
                                                break;
                                            case 'light magenta':
                                                $productNameColorClass = 'text-pink-400';
                                                break;
                                            case 'gray':
                                                $productNameColorClass = 'text-gray-600';
                                                break;
                                            case 'universal dye':
                                                $productNameColorClass = 'text-indigo-600';
                                                break;
                                            case 'color':
                                                $productNameColorClass = 'text-gradient-multicolor';
                                                break;
                                            default:
                                                $productNameColorClass = 'text-gray-900';
                                                break;
                                        }
                                    }
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <!-- Producto -->
                                    <td class="px-6 py-4 text-sm {{ $productNameColorClass }}">
                                        {{ $product->nombre ?? 'N/A' }}
                                    </td>
                                    <!-- Stock -->
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product->stock }}</td>
                                    <!-- Marca -->
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $product->marca->nombre ?? 'N/A' }}
                                    </td>
                                    <!-- Categoria -->
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ $product->categoria->nombre ?? 'N/A' }}</td>

                                    <!-- Sub Categoría -->
                                    <td class="px-6 py-4 text-sm {{ $productNameColorClass }} text-right">
                                        {{ $product->sub_categoria ?? 'N/A' }}
                                    </td>

                                    <!-- Precio Proveedor -->
                                    <td class="text-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            ${{ number_format($product->precio_proveedor, 2) }}
                                        </span>
                                    </td>

                                    <!-- Precio Venta -->
                                    <td class="text-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-custom-100 text-white">
                                            ${{ number_format($product->precio, 2) }}
                                        </span>
                                    </td>

                                    <!-- Ganancia -->
                                    <td class="text-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $ganancia >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            %{{ number_format($ganancia, 2) }}
                                        </span>
                                    </td>

                                    <!-- Beneficio Neto -->
                                    <td class="text-center">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $beneficioNeto >= 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                            ${{ number_format($beneficioNeto, 2) }}
                                        </span>
                                    </td>

                                    <!-- Acciones -->
                                    <td class="px-6 py-4 text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Ver Imagen -->
                                            <a href="{{ $product->url_imagen }}" target="_blank"
                                                class="text-blue-600 hover:text-blue-900" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}"
                                                method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </main>
    </div>

    <!-- jQuery y Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Script para inicializar Select2 -->
    <script src="{{ asset('js/select2.js') }}"></script> <!-- siempre recordar que la ruta asset es dentro de la carpeta public -->

</body>

</html>
