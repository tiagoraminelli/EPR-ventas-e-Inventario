<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparacion extends Model
{
    use HasFactory;

    protected $table = 'reparaciones';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<string>
     */
    protected $fillable = [
        'codigo_unico',
        'cliente_id',
        'equipo_descripcion',
        'equipo_marca',
        'equipo_modelo',
        'descripcion_danio',
        'solucion_aplicada',
        'reparable',
        'estado_reparacion',
        'fecha_ingreso', // Nueva columna para controlar la fecha de ingreso
    ];

    // Desactivamos timestamps automáticos
    public $timestamps = false;

    // Definimos los campos que se tratan como fechas
    protected $dates = [
        'fecha_ingreso',
    ];

    // Relación con Cliente
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'reparacion_servicio', 'reparacion_id', 'servicio_id');
    }

    public function reparacionServicios()
    {
        return $this->hasMany(ReparacionServicio::class, 'reparacion_id');
    }

    public function productos()
    {
        return $this->belongsToMany(Product::class, 'reparacion_producto', 'reparacion_id', 'producto_id')
            ->withPivot('precio', 'cantidad');
    }

    // En Reparacion.php
    public function reparacionProductos()
    {
        return $this->hasMany(ReparacionProducto::class, 'reparacion_id');
    }
}
