<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    //Campos que estan en la base de datos para asignacion masiva de la tabla Teachers
    //fillable permite asignacion masiva
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'status',
    ];

    //Relaciones con otras tablas del sistema

    //Relacion uno a uno con User
    public function user()
    {
        return $this->belongsTo(User::class);

    }

    //Relacion muchos a muchos con Subject
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects');
    }

    //Relacion uno a muchos con Schedule
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}