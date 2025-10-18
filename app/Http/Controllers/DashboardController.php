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
    public function index()
    {
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

        // Totales de ventas del mes
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
            ///////////// ventas de hoy
            'totalVentas',
            'totalVentasDia',
            ///////////// mes actual
            'totalVentasMes',
            'totalVentasMesImporte',
            // ventas recientes
            'recentlyUpdatedVentas',
            'recentVentas'

        ));
    }
}
