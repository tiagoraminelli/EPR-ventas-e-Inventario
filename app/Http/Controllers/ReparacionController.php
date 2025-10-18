<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PDF; // Asegúrate de tener esta línea para usar la librería de PDF

class ReparacionController extends Controller
{
    public function index(Request $request)
    {
        $query = Reparacion::with('cliente');

        // FILTRO: Estado de reparación
        if ($request->filled('estado_reparacion')) {
            $query->where('estado_reparacion', $request->estado_reparacion);
        }

        // FILTRO: Cliente
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->cliente_id);
        }

        // FILTRO: Reparable
        if ($request->filled('reparable')) {
            $query->where('reparable', $request->reparable);
        }

        // FILTRO: Fecha de ingreso
        if ($request->filled('fecha_ingreso')) {
            $query->whereDate('fecha_ingreso', $request->fecha_ingreso);
        }

        // FILTRO: Búsqueda general (código, equipo, descripción)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('codigo_unico', 'like', "%{$search}%")
                    ->orWhere('equipo_descripcion', 'like', "%{$search}%")
                    ->orWhere('equipo_marca', 'like', "%{$search}%")
                    ->orWhere('equipo_modelo', 'like', "%{$search}%");
            });
        }

        // Obtener resultados ordenados por fecha de ingreso
        $reparaciones = $query->orderBy('fecha_ingreso', 'desc')->get();

        // Clientes para el select
        $clientes = Cliente::all();

        return view('admin.reparaciones.index', compact('reparaciones', 'clientes'));
    }

    // Método para mostrar el formulario de creación
    public function create()
    {
        $clientes = Cliente::all(); // Para el select de clientes
        return view('admin.reparaciones.create', compact('clientes'));
    }

    // Método para guardar una nueva reparación
    public function store(Request $request)
    {
        // Reglas de validación
        $validator = Validator::make($request->all(), [
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
        ]);

        // Si la validación falla, redirige de vuelta con errores y valores antiguos
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Datos validados
        $data = $validator->validated();

        // ⚡ Procesamiento adicional si se necesitara
        // Por ejemplo, generar un código dinámico si no viene
        if (empty($data['codigo_unico'])) {
            $data['codigo_unico'] = 'REP-' . now()->format('YmdHis');
        }

        // Crear la reparación
        $reparacion = Reparacion::create($data);

        return redirect()->route('reparaciones.index')
            ->with('success', 'Reparación creada correctamente.');
    }

    public function edit(Reparacion $reparacion)
    {
        // Obtener clientes para el select
        $clientes = Cliente::all();

        // Retornar la vista con la reparación y los clientes
        return view('admin.reparaciones.edit', compact('reparacion', 'clientes'));
    }



    public function update(Request $request, $id)
    {
        // Busca la reparación a actualizar
        $reparacion = Reparacion::findOrFail($id);

        // Reglas de validación, ignorando el ID de la reparación actual para el código único
        $validator = Validator::make($request->all(), [
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Actualiza la reparación con los datos validados
        $reparacion->update($validator->validated());

        // Redirige al index con mensaje de éxito
        return redirect()->route('reparaciones.index')->with('success', 'Reparación actualizada exitosamente.');
    }

    public function show(Reparacion $reparacion)
    {

        // Retornar la vista con la reparación
        return view('admin.reparaciones.show', compact('reparacion'));
    }



    /**
     * Elimina una reparación.
     *
     * Busca la reparación por su ID y la elimina. Si no existe, redirige al index con un mensaje de error.
     *
     * @param int $id El ID de la reparación a eliminar.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Buscar la reparación
        $reparacion = Reparacion::find($id);

        if (!$reparacion) {
            return redirect()->route('reparaciones.index')
                ->with('error', 'La reparación no existe.');
        }

        // Eliminar la reparación (soft delete si usas SoftDeletes)
        $reparacion->delete();

        return redirect()->route('reparaciones.index')
            ->with('success', 'Reparación eliminada correctamente.');
    }



    public function exportPdf($id)
    {
        $reparacion = Reparacion::with(['cliente', 'reparacionServicios.servicio', 'reparacionProductos.producto'])->findOrFail($id);

        $pdf = Pdf::loadView('admin.reparaciones.pdf', compact('reparacion'))
            ->setPaper('a4', 'portrait'); // tamaño A4 vertical

        return $pdf->stream("reparacion_{$reparacion->id}.pdf"); // también podés usar ->download()
    }
}
