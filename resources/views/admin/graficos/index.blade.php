<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Información</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50 font-sans text-gray-800">
    <div class="flex">
        <x-admin-nav />

        <div class="max-w-7xl mx-auto p-6">

            <!-- Título -->
            <h1 class="text-4xl font-bold mb-8 text-gray-900">📊 Dashboard de Información</h1>

            <!-- Resumen total -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white p-6 rounded-2xl shadow-md shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-medium">Valor total de inventario</h2>
                    <p class="text-4xl font-bold mt-2">${{ number_format($totalInventoryValue, 2) }}</p>
                </div>

                <div class="bg-gradient-to-r from-pink-500 to-rose-600 text-white p-6 rounded-2xl shadow-md shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-medium">Valor de proveedor</h2>
                    <p class="text-4xl font-bold mt-2">${{ number_format($totalSupplierValue, 2) }}</p>
                </div>

                <div class="bg-gradient-to-r from-emerald-500 to-green-600 text-white p-6 rounded-2xl shadow-md shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-medium">Ganancia total estimada</h2>
                    <p class="text-4xl font-bold mt-2">${{ number_format($totalProfit, 2) }}</p>
                </div>
            </div>

            <!-- Ventas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <!-- Ventas de Hoy -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow-md shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-medium">Ventas de Hoy</h2>
                    <p class="text-4xl font-bold mt-2">{{ $totalVentas }}</p>
                </div>

                <!-- Ventas del Mes -->
                <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white p-6 rounded-2xl shadow-md shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-medium">Ventas del Mes</h2>
                    <p class="text-4xl font-bold mt-2">{{ $totalVentasMes }}</p>
                </div>

                <!-- Ingresos de Hoy -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white p-6 rounded-2xl shadow-md shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-medium">Ingresos de Hoy</h2>
                    <p class="text-4xl font-bold mt-2">${{ number_format($totalVentasDia, 2, ',', '.') }}</p>
                </div>

                <!-- Ingresos del Mes -->
                <div class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white p-6 rounded-2xl shadow-md shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-medium">Ingresos del Mes</h2>
                    <p class="text-4xl font-bold mt-2">${{ number_format($totalVentasMesImporte, 2, ',', '.') }}</p>
                </div>
            </div>

            <!-- Estadísticas principales -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold">Total de Productos</h2>
                    <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $totalProducts }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold">Total Stock</h2>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalStock }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold">Sin Stock</h2>
                    <p class="text-3xl font-bold text-red-500 mt-2">{{ $productsOutOfStock }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold">Visibles / Ocultos</h2>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $visibleProducts }} /
                        {{ $invisibleProducts }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold">Productos con Stock Bajo</h2>
                    <p class="text-3xl font-bold text-yellow-500 mt-2">{{ $lowStockProducts }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow hover:shadow-lg transition">
                    <h2 class="text-lg font-semibold">Clientes Totales</h2>
                    <p class="text-3xl font-bold text-black-500 mt-2">{{ $totalClientes }}</p>
                </div>
            </div>


            <!-- Listas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">



                <!-- Productos recientes -->
                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-3">🆕 Productos recientes</h2>
                    <ul class="space-y-2 text-gray-700">
                        @foreach ($recentProducts as $prod)
                            <li class="border-b pb-1">
                                {{ $prod->nombre }}
                                <span
                                    class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($prod->created_at)->format('d/m/Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Clientes -->
                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-3">🆕 Ultimos Clientes</h2>
                    <ul class="space-y-2 text-gray-700">
                        @foreach ($recentClientes as $cliente)
                            <li class="border-b pb-1">
                                {{ $cliente->NombreCompleto }}
                                <span
                                    class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($cliente->created_at)->format('d/m/Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Actualizaciones -->
                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-3">♻️ Últimas actualizaciones</h2>
                    <ul class="space-y-2 text-gray-700">
                        @foreach ($recentlyUpdatedProducts as $prod)
                            <li class="border-b pb-1">
                                {{ $prod->nombre }}
                                <span
                                    class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($prod->updated_at)->format('d/m/Y H:i') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- ultimas ventas -->

                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-3">🛒 Ultimas Ventas</h2>
                    <ul class="space-y-2 text-gray-700">
                        @foreach ($recentVentas as $venta)
                            <li class="border-b pb-1">
                                {{ $venta->id }} | {{ $venta->tipo_comprobante }} | {{ $venta->estado_venta }} |
                                {{ $venta->importe_total }}
                                <span
                                    class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-3">♻️ Ultimas Ventas Actualizadas</h2>
                    <ul class="space-y-2 text-gray-700">
                        @foreach ($recentlyUpdatedVentas as $venta)
                            <li class="border-b pb-1">
                                {{ $venta->id }} | {{ $venta->tipo_comprobante }} | {{ $venta->estado_venta }} |
                                {{ $venta->importe_total }}
                                <span
                                    class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y') }})</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-4">📦 Productos por Categoría</h2>
                    <canvas id="chartCategories"></canvas>
                </div>

                <div class="bg-white p-6 rounded-xl shadow">
                    <h2 class="text-lg font-semibold mb-4">🏷️ Productos por Marca</h2>
                    <canvas id="chartMarcas"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-4">📅 Ingresos durante el mes</h2>
                <canvas id="chartIngresosDelMes"></canvas>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h2 class="text-lg font-semibold mb-4">📅 Ingresos por Mes (Últimos 6 meses)</h2>
                <canvas id="chartProductsByMonth"></canvas>
            </div>
        </div>

        <!-- Scripts Chart.js -->
        <script>
            const chartColors = [
                '#3b82f6', '#10b981', '#f59e0b', '#ef4444',
                '#8b5cf6', '#ec4899', '#22d3ee', '#14b8a6'
            ];

            // Categorías
            new Chart(document.getElementById('chartCategories'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($productsByCategory->pluck('nombre')) !!},
                    datasets: [{
                        label: 'Productos',
                        data: {!! json_encode($productsByCategory->pluck('total')) !!},
                        backgroundColor: chartColors,
                        borderRadius: 8,
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Marcas
            new Chart(document.getElementById('chartMarcas'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($productsByMarca->pluck('nombre')) !!},
                    datasets: [{
                        data: {!! json_encode($productsByMarca->pluck('total')) !!},
                        backgroundColor: chartColors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
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
                        label: 'Ingresos',
                        data: {!! json_encode($productsByMonth->pluck('total')) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.15)',
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointHoverRadius: 6,
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            grid: {
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</body>

</html>
