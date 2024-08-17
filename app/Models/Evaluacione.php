<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacione extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones';

    public function preguntas(){
        return $this->hasMany(Pregunta::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'user_id', 
        'name', 
    ];
}
