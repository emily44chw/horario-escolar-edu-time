<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = ['grade', 'parallel', 'school_year']; // Campos fillable

    // Relaciones (ya existentes)
    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Accessor para 'name' (combina grade, parallel y school_year)
    public function getNameAttribute()
    {
        return $this->grade . ' ' . $this->parallel . ' (' . $this->school_year . ')';
    }
}