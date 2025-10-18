<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\MarcaController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ServicioVentaController;
use App\Http\Controllers\ReparacionController;
use App\Http\Controllers\ReparacionServicioController;
use App\Http\Controllers\ReparacionProductoController;


Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('admin')->group(function () {
    // Rutas para el dashboard de productos
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Rutas para la API de marcas
    Route::get('marcas', [MarcaController::class, 'index'])->name('marcas.index');
    Route::get('marcas/create', [MarcaController::class, 'create'])->name('marcas.create');
    Route::post('marcas', [MarcaController::class, 'store'])->name('marcas.store');
    Route::get('marcas/{marca}/edit', [MarcaController::class, 'edit'])->name('marcas.edit');
    Route::put('marcas/{marca}', [MarcaController::class, 'update'])->name('marcas.update');
    Route::delete('marcas/{marca}', [MarcaController::class, 'destroy'])->name('marcas.destroy');

    // Rutas para la API de categorías
    Route::get('categories', [CategoryController::class, 'index'])->name('categorias.index');
    Route::get('categories/create', [CategoryController::class, 'create'])->name('categorias.create');
    Route::post('categories', [CategoryController::class, 'store'])->name('categorias.store');
    Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categorias.edit');
    Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categorias.update');
    Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categorias.destroy');

    // Otras rutas administrativas...
    Route::get('graficos', [DashboardController::class, 'index'])->name('admin.graficos');

    // Rutas para la gestión de clientes
    Route::get('clientes', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('clientes', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('clientes/{cliente}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');

    // Rutas para la gestión de ventas
    Route::get('ventas', [VentaController::class, 'index'])->name('ventas.index');
    Route::get('ventas/create', [VentaController::class, 'create'])->name('ventas.create');
    Route::post('ventas', [VentaController::class, 'store'])->name('ventas.store');
    Route::get('ventas/{venta}/edit', [VentaController::class, 'edit'])->name('ventas.edit');
    Route::put('ventas/{venta}', [VentaController::class, 'update'])->name('ventas.update');
    Route::delete('ventas/{venta}', [VentaController::class, 'destroy'])->name('ventas.destroy');
    Route::get('ventas/{venta}', [VentaController::class, 'show'])->name('ventas.show');

    // Rutas para la exportación de ventas
    // web.php
    Route::get('ventas/{venta}/export-pdf', [VentaController::class, 'exportPdf'])->name('ventas.export.pdf');
    Route::get('ventas/{venta}/export-excel', [VentaController::class, 'exportExcel'])->name('ventas.export.excel');

    // Rutas para la gestión de servicios
    Route::get('servicios', [ServicioController::class, 'index'])->name('servicios.index');
    Route::get('servicios/create', [ServicioController::class, 'create'])->name('servicios.create');
    Route::post('servicios', [ServicioController::class, 'store'])->name('servicios.store');
    Route::get('servicios/{servicio}/edit', [ServicioController::class, 'edit'])->name('servicios.edit');
    Route::put('servicios/{servicio}', [ServicioController::class, 'update'])->name('servicios.update');
    Route::delete('servicios/{servicio}', [ServicioController::class, 'destroy'])->name('servicios.destroy');

    // Rutas para la gestión de la tabla pivote de serviciosventascontroller
    Route::get('serviciosventas', [ServicioVentaController::class, 'index'])->name('serviciosventas.index');
    Route::get('serviciosventas/create', [ServicioVentaController::class, 'create'])->name('serviciosventas.create');
    Route::post('serviciosventas', [ServicioVentaController::class, 'store'])->name('serviciosventas.store');
    // Corregido: El nombre del parámetro ahora es 'venta' para consistencia
    Route::get('serviciosventas/{venta}/edit', [ServicioVentaController::class, 'edit'])->name('serviciosventas.edit');
    Route::put('serviciosventas/{venta}', [ServicioVentaController::class, 'update'])->name('serviciosventas.update');
    // Corregido: El nombre del parámetro ahora es 'venta' para consistencia
    Route::delete('serviciosventas/{venta}', [ServicioVentaController::class, 'destroy'])->name('serviciosventas.destroy');


    // Rutas de Reparaciones
    Route::get('reparaciones', [ReparacionController::class, 'index'])->name('reparaciones.index');
    Route::get('reparaciones/create', [ReparacionController::class, 'create'])->name('reparaciones.create');
    Route::post('reparaciones', [ReparacionController::class, 'store'])->name('reparaciones.store');
    Route::get('reparaciones/{reparacion}/edit', [ReparacionController::class, 'edit'])->name('reparaciones.edit');
    Route::put('reparaciones/{reparacion}', [ReparacionController::class, 'update'])->name('reparaciones.update');
    Route::get('reparaciones/{reparacion}', [ReparacionController::class, 'show'])->name('reparaciones.show');
    Route::delete('reparaciones/{reparacion}', [ReparacionController::class, 'destroy'])->name('reparaciones.destroy');

    // Rutas de Reparacion Servicios
    Route::prefix('reparacionservicios')->group(function () {

        Route::get('/', [ReparacionServicioController::class, 'index'])->name('reparacionservicios.index');

        Route::get('/create', [ReparacionServicioController::class, 'create'])->name('reparacionservicios.create');

        Route::post('/', [ReparacionServicioController::class, 'store'])->name('reparacionservicios.store');

        // Editar los servicios de una reparación
        Route::get('/{reparacion}/edit', [ReparacionServicioController::class, 'edit'])->name('reparacionservicios.edit');

        Route::put('/{reparacion}', [ReparacionServicioController::class, 'update'])->name('reparacionservicios.update');

        Route::get('/{reparacion}', [ReparacionServicioController::class, 'show'])->name('reparacionservicios.show');

        Route::delete('/{reparacion}', [ReparacionServicioController::class, 'destroyPorReparacion'])->name('reparacionservicios.destroy');
    });

    // ruta de reparacion con los productos asociados a ella
    // Rutas de Reparacion Productos
    Route::prefix('reparacionproductos')->group(function () {

        Route::get('/', [ReparacionProductoController::class, 'index'])->name('reparacionproductos.index');

        Route::get('/create', [ReparacionProductoController::class, 'create'])->name('reparacionproductos.create');

        Route::post('/', [ReparacionProductoController::class, 'store'])->name('reparacionproductos.store');

        // Editar los productos de una reparación
        Route::get('/{reparacion}/edit', [ReparacionProductoController::class, 'edit'])->name('reparacionproductos.edit');

        Route::put('/{reparacion}', [ReparacionProductoController::class, 'update'])->name('reparacionproductos.update');
        // Exportar PDF
        Route::get('/{reparacion}/export-pdf', [ReparacionController::class, 'exportPdf'])->name('reparaciones.export.pdf');

        Route::get('/{reparacion}', [ReparacionProductoController::class, 'show'])->name('reparacionproductos.show');

        Route::delete('/{reparacion}', [ReparacionProductoController::class, 'destroyPorReparacion'])->name('reparacionproductos.destroy');
    });
});
