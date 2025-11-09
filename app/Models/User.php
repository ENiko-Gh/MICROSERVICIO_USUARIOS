<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'fecha_nacimiento',
        'sexo',
        'numero_seguro',
        'historial_medico', 
        'contacto_emergencia',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'fecha_nacimiento' => 'date',
    ];

    // RELACIONES
    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class, 'user_id');
    }

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    public function historiasMedicas(): HasMany
    {
        return $this->hasMany(HistoriaMedica::class, 'paciente_id');
    }
}
// Fin de app/Models/User.php