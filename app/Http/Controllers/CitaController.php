<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;
use App\Models\Doctor;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function indexDoctores()
    {
        $doctores = Doctor::with('user:id,name,email')
                          ->get(['id', 'user_id', 'especialidad', 'dias_disponibles']);

        return response()->json($doctores, 200);
    }
    
    public function indexCitasPaciente(Request $request)
    {
        $pacienteId = $request->input('paciente_id', 1); 

        $citas = Cita::where('paciente_id', $pacienteId)
                     ->with('doctor.user:id,name,email')
                     ->orderBy('fecha_hora', 'asc')
                     ->get();

        return response()->json($citas, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'paciente_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:doctores,id',
            'fecha_hora' => 'required|date_format:Y-m-d H:i:s|after:now',
            'motivo' => 'required|string|max:255',
        ]);
        
        $doctorId = $validated['doctor_id'];
        $fechaHora = $validated['fecha_hora'];

        $conflicto = Cita::where('doctor_id', $doctorId)
                         ->where('fecha_hora', $fechaHora)
                         ->whereNotIn('estado', ['cancelada'])
                         ->exists();

        if ($conflicto) {
            return response()->json(['message' => 'El doctor ya tiene una cita programada a esta hora.'], 409);
        }

        $cita = Cita::create([
            'paciente_id' => $validated['paciente_id'],
            'doctor_id' => $doctorId,
            'fecha_hora' => $fechaHora,
            'motivo' => $validated['motivo'],
            'estado' => 'pendiente',
        ]);

        return response()->json([
            'message' => 'Cita reservada con éxito.',
            'cita' => $cita->load('doctor.user:id,name,email')
        ], 201);
    }
}
// Fin de app/Http/Controllers/CitaController.php