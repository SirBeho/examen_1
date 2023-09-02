<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trabajador extends Model
{
    use HasFactory;

    protected $table ="trabajadores";



    protected $fillable = [
        'tercero_id',
        'usuario',
        'contra',
        'status'
      
    ];

 

    public function tercero() :BelongsTo
    {
        return $this->belongsTo(Tercero::class, 'tercero_id');
    }



}
