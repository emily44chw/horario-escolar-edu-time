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
        return $this->belongsToMany(
            Subject::class,
            'course_subject',
            'course_id',
            'subject_id'
        );
    }


    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_courses', 'course_id', 'student_id');
    }


    public function getNameAttribute()
    {
        return $this->grade . ' ' . $this->parallel . ' (' . $this->school_year . ')';
    }
}