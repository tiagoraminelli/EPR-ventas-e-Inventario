<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Si tu tabla se llama 'categorias', Laravel ya lo detecta por convención.
    protected $table = 'categorias';
    protected $fillable = ['nombre', 'descripcion'];

    /**
     * Obtiene los productos para esta categoría.
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'categoria_id');
    }
}
