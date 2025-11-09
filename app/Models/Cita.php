<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha_hora',
        'motivo',
        'estado',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
        'estado' => 'string',
    ];

    // RELACIONES
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paciente_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
// Fin de app/Models/Cita.php