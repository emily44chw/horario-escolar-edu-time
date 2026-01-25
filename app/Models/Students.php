<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Students extends Model
{
    use HasFactory;

    //Campos que estan en la base de datos para asignacion masiva de la tabla Students
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'birth_date',
        'representative',
        'representative_phone',
        'status',
    ];

    //Relaciones con otras tablas del sistema
    //Relacion uno a uno con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //Relacion muchos a uno con Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    //Relacion muchos a uno con classroom
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

}
