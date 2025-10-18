<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Servicio;
use App\Models\ReparacionServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReparacionServicioController extends Controller
{
    /**
     * Lista todas las reparaciones con sus servicios asociados.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $reparacionFiltro = $request->query('reparacion');
        $codigoUnico = $request->query('codigo_unico'); // nuevo filtro
        $serviciosFiltroSeleccionados = $request->query('servicios', []);

        $query = Reparacion::with('servicios');

        if ($search) {
            $query->whereHas('servicios', function ($q) use ($search) {
                $q->where('servicios.nombre', 'LIKE', "%{$search}%");
                $q->orWhere('codigo_unico', 'LIKE', "%{$search}%");
            });
        }

        if ($reparacionFiltro) {
            $query->where('id', $reparacionFiltro);
        }

        if (!empty($serviciosFiltroSeleccionados)) {
            foreach ($serviciosFiltroSeleccionados as $servicioId) {
                $query->whereHas('servicios', function ($q) use ($servicioId) {
                    $q->where('servicios.id', $servicioId); // tabla especificada
                });
            }
        }

        // Buscar por código único de la reparación
        if ($codigoUnico) {
            $query->where('codigo_unico', 'LIKE', "%{$codigoUnico}%");
        }

        $reparaciones = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();
        $reparacionesFiltro = Reparacion::all();
        $serviciosFiltro = Servicio::all();

        return view('admin.reparacionservicios.index', compact(
            'reparaciones',
            'reparacionesFiltro',
            'serviciosFiltro',
            'search',
            'reparacionFiltro',
            'codigoUnico',
            'serviciosFiltroSeleccionados'
        ));
    }


    /**
     * Muestra el formulario para crear un nuevo registro de la tabla pivote.
     */
    public function create()
    {
        $reparaciones = Reparacion::all();
        $servicios = Servicio::all();
        return view('admin.reparacionservicios.create', compact('reparaciones', 'servicios'));
    }

    /**
     * Almacena registros en la tabla pivote.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reparacion_id' => 'required|exists:reparaciones,id',
            'servicios' => 'required|array|min:1',
            'servicios.*.servicio_id' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.precio' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $reparacionId = $request->input('reparacion_id');

        foreach ($request->input('servicios') as $servicioData) {
            $servicio = Servicio::find($servicioData['servicio_id']);
            if (!$servicio) continue;

            $precioFinal = $servicioData['precio'] ?? $servicio->precio;

            ReparacionServicio::create([
                'reparacion_id' => $reparacionId,
                'servicio_id' => $servicioData['servicio_id'],
                'cantidad' => $servicioData['cantidad'],
                'precio' => $precioFinal,
            ]);
        }

        return redirect()->route('reparacionservicios.index')->with('success', 'Registros creados exitosamente.');
    }

    /**
     * Muestra el formulario para editar los servicios de una reparación.
     */
    public function edit(Reparacion $reparacion)
    {
        $servicios = Servicio::all();

        // Cargar los servicios asociados (para checkboxes marcados)
        $reparacion->load('servicios');

        return view('admin.reparacionservicios.edit', compact('reparacion', 'servicios'));
    }


    public function update(Request $request, Reparacion $reparacion)
    {
        $request->validate([
            'servicios.*.id' => [
                'nullable',
                Rule::exists('reparacion_servicio', 'id'),
            ],
            'servicios.*.servicio_id' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|numeric|min:1',
            'servicios.*.precio' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $reparacion) {
                $requestServicioIds = collect($request->servicios)->pluck('id')->filter()->all();
                $existingServicioIds = ReparacionServicio::where('reparacion_id', $reparacion->id)->pluck('id')->all();

                // Eliminar servicios que ya no están
                $toDelete = array_diff($existingServicioIds, $requestServicioIds);
                if (!empty($toDelete)) {
                    ReparacionServicio::destroy($toDelete);
                }

                foreach ($request->servicios as $item) {
                    $servicio = Servicio::find($item['servicio_id']);
                    if (!$servicio) continue;

                    $precioFinal = $item['precio'] ?? $servicio->precio;

                    $data = [
                        'reparacion_id' => $reparacion->id,
                        'servicio_id' => $item['servicio_id'],
                        'cantidad' => $item['cantidad'],
                        'precio' => $precioFinal,
                    ];

                    if (!empty($item['id'])) {
                        ReparacionServicio::where('id', $item['id'])->update($data);
                    } else {
                        ReparacionServicio::create($data);
                    }
                }
            });

            return redirect()->route('reparacionservicios.index')
                ->with('success', 'Servicios de la reparación actualizados correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar servicios de reparación: ' . $e->getMessage());
            return redirect()->route('reparacionservicios.index')
                ->with('error', 'Hubo un error al actualizar los servicios.');
        }
    }

    /**
     * Elimina un registro de la tabla pivote.
     */
    public function destroyPorReparacion(Reparacion $reparacion)
    {
        $reparacion->servicios()->detach(); // Elimina todos los registros pivote
        return redirect()->route('reparacionservicios.index')->with('success', 'Servicios eliminados');
    }
}
