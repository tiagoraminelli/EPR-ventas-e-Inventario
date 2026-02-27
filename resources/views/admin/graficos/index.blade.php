<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Información</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .tr-clickable {
            cursor: pointer;
            transition: background-color 0.15s;
        }
        .tr-clickable:hover {
            background-color: #f3f4f6;
        }
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
            max-width: 780px;
            margin: 0 auto;
        }
        .kpi-card {
            cursor: pointer;
            transition: all 0.15s;
            border-left: 3px solid transparent;
        }
        .kpi-card:hover {
            background-color: #f9fafb;
            border-left-color: #6b7280;
        }
        .table-header {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.7rem;
            letter-spacing: 0.05em;
            color: #6b7280;
        }
        .badge {
            display: inline-block;
            padding: 0.15rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 500;
            background-color: #f3f4f6;
            color: #374151;
        }
        .section-title {
            font-weight: 700;
            font-size: 1.1rem;
            color: #111827;
            letter-spacing: -0.01em;
        }
        .filter-input {
            border: 1px solid #e5e7eb;
            background-color: white;
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
        }
        .filter-input:focus {
            outline: none;
            border-color: #9ca3af;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 antialiased">
    <div class="flex min-h-screen">
        <x-admin-nav />

        <main class="flex-1 p-6 overflow-x-hidden">
            <div class="max-w-7xl mx-auto space-y-6">
                <!-- Header con título y fecha -->
                <div class="flex justify-between items-center mb-2">
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">PANEL DE CONTROL GENERAL</h1>
                    <p class="text-sm text-gray-500">{{ now()->format('d/m/Y') }}</p>
                </div>

                <!-- Resumen total - Tarjetas principales -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div class="bg-white p-5 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Valor Inventario</p>
                        <p class="text-2xl font-semibold mt-1">${{ number_format($totalInventoryValue, 2, ',', '.') }}</p>
                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-500">Ganancia estimada</span>
                            <span class="text-sm font-medium text-gray-800">${{ number_format($totalProfit, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-white p-5 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Ventas del Mes</p>
                        <p class="text-2xl font-semibold mt-1">${{ number_format($totalVentasMesImporte, 2, ',', '.') }}</p>
                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-500">Cantidad</span>
                            <span class="text-sm font-medium text-gray-800">{{ $totalVentasMes }} ventas</span>
                        </div>
                    </div>

                    <div class="bg-white p-5 border border-gray-200">
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Stock Total</p>
                        <p class="text-2xl font-semibold mt-1">{{ $totalStock }} unidades</p>
                        <div class="flex justify-between items-center mt-2 pt-2 border-t border-gray-100">
                            <span class="text-xs text-gray-500">Stock bajo</span>
                            <span class="text-sm font-medium text-amber-600">{{ $lowStockProducts }}</span>
                        </div>
                    </div>
                </div>

                <!-- KPIs Rápidos con filtros -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    <a href="{{ route('admin.graficos', ['stock_filter' => 'bajo']) }}"
                        class="kpi-card bg-white p-3 border border-gray-200 block">
                        <p class="text-xs text-gray-500">Stock Bajo</p>
                        <p class="text-xl font-semibold">{{ $lowStockProducts }}</p>
                    </a>
                    <a href="{{ route('admin.graficos', ['stock_filter' => 'sin']) }}"
                        class="kpi-card bg-white p-3 border border-gray-200 block">
                        <p class="text-xs text-gray-500">Sin Stock</p>
                        <p class="text-xl font-semibold">{{ $productsOutOfStock }}</p>
                    </a>
                    <a href="{{ route('admin.graficos', ['tipo' => 'ventas', 'estado' => 'Pendiente']) }}"
                        class="kpi-card bg-white p-3 border border-gray-200 block">
                        <p class="text-xs text-gray-500">Ventas Pend.</p>
                        <p class="text-xl font-semibold">{{ $estadisticas['ventas_pendientes'] }}</p>
                    </a>
                    <a href="{{ route('admin.graficos', ['tipo' => 'reparaciones', 'estado' => 'Pendiente']) }}"
                        class="kpi-card bg-white p-3 border border-gray-200 block">
                        <p class="text-xs text-gray-500">Repar. Pend.</p>
                        <p class="text-xl font-semibold">{{ $estadisticas['reparaciones_pendientes'] }}</p>
                    </a>
                    <a href="{{ route('admin.graficos', ['tipo' => 'reparaciones', 'estado' => 'En proceso']) }}"
                        class="kpi-card bg-white p-3 border border-gray-200 block">
                        <p class="text-xs text-gray-500">En Proceso</p>
                        <p class="text-xl font-semibold">{{ DB::table('reparaciones')->where('estado_reparacion', 'En proceso')->count() }}</p>
                    </a>
                </div>

                <!-- ============= PRODUCTOS Y SERVICIOS ============= -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- PRODUCTOS -->
                    <div class="bg-white border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="section-title">Productos</h2>
                            <span class="badge">{{ $estadisticas['total_productos'] }} total</span>
                        </div>

                        <!-- Filtros de Productos -->
                        <form action="{{ route('admin.graficos') }}" method="GET" class="p-3 border-b border-gray-200 bg-gray-50">
                            <input type="hidden" name="tipo" value="{{ $tipo }}">
                            <div class="flex flex-wrap gap-2">
                                <input type="text" name="search_productos" placeholder="Buscar producto..."
                                    value="{{ $searchProductos ?? '' }}"
                                    class="flex-1 min-w-[180px] filter-input">
                                <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white text-sm border border-gray-800 hover:bg-gray-700 transition">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.graficos') }}" class="px-3 py-1.5 bg-white text-sm border border-gray-300 hover:bg-gray-50 transition">
                                    Limpiar
                                </a>
                            </div>
                        </form>

                        <!-- Tabla de Productos -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50">
                                        <th class="px-3 py-2 text-left table-header">Código</th>
                                        <th class="px-3 py-2 text-left table-header">Producto</th>
                                        <th class="px-3 py-2 text-left table-header">Categoría</th>
                                        <th class="px-3 py-2 text-right table-header">Stock</th>
                                        <th class="px-3 py-2 text-right table-header">Precio</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($productos as $producto)
                                        <tr class="tr-clickable" onclick="window.location='{{ route('products.edit', $producto->id) }}'">
                                            <td class="px-3 py-2 font-mono text-xs">{{ $producto->codigo }}</td>
                                            <td class="px-3 py-2">
                                                <div class="font-medium">{{ $producto->nombre }}</div>
                                                <div class="text-xs text-gray-500 truncate max-w-[140px]">{{ $producto->descripcion }}</div>
                                            </td>
                                            <td class="px-3 py-2 text-xs">{{ $producto->categoria_nombre ?? '-' }}</td>
                                            <td class="px-3 py-2 text-right text-sm {{ $producto->stock <= 0 ? 'text-red-600 font-medium' : ($producto->stock <= 5 ? 'text-amber-600 font-medium' : '') }}">
                                                {{ $producto->stock }}
                                            </td>
                                            <td class="px-3 py-2 text-right font-mono text-sm">${{ number_format($producto->precio, 2, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="px-3 py-8 text-center text-gray-500">No hay productos</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 border-t border-gray-200 text-xs text-gray-500">{{ $productos->links() }}</div>
                    </div>

                    <!-- SERVICIOS -->
                    <div class="bg-white border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="section-title">Servicios</h2>
                            <span class="badge">{{ $estadisticas['total_servicios'] }} total</span>
                        </div>

                        <!-- Filtros de Servicios -->
                        <form action="{{ route('admin.graficos') }}" method="GET" class="p-3 border-b border-gray-200 bg-gray-50">
                            <input type="hidden" name="tipo" value="{{ $tipo }}">
                            <div class="flex flex-wrap gap-2">
                                <input type="text" name="search_servicios" placeholder="Buscar servicio..."
                                    value="{{ $searchServicios ?? '' }}" class="flex-1 min-w-[140px] filter-input">
                                <select name="activo_filter" class="filter-input">
                                    <option value="">Todos</option>
                                    <option value="1" {{ $activo_filter == '1' ? 'selected' : '' }}>Activos</option>
                                    <option value="0" {{ $activo_filter == '0' ? 'selected' : '' }}>Inactivos</option>
                                </select>
                                <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white text-sm border border-gray-800 hover:bg-gray-700 transition">
                                    Filtrar
                                </button>
                                <a href="{{ route('admin.graficos') }}" class="px-3 py-1.5 bg-white text-sm border border-gray-300 hover:bg-gray-50 transition">
                                    Limpiar
                                </a>
                            </div>
                        </form>

                        <!-- Tabla de Servicios -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50">
                                        <th class="px-3 py-2 text-left table-header">Servicio</th>
                                        <th class="px-3 py-2 text-left table-header">Descripción</th>
                                        <th class="px-3 py-2 text-right table-header">Precio</th>
                                        <th class="px-3 py-2 text-center table-header">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @forelse($servicios as $servicio)
                                        <tr class="tr-clickable" onclick="window.location='{{ route('servicios.edit', $servicio->id) }}'">
                                            <td class="px-3 py-2 font-medium">{{ $servicio->nombre }}</td>
                                            <td class="px-3 py-2 text-xs text-gray-600 truncate max-w-[150px]">{{ $servicio->descripcion ?? '-' }}</td>
                                            <td class="px-3 py-2 text-right font-mono">${{ number_format($servicio->precio, 2, ',', '.') }}</td>
                                            <td class="px-3 py-2 text-center">
                                                @if ($servicio->activo)
                                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5">Activo</span>
                                                @else
                                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5">Inactivo</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="px-3 py-8 text-center text-gray-500">No hay servicios</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-3 border-t border-gray-200 text-xs text-gray-500">{{ $servicios->links() }}</div>
                    </div>
                </div>

                <!-- ============= HISTORIAL ============= -->
                <div class="bg-white border border-gray-200">
                    <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="section-title">Historial de Ventas y Reparaciones</h2>
                        <div class="flex gap-2 text-xs">
                            <span class="badge">Ventas: {{ $estadisticas['total_ventas'] }}</span>
                            <span class="badge">Reparac.: {{ $estadisticas['total_reparaciones'] }}</span>
                        </div>
                    </div>

                    <!-- Filtros -->
                    <form action="{{ route('admin.graficos') }}" method="GET" class="p-3 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-wrap gap-2">
                            <input type="text" name="search" placeholder="Buscar por código, cliente o equipo..."
                                value="{{ $search ?? '' }}" class="flex-1 min-w-[200px] filter-input">
                            <select name="tipo" class="filter-input">
                                <option value="todos" {{ $tipo == 'todos' ? 'selected' : '' }}>Todos</option>
                                <option value="ventas" {{ $tipo == 'ventas' ? 'selected' : '' }}>Solo Ventas</option>
                                <option value="reparaciones" {{ $tipo == 'reparaciones' ? 'selected' : '' }}>Solo Reparac.</option>
                            </select>
                            <input type="date" name="fecha_desde" value="{{ $fecha_desde }}" class="filter-input w-36">
                            <input type="date" name="fecha_hasta" value="{{ $fecha_hasta }}" class="filter-input w-36">
                            <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white text-sm border border-gray-800 hover:bg-gray-700 transition">
                                Filtrar
                            </button>
                            <a href="{{ route('admin.graficos') }}" class="px-3 py-1.5 bg-white text-sm border border-gray-300 hover:bg-gray-50 transition">
                                Limpiar
                            </a>
                        </div>
                    </form>

                    <!-- Tabla -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50">
                                    <th class="px-3 py-2 text-left table-header">Tipo</th>
                                    <th class="px-3 py-2 text-left table-header">Número</th>
                                    <th class="px-3 py-2 text-left table-header">Cliente</th>
                                    <th class="px-3 py-2 text-left table-header">Detalle</th>
                                    <th class="px-3 py-2 text-left table-header">Estado</th>
                                    <th class="px-3 py-2 text-right table-header">Importe</th>
                                    <th class="px-3 py-2 text-left table-header">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($historial as $item)
                                    <tr class="tr-clickable" onclick="window.location='{{ $item->tipo == 'Venta' ? route('ventas.show', $item->id) : route('reparaciones.show', $item->id) }}'">
                                        <td class="px-3 py-2">
                                            <span class="badge">{{ $item->tipo == 'Venta' ? 'Venta' : 'Reparac.' }}</span>
                                        </td>
                                        <td class="px-3 py-2 font-mono text-xs">{{ $item->numero_comprobante }}</td>
                                        <td class="px-3 py-2">{{ $item->cliente_nombre }}</td>
                                        <td class="px-3 py-2 text-xs text-gray-600">{{ $item->tipo == 'Venta' ? $item->tipo_comprobante : $item->equipo_descripcion }}</td>
                                        <td class="px-3 py-2 text-xs">
                                            @if ($item->tipo == 'Venta')
                                                <span class="{{ $item->estado == 'Pagada' ? 'text-green-600' : ($item->estado == 'Pendiente' ? 'text-amber-600' : 'text-gray-600') }}">
                                                    {{ $item->estado }}
                                                </span>
                                            @else
                                                <span class="{{ $item->estado == 'Entregado' ? 'text-green-600' : ($item->estado == 'Pendiente' ? 'text-amber-600' : ($item->estado == 'En proceso' ? 'text-blue-600' : 'text-gray-600')) }}">
                                                    {{ $item->estado }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2 text-right font-mono">${{ number_format($item->importe_total, 2, ',', '.') }}</td>
                                        <td class="px-3 py-2 text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-3 py-8 text-center text-gray-500">No hay registros</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-3 border-t border-gray-200 text-xs text-gray-500">{{ $historial->links() }}</div>
                </div>

                <!-- Gráficos -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Productos por Categoría</h3>
                        <div class="chart-container"><canvas id="chartCategories"></canvas></div>
                    </div>
                    <div class="bg-white p-4 border border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Productos por Marca</h3>
                        <div class="chart-container"><canvas id="chartMarcas"></canvas></div>
                    </div>
                </div>

                <div class="bg-white p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Productos por Mes (6 meses)</h3>
                    <div class="chart-container"><canvas id="chartProductsByMonth"></canvas></div>
                </div>

                <div class="bg-white p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-tools text-gray-500 text-xs"></i> Reparaciones (2 meses)
                    </h3>
                    <div class="chart-container"><canvas id="chartReparaciones"></canvas></div>
                </div>

                <div class="bg-white p-4 border border-gray-200">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-shopping-cart text-gray-500 text-xs"></i> Ventas (2 meses)
                    </h3>
                    <div class="chart-container"><canvas id="chartVentas"></canvas></div>
                </div>

                <!-- Últimos registros -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-sm">Últimas Ventas</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach ($recentVentas as $venta)
                                <div class="px-4 py-2 text-sm flex justify-between tr-clickable hover:bg-gray-50"
                                    onclick="window.location='{{ route('ventas.show', $venta->id) }}'">
                                    <span>#{{ $venta->id }} · {{ $venta->tipo_comprobante }}</span>
                                    <span class="font-mono text-gray-600">${{ number_format($venta->importe_total, 2, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="bg-white border border-gray-200">
                        <div class="px-4 py-3 border-b border-gray-200">
                            <h3 class="font-semibold text-sm">Últimos Clientes</h3>
                        </div>
                        <div class="divide-y divide-gray-100">
                            @foreach ($recentClientes as $cliente)
                                <div class="px-4 py-2 text-sm tr-clickable hover:bg-gray-50"
                                    onclick="window.location='{{ route('clientes.edit', $cliente->id) }}'">
                                    {{ $cliente->NombreCompleto }}
                                    <span class="text-xs text-gray-500 ml-2">{{ $cliente->TipoCliente }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

     <!-- Scripts -->
    <script>
        // Configuración de gráficos con tamaño controlado
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        maxTicksLimit: 6
                    }
                }
            }
        };

        const chartColors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'];

        // Categorías
        new Chart(document.getElementById('chartCategories'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($productsByCategory->pluck('nombre')) !!},
                datasets: [{
                    data: {!! json_encode($productsByCategory->pluck('total')) !!},
                    backgroundColor: '#3b82f6',
                }]
            },
            options: chartOptions
        });

        // Marcas
        new Chart(document.getElementById('chartMarcas'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($productsByMarca->pluck('nombre')) !!},
                datasets: [{
                    data: {!! json_encode($productsByMarca->pluck('total')) !!},
                    backgroundColor: chartColors,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        });

        // Productos por mes
        new Chart(document.getElementById('chartProductsByMonth'), {
            type: 'line',
            data: {
                labels: {!! json_encode(
                    $productsByMonth->map(fn($m) => \Carbon\Carbon::create()->month($m->month)->translatedFormat('F')),
                ) !!},
                datasets: [{
                    label: 'Productos',
                    data: {!! json_encode($productsByMonth->pluck('total')) !!},
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            maxTicksLimit: 5
                        }
                    }
                }
            }
        });

        // Gráfico de Reparaciones
        new Chart(document.getElementById('chartReparaciones'), {
            type: 'line',
            data: {
                labels: {!! json_encode($reparacionesMeses) !!},
                datasets: [{
                        label: 'Ingresadas',
                        data: {!! json_encode($reparacionesIngresadas) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointRadius: 4
                    },
                    {
                        label: 'Entregadas',
                        data: {!! json_encode($reparacionesEntregadas) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            maxTicksLimit: 5
                        }
                    }
                }
            }
        });

        // Gráfico de Ventas
        new Chart(document.getElementById('chartVentas'), {
            type: 'line',
            data: {
                labels: {!! json_encode($ventasMeses) !!},
                datasets: [{
                        label: 'Creadas',
                        data: {!! json_encode($ventasCreadas) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#3b82f6',
                        pointBorderColor: '#fff',
                        pointRadius: 4
                    },
                    {
                        label: 'Pagadas',
                        data: {!! json_encode($ventasPagadas) !!},
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointRadius: 4
                    },
                    {
                        label: 'Pendientes',
                        data: {!! json_encode($ventasPendientes) !!},
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#f59e0b',
                        pointBorderColor: '#fff',
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 10
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            maxTicksLimit: 5
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>

