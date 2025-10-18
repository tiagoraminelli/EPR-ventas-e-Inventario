<?php

namespace App\Http\Controllers;

use App\Models\ServicioVenta;
use App\Models\Venta;
use App\Models\Servicio;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // <-- ¡Esta es la línea que faltaba!

class ServicioVentaController extends Controller
{
    /**
     * Muestra la lista de ventas agrupadas con sus servicios asociados.
     */
  public function index(Request $request)
{
    $search = $request->query('search');
    $numero_comprobante = $request->query('numero_comprobante');
    $ventaFiltro = $request->query('venta');
    $clienteFiltro = $request->query('cliente');
    $serviciosFiltroSeleccionados = $request->query('servicios', []);

    // Empezamos con el modelo Venta con relaciones necesarias
    $query = Venta::with(['cliente', 'serviciosVentas.servicio'])->whereHas('serviciosVentas');
   // solo las que tienen el campo visible = 1
     $query->where('visible', 1);
    // Filtro por servicio(s) mediante búsqueda de nombre
    if ($search) {
        $query->whereHas('serviciosVentas.servicio', function ($q) use ($search) {
            $q->where('nombre', 'LIKE', "%{$search}%");
        });
    }

    // Filtro por venta
    if ($ventaFiltro) {
        $query->where('id', $ventaFiltro);
    }

    // Filtro por cliente
    if ($clienteFiltro) {
        $query->where('cliente_id', $clienteFiltro);
    }

    // Filtro por comprobante
    if ($numero_comprobante) {
        $query->where('numero_comprobante', $numero_comprobante);
    }

    // Filtro por varios servicios: solo ventas que tengan todos los servicios seleccionados
    if (!empty($serviciosFiltroSeleccionados)) {
        foreach ($serviciosFiltroSeleccionados as $servicioId) {
            $query->whereHas('serviciosVentas', function($q) use ($servicioId) {
                $q->where('servicio_id', $servicioId);
            });
        }
    }

    // Paginación
    $ventas = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

    // Datos para filtros
    $ventasFiltro = Venta::with('cliente')->get();
    $serviciosFiltro = Servicio::all();
    $clientesFiltro = Cliente::all();



    return view('admin.serviciosventas.index', compact('ventas', 'ventasFiltro', 'serviciosFiltro', 'clientesFiltro', 'search', 'ventaFiltro', 'clienteFiltro', 'serviciosFiltroSeleccionados'));
}


    /**
     * Muestra el formulario para crear un nuevo registro.
     */
    public function create()
    {
        $ventas = Venta::all();
        $servicios = Servicio::all();
        return view('admin.serviciosventas.create', compact('ventas', 'servicios'));
    }

    /**
     * Almacena uno o más registros en la tabla pivote.
     */
    public function store(Request $request)
    {
        // Validación de la venta y del array de servicios
        $validator = Validator::make($request->all(), [
            'venta_id' => 'required|exists:ventas,id',
            'servicios' => 'required|array|min:1',
            'servicios.*.servicio_id' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|integer|min:1',
            'servicios.*.precio' => 'nullable|numeric|min:0', // El precio ahora puede ser nulo o vacío
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Obtener la venta_id para asociar los servicios
        $ventaId = $request->input('venta_id');

        // Iterar sobre cada servicio para crearlo
        foreach ($request->input('servicios') as $servicioData) {
            $servicio = Servicio::find($servicioData['servicio_id']);

            if (!$servicio) {
                continue; // Saltar si el servicio no existe
            }

            // Lógica para determinar el precio
            $precioFinal = $servicioData['precio'];
            if (is_null($precioFinal) || $precioFinal === '') {
                $precioFinal = $servicio->precio;
            }

            // Crear el registro en la tabla pivote
            ServicioVenta::create([
                'venta_id' => $ventaId,
                'servicio_id' => $servicioData['servicio_id'],
                'cantidad' => $servicioData['cantidad'],
                'precio' => $precioFinal,
            ]);
        }

        return redirect()->route('serviciosventas.index')->with('success', 'Registros creados exitosamente.');
    }

    /**
     * Muestra el formulario para editar una venta con sus servicios asociados.
     */
    public function edit(Venta $venta)
    {
        $servicios = Servicio::all();
        return view('admin.serviciosventas.edit', compact('venta', 'servicios'));
    }

    /**
     * Actualiza los registros de una venta utilizando transacciones.
     */
    public function update(Request $request, Venta $venta)
    {
        // 1. Validar la solicitud.
        $request->validate([
            // La validación se aplica a cada elemento del array 'servicios'.
            'servicios.*.id' => [
                'nullable',
                // Usar Rule::exists para especificar la tabla 'servicio_venta'.
                // Se le puede pasar el nombre de la tabla o el modelo.
                Rule::exists('servicio_venta', 'id'),
            ],
            'servicios.*.servicio_id' => 'required|exists:servicios,id',
            'servicios.*.cantidad' => 'required|numeric|min:1',
            'servicios.*.precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $venta) {
                // Obtener los IDs de los servicios de la solicitud
                $requestServicioVentaIds = collect($request->servicios)->pluck('id')->filter()->all();

                // Obtener los IDs de los servicios existentes para esta venta
                $existingServicioVentaIds = ServicioVenta::where('venta_id', $venta->id)->pluck('id')->all();

                // Eliminar los detalles que ya no están en la solicitud
                $serviciosVentasToDelete = array_diff($existingServicioVentaIds, $requestServicioVentaIds);
                if (!empty($serviciosVentasToDelete)) {
                    ServicioVenta::destroy($serviciosVentasToDelete);
                }

                // Recorrer los detalles de la solicitud para actualizar o crear
                foreach ($request->servicios as $item) {
                    $servicio = Servicio::find($item['servicio_id']);

                    if (!$servicio) {
                        continue;
                    }

                    // Lógica para determinar el precio
                    $precioFinal = $item['precio'];
                    if (is_null($precioFinal) || $precioFinal === '') {
                        $precioFinal = $servicio->precio;
                    }

                    $servicioVentaData = [
                        'venta_id' => $venta->id,
                        'servicio_id' => $item['servicio_id'],
                        'cantidad' => $item['cantidad'],
                        'precio' => $precioFinal,
                    ];

                    if (isset($item['id'])) {
                        // Es una actualización
                        ServicioVenta::where('id', $item['id'])->update($servicioVentaData);
                    } else {
                        // Es una creación
                        ServicioVenta::create($servicioVentaData);
                    }
                }
            });

            return redirect()->route('serviciosventas.index')->with('success', 'Registros actualizados exitosamente.');
        } catch (\Exception $e) {
            Log::error('Error al actualizar los servicios de la venta: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('serviciosventas.index')->with('error', 'Error al actualizar los servicios. Por favor, intente de nuevo.');
        }
    }


    /**
     * Elimina un registro de la tabla pivote.
     */
    public function destroy($id)
    {
        $servicioVenta = ServicioVenta::findOrFail($id);
        $servicioVenta->delete();

        return redirect()->route('serviciosventas.index')->with('success', 'Registro eliminado exitosamente.');
    }
}
