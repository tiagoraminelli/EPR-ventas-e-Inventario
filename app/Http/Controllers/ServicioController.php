<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ServicioController extends Controller
{
    /**
     * Muestra una lista de todos los servicios.
     */
    public function index(Request $request)
    {
        $query = Servicio::query();

        // Filtro de búsqueda por nombre o descripción
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nombre', 'like', $searchTerm)
                  ->orWhere('descripcion', 'like', $searchTerm);
            });
        }

        // Filtro por estado activo
        if ($request->filled('activo')) {
            $query->where('activo', $request->input('activo'));
        }

        $servicios = $query->latest()->paginate(10)->withQueryString();

        return view('admin.servicios.index', compact('servicios'));
    }

    /**
     * Muestra el formulario para crear un nuevo servicio.
     */
    public function create()
    {
        return view('admin.servicios.create');
    }

    /**
     * Almacena un nuevo servicio en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'iva_aplicable' => 'nullable|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
            'visible' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            Servicio::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'iva_aplicable' => $request->iva_aplicable ?? 0,
                'precio' => $request->precio,
                'activo' => $request->activo ?? true,
                'visible' => $request->visible ?? true,
            ]);

            DB::commit();

            return redirect()->route('servicios.index')->with('success', 'Servicio creado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear servicio: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->withErrors(['message' => 'Ocurrió un error al guardar el servicio.']);
        }
    }

    /**
     * Muestra un servicio específico.
     */
    public function show(Servicio $servicio)
    {

        return view('admin.servicios.show', compact('servicio'));
    }

    /**
     * Muestra el formulario para editar un servicio existente.
     */
    public function edit(Servicio $servicio)
    {
        $servicio = Servicio::findOrFail($servicio->id);

        return view('admin.servicios.edit', compact('servicio'));
    }

    /**
     * Actualiza un servicio existente en la base de datos.
     */
    public function update(Request $request, Servicio $servicio)
    {
        $servicio = Servicio::findOrFail($servicio->id);
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'iva_aplicable' => 'nullable|numeric|min:0',
            'precio' => 'required|numeric|min:0',
            'activo' => 'nullable|boolean',
            'visible' => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {
            $servicio->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'iva_aplicable' => $request->iva_aplicable ?? 0,
                'precio' => $request->precio,
                'activo' => $request->activo ?? true,
                'visible' => $request->visible ?? true,
            ]);

            DB::commit();


            return redirect()->route('servicios.index')->with('success', 'Servicio actualizado correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar servicio: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->withInput()->withErrors(['message' => 'Ocurrió un error al actualizar el servicio.']);
        }
    }

    /**
     * Marca un servicio como no visible (eliminación lógica).
     */
    public function destroy(Servicio $servicio)
    {
        try {
            $servicio->update(['visible' => 0]);
            return redirect()->route('servicios.index')->with('success', 'Servicio eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error al eliminar servicio: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('servicios.index')->with('error', 'Ocurrió un error al eliminar el servicio.');
        }
    }
}
