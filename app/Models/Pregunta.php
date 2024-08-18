<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = ['num1', 'num2', 'ope', 'res', 'evaluacione_id', 'res_usu'];

    protected $table = 'preguntas';

    public function evaluacione(){
        return $this->belongsTo(Evaluacione::class);
    }
}
