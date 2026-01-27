<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'status'];

    protected $hidden = ['password'];

    // Método para verificar rol
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Relación uno a uno con Teacher
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    // Relación uno a uno con Students
    public function student()
    {
        return $this->hasOne(Students::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'students_courses', 'student_id', 'course_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teachers_subjects', 'teacher_id', 'subject_id');
    }

}