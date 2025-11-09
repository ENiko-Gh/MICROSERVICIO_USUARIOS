<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tratamiento extends Model
{
    use HasFactory;

    protected $table = 'tratamientos';

    protected $fillable = [
        'historia_id',
        'nombre',
        'instrucciones_dosis',
        'fecha_inicio',
        'fecha_fin_estimada',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin_estimada' => 'date',
        'activo' => 'boolean',
    ];

    // RELACIONES
    public function historiaMedica(): BelongsTo
    {
        return $this->belongsTo(HistoriaMedica::class, 'historia_id');
    }
}
// Fin de app/Models/Tratamiento.php