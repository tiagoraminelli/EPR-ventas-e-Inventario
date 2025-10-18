<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MarcaController extends Controller
{
    /**
     * Muestra la lista de marcas.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Marca::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        $marcas = $query->orderBy('nombre', 'asc')->paginate(10);

        return view('admin.marcas.index', compact('marcas', 'search'));
    }

    /**
     * Muestra el formulario para crear una nueva marca.
     */
    public function create()
    {
        return view('admin.marcas.create');
    }

    /**
     * Almacena una nueva marca en la base de datos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:3|unique:marcas,nombre',
            'descripcion' => 'nullable|string|min:5',
        ]);

        if ($validator->fails()) {
            // En caso de error, retorna a la vista anterior con errores
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Marca::create($validator->validated());

        return redirect()->route('marcas.index')->with('success', 'Marca creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una marca existente.
     */
    public function edit($id)
    {
        $marca = Marca::findOrFail($id);

        return view('admin.marcas.edit', compact('marca'));
    }

    /**
     * Actualiza una marca existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $marca = Marca::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:3|unique:marcas,nombre,' . $marca->id,
            'descripcion' => 'nullable|string|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $marca->update($validator->validated());

        return redirect()->route('marcas.index')->with('success', 'Marca actualizada exitosamente.');
    }

    /**
     * Elimina una marca de la base de datos.
     */
    public function destroy($id)
    {
        $marca = Marca::findOrFail($id);
        $marca->update(['visible' => false]);

        return redirect()->route('marcas.index')->with('success', 'Marca eliminada exitosamente.');
    }
}
