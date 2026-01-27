<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    /**
     * Campos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name', // Nombre de la asignatura (ej. "Matemáticas")
    ];

    /**
     * Relaciones
     */
    // Many-to-many con cursos (una asignatura puede estar en varios cursos)
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_subject');
    }

    // Many-to-many con usuarios (profesores que dictan esta asignatura)
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'teacher_subjects', 'subject_id', 'teacher_id');
    }

    // Relación con horarios (una asignatura puede tener varios horarios)
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}