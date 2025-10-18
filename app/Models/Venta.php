<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'cliente_id',
        'tipo_comprobante',
        'numero_comprobante',
        'condicion_pago',
        'importe_neto',
        'importe_iva',
        'importe_total',
        'estado_venta',
        'observaciones',
        'fecha_venta',
        'visible',
    ];

    /**
     * Obtiene el cliente asociado a la venta.
     */
    // App\Models\Venta.php
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id'); // revisa si la FK es cliente_id
    }

    /**
     * Obtiene los detalles de la venta.
     */
    // Relación con detalles de venta
    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }

    public function serviciosVentas()
    {
        return $this->hasMany(ServicioVenta::class, 'venta_id');
    }

    // En tu modelo Venta.php
    public function servicios()
    {
        return $this->belongsToMany(Servicio::class, 'servicio_venta')
            ->withPivot('id', 'cantidad', 'precio')
            ->withTimestamps(); // si tu tabla tiene timestamps
    }
}
