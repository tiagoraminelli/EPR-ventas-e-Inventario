<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Cliente;
use App\Models\Product as Producto;
use App\Models\Servicio;
use App\Models\ReparacionProducto;
use App\Models\ReparacionServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

use PDF;

class ReparacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Reparacion::with('cliente');

        // Estado
        if ($request->filled('estado_reparacion')) {
            $query->where('estado_reparacion', $request->estado_reparacion);
        }

        // Reparable
        if ($request->filled('reparable')) {
            $query->where('reparable', $request->reparable);
        }

        // Fecha
        if ($request->filled('fecha_ingreso')) {
            $query->whereDate('fecha_ingreso', $request->fecha_ingreso);
        }

        // Búsqueda general
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigo_unico', 'like', "%{$search}%")
                    ->orWhere('equipo_descripcion', 'like', "%{$search}%")
                    ->orWhere('equipo_marca', 'like', "%{$search}%")
                    ->orWhere('equipo_modelo', 'like', "%{$search}%");
            });
        }

        $reparaciones = $query
            ->orderBy('fecha_ingreso', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $clientes = Cliente::orderBy('NombreCompleto')->get();

        return view('admin.reparaciones.index', compact('reparaciones', 'clientes'));
    }

    /**
     * Muestra el formulario de creación con productos y servicios disponibles
     */
    public function create()
    {
        $clientes = Cliente::all()->sortBy('NombreCompleto', SORT_NATURAL | SORT_FLAG_CASE);
        $productos = Producto::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);
        $servicios = Servicio::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);

        return view('admin.reparaciones.create', compact('clientes', 'productos', 'servicios'));
    }

    /**
     * Guarda una nueva reparación con sus productos y servicios
     */
    public function store(Request $request)
    {
        // Validación completa incluyendo productos y servicios
        $validator = Validator::make($request->all(), [
            // Campos de reparación
            'codigo_unico' => 'nullable|string|max:50|unique:reparaciones,codigo_unico',
            'cliente_id' => 'required|exists:clientes,id',
            'equipo_descripcion' => 'required|string|min:3',
            'equipo_marca' => 'required|string|min:2',
            'equipo_modelo' => 'required|string|min:2',
            'descripcion_danio' => 'required|string|min:5',
            'solucion_aplicada' => 'nullable|string|min:5',
            'reparable' => 'required|boolean',
            'estado_reparacion' => 'required|string|in:Pendiente,En proceso,Reparado,No reparable,Entregado',
            'fecha_ingreso' => 'required|date',
            'fecha_egreso' => 'nullable|date|after_or_equal:fecha_ingreso',
            'costo_total' => 'nullable|numeric|min:0',

            // Productos (opcional)
            'productos' => 'nullable|array',
            'productos.*.id' => 'required_with:productos|exists:productos,id',
            'productos.*.cantidad' => 'required_with:productos|integer|min:1',
            'productos.*.precio_unitario' => 'required_with:productos|numeric|min:0',
            'productos.*.descuento' => 'nullable|numeric|min:0|max:100',

            // Servicios (opcional)
            'servicios' => 'nullable|array',
            'servicios.*.servicio_id' => 'required_with:servicios|exists:servicios,id',
            'servicios.*.cantidad' => 'required_with:servicios|integer|min:1',
            'servicios.*.precio' => 'required_with:servicios|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Obtener los datos validados ANTES de la transacción
            $validatedData = $validator->validated();

            DB::beginTransaction();

            // Generar código único si no viene
            if (empty($validatedData['codigo_unico'])) {
                $validatedData['codigo_unico'] = 'REP-' . now()->format('YmdHis');
            }

            // Establecer costo_total inicial
            $validatedData['costo_total'] = 0;

            // Crear la reparación
            $reparacion = Reparacion::create($validatedData);

            $costoTotal = 0;

            // ===== PROCESAR PRODUCTOS =====
            $requestProductos = $request->productos ?? [];

            foreach ($requestProductos as $item) {
                $subtotalSinDescuento = $item['cantidad'] * $item['precio_unitario'];
                $descuentoPorcentaje = $item['descuento'] ?? 0;
                $montoDescuento = ($subtotalSinDescuento * $descuentoPorcentaje) / 100;
                $subtotalConDescuento = $subtotalSinDescuento - $montoDescuento;

                $costoTotal += $subtotalConDescuento;

                // Crear detalle de producto
                ReparacionProducto::create([
                    'reparacion_id' => $reparacion->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio_unitario'],
                    'descuento' => $montoDescuento,
                    'subtotal' => $subtotalConDescuento,
                ]);

                // Actualizar stock del producto
                $producto = Producto::find($item['id']);
                if ($producto) {
                    $producto->stock -= $item['cantidad'];
                    $producto->save();
                }
            }

            // ===== PROCESAR SERVICIOS =====
            $requestServicios = $request->servicios ?? [];

            foreach ($requestServicios as $item) {
                $subtotalServicio = $item['cantidad'] * $item['precio'];
                $costoTotal += $subtotalServicio;

                // Crear detalle de servicio
                ReparacionServicio::create([
                    'reparacion_id' => $reparacion->id,
                    'servicio_id' => $item['servicio_id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $subtotalServicio,
                ]);
            }

            // Actualizar costo total de la reparación
            $reparacion->update(['costo_total' => $costoTotal]);

            DB::commit();

            return redirect()->route('reparaciones.index')
                ->with('success', 'Reparación creada correctamente con productos y servicios.');

        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear reparación: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withErrors(['error' => 'Error al crear la reparación: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Muestra el formulario de edición con productos y servicios
     */
    public function edit(Reparacion $reparacion)
    {
        // Cargar relaciones necesarias
        $reparacion->load(['cliente', 'reparacionProductos.producto', 'reparacionServicios.servicio']);

        $clientes = Cliente::all()->sortBy('NombreCompleto', SORT_NATURAL | SORT_FLAG_CASE);
        $productos = Producto::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);
        $servicios = Servicio::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);

        return view('admin.reparaciones.edit', compact('reparacion', 'clientes', 'productos', 'servicios'));
    }

    /**
     * Actualiza una reparación con sus productos y servicios
     */
 public function update(Request $request, $id)
{
    $reparacion = Reparacion::findOrFail($id);

    // Validación completa incluyendo productos y servicios
    $validator = Validator::make($request->all(), [
        // Campos de reparación
        'codigo_unico' => 'required|string|max:255|unique:reparaciones,codigo_unico,' . $reparacion->id,
        'cliente_id' => 'required|exists:clientes,id',
        'equipo_descripcion' => 'required|string|max:255',
        'equipo_marca' => 'required|string|max:255',
        'equipo_modelo' => 'required|string|max:255',
        'descripcion_danio' => 'required|string|min:5',
        'solucion_aplicada' => 'nullable|string|min:5',
        'reparable' => 'required|boolean',
        'estado_reparacion' => 'required|string|max:50',
        'fecha_ingreso' => 'required|date',
        'fecha_egreso' => 'nullable|date|after_or_equal:fecha_ingreso',
        'costo_total' => 'nullable|numeric|min:0',

        // Productos (opcional)
        'productos' => 'nullable|array',
        'productos.*.detalle_id' => 'nullable|exists:reparacion_producto,id',
        'productos.*.id' => 'required_with:productos|exists:productos,id',
        'productos.*.cantidad' => 'required_with:productos|integer|min:1',
        'productos.*.precio_unitario' => 'required_with:productos|numeric|min:0',
        'productos.*.descuento' => 'nullable|numeric|min:0|max:100',

        // Servicios (opcional) - CORREGIDO: 'reparacion_servicio' en singular
        'servicios' => 'nullable|array',
        'servicios.*.detalle_id' => 'nullable|exists:reparacion_servicio,id', // ✅ Cambiado de 'reparacion_servicios' a 'reparacion_servicio'
        'servicios.*.servicio_id' => 'required_with:servicios|exists:servicios,id',
        'servicios.*.cantidad' => 'required_with:servicios|integer|min:1',
        'servicios.*.precio' => 'required_with:servicios|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    try {
        // Obtener los datos validados ANTES de la transacción
        $validatedData = $validator->validated();

        DB::beginTransaction();

        // Obtener IDs existentes antes de procesar
        $existingProductoDetalleIds = ReparacionProducto::where('reparacion_id', $reparacion->id)
            ->pluck('id')
            ->toArray();
        $existingServicioDetalleIds = ReparacionServicio::where('reparacion_id', $reparacion->id) // ✅ Esto ya usa el modelo correcto
            ->pluck('id')
            ->toArray();

        $costoTotal = 0;
        $productosProcesados = [];
        $serviciosProcesados = [];

        // ===== PROCESAR PRODUCTOS =====
        $requestProductos = $request->productos ?? [];

        foreach ($requestProductos as $item) {
            $subtotalSinDescuento = $item['cantidad'] * $item['precio_unitario'];
            $descuentoPorcentaje = $item['descuento'] ?? 0;
            $montoDescuento = ($subtotalSinDescuento * $descuentoPorcentaje) / 100;
            $subtotalConDescuento = $subtotalSinDescuento - $montoDescuento;


            $costoTotal += $subtotalConDescuento;

            $detalleData = [
                'reparacion_id' => $reparacion->id,
                'producto_id' => $item['id'],
                'cantidad' => $item['cantidad'],
                'precio' => $subtotalConDescuento
            ];

            if (!empty($item['detalle_id'])) {
                // Actualizar producto existente
                ReparacionProducto::where('id', $item['detalle_id'])->update($detalleData);
                $productosProcesados[] = (int) $item['detalle_id'];
            } else {
                // Crear nuevo producto
                $nuevoDetalle = ReparacionProducto::create($detalleData);
                $productosProcesados[] = $nuevoDetalle->id;
            }
        }

        // Eliminar productos no presentes
        $productosAEliminar = array_diff($existingProductoDetalleIds, $productosProcesados);
        if (!empty($productosAEliminar)) {
            ReparacionProducto::whereIn('id', $productosAEliminar)->delete();
        }

        // ===== PROCESAR SERVICIOS =====
        $requestServicios = $request->servicios ?? [];

        foreach ($requestServicios as $item) {
            $subtotalServicio = $item['cantidad'] * $item['precio'];
            $costoTotal += $subtotalServicio;

            $servicioData = [
                'reparacion_id' => $reparacion->id,
                'servicio_id' => $item['servicio_id'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio']
            ];

            if (!empty($item['detalle_id'])) {
                // Actualizar servicio existente
                ReparacionServicio::where('id', $item['detalle_id'])->update($servicioData);
                $serviciosProcesados[] = (int) $item['detalle_id'];
            } else {
                // Crear nuevo servicio
                $nuevoServicio = ReparacionServicio::create($servicioData);
                $serviciosProcesados[] = $nuevoServicio->id;
            }
        }

        // Eliminar servicios no presentes
        $serviciosAEliminar = array_diff($existingServicioDetalleIds, $serviciosProcesados);
        if (!empty($serviciosAEliminar)) {
            ReparacionServicio::whereIn('id', $serviciosAEliminar)->delete();
        }

        // Actualizar datos de la reparación
        $validatedData['costo_total'] = $costoTotal;
        $reparacion->update($validatedData);

        DB::commit();

        return redirect()->route('reparaciones.index')
            ->with('success', 'Reparación actualizada exitosamente con productos y servicios.');

    } catch (ValidationException $e) {
        DB::rollBack();
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar reparación: ' . $e->getMessage(), [
            'exception' => $e,
            'trace' => $e->getTraceAsString()
        ]);
        return redirect()->back()
            ->withErrors(['error' => 'Error al actualizar la reparación: ' . $e->getMessage()])
            ->withInput();
    }
}

    /**
     * Muestra los detalles de una reparación
     */
    public function show(Reparacion $reparacion)
    {
        $reparacion->load(['cliente', 'reparacionProductos.producto', 'reparacionServicios.servicio']);
        return view('admin.reparaciones.show', compact('reparacion'));
    }

    /**
     * Elimina una reparación y sus detalles asociados
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $reparacion = Reparacion::findOrFail($id);

            // Restaurar stock de productos
            foreach ($reparacion->reparacionProductos as $detalle) {
                $producto = Producto::find($detalle->producto_id);
                if ($producto) {
                    $producto->stock += $detalle->cantidad;
                    $producto->save();
                }
            }

            // Eliminar detalles y la reparación
            $reparacion->reparacionProductos()->delete();
            $reparacion->reparacionServicios()->delete();
            $reparacion->delete();

            DB::commit();

            return redirect()->route('reparaciones.index')
                ->with('success', 'Reparación eliminada correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar reparación: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('reparaciones.index')
                ->with('error', 'Error al eliminar la reparación: ' . $e->getMessage());
        }
    }

    /**
     * Exporta una reparación a PDF
     */
 public function exportPdf($id)
{
    $reparacion = Reparacion::with([
        'cliente',
        'reparacionProductos.producto',
        'reparacionServicios.servicio'
    ])->findOrFail($id);

    $pdf = PDF::loadView('admin.reparaciones.pdf', compact('reparacion'))
        ->setPaper('a4', 'portrait');

    // ==============================
    // Generación del nombre archivo
    // ==============================

    $cliente = $reparacion->cliente;

    // Razón Social o Nombre Completo
    $nombreCliente = $cliente->RazonSocial
        ?? $cliente->NombreCompleto
        ?? 'SinCliente';

    // Fecha creación formateada
    $fecha = Carbon::parse($reparacion->created_at)->format('Y-m-d');

    // Estado sin espacios
    $estado = Str::slug($reparacion->estado_reparacion, '-');

    // Construcción base
    $fileName = "{$reparacion->codigo_unico} - {$nombreCliente} - {$reparacion->id} - {$fecha} - {$estado}";

    // Limpiar caracteres no permitidos
    $fileName = Str::slug($fileName, '_');

    return $pdf->stream($fileName . '.pdf');
}
}
