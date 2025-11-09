<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HistoriaMedica extends Model
{
    use HasFactory;

    protected $table = 'historias_medicas';

    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha_registro',
        'sintomas',
        'diagnostico',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
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

    public function tratamientos(): HasMany
    {
        return $this->hasMany(Tratamiento::class, 'historia_id');
    }
}
// Fin de app/Models/HistoriaMedica.php