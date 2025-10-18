<?php

namespace App\Models;

// Usar la clase Pivot para modelos de tablas intermedias
use Illuminate\Database\Eloquent\Relations\Pivot;

// Un modelo para una tabla pivote debería extender Pivot en lugar de Model
class ServicioVenta extends Pivot
{
    // Indicar explícitamente el nombre de la tabla
    protected $table = 'servicio_venta';

    // Opcional: Desactivar los timestamps si la tabla no los tiene
    public $timestamps = false;

    protected $fillable = [
        'venta_id',
        'servicio_id',
        'cantidad',
        'precio',
    ];

    /**
     * IMPORTANTE: No necesitas definir las relaciones belongsTo
     * en el modelo de la tabla pivote. La relación principal se define
     * en los modelos Venta y Servicio.
     */

 public function servicio()
{
    return $this->belongsTo(Servicio::class, 'servicio_id');
}

}
