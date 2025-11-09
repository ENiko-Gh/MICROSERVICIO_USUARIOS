<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HistoriaMedica;
use App\Models\Tratamiento;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HistoriaMedicaController extends Controller
{
    // Función de ayuda para verificar si el usuario autenticado es un doctor
    private function isDoctor($userId)
    {
        return Doctor::where('user_id', $userId)->exists();
    }

    // LISTAR HISTORIAL DE UN PACIENTE (Protegida)
    // Usada por el doctor para ver el historial de un paciente específico.
    public function index($paciente_id)
    {
        // 1. Verificar si el usuario autenticado es un Doctor
        $doctorId = Auth::id();
        if (!$this->isDoctor($doctorId)) {
            return response()->json(['message' => 'Acceso denegado. Solo doctores pueden ver el historial médico.'], 403);
        }

        // 2. Obtener el historial completo
        $historial = HistoriaMedica::where('paciente_id', $paciente_id)
            ->with('doctor.user:id,name', 'tratamientos')
            ->orderBy('fecha_registro', 'desc')
            ->get();

        if ($historial->isEmpty()) {
            return response()->json(['message' => 'No se encontró historial médico para este paciente.'], 404);
        }

        return response()->json($historial, 200);
    }

    // CREAR NUEVO REGISTRO DE HISTORIA (Solo Doctor)
    public function store(Request $request)
    {
        // 1. Verificar si el usuario autenticado es un Doctor
        $doctorId = Auth::id();
        $doctorProfile = Doctor::where('user_id', $doctorId)->first();

        if (!$doctorProfile) {
            return response()->json(['message' => 'Acceso denegado. Solo doctores pueden crear registros.'], 403);
        }

        // 2. Validación
        $validated = $request->validate([
            'paciente_id' => 'required|exists:users,id',
            'sintomas' => 'required|string|max:1000',
            'diagnostico' => 'required|string|max:1000',
        ]);

        // 3. Creación del registro
        $historia = HistoriaMedica::create([
            'paciente_id' => $validated['paciente_id'],
            'doctor_id' => $doctorProfile->id, // Usamos el ID de la tabla 'doctores'
            'fecha_registro' => now(),
            'sintomas' => $validated['sintomas'],
            'diagnostico' => $validated['diagnostico'],
        ]);

        return response()->json([
            'message' => 'Registro de historial médico creado con éxito.',
            'historia' => $historia
        ], 201);
    }
    
    // ACTUALIZAR REGISTRO DE HISTORIA (Solo Doctor)
    public function update(Request $request, $historia_id)
    {
        $historia = HistoriaMedica::find($historia_id);

        if (!$historia) {
            return response()->json(['message' => 'Historia médica no encontrada.'], 404);
        }

        // 1. Verificar si el usuario autenticado es un Doctor
        $doctorId = Auth::id();
        if (!$this->isDoctor($doctorId)) {
            return response()->json(['message' => 'Acceso denegado. Solo doctores pueden actualizar registros.'], 403);
        }

        // 2. Validación
        $validated = $request->validate([
            'sintomas' => 'sometimes|required|string|max:1000',
            'diagnostico' => 'sometimes|required|string|max:1000',
        ]);

        // 3. Actualizar
        $historia->update($validated);

        return response()->json([
            'message' => 'Registro de historial médico actualizado con éxito.',
            'historia' => $historia
        ], 200);
    }


    // AÑADIR TRATAMIENTO A UNA HISTORIA EXISTENTE (Solo Doctor)
    public function addTratamiento(Request $request, $historia_id)
    {
        // 1. Verificar si el usuario autenticado es un Doctor
        $doctorId = Auth::id();
        if (!$this->isDoctor($doctorId)) {
            return response()->json(['message' => 'Acceso denegado. Solo doctores pueden añadir tratamientos.'], 403);
        }

        // 2. Buscar Historia Médica
        if (!HistoriaMedica::find($historia_id)) {
            return response()->json(['message' => 'Historia médica no encontrada.'], 404);
        }

        // 3. Validación
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'instrucciones_dosis' => 'required|string|max:500',
            'fecha_inicio' => 'required|date_format:Y-m-d',
            'fecha_fin_estimada' => 'nullable|date_format:Y-m-d|after_or_equal:fecha_inicio',
        ]);

        // 4. Creación del tratamiento
        $tratamiento = Tratamiento::create(array_merge($validated, [
            'historia_id' => $historia_id,
            'activo' => true,
        ]));

        return response()->json([
            'message' => 'Tratamiento añadido con éxito.',
            'tratamiento' => $tratamiento
        ], 201);
    }
    
    // ACTUALIZAR TRATAMIENTO (Solo Doctor)
    public function updateTratamiento(Request $request, $tratamiento_id)
    {
        $tratamiento = Tratamiento::find($tratamiento_id);

        if (!$tratamiento) {
            return response()->json(['message' => 'Tratamiento no encontrado.'], 404);
        }

        // 1. Verificar si el usuario autenticado es un Doctor
        $doctorId = Auth::id();
        if (!$this->isDoctor($doctorId)) {
            return response()->json(['message' => 'Acceso denegado. Solo doctores pueden actualizar tratamientos.'], 403);
        }

        // 2. Validación
        $validated = $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'instrucciones_dosis' => 'sometimes|required|string|max:500',
            'fecha_fin_estimada' => 'nullable|date_format:Y-m-d|after_or_equal:fecha_inicio',
            'activo' => ['sometimes', 'required', 'boolean', Rule::in([true, false, 0, 1])],
        ]);

        // 3. Actualizar
        $tratamiento->update($validated);

        return response()->json([
            'message' => 'Tratamiento actualizado con éxito.',
            'tratamiento' => $tratamiento
        ], 200);
    }
    
    // ELIMINAR TRATAMIENTO (Solo Doctor)
    public function destroyTratamiento($tratamiento_id)
    {
        $tratamiento = Tratamiento::find($tratamiento_id);

        if (!$tratamiento) {
            return response()->json(['message' => 'Tratamiento no encontrado.'], 404);
        }

        // 1. Verificar si el usuario autenticado es un Doctor
        $doctorId = Auth::id();
        if (!$this->isDoctor($doctorId)) {
            return response()->json(['message' => 'Acceso denegado. Solo doctores pueden eliminar tratamientos.'], 403);
        }

        // 2. Eliminar
        $tratamiento->delete();

        return response()->json(['message' => 'Tratamiento eliminado con éxito.'], 200);
    }

}
// Fin de app/Http/Controllers/HistoriaMedicaController.php