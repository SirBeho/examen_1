<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tercero extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'apellido',
        'sexo',
        'nacimiento',
        'documento',
        'tipo_documento_id',
        'direccion',
        'telefono',
        'email',
        'status'
      
    ];

   


    public function documento() :BelongsTo  
    {
        return $this->belongsTo(tiposDocumento::class, 'tipo_documento_id');
    }


}
