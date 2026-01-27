<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['grade', 'parallel', 'school_year'];

    // Relaciones (ajustadas para especificar tabla pivot y claves)
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'course_subjects', 'course_id', 'subject_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);  // Asume que Schedule tiene course_id
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'students_courses', 'course_id', 'student_id');
    }

    // Accessor para 'name' (perfecto para combinar campos)
    public function getNameAttribute()
    {
        return $this->grade . ' ' . $this->parallel . ' (' . $this->school_year . ')';
    }
}