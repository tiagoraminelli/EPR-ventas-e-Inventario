<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\ServicioVenta as DetalleServicio;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Product as Producto;
use App\Models\ServicioVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Se ha agregado la importación del Facade Log
use PDF; // Asegúrate de tener esta línea para usar la librería de PDF
use Maatwebsite\Excel\Facades\Excel;
use carbon\Carbon;


class VentaController extends Controller
{
    /**
     * Muestra una lista de todas las ventas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Venta::query();
        // solo las que tienen el campo visible = 1
        $query->where('visible', 1);

        // Filtro de búsqueda general
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('cliente', function ($subQuery) use ($searchTerm) {
                    $subQuery->where('NombreCompleto', 'like', $searchTerm);
                })
                    ->orWhere('numero_comprobante', 'like', $searchTerm)
                    ->orWhere('tipo_comprobante', 'like', $searchTerm)
                    ->orWhere('estado_venta', 'like', $searchTerm)
                    ->orWhere('fecha_venta', 'like', $searchTerm);
            });
        }

        // Filtro por estado de venta
        if ($request->filled('estado_venta')) {
            $query->where('estado_venta', $request->input('estado_venta'));
        }

        // Filtro por tipo de comprobante
        if ($request->filled('tipo_comprobante')) {
            $query->where('tipo_comprobante', $request->input('tipo_comprobante'));
        }

        // Filtro por condición de pago
        if ($request->filled('condicion_pago')) {
            $query->where('condicion_pago', $request->input('condicion_pago'));
        }

        if ($request->filled('fecha_venta') || $request->filled('fecha_venta_exacta')) {
            $fechaFiltro = $request->input('fecha_venta');
            $fechaExacta = $request->input('fecha_venta_exacta');

            if ($fechaExacta) {
                // Filtrar por fecha exacta
                $query->whereDate('fecha_venta', $fechaExacta);
            } else {
                // Filtros especiales
                switch ($fechaFiltro) {
                    case 'Hoy':
                        $query->whereDate('fecha_venta', Carbon::today('America/Argentina/Buenos_Aires'));
                        break;
                    case 'Ayer':
                        $query->whereDate('fecha_venta', Carbon::yesterday('America/Argentina/Buenos_Aires'));
                        break;
                    case 'Esta semana':
                        $query->whereBetween('fecha_venta', [
                            Carbon::now('America/Argentina/Buenos_Aires')->startOfWeek(),
                            Carbon::now('America/Argentina/Buenos_Aires')->endOfWeek()
                        ]);
                        break;
                    case 'Este mes':
                        $query->whereMonth('fecha_venta', Carbon::now('America/Argentina/Buenos_Aires')->month)
                            ->whereYear('fecha_venta', Carbon::now('America/Argentina/Buenos_Aires')->year);
                        break;
                }
            }
        }


        $ventas = $query->with('cliente')->latest()->paginate(10)->withQueryString();

        return view('admin.ventas.index', compact('ventas'));
    }


    /**
     * Muestra el formulario para crear una nueva venta.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Ahora estos modelos son reconocidos gracias a las sentencias 'use' y ordenadas ASC por nombre
        $servicios = Servicio::all()->sortBy('Nombre', SORT_NATURAL | SORT_FLAG_CASE);
        $clientes = Cliente::all()->sortBy('NombreCompleto', SORT_NATURAL | SORT_FLAG_CASE);
        $productos = Producto::all()->sortBy('Nombre', SORT_NATURAL | SORT_FLAG_CASE);
        return view('admin.ventas.create', compact('clientes', 'productos', 'servicios'));
    }

    /**
     * Almacena una nueva venta y sus detalles.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validación de la venta, productos y servicios
        $request->validate(
            [
                'cliente_id' => 'required|exists:clientes,id',
                'tipo_comprobante' => 'required|string|max:50',
                'condicion_pago' => 'required|string|max:255',
                'estado_venta' => 'required|string|max:255',
                'observaciones' => 'nullable|string',
                'fecha_venta' => 'required|date',

                // Validación productos (opcional)
                'productos' => 'nullable|array|min:1',
                'productos.*.id' => 'required_with:productos|exists:productos,id',
                'productos.*.cantidad' => 'required_with:productos|integer|min:1',
                'productos.*.precio_unitario' => 'required_with:productos|numeric|min:0',
                'productos.*.descuento' => 'nullable|numeric|min:0',

                // Validación servicios (opcional)
                'servicios' => 'nullable|array',
                'servicios.*.servicio_id' => 'required_with:servicios|exists:servicios,id',
                'servicios.*.cantidad' => 'required_with:servicios|integer|min:1',
                'servicios.*.precio' => 'required_with:servicios|numeric|min:0',
            ],  // mensajes
            [
                // errores personalizados de validación
                'cliente_id.required' => 'El cliente es obligatorio.',
                'cliente_id.exists' => 'El cliente seleccionado no existe.',
                'tipo_comprobante.required' => 'El tipo de comprobante es obligatorio.',
                'tipo_comprobante.string' => 'El tipo de comprobante debe ser un texto.',
                'tipo_comprobante.max' => 'El tipo de comprobante no puede superar los 50 caracteres.',
                'condicion_pago.required' => 'La condición de pago es obligatoria.',
                'condicion_pago.string' => 'La condición de pago debe ser un texto.',
                'condicion_pago.max' => 'La condición de pago no puede superar los 255 caracteres.',
                'estado_venta.required' => 'El estado de la venta es obligatorio.',
                'estado_venta.string' => 'El estado de la venta debe ser un texto.',
                'estado_venta.max' => 'El estado de la venta no puede superar los 255 caracteres.',
                'fecha_venta.required' => 'La fecha de venta es obligatoria.',
                'fecha_venta.date' => 'La fecha de venta no es válida.',

                // Productos
                'productos.array' => 'Los productos deben ser un arreglo.',
                'productos.*.id.required_with' => 'El producto seleccionado es obligatorio.',
                'productos.*.id.exists' => 'El producto seleccionado no existe.',
                'productos.*.cantidad.required_with' => 'La cantidad del producto es obligatoria.',
                'productos.*.cantidad.integer' => 'La cantidad del producto debe ser un número entero.',
                'productos.*.cantidad.min' => 'La cantidad del producto debe ser al menos 1.',
                'productos.*.precio_unitario.required_with' => 'El precio unitario del producto es obligatorio.',
                'productos.*.precio_unitario.numeric' => 'El precio unitario del producto debe ser un número.',
                'productos.*.precio_unitario.min' => 'El precio unitario del producto no puede ser negativo.',
                'productos.*.descuento.numeric' => 'El descuento del producto debe ser un número.',
                'productos.*.descuento.min' => 'El descuento del producto no puede ser negativo.',

                // Servicios
                'servicios.array' => 'Los servicios deben ser un arreglo.',
                'servicios.*.servicio_id.required_with' => 'El servicio seleccionado es obligatorio.',
                'servicios.*.servicio_id.exists' => 'El servicio seleccionado no existe.',
                'servicios.*.cantidad.required_with' => 'La cantidad del servicio es obligatoria.',
                'servicios.*.cantidad.integer' => 'La cantidad del servicio debe ser un número entero.',
                'servicios.*.cantidad.min' => 'La cantidad del servicio debe ser al menos 1.',
                'servicios.*.precio.required_with' => 'El precio del servicio es obligatorio.',
                'servicios.*.precio.numeric' => 'El precio del servicio debe ser un número.',
                'servicios.*.precio.min' => 'El precio del servicio no puede ser negativo.',
            ]
        );

        $importeNeto = 0;

        // 1.1 Generar el número de comprobante de forma dinámica
        //Generar número de comprobante dinámico
        $tipo = strtoupper(substr($request->tipo_comprobante, 0, 2));
        $condicion = strtoupper(substr($request->condicion_pago, 0, 2));
        $fechaHora = Carbon::now()->format('YmdHis'); // YYYYMMDDHHMMSS
        $clienteId = str_pad($request->cliente_id, 2, '0', STR_PAD_LEFT);
        $numeroComprobante = $tipo . $condicion . $fechaHora . $clienteId;


        // dd($request->all()); // mi mejor amigo
        // 2. Calcular total de productos
        if ($request->has('productos')) {
            foreach ($request->productos as $productoData) {
                $subtotalSinDescuento = $productoData['cantidad'] * $productoData['precio_unitario'];
                $descuentoPorcentaje = $productoData['descuento'] ?? 0;
                $montoDescuento = ($subtotalSinDescuento * $descuentoPorcentaje) / 100;
                $subtotalConDescuento = $subtotalSinDescuento - $montoDescuento;
                $importeNeto += $subtotalConDescuento;
            }
        }

        // 3. Calcular total de servicios
        if ($request->has('servicios')) {
            foreach ($request->servicios as $servicioData) {
                $subtotalServicio = $servicioData['cantidad'] * $servicioData['precio'];
                $importeNeto += $subtotalServicio;
            }
        }

        $importeIVA = 0; // Por ahora, no se calcula el IVA
        $importeTotal = $importeNeto;

        DB::beginTransaction();

        try {
            // 4. Crear la venta principal
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'tipo_comprobante' => $request->tipo_comprobante,
                'numero_comprobante' => $numeroComprobante,
                'condicion_pago' => $request->condicion_pago,
                'estado_venta' => $request->estado_venta,
                'observaciones' => $request->observaciones,
                'fecha_venta' => $request->fecha_venta,
                'importe_neto' => $importeNeto,
                'importe_iva' => $importeIVA,
                'importe_total' => $importeTotal,
            ]);

            // 5. Guardar productos y actualizar stock
            if ($request->has('productos')) {
                foreach ($request->productos as $productoData) {
                    $subtotalSinDescuento = $productoData['cantidad'] * $productoData['precio_unitario'];
                    $descuentoPorcentaje = $productoData['descuento'] ?? 0;
                    $montoDescuento = ($subtotalSinDescuento * $descuentoPorcentaje) / 100;
                    $subtotalConDescuento = $subtotalSinDescuento - $montoDescuento;
                    $cantidad = $productoData['cantidad'];

                    // if (in_array($request->tipo_comprobante, ['Nota de Débito', 'Nota de Crédito'])) {
                    //     $producto = Producto::find($productoData['id']);
                    //     if ($producto->stock < $cantidad) {
                    //         throw ValidationException::withMessages([
                    //             'productos' => "Stock insuficiente para el producto '{$producto->nombre}'. Disponible: {$producto->stock}, solicitado: {$cantidad}."
                    //         ]);
                    //     }
                    //     $producto->stock -= $cantidad;
                    //     $producto->save();
                    // }

                    DetalleVenta::create([
                        'venta_id' => $venta->id,
                        'producto_id' => $productoData['id'],
                        'cantidad' => $cantidad,
                        'precio_unitario' => $productoData['precio_unitario'],
                        'descuento' => $montoDescuento,
                        'subtotal' => $subtotalConDescuento,
                        'visible' => 1,
                    ]);
                }
            }

            // 6. Guardar servicios
            if ($request->has('servicios')) {
                foreach ($request->servicios as $servicioData) {
                    $subtotalServicio = $servicioData['cantidad'] * $servicioData['precio'];

                    DetalleServicio::create([
                        'venta_id' => $venta->id,
                        'servicio_id' => $servicioData['servicio_id'],
                        'cantidad' => $servicioData['cantidad'],
                        'precio' => $subtotalServicio,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('ventas.show', $venta->id)
                ->with('success', 'Venta creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }


    /**
     * Muestra los detalles de una venta específica.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show(Venta $venta)
    {
        $venta->load('cliente', 'detalleVentas.producto');
        return view('admin.ventas.show', compact('venta'));
    }

    /**
     * Muestra el formulario para editar una venta existente.
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        // Cargar relaciones necesarias incluyendo servicios
        $venta->load(['cliente', 'detalleVentas.producto.marca', 'servicios']);

        $clientes = Cliente::all()->sortBy('NombreCompleto', SORT_NATURAL | SORT_FLAG_CASE);
        $productos = Producto::all()->sortBy('Nombre', SORT_NATURAL | SORT_FLAG_CASE);
        $servicios = Servicio::all()->sortBy('Nombre', SORT_NATURAL | SORT_FLAG_CASE);

        return view('admin.ventas.edit', compact('venta', 'clientes', 'productos', 'servicios'));
    }

    /**
     * Actualiza una venta existente en la base de datos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Venta $venta)
    {
        // 1. Validación de datos (ajustada para hacer productos/servicios opcionales)
        $request->validate(
            [
                'cliente_id' => 'required|exists:clientes,id',
                'tipo_comprobante' => 'required|string|max:50',
                'numero_comprobante' => 'required|string|unique:ventas,numero_comprobante,' . $venta->id,
                'fecha_venta' => 'required|date',
                'condicion_pago' => 'required|string|max:255',
                'estado_venta' => 'required|string|max:255',
                'observaciones' => 'nullable|string',
                'visible' => 'nullable|boolean',

                // Validación productos (nullable para permitir solo servicios)
                'productos' => 'nullable|array',
                'productos.*.id' => 'required_with:productos|exists:productos,id',
                'productos.*.detalle_id' => 'nullable|exists:detalle_ventas,id',
                'productos.*.cantidad' => 'required_with:productos|integer|min:1',
                'productos.*.precio_unitario' => 'required_with:productos|numeric|min:0',
                'productos.*.descuento' => 'nullable|numeric|min:0',

                // Validación servicios (nullable para permitir solo productos)
                'servicios' => 'nullable|array',
                'servicios.*.servicio_id' => 'required_with:servicios|exists:servicios,id',
                'servicios.*.detalle_id' => 'nullable|exists:servicio_venta,id', // Asumiendo 'servicio_venta' es la tabla pivot
                'servicios.*.cantidad' => 'required_with:servicios|integer|min:1',
                'servicios.*.precio' => 'required_with:servicios|numeric|min:0',
            ]
        );

        try {
            DB::transaction(function () use ($request, $venta) {
                $importeNeto = 0;
                $importeIVA = 0;

                // 2. OBTENER IDS EXISTENTES (antes de procesar el request)
                $existingProductoDetalleIds = DetalleVenta::where('venta_id', $venta->id)->pluck('id')->toArray();
                $existingServicioDetalleIds = ServicioVenta::where('venta_id', $venta->id)->pluck('id')->toArray();

                // ===== PROCESAR PRODUCTOS (Mantiene la lógica original y es segura) =====
                $productosProcesados = [];
                $requestProductos = $request->productos ?? [];

                foreach ($requestProductos as $item) {
                    $subtotalSinDescuento = $item['cantidad'] * $item['precio_unitario'];
                    $descuentoPorcentaje = $item['descuento'] ?? 0;
                    $montoDescuento = ($subtotalSinDescuento * $descuentoPorcentaje) / 100;
                    $subtotalConDescuento = $subtotalSinDescuento - $montoDescuento;
                    $importeNeto += $subtotalConDescuento;

                    // Lógica para manejar el stock (si aplica)
                    // NOTA: La reversión de stock en la edición es compleja,
                    // se asume que el stock ya fue verificado o se manejará por separado.

                    $detalleData = [
                        'venta_id' => $venta->id,
                        'producto_id' => $item['id'], // 'id' es el producto_id
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'descuento' => $montoDescuento,
                        'subtotal' => $subtotalConDescuento,
                        'visible' => $request->visible ?? 0,
                    ];

                    if (!empty($item['detalle_id'])) {
                        DetalleVenta::where('id', $item['detalle_id'])->update($detalleData);
                        $productosProcesados[] = (int) $item['detalle_id'];
                    } else {
                        $nuevoDetalle = DetalleVenta::create($detalleData);
                        $productosProcesados[] = $nuevoDetalle->id;
                    }
                }

                // Eliminar productos no presentes en la solicitud
                $productosAEliminar = array_diff($existingProductoDetalleIds, $productosProcesados);
                if (!empty($productosAEliminar)) {
                    DetalleVenta::whereIn('id', $productosAEliminar)->delete();
                }

                // ===== PROCESAR SERVICIOS (Lógica Corregida) =====
                $serviciosProcesados = [];
                $requestServicios = $request->servicios ?? [];

                foreach ($requestServicios as $item) {
                    // Validamos que exista la ID del servicio
                    if (empty($item['servicio_id'])) continue;

                    $precioFinal = $item['precio'] ?? 0;
                    $subtotalServicio = $item['cantidad'] * $precioFinal;
                    $importeNeto += $subtotalServicio;

                    $servicioData = [
                        'venta_id' => $venta->id,
                        'servicio_id' => $item['servicio_id'],
                        'cantidad' => $item['cantidad'],
                        'precio' => $precioFinal,
                    ];

                    // Si tienes un campo subtotal en la tabla servicio_venta, descomenta la línea de abajo.
                    // $servicioData['subtotal'] = $subtotalServicio;

                    if (!empty($item['detalle_id'])) {
                        // Actualizar servicio existente (si viene con ID)
                        ServicioVenta::where('id', $item['detalle_id'])->update($servicioData);
                        $serviciosProcesados[] = (int) $item['detalle_id'];
                    } else {
                        // Crear nuevo servicio (si no tiene ID)
                        $nuevoServicio = ServicioVenta::create($servicioData);
                        $serviciosProcesados[] = $nuevoServicio->id;
                    }
                }

                // 3. Eliminar servicios no presentes en la solicitud (LA CLAVE DE LA SOLUCIÓN)
                $serviciosAEliminar = array_diff($existingServicioDetalleIds, $serviciosProcesados);
                if (!empty($serviciosAEliminar)) {
                    // Usamos whereIn para eliminar solo los IDs que ya no están en la lista de procesados
                    ServicioVenta::whereIn('id', $serviciosAEliminar)->delete();
                }
                // FIN: PROCESAR SERVICIOS

                // 4. ACTUALIZAR VENTA PRINCIPAL
                $venta->update([
                    'cliente_id' => $request->cliente_id,
                    'tipo_comprobante' => $request->tipo_comprobante,
                    'numero_comprobante' => $request->numero_comprobante,
                    'condicion_pago' => $request->condicion_pago,
                    'estado_venta' => $request->estado_venta,
                    'observaciones' => $request->observaciones,
                    'visible' => $request->visible ?? 0,
                    'importe_neto' => $importeNeto,
                    'importe_iva' => $importeIVA,
                    'importe_total' => $importeNeto + $importeIVA, // Calculo total
                    'fecha_venta' => $request->fecha_venta,
                ]);
            });

            return redirect()->route('ventas.index')->with('success', 'Venta actualizada exitosamente.');
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withInput()->withErrors($e->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar la venta: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'Error al actualizar la venta: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Marca una venta como no visible (eliminación lógica).
     *
     * @param  \App\Models\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Venta $venta)
    {
        try {
            $venta->update(['visible' => 0]);
            return redirect()->route('ventas.index')->with('success', 'Venta eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('ventas.index')->with('error', 'Error al eliminar la venta: ' . $e->getMessage());
        }
    }

    public function exportPdf(Venta $venta)
    {
        $venta->load('cliente', 'detalleVentas.producto', 'serviciosVentas.servicio');

        $pdf = Pdf::loadView('admin.ventas.pdf', compact('venta'))
            ->setPaper('a4', 'portrait');
        // informacion con la que se genera el pdf, incluyendo el Id, el NombreCompleto del cliente y el numero de comprobante
        return $pdf->stream('Venta_' . $venta->id . '_' . $venta->cliente->nombreCompleto . '_' . $venta->cliente->RazonSocial . '_' . $venta->tipo_comprobante . '_' . '_' . $venta->created_at . '_' . $venta->numero_comprobante . '.pdf');
    }

    public function exportExcel(Venta $venta)
    {
        $venta->load('cliente', 'detalleVentas.producto', 'serviciosVentas.servicio');

        return Excel::download(new \App\Exports\VentaExport($venta), 'Venta_' . $venta->id . '.xlsx');
    }
}
