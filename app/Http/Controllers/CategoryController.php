<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Muestra la lista de categorías.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Category::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }

        $categorias = $query->orderBy('nombre', 'asc')->paginate(10);

        return view('admin.categorias.index', compact('categorias', 'search'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Almacena una nueva categoría en la base de datos.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:3|unique:categorias,nombre',
            'descripcion' => 'nullable|string|min:5',
        ]);

        if ($validator->fails()) {
            // En caso de error, retorna a la vista anterior con errores
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Category::create($validator->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una marca existente.
     */
    public function edit($id)
    {
        $categoria = Category::findOrFail($id);

        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Actualiza una categoría existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $categoria = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:3|unique:categorias,nombre,' . $categoria->id,
            'descripcion' => 'nullable|string|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $categoria->update($validator->validated());

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Elimina una categoría de la base de datos.
     */
    public function destroy($id)
    {
        $categoria = Category::findOrFail($id);
        $categoria->update(['visible' => false]);

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}
