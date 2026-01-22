<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'course_id',     // ID del curso
        'subject_id',    // ID de la asignatura
        'teacher_id',    // ID del profesor (usuario)
        'day',           // Día de la semana (ej. "Lunes")
        'start_time',    // Hora de inicio (ej. "07:00")
        'end_time',      // Hora de fin (ej. "08:00")
        'status',        // Estado (ej. "completed" o "pending")
    ];

    /**
     * Relaciones
     */
    // BelongsTo con curso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // BelongsTo con asignatura
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    // BelongsTo con usuario (profesor)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
