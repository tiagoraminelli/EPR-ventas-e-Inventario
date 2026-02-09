<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Muestra la lista de productos con paginación.
     */

public function index(Request $request)
{
    $search           = $request->query('search');
    $categoriaFiltro  = $request->query('categoria');
    $marcaFiltro      = $request->query('marca');
    $stock            = $request->input('stock');
    $stockOperator    = $request->input('stock_type', '=');
    $withTrashed      = $request->boolean('with_trashed');

    $query = Product::with(['categoria', 'marca'])
        ->when(!$withTrashed, fn ($q) => $q->where('visible', true))

        // 🔍 Búsqueda general
        ->when($search, function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%")
                  ->orWhere('stock', 'like', "%{$search}%")
                  ->orWhere('precio_proveedor', 'like', "%{$search}%")
                  ->orWhere('precio', 'like', "%{$search}%")
                  ->orWhere('sub_categoria', 'like', "%{$search}%")
                  ->orWhereHas('categoria', fn ($q) =>
                      $q->where('nombre', 'like', "%{$search}%")
                  )
                  ->orWhereHas('marca', fn ($q) =>
                      $q->where('nombre', 'like', "%{$search}%")
                  );
            });
        })

        // 📦 Categoría
        ->when($categoriaFiltro, fn ($q) =>
            $q->where('categoria_id', $categoriaFiltro)
        )

        // 🏷 Marca
        ->when($marcaFiltro, fn ($q) =>
            $q->where('marca_id', $marcaFiltro)
        )

        // 📊 Stock
        ->when($request->filled('stock'), fn ($q) =>
            $q->where('stock', $stockOperator, $stock)
        );

    $products = $query
        ->orderBy('nombre')
        ->paginate(12)
        ->withQueryString();

    $categorias = Category::all();
    $marcas     = Marca::all();

    return view('admin.products.index', compact(
        'products',
        'search',
        'categorias',
        'marcas',
        'categoriaFiltro',
        'marcaFiltro'
    ));
}


    /**
     * Muestra el formulario para crear un nuevo producto.
     * Ajustamos para el manejo de imagenes más adelante.
     */
    public function create()
    {
        // Obtiene todas las categorías y marcas para los menús del formulario.
        $categories = Category::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);
        $marcas = Marca::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);

        // Retorna la vista de creación con los datos necesarios.
        return view('admin.products.create', compact('categories', 'marcas'));
    }

    /**
     * Almacena un nuevo producto en la base de datos.
     */
    public function store(Request $request)
    {
        // Reglas de validación
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:3',
            'descripcion' => 'nullable|string|min:5',
            'precio_proveedor' => 'required|numeric|not_in:0',
            'precio' => 'required|numeric|not_in:0', // en el front se coloca el porcentaje, no el precio final
            'stock' => 'nullable|integer',
            'url_imagen' => 'nullable|string|max:255',
            'imagen' => 'nullable|image|max:8192', // 8MB
            'categoria_id' => 'required|exists:categorias,id',
            'sub_categoria' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'visible' => 'boolean',
        ],
        [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre del producto debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del producto no debe superar los 255 caracteres.',
            'nombre.min' => 'El nombre del producto debe tener al menos 3 caracteres.',
            'descripcion.string' => 'La descripción del producto debe ser una cadena de texto.',
            'descripcion.min' => 'La descripción del producto debe tener al menos 5 caracteres.',
            'precio_proveedor.required' => 'El precio del proveedor es obligatorio.',
            'precio_proveedor.numeric' => 'El precio del proveedor debe ser un número.',
            'precio_proveedor.not_in' => 'El precio del proveedor no puede ser cero.',
            'precio.required' => 'El precio de venta es obligatorio.',
            'precio.numeric' => 'El precio de venta debe ser un número.',
            'precio_proveedor.gt' => 'El precio de venta debe ser mayor que el precio del proveedor.',
            'precio.gt' => 'El precio de venta debe ser mayor que el precio del proveedor.',
            'precio.not_in' => 'El precio de venta no puede ser cero.',
            'precio_proveedor.not_in' => 'El precio del proveedor no puede ser cero.',
            'imagen.max' => 'La imagen no debe superar los 8MB.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'sub_categoria.required' => 'La subcategoría es obligatoria.',
            'sub_categoria.string' => 'La subcategoría debe ser una cadena de texto.',
            'sub_categoria.max' => 'La subcategoría no debe superar los 255 caracteres.',
            'marca_id.required' => 'La marca es obligatoria.',
            'marca_id.exists' => 'La marca seleccionada no es válida.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // ⚠️ Nota: estamos usando el campo 'precio' como porcentaje de ganancia solo en la creación.
        // A futuro convendría renombrar este campo a 'ganancia_porcentual' para mayor claridad.
        $data['precio'] = $data['precio_proveedor'] * (1 + ($data['precio'] / 100));

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $data['url_imagen'] = $path;
        }


        // Crea el producto
        $product = Product::create($data);

        // Genera el código dinámicamente
        $product->codigo = 'COD' . $product->id;
        $product->save();

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un producto existente.
     */
    public function edit($id)
    {
        // Busca el producto por ID, incluyendo sus relaciones, o falla con un 404.
        $product = Product::with('categoria', 'marca')->findOrFail($id);

        // Obtiene todas las categorías y marcas para los menús del formulario.
        $categories = Category::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);
        $marcas = Marca::all()->sortBy('nombre', SORT_NATURAL | SORT_FLAG_CASE);

        // Retorna la vista de edición con los datos del producto y las opciones.
        return view('admin.products.edit', compact('product', 'categories', 'marcas'));
    }

    /**
     * Actualiza un producto existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255|min:3',
            'descripcion' => 'nullable|string|min:5',
            'precio_proveedor' => 'required|numeric|not_in:0',
            'precio' => 'required|numeric|gt:precio_proveedor|not_in:0',
            'stock' => 'nullable|integer',
            'url_imagen' => 'nullable|string',
            'imagen' => 'nullable|image|max:8192',
            'categoria_id' => 'required|exists:categorias,id',
            'sub_categoria' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'visible' => 'boolean',
        ], [
            'nombre.required' => 'El nombre del producto es obligatorio.',
            'nombre.string' => 'El nombre del producto debe ser una cadena de texto.',
            'nombre.max' => 'El nombre del producto no debe superar los 255 caracteres.',
            'nombre.min' => 'El nombre del producto debe tener al menos 3 caracteres.',
            'descripcion.string' => 'La descripción del producto debe ser una cadena de texto.',
            'descripcion.min' => 'La descripción del producto debe tener al menos 5 caracteres.',
            'precio_proveedor.required' => 'El precio del proveedor es obligatorio.',
            'precio_proveedor.numeric' => 'El precio del proveedor debe ser un número.',
            'precio_proveedor.not_in' => 'El precio del proveedor no puede ser cero.',
            'precio.required' => 'El precio de venta es obligatorio.',
            'precio.numeric' => 'El precio de venta debe ser un número.',
            'precio_proveedor.gt' => 'El precio de venta debe ser mayor que el precio del proveedor.',
            'precio.gt' => 'El precio de venta debe ser mayor que el precio del proveedor.',
            'precio.not_in' => 'El precio de venta no puede ser cero.',
            'precio_proveedor.not_in' => 'El precio del proveedor no puede ser cero.',
            'imagen.max' => 'La imagen no debe superar los 8MB.',
            'imagen.image' => 'El archivo debe ser una imagen.',
            'categoria_id.required' => 'La categoría es obligatoria.',
            'categoria_id.exists' => 'La categoría seleccionada no es válida.',
            'sub_categoria.required' => 'La subcategoría es obligatoria.',
            'sub_categoria.string' => 'La subcategoría debe ser una cadena de texto.',
            'sub_categoria.max' => 'La subcategoría no debe superar los 255 caracteres.',
            'marca_id.required' => 'La marca es obligatoria.',
            'marca_id.exists' => 'La marca seleccionada no es válida.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        // 🔽 VALIDACIÓN DE IMAGEN DUPLICADA
        if ($request->hasFile('imagen')) {

            $nuevaImagen = $request->file('imagen');

            $guardarImagen = true;

            if ($product->url_imagen && Storage::disk('public')->exists($product->url_imagen)) {

                $hashActual = md5_file(storage_path('app/public/' . $product->url_imagen));
                $hashNueva  = md5_file($nuevaImagen->getRealPath());

                // Si son iguales → NO guardar
                if ($hashActual === $hashNueva) {
                    $guardarImagen = false;
                }
            }

            if ($guardarImagen) {
                $path = $nuevaImagen->store('productos', 'public');
                $data['url_imagen'] = $path;
            }
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }


    /**
     * Elimina un producto de la base de datos.
     */
    public function destroy($id)
    {
        // Busca y elimina el producto por su ID.
        $product = Product::findOrFail($id);
        $product->update(['visible' => false]);

        // Redirige al dashboard con un mensaje de éxito.
        return redirect()->route('products.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
