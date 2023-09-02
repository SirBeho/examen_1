<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'serie',
        'cliente_id',
        'trabajador_id',
        'fecha',
        'comprobante_id',
        'status'
      
    ];

    public function cliente() :BelongsTo
    {
        return $this->belongsTo(Tercero::class, 'cliente_id');
    }

    public function trabajador() :BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }

    public function comprobante() :BelongsTo
    {
        return $this->belongsTo(comprobante::class, 'comprobante_id');
    }

    public function detalle(): HasMany
    {
        return $this->hasMany(DetalleVenta::class,'venta_id');
    }



}
