<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    // Si tu tabla se llama 'marcas', Laravel ya lo detecta por convención.
    protected $table = 'marcas';

    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Obtiene los productos para esta marca.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'marca_id');
    }
}
