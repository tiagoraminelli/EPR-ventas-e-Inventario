<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Muestra la lista de clientes.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Cliente::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('RazonSocial', 'LIKE', "%{$search}%")
                  ->orWhere('NombreCompleto', 'LIKE', "%{$search}%")
                  ->orWhere('cuit_dni', 'LIKE', "%{$search}%")
                  ->orWhere('TipoCliente', 'LIKE', "%{$search}%")
                  ->orWhere('Email', 'LIKE', "%{$search}%");
            });
        }
        $query->where('visible', true);
        $clientes = $query->orderBy('NombreCompleto', 'asc')->paginate(10);

        return view('admin.clientes.index', compact('clientes', 'search'));
    }

    /**
     * Muestra el formulario para crear un nuevo cliente.
     */
    public function create()
    {
        return view('admin.clientes.create');
    }

    /**
     * Almacena un nuevo cliente en la base de datos.
     */
    public function store(Request $request)
    {
        // Esto es lo más importante: Asegura que el campo 'visible'
        // sea `true` si se envió en la petición (está marcado),
        // y `false` si no se envió (no está marcado).
        $request->merge(['visible' => $request->has('visible')]);
        $validator = Validator::make($request->all(), [
            'RazonSocial' => 'nullable|string|max:100',
            'NombreCompleto' => 'nullable|string|max:100',
            // Nota: El campo 'CUIT/DNI' es único y se valida aquí.
            'cuit_dni' => 'nullable|string|max:100|unique:clientes',
            'Domicilio' => 'nullable|string|max:100',
            'Localidad' => 'nullable|string|max:100',
            'Detalle' => 'nullable|string|max:255',
            'Email' => 'required|email|unique:clientes',
            'Telefono' => 'nullable|string|max:50',
            'TipoCliente' => 'required|in:Persona,Empresa,Institucion Publica',
            'visible' => 'boolean|nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Cliente::create($validator->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un cliente existente.
     */
    public function edit(Cliente $cliente)
    {
        return view('admin.clientes.edit', compact('cliente'));
    }

    /**
     * Actualiza un cliente existente en la base de datos.
     */
    public function update(Request $request, Cliente $cliente)
    {
        // update clientes SET visible = 1 WHERE visible = 0
        $request->merge([
            'visible' => $request->has('visible')
        ]);
        $validator = Validator::make($request->all(), [
            'RazonSocial' => 'nullable|string|max:100',
            'NombreCompleto' => 'nullable|string|max:100',
            'cuit_dni' => [
                'required',
                'string',
                'max:100',
                // Evita que el mismo CUIT/DNI de la instancia actual sea considerado un duplicado
                Rule::unique('clientes')->ignore($cliente->id),
            ],
            'Domicilio' => 'nullable|string|max:100',
            'Localidad' => 'nullable|string|max:100',
            'Detalle' => 'nullable|string|max:255',
            'Email' => [
                'required',
                'email',
                // Evita que el mismo email de la instancia actual sea considerado un duplicado
                Rule::unique('clientes')->ignore($cliente->id),
            ],
            'Telefono' => 'nullable|string|max:50',
            'TipoCliente' => 'required|in:Persona,Empresa,Institucion Publica',
            'visible' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $cliente->update($validator->validated());

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Elimina un cliente de la base de datos.
     */
    public function destroy(Cliente $cliente)
    {
        $cliente = Cliente::findOrFail($cliente->id);
        $cliente->update(['visible' => false]);

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
