<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctores';

    protected $fillable = [
        'user_id',
        'numero_licencia',
        'especialidad',
        'dias_disponibles',
    ];

    protected $casts = [
        'dias_disponibles' => 'array',
    ];

    // RELACIONES
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'doctor_id');
    }

    public function historiasMedicas(): HasMany
    {
        return $this->hasMany(HistoriaMedica::class, 'doctor_id');
    }
}
// Fin de app/Models/Doctor.php