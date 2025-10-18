<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReparacionServicio extends Pivot
{
    protected $table = 'reparacion_servicio';


    protected $fillable = [
        'reparacion_id',
        'servicio_id',
        'cantidad',
        'precio'
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function reparacion()
    {
        return $this->belongsTo(Reparacion::class, 'reparacion_id');
    }
}
