<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Marca;
use App\Models\Cliente;
use Carbon\Carbon;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ============= ESTADÍSTICAS EXISTENTES =============
        // Total de productos
        $totalProducts = Product::count();

        // Productos por categoría
        $productsByCategory = DB::table('productos')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre', DB::raw('count(productos.id) as total'))
            ->groupBy('categorias.nombre')
            ->get();

        // Productos por marca
        $productsByMarca = DB::table('productos')
            ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select('marcas.nombre', DB::raw('count(productos.id) as total'))
            ->groupBy('marcas.nombre')
            ->get();

        // Productos recientes (últimos 5)
        $recentProducts = Product::latest()->take(5)->get();

        // Productos sin stock
        $productsOutOfStock = Product::where('stock', '<=', 0)->count();

        // Stock total
        $totalStock = Product::sum('stock');

        // Productos visibles vs no visibles
        $visibleProducts = Product::where('visible', 1)->count();
        $invisibleProducts = Product::where('visible', 0)->count();

        // Productos por mes (últimos 6 meses)
        $productsByMonth = Product::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Productos recientemente actualizados (últimos 5)
        $recentlyUpdatedProducts = Product::orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Valor total de inventario (precio de venta * stock)
        $totalInventoryValue = Product::sum(DB::raw('precio * stock'));

        // Valor total del inventario según proveedor (precio_proveedor * stock)
        $totalSupplierValue = Product::sum(DB::raw('precio_proveedor * stock'));

        // Ganancia total estimada (precio - precio_proveedor) * stock
        $totalProfit = Product::sum(DB::raw('(precio - precio_proveedor) * stock'));

        // Total de productos con bajo stock (por ejemplo, stock <= 5)
        $lowStockProducts = Product::where('stock', '<=', 5)->count();

        // Total de clientes
        $totalClientes = Cliente::count();

        // ultimos clientes
        $recentClientes = Cliente::latest()->take(5)->get();

        // FECHA ACTUAL FORMATEADA
        $hoy = Carbon::today('America/Argentina/Buenos_Aires')->format('Y-m-d');

        // Totales de ventas del día
        $totalVentas = DB::table('ventas')
            ->where('fecha_venta', $hoy)
            ->count();

        $totalVentasDia = DB::table('ventas')
            ->where('fecha_venta', $hoy)
            ->sum('importe_total');

        // Totales de ventas del mes
        $totalVentasMes = DB::table('ventas')
            ->whereMonth('fecha_venta', Carbon::now()->month)
            ->whereYear('fecha_venta', Carbon::now()->year)
            ->count();

        $totalVentasMesImporte = DB::table('ventas')
            ->whereMonth('fecha_venta', Carbon::now()->month)
            ->whereYear('fecha_venta', Carbon::now()->year)
            ->sum('importe_total');

        // ventas recientes (últimos 5)
        $recentVentas = DB::table('ventas')
            ->orderBy('fecha_venta', 'desc')
            ->take(5)
            ->get();

        // ventas recientes actualizadas (últimos 5)
        $recentlyUpdatedVentas = DB::table('ventas')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // ============= HISTORIAL DE VENTAS Y REPARACIONES =============

        // Obtener filtros del request
        $search = $request->get('search');
        $tipo = $request->get('tipo', 'todos'); // ventas, reparaciones, todos
        $fecha_desde = $request->get('fecha_desde');
        $fecha_hasta = $request->get('fecha_hasta');
        $cliente_id = $request->get('cliente_id');
        $estado = $request->get('estado');

        // ============= CONSULTA DE VENTAS =============
        $ventasQuery = DB::table('ventas')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->select(
                'ventas.id',
                'ventas.tipo_comprobante',
                'ventas.numero_comprobante',
                'ventas.condicion_pago',
                'ventas.importe_total',
                'ventas.estado_venta as estado',
                'ventas.fecha_venta as fecha',
                'ventas.created_at',
                'ventas.updated_at',
                'clientes.NombreCompleto as cliente_nombre',
                'clientes.id as cliente_id',
                DB::raw("'Venta' as tipo"),
                DB::raw('NULL as equipo_descripcion'),
                DB::raw('NULL as equipo_marca'),
                DB::raw('NULL as equipo_modelo')
            )
            ->where('ventas.visible', 1);

        // ============= CONSULTA DE REPARACIONES =============
        $reparacionesQuery = DB::table('reparaciones')
            ->join('clientes', 'reparaciones.cliente_id', '=', 'clientes.id')
            ->select(
                'reparaciones.id',
                DB::raw("'Reparación' as tipo_comprobante"),
                'reparaciones.codigo_unico as numero_comprobante',
                DB::raw("'Servicio Técnico' as condicion_pago"),
                DB::raw('COALESCE((
                    SELECT SUM(rp.precio * rp.cantidad)
                    FROM reparacion_producto rp
                    WHERE rp.reparacion_id = reparaciones.id
                ), 0) + COALESCE((
                    SELECT SUM(rs.precio * rs.cantidad)
                    FROM reparacion_servicio rs
                    WHERE rs.reparacion_id = reparaciones.id
                ), 0) as importe_total'),
                'reparaciones.estado_reparacion as estado',
                'reparaciones.fecha_ingreso as fecha',
                'reparaciones.created_at',
                'reparaciones.updated_at',
                'clientes.NombreCompleto as cliente_nombre',
                'clientes.id as cliente_id',
                DB::raw("'Reparación' as tipo"),
                'reparaciones.equipo_descripcion',
                'reparaciones.equipo_marca',
                'reparaciones.equipo_modelo'
            );

        // Aplicar filtros según el tipo seleccionado
        if ($tipo === 'ventas') {
            $query = $ventasQuery;
        } elseif ($tipo === 'reparaciones') {
            $query = $reparacionesQuery;
        } else {
            // Unir ambas consultas para "todos"
            $query = $ventasQuery->union($reparacionesQuery);
        }

        // Aplicar filtros comunes
        if ($tipo === 'ventas' || $tipo === 'todos') {
            if ($search) {
                $ventasQuery->where(function ($q) use ($search) {
                    $q->where('ventas.numero_comprobante', 'LIKE', "%{$search}%")
                        ->orWhere('clientes.NombreCompleto', 'LIKE', "%{$search}%")
                        ->orWhere('ventas.tipo_comprobante', 'LIKE', "%{$search}%");
                });
            }

            if ($fecha_desde) {
                $ventasQuery->where('ventas.fecha_venta', '>=', $fecha_desde);
            }

            if ($fecha_hasta) {
                $ventasQuery->where('ventas.fecha_venta', '<=', $fecha_hasta);
            }

            if ($cliente_id) {
                $ventasQuery->where('ventas.cliente_id', $cliente_id);
            }

            if ($estado) {
                $ventasQuery->where('ventas.estado_venta', $estado);
            }
        }

        if ($tipo === 'reparaciones' || $tipo === 'todos') {
            if ($search) {
                $reparacionesQuery->where(function ($q) use ($search) {
                    $q->where('reparaciones.codigo_unico', 'LIKE', "%{$search}%")
                        ->orWhere('clientes.NombreCompleto', 'LIKE', "%{$search}%")
                        ->orWhere('reparaciones.equipo_descripcion', 'LIKE', "%{$search}%")
                        ->orWhere('reparaciones.equipo_marca', 'LIKE', "%{$search}%");
                });
            }

            if ($fecha_desde) {
                $reparacionesQuery->where('reparaciones.fecha_ingreso', '>=', $fecha_desde);
            }

            if ($fecha_hasta) {
                $reparacionesQuery->where('reparaciones.fecha_ingreso', '<=', $fecha_hasta);
            }

            if ($cliente_id) {
                $reparacionesQuery->where('reparaciones.cliente_id', $cliente_id);
            }

            if ($estado) {
                $reparacionesQuery->where('reparaciones.estado_reparacion', $estado);
            }
        }

        // Ordenar y paginar
        $historial = $query->orderBy('fecha', 'desc')
            ->paginate(15)
            ->withQueryString();

        // ============= NUEVO: HISTORIAL DE PRODUCTOS =============
        $searchProductos = $request->get('search_productos');
        $categoria_id = $request->get('categoria_id');
        $marca_id = $request->get('marca_id');
        $stock_filter = $request->get('stock_filter');
        $visible_filter = $request->get('visible_filter');

        $productosQuery = DB::table('productos')
            ->leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select(
                'productos.*',
                'categorias.nombre as categoria_nombre',
                'marcas.nombre as marca_nombre'
            );

        if ($searchProductos) {
            $productosQuery->where(function ($q) use ($searchProductos) {
                $q->where('productos.nombre', 'LIKE', "%{$searchProductos}%")
                    ->orWhere('productos.codigo', 'LIKE', "%{$searchProductos}%")
                    ->orWhere('productos.descripcion', 'LIKE', "%{$searchProductos}%");
            });
        }

        if ($categoria_id) {
            $productosQuery->where('productos.categoria_id', $categoria_id);
        }

        if ($marca_id) {
            $productosQuery->where('productos.marca_id', $marca_id);
        }

        if ($stock_filter === 'bajo') {
            $productosQuery->where('productos.stock', '<=', 5);
        } elseif ($stock_filter === 'sin') {
            $productosQuery->where('productos.stock', '<=', 0);
        } elseif ($stock_filter === 'normal') {
            $productosQuery->where('productos.stock', '>', 5);
        }

        if ($visible_filter !== null && $visible_filter !== '') {
            $productosQuery->where('productos.visible', $visible_filter);
        }

        $productos = $productosQuery->orderBy('productos.created_at', 'desc')
            ->paginate(10, ['*'], 'productos_page')
            ->withQueryString();

        // ============= NUEVO: HISTORIAL DE SERVICIOS =============
        $searchServicios = $request->get('search_servicios');
        $activo_filter = $request->get('activo_filter');

        $serviciosQuery = DB::table('servicios')
            ->select('*');

        if ($searchServicios) {
            $serviciosQuery->where(function ($q) use ($searchServicios) {
                $q->where('nombre', 'LIKE', "%{$searchServicios}%")
                    ->orWhere('descripcion', 'LIKE', "%{$searchServicios}%");
            });
        }

        if ($activo_filter !== null && $activo_filter !== '') {
            $serviciosQuery->where('activo', $activo_filter);
        }

        $servicios = $serviciosQuery->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'servicios_page')
            ->withQueryString();

        // Obtener lista de clientes para el filtro
        $clientes = Cliente::where('visible', 1)->orderBy('NombreCompleto')->get();

        // Obtener categorías y marcas para filtros
        $categorias = DB::table('categorias')->where('visible', 1)->orderBy('nombre')->get();
        $marcas = DB::table('marcas')->where('visible', 1)->orderBy('nombre')->get();

        // Estadísticas adicionales para el historial
        $estadisticas = [
            'total_ventas' => DB::table('ventas')->where('visible', 1)->count(),
            'total_reparaciones' => DB::table('reparaciones')->count(),
            'total_ingresos_ventas' => DB::table('ventas')->where('visible', 1)->where('estado_venta', 'Pagada')->sum('importe_total'),
            'total_ingresos_reparaciones' =>
            DB::table('reparacion_producto')
                ->join('reparaciones', 'reparacion_producto.reparacion_id', '=', 'reparaciones.id')
                ->sum(DB::raw('reparacion_producto.precio * reparacion_producto.cantidad'))
                +
                DB::table('reparacion_servicio')
                ->join('reparaciones', 'reparacion_servicio.reparacion_id', '=', 'reparaciones.id')
                ->sum(DB::raw('reparacion_servicio.precio * reparacion_servicio.cantidad')),
            'ventas_pendientes' => DB::table('ventas')->where('visible', 1)->where('estado_venta', 'Pendiente')->count(),
            'reparaciones_pendientes' => DB::table('reparaciones')->where('estado_reparacion', 'Pendiente')->count(),
            'total_productos' => $totalProducts,
            'total_servicios' => DB::table('servicios')->count(),
            'productos_visibles' => $visibleProducts,
            'productos_ocultos' => $invisibleProducts,
            'servicios_activos' => DB::table('servicios')->where('activo', 1)->count(),
            'servicios_inactivos' => DB::table('servicios')->where('activo', 0)->count(),
        ];

        // ============= GRÁFICOS DE REPARACIONES (ÚLTIMOS 2 MESES) =============
        $reparacionesUltimos2Meses = DB::table('reparaciones')
            ->select(
                DB::raw('DATE_FORMAT(fecha_ingreso, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN estado_reparacion = "Entregado" THEN 1 ELSE 0 END) as entregadas'),
                DB::raw('SUM(CASE WHEN estado_reparacion = "Pendiente" THEN 1 ELSE 0 END) as pendientes'),
                DB::raw('SUM(CASE WHEN estado_reparacion = "En proceso" THEN 1 ELSE 0 END) as proceso')
            )
            ->where('fecha_ingreso', '>=', Carbon::now()->subMonths(2)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Preparar datos para el gráfico
        $reparacionesMeses = $reparacionesUltimos2Meses->pluck('mes')->map(function ($mes) {
            $parts = explode('-', $mes);
            return Carbon::create($parts[0], $parts[1])->translatedFormat('M Y');
        });

        $reparacionesIngresadas = $reparacionesUltimos2Meses->pluck('total');
        $reparacionesEntregadas = $reparacionesUltimos2Meses->pluck('entregadas');

        // ============= GRÁFICOS DE VENTAS (ÚLTIMOS 2 MESES) =============
        $ventasUltimos2Meses = DB::table('ventas')
            ->select(
                DB::raw('DATE_FORMAT(fecha_venta, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN estado_venta = "Pagada" THEN 1 ELSE 0 END) as pagadas'),
                DB::raw('SUM(CASE WHEN estado_venta = "Pendiente" THEN 1 ELSE 0 END) as pendientes')
            )
            ->where('fecha_venta', '>=', Carbon::now()->subMonths(2)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Preparar datos para el gráfico
        $ventasMeses = $ventasUltimos2Meses->pluck('mes')->map(function ($mes) {
            $parts = explode('-', $mes);
            return Carbon::create($parts[0], $parts[1])->translatedFormat('M Y');
        });

        $ventasCreadas = $ventasUltimos2Meses->pluck('total');
        $ventasPagadas = $ventasUltimos2Meses->pluck('pagadas');
        $ventasPendientes = $ventasUltimos2Meses->pluck('pendientes');

        // Retornar los datos a la vista del dashboard
        return view('admin.graficos.index', compact(
            // productos
            'totalProducts',
            'productsByCategory',
            'productsByMarca',
            'recentProducts',
            'productsOutOfStock',
            'totalStock',
            'visibleProducts',
            'invisibleProducts',
            'productsByMonth',
            'recentlyUpdatedProducts',
            'totalInventoryValue',
            'totalSupplierValue',
            'lowStockProducts',
            'totalProfit',
            // clientes
            'totalClientes',
            'recentClientes',
            // ventas de hoy
            'totalVentas',
            'totalVentasDia',
            // mes actual
            'totalVentasMes',
            'totalVentasMesImporte',
            // ventas recientes
            'recentlyUpdatedVentas',
            'recentVentas',
            // historial ventas/reparaciones
            'historial',
            'clientes',
            'estadisticas',
            'search',
            'tipo',
            'fecha_desde',
            'fecha_hasta',
            'cliente_id',
            'estado',
            // NUEVO: historial productos
            'productos',
            'searchProductos',
            'categoria_id',
            'marca_id',
            'stock_filter',
            'visible_filter',
            'categorias',
            'marcas',
            // NUEVO: historial servicios
            'servicios',
            'searchServicios',
            'activo_filter',
            // ... todas las variables existentes ...
            'reparacionesMeses',
            'reparacionesIngresadas',
            'reparacionesEntregadas',
            'ventasMeses',
            'ventasCreadas',
            'ventasPagadas',
            'ventasPendientes'
        ));
    }

    /**
     * Obtener detalles de una venta específica
     */
    public function detalleVenta($id)
    {
        $venta = DB::table('ventas')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->where('ventas.id', $id)
            ->select(
                'ventas.*',
                'clientes.NombreCompleto as cliente_nombre',
                'clientes.Domicilio',
                'clientes.Telefono',
                'clientes.Email',
                'clientes.cuit_dni'
            )
            ->first();

        $productos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->where('detalle_ventas.venta_id', $id)
            ->select(
                'productos.nombre',
                'productos.codigo',
                'detalle_ventas.cantidad',
                'detalle_ventas.precio_unitario',
                'detalle_ventas.descuento',
                'detalle_ventas.subtotal'
            )
            ->get();

        $servicios = DB::table('servicio_venta')
            ->join('servicios', 'servicio_venta.servicio_id', '=', 'servicios.id')
            ->where('servicio_venta.venta_id', $id)
            ->select(
                'servicios.nombre',
                'servicios.descripcion',
                'servicio_venta.cantidad',
                'servicio_venta.precio'
            )
            ->get();

        return response()->json([
            'venta' => $venta,
            'productos' => $productos,
            'servicios' => $servicios
        ]);
    }

    /**
     * Obtener detalles de una reparación específica
     */
    public function detalleReparacion($id)
    {
        $reparacion = DB::table('reparaciones')
            ->join('clientes', 'reparaciones.cliente_id', '=', 'clientes.id')
            ->where('reparaciones.id', $id)
            ->select(
                'reparaciones.*',
                'clientes.NombreCompleto as cliente_nombre',
                'clientes.Telefono',
                'clientes.Email'
            )
            ->first();

        $productos = DB::table('reparacion_producto')
            ->join('productos', 'reparacion_producto.producto_id', '=', 'productos.id')
            ->where('reparacion_producto.reparacion_id', $id)
            ->select(
                'productos.nombre',
                'productos.codigo',
                'reparacion_producto.cantidad',
                'reparacion_producto.precio'
            )
            ->get();

        $servicios = DB::table('reparacion_servicio')
            ->join('servicios', 'reparacion_servicio.servicio_id', '=', 'servicios.id')
            ->where('reparacion_servicio.reparacion_id', $id)
            ->select(
                'servicios.nombre',
                'servicios.descripcion',
                'reparacion_servicio.cantidad',
                'reparacion_servicio.precio'
            )
            ->get();

        return response()->json([
            'reparacion' => $reparacion,
            'productos' => $productos,
            'servicios' => $servicios
        ]);
    }

    /**
     * Obtener detalles de un producto específico
     */
    public function detalleProducto($id)
    {
        $producto = DB::table('productos')
            ->leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->where('productos.id', $id)
            ->select(
                'productos.*',
                'categorias.nombre as categoria_nombre',
                'marcas.nombre as marca_nombre'
            )
            ->first();

        // Últimos movimientos en ventas
        $ventas = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->where('detalle_ventas.producto_id', $id)
            ->select(
                'ventas.fecha_venta as fecha',
                'clientes.NombreCompleto as cliente',
                'detalle_ventas.cantidad',
                'detalle_ventas.precio_unitario',
                'detalle_ventas.subtotal'
            )
            ->orderBy('ventas.fecha_venta', 'desc')
            ->limit(5)
            ->get();

        // Últimos movimientos en reparaciones
        $reparaciones = DB::table('reparacion_producto')
            ->join('reparaciones', 'reparacion_producto.reparacion_id', '=', 'reparaciones.id')
            ->join('clientes', 'reparaciones.cliente_id', '=', 'clientes.id')
            ->where('reparacion_producto.producto_id', $id)
            ->select(
                'reparaciones.fecha_ingreso as fecha',
                'clientes.NombreCompleto as cliente',
                'reparacion_producto.cantidad',
                'reparacion_producto.precio'
            )
            ->orderBy('reparaciones.fecha_ingreso', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'producto' => $producto,
            'ventas' => $ventas,
            'reparaciones' => $reparaciones
        ]);
    }

    /**
     * Obtener detalles de un servicio específico
     */
    public function detalleServicio($id)
    {
        $servicio = DB::table('servicios')
            ->where('id', $id)
            ->first();

        // Últimos movimientos en ventas
        $ventas = DB::table('servicio_venta')
            ->join('ventas', 'servicio_venta.venta_id', '=', 'ventas.id')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->where('servicio_venta.servicio_id', $id)
            ->select(
                'ventas.fecha_venta as fecha',
                'clientes.NombreCompleto as cliente',
                'servicio_venta.cantidad',
                'servicio_venta.precio'
            )
            ->orderBy('ventas.fecha_venta', 'desc')
            ->limit(5)
            ->get();

        // Últimos movimientos en reparaciones
        $reparaciones = DB::table('reparacion_servicio')
            ->join('reparaciones', 'reparacion_servicio.reparacion_id', '=', 'reparaciones.id')
            ->join('clientes', 'reparaciones.cliente_id', '=', 'clientes.id')
            ->where('reparacion_servicio.servicio_id', $id)
            ->select(
                'reparaciones.fecha_ingreso as fecha',
                'clientes.NombreCompleto as cliente',
                'reparacion_servicio.cantidad',
                'reparacion_servicio.precio'
            )
            ->orderBy('reparaciones.fecha_ingreso', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'servicio' => $servicio,
            'ventas' => $ventas,
            'reparaciones' => $reparaciones
        ]);
    }
}
