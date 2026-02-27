<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Cliente;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ============= ESTADÍSTICAS DE PRODUCTOS =============
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $productsOutOfStock = Product::where('stock', '<=', 0)->count();
        $lowStockProducts = Product::where('stock', '<=', 5)->count();
        $visibleProducts = Product::where('visible', 1)->count();
        $invisibleProducts = Product::where('visible', 0)->count();

        // Valores de inventario
        $totalInventoryValue = Product::sum(DB::raw('precio * stock'));
        $totalProfit = Product::sum(DB::raw('(precio - precio_proveedor) * stock'));

        // Productos por categoría y marca (para gráficos)
        $productsByCategory = DB::table('productos')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('categorias.nombre', DB::raw('count(productos.id) as total'))
            ->groupBy('categorias.nombre')
            ->get();

        $productsByMarca = DB::table('productos')
            ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->select('marcas.nombre', DB::raw('count(productos.id) as total'))
            ->groupBy('marcas.nombre')
            ->get();

        // Productos por mes (últimos 6 meses)
        $productsByMonth = Product::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // ============= ESTADÍSTICAS DE CLIENTES =============
        $totalClientes = Cliente::count();
        $recentClientes = Cliente::latest()->take(5)->get();

        // ============= ESTADÍSTICAS DE VENTAS =============
        $hoy = Carbon::today('America/Argentina/Buenos_Aires')->format('Y-m-d');

        $totalVentasDia = DB::table('ventas')
            ->where('fecha_venta', $hoy)
            ->sum('importe_total');

        $totalVentasMes = DB::table('ventas')
            ->whereMonth('fecha_venta', Carbon::now()->month)
            ->whereYear('fecha_venta', Carbon::now()->year)
            ->count();

        $totalVentasMesImporte = DB::table('ventas')
            ->whereMonth('fecha_venta', Carbon::now()->month)
            ->whereYear('fecha_venta', Carbon::now()->year)
            ->sum('importe_total');

        $recentVentas = DB::table('ventas')
            ->orderBy('fecha_venta', 'desc')
            ->take(5)
            ->get();

        // ============= HISTORIAL DE VENTAS Y REPARACIONES =============
        $search = $request->get('search');
        $tipo = $request->get('tipo', 'todos');
        $fecha_desde = $request->get('fecha_desde');
        $fecha_hasta = $request->get('fecha_hasta');
        $estado = $request->get('estado');

        // Consulta de Ventas
        $ventasQuery = DB::table('ventas')
            ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
            ->select(
                'ventas.id',
                'ventas.tipo_comprobante',
                'ventas.numero_comprobante',
                'ventas.importe_total',
                'ventas.estado_venta as estado',
                'ventas.fecha_venta as fecha',
                'clientes.NombreCompleto as cliente_nombre',
                DB::raw("'Venta' as tipo"),
                DB::raw('NULL as equipo_descripcion')
            )
            ->where('ventas.visible', 1);

        // Consulta de Reparaciones
        $reparacionesQuery = DB::table('reparaciones')
            ->join('clientes', 'reparaciones.cliente_id', '=', 'clientes.id')
            ->select(
                'reparaciones.id',
                DB::raw("'Reparación' as tipo_comprobante"),
                'reparaciones.codigo_unico as numero_comprobante',
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
                'clientes.NombreCompleto as cliente_nombre',
                DB::raw("'Reparación' as tipo"),
                'reparaciones.equipo_descripcion'
            );

        // Aplicar filtros
        if ($tipo === 'ventas') {
            $query = $ventasQuery;
        } elseif ($tipo === 'reparaciones') {
            $query = $reparacionesQuery;
        } else {
            $query = $ventasQuery->union($reparacionesQuery);
        }

        if ($tipo === 'ventas' || $tipo === 'todos') {
            if ($search) {
                $ventasQuery->where(function ($q) use ($search) {
                    $q->where('ventas.numero_comprobante', 'LIKE', "%{$search}%")
                        ->orWhere('clientes.NombreCompleto', 'LIKE', "%{$search}%");
                });
            }
            if ($fecha_desde) $ventasQuery->where('ventas.fecha_venta', '>=', $fecha_desde);
            if ($fecha_hasta) $ventasQuery->where('ventas.fecha_venta', '<=', $fecha_hasta);
            if ($estado) $ventasQuery->where('ventas.estado_venta', $estado);
        }

        if ($tipo === 'reparaciones' || $tipo === 'todos') {
            if ($search) {
                $reparacionesQuery->where(function ($q) use ($search) {
                    $q->where('reparaciones.codigo_unico', 'LIKE', "%{$search}%")
                        ->orWhere('clientes.NombreCompleto', 'LIKE', "%{$search}%")
                        ->orWhere('reparaciones.equipo_descripcion', 'LIKE', "%{$search}%");
                });
            }
            if ($fecha_desde) $reparacionesQuery->where('reparaciones.fecha_ingreso', '>=', $fecha_desde);
            if ($fecha_hasta) $reparacionesQuery->where('reparaciones.fecha_ingreso', '<=', $fecha_hasta);
            if ($estado) $reparacionesQuery->where('reparaciones.estado_reparacion', $estado);
        }

        $historial = $query->orderBy('fecha', 'desc')->paginate(15)->withQueryString();

        // ============= HISTORIAL DE PRODUCTOS =============
        $searchProductos = $request->get('search_productos');
        $stock_filter = $request->get('stock_filter');

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
                    ->orWhere('productos.codigo', 'LIKE', "%{$searchProductos}%");
            });
        }

        if ($stock_filter === 'bajo') {
            $productosQuery->where('productos.stock', '<=', 5);
        } elseif ($stock_filter === 'sin') {
            $productosQuery->where('productos.stock', '<=', 0);
        }

        $productos = $productosQuery->orderBy('productos.created_at', 'desc')
            ->paginate(10, ['*'], 'productos_page')
            ->withQueryString();

        // ============= HISTORIAL DE SERVICIOS =============
        $searchServicios = $request->get('search_servicios');
        $activo_filter = $request->get('activo_filter');

        $serviciosQuery = DB::table('servicios');

        if ($searchServicios) {
            $serviciosQuery->where(function ($q) use ($searchServicios) {
                $q->where('nombre', 'LIKE', "%{$searchServicios}%");
            });
        }

        if ($activo_filter !== null && $activo_filter !== '') {
            $serviciosQuery->where('activo', $activo_filter);
        }

        $servicios = $serviciosQuery->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'servicios_page')
            ->withQueryString();

        // ============= GRÁFICOS DE REPARACIONES (ÚLTIMOS 2 MESES) =============
        $reparacionesUltimos2Meses = DB::table('reparaciones')
            ->select(
                DB::raw('DATE_FORMAT(fecha_ingreso, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN estado_reparacion = "Entregado" THEN 1 ELSE 0 END) as entregadas')
            )
            ->where('fecha_ingreso', '>=', Carbon::now()->subMonths(2)->startOfMonth())
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

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

        $ventasMeses = $ventasUltimos2Meses->pluck('mes')->map(function ($mes) {
            $parts = explode('-', $mes);
            return Carbon::create($parts[0], $parts[1])->translatedFormat('M Y');
        });
        $ventasCreadas = $ventasUltimos2Meses->pluck('total');
        $ventasPagadas = $ventasUltimos2Meses->pluck('pagadas');
        $ventasPendientes = $ventasUltimos2Meses->pluck('pendientes');

        // ============= ESTADÍSTICAS GENERALES =============
        $estadisticas = [
            'total_ventas' => DB::table('ventas')->where('visible', 1)->count(),
            'total_reparaciones' => DB::table('reparaciones')->count(),
            'ventas_pendientes' => DB::table('ventas')->where('visible', 1)->where('estado_venta', 'Pendiente')->count(),
            'reparaciones_pendientes' => DB::table('reparaciones')->where('estado_reparacion', 'Pendiente')->count(),
            'total_productos' => $totalProducts,
            'total_servicios' => DB::table('servicios')->count(),
        ];

        return view('admin.graficos.index', compact(
            // Productos
            'totalProducts',
            'totalStock',
            'productsOutOfStock',
            'lowStockProducts',
            'visibleProducts',
            'invisibleProducts',
            'totalInventoryValue',
            'totalProfit',
            'productsByCategory',
            'productsByMarca',
            'productsByMonth',

            // Clientes
            'totalClientes',
            'recentClientes',

            // Ventas
            'totalVentasDia',
            'totalVentasMes',
            'totalVentasMesImporte',
            'recentVentas',

            // Historial ventas/reparaciones
            'historial',
            'search',
            'tipo',
            'fecha_desde',
            'fecha_hasta',
            'estado',

            // Historial productos
            'productos',
            'searchProductos',
            'stock_filter',

            // Historial servicios
            'servicios',
            'searchServicios',
            'activo_filter',

            // Estadísticas
            'estadisticas',

            // Datos gráficos
            'reparacionesMeses',
            'reparacionesIngresadas',
            'reparacionesEntregadas',
            'ventasMeses',
            'ventasCreadas',
            'ventasPagadas',
            'ventasPendientes'
        ));
    }
}
