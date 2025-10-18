<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cliente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'RazonSocial',
        'NombreCompleto',
        'cuit_dni',
        'Domicilio',
        'Localidad',
        'Detalle',
        'Email',
        'Telefono',
        'TipoCliente',
        'visible',
    ];

    /**
     * Get the cuenta corriente associated with the cliente.
     */
    // public function cuentaCorriente(): HasOne
    // {
    //     return $this->hasOne(CuentaCorriente::class, 'cliente_id');
    // }

    public function reparaciones()
    {
        return $this->hasMany(Reparacion::class);
    }
}
