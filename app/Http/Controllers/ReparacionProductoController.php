<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Product as Producto;
use App\Models\ReparacionProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReparacionProductoController extends Controller
{
    /**
     * Lista todas las reparaciones con sus productos asociados.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $reparacionFiltro = $request->query('reparacion');
        $codigoUnico = $request->query('codigo_unico');
        $productosFiltroSeleccionados = $request->query('productos', []);

        $query = Reparacion::with('productos');

        if ($search) {
            $query->whereHas('productos', function ($q) use ($search) {
                $q->where('productos.nombre', 'LIKE', "%{$search}%");
                $q->orWhere('codigo_unico', 'LIKE', "%{$search}%");
                $q->orWhere('reparaciones.equipo_descripcion', 'LIKE', "%{$search}%");
            });
        }



        if ($reparacionFiltro) {
            $query->where('id', $reparacionFiltro);
        }

        if (!empty($productosFiltroSeleccionados)) {
            foreach ($productosFiltroSeleccionados as $productoId) {
                $query->whereHas('productos', function ($q) use ($productoId) {
                    $q->where('productos.id', $productoId);
                });
            }
        }

        if ($codigoUnico) {
            $query->where('codigo_unico', 'LIKE', "%{$codigoUnico}%");
        }

        $reparaciones = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $reparacionesFiltro = Reparacion::all();
        $productosFiltro = Producto::all();

        return view('admin.reparacionproductos.index', compact(
            'reparaciones',
            'reparacionesFiltro',
            'productosFiltro',
            'search',
            'reparacionFiltro',
            'codigoUnico',
            'productosFiltroSeleccionados'
        ));
    }

    /**
     * Formulario para crear nuevo registro en la tabla pivote.
     */
    public function create()
    {
        $reparaciones = Reparacion::all();
        $productos = Producto::all();
        return view('admin.reparacionproductos.create', compact('reparaciones', 'productos'));
    }

    /**
     * Almacenar registros en la tabla pivote.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reparacion_id' => 'required|exists:reparaciones,id',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $reparacionId = $request->input('reparacion_id');

        foreach ($request->input('productos') as $productoData) {
            $producto = Producto::find($productoData['producto_id']);
            if (!$producto) continue;

            $precioFinal = $productoData['precio'] ?? $producto->precio;

            ReparacionProducto::create([
                'reparacion_id' => $reparacionId,
                'producto_id' => $productoData['producto_id'],
                'cantidad' => $productoData['cantidad'],
                'precio' => $precioFinal,
            ]);
        }

        return redirect()->route('reparacionproductos.index')->with('success', 'Productos agregados a la reparación correctamente.');
    }

    /**
     * Formulario para editar productos de una reparación.
     */
    public function edit(Reparacion $reparacion)
    {
        $productos = Producto::all();

        $reparacion->load('productos');

        return view('admin.reparacionproductos.edit', compact('reparacion', 'productos'));
    }

    /**
     * Actualiza productos de una reparación.
     */
    public function update(Request $request, Reparacion $reparacion)
    {
        $request->validate([
            'productos.*.id' => [
                'nullable',
                Rule::exists('reparacion_producto', 'id'),
            ],
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|numeric|min:1',
            'productos.*.precio' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $reparacion) {
                $requestProductoIds = collect($request->productos)->pluck('id')->filter()->all();
                $existingProductoIds = ReparacionProducto::where('reparacion_id', $reparacion->id)->pluck('id')->all();

                $toDelete = array_diff($existingProductoIds, $requestProductoIds);
                if (!empty($toDelete)) {
                    ReparacionProducto::destroy($toDelete);
                }

                foreach ($request->productos as $item) {
                    $producto = Producto::find($item['producto_id']);
                    if (!$producto) continue;

                    $precioFinal = $item['precio'] ?? $producto->precio;

                    $data = [
                        'reparacion_id' => $reparacion->id,
                        'producto_id' => $item['producto_id'],
                        'cantidad' => $item['cantidad'],
                        'precio' => $precioFinal,
                    ];

                    if (!empty($item['id'])) {
                        ReparacionProducto::where('id', $item['id'])->update($data);
                    } else {
                        ReparacionProducto::create($data);
                    }
                }
            });

            return redirect()->route('reparacionproductos.index')
                ->with('success', 'Productos de la reparación actualizados correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar productos de reparación: ' . $e->getMessage());
            return redirect()->route('reparacionproductos.index')
                ->with('error', 'Hubo un error al actualizar los productos.');
        }
    }

    /**
     * Elimina productos asociados a una reparación.
     */
    public function destroyPorReparacion(Reparacion $reparacion)
    {
        $reparacion->productos()->detach();
        return redirect()->route('reparacionproductos.index')->with('success', 'Productos eliminados.');
    }
}
