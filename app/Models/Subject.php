<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // Relaciones
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_subject',
            'subject_id',
            'course_id'
        );
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subjects', 'subject_id', 'teacher_id');
    }



    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}