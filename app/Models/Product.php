<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    /**
     * El nombre de la tabla asociada al modelo.
     * Laravel busca por defecto 'productos' (plural del nombre del modelo),
     * pero es buena práctica especificarlo para evitar errores.
     *
     * @var string
     */
    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio_proveedor',
        'precio',
        'stock',
        'url_imagen',
        'categoria_id',
        'sub_categoria',
        'marca_id',
        'visible',
    ];

    /**
        * Indica si el modelo debe usar marcas de tiempo.
     */




    /**
     * Obtiene la categoría a la que pertenece el producto.
     *
     */

    public $timestamps = false;
    public function categoria()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }

    /**
     * Obtiene la marca a la que pertenece el producto.
     */
    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }
       // Opcional: Define la relación si DetalleVenta pertenece a este producto
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'producto_id');
    }

     public function detalleReparaciones()
    {
        return $this->hasMany(ReparacionProducto::class, 'producto_id');
    }
}
