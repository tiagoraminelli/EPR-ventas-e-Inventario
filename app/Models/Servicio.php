<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Venta;

class Servicio extends Model
{
    use HasFactory;

    // Nombre de la tabla (opcional si sigue la convención)
    protected $table = 'servicios';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nombre',
        'descripcion',
        'iva_aplicable',
        'precio',
        'activo',
        'visible',
    ];

    // Relación con el modelo Cliente
    public function ServicioVentas()
    {
        return $this->hasMany(ServicioVenta::class, 'servicio_id');
    }
    public function reparaciones()
    {
        return $this->belongsToMany(Reparacion::class, 'reparacion_servicio', 'servicio_id', 'reparacion_id');
    }
}
