<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Venta;
use App\Models\Product;
use App\Models\Servicio;

class DetalleVenta extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'detalle_ventas';

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'venta_id',
        'producto_id',
        //'servicio_id',   // Servicio asociado (si aplica)
        'cantidad',
        'precio_unitario',
        'descuento',
        'subtotal',
        'visible',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'visible' => 'boolean',
    ];

    /**
     * Relación con la venta a la que pertenece este detalle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function venta()
    {
        return $this->belongsTo(Venta::class, 'venta_id');
    }

    /**
     * Relación con el producto asociado a este detalle.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function producto()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }

    // Si quieres acceder al cliente a través de la venta:
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}
