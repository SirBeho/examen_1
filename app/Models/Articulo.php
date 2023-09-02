<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Articulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio_c',
        'precio_v',
        'strock',
        'categoria_id',
        'status'
      
    ];


    public function categoria() :BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

  


}
