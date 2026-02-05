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
        // Obtiene los parámetros de búsqueda y filtros
        $search = $request->query('search');
        $categoriaFiltro = $request->query('categoria');
        $marcaFiltro = $request->query('marca');
        $filtroStock = $request->query('stock');

        // Inicia la consulta del modelo Product con relaciones
        $query = Product::with('categoria', 'marca')->where('visible', true);

        // Filtro por búsqueda
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'LIKE', "%{$search}%")
                    ->orWhere('codigo', 'LIKE', "%{$search}%")
                    ->orWhere('descripcion', 'LIKE', "%{$search}%")
                    ->orWhere('stock', 'LIKE', "%{$search}%")
                    ->orWhere('precio_proveedor', 'LIKE', "%{$search}%")
                    ->orWhere('precio', 'LIKE', "%{$search}%")
                    ->orWhere('sub_categoria', 'LIKE', "%{$search}%")
                    ->orWhereHas('categoria', function ($query) use ($search) {
                        $query->where('nombre', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('marca', function ($query) use ($search) {
                        $query->where('nombre', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filtro por categoría
        if ($categoriaFiltro) {
            $query->where('categoria_id', $categoriaFiltro);
        }


        // Filtro por stock
        if ($request->filled('stock')) {
            $filtroStock = $request->input('stock');
            $operador = $request->input('stock_type', '='); // por defecto "="
            $query->where('stock', $operador, $filtroStock);
        }



        // Filtro por marca
        if ($marcaFiltro) {
            $query->where('marca_id', $marcaFiltro);
        }

        // Ordena y pagina
        $products = $query->orderBy('nombre', 'asc')->paginate(12)->withQueryString();

        // Obtener todas las categorías y marcas para los selects
        $categorias = Category::all();
        $marcas = Marca::all();

        // Retorna la vista con todo
        return view('admin.products.index', compact('products', 'search', 'categorias', 'marcas', 'categoriaFiltro', 'marcaFiltro'));
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
            'imagen' => 'nullable|image|max:4096', // 4B
            'categoria_id' => 'required|exists:categorias,id',
            'sub_categoria' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'visible' => 'boolean',
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
            'imagen' => 'nullable|image|max:2048',
            'categoria_id' => 'required|exists:categorias,id',
            'sub_categoria' => 'required|string|max:255',
            'marca_id' => 'required|exists:marcas,id',
            'visible' => 'boolean',
        ], [
            'precio.gt' => 'El precio de venta debe ser mayor que el precio del proveedor.',
            'precio.not_in' => 'El precio de venta no puede ser cero.',
            'precio_proveedor.not_in' => 'El precio del proveedor no puede ser cero.',
            'imagen.max' => 'La imagen no debe superar los 2MB.',
            'imagen.image' => 'El archivo debe ser una imagen.',
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
