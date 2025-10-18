<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReparacionProducto extends Model
{
    use HasFactory;

    protected $table = 'reparacion_producto';

    protected $fillable = [
        'reparacion_id',
        'producto_id',
        'precio',
        'cantidad',
    ];

    // Relación con Reparacion
    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_id');
    }

    // Relación con Producto
    public function producto()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }
}
