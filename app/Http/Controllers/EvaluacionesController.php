<?php

namespace App\Http\Controllers;

use App\Models\Evaluacione;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionesController extends Controller
{
    public function generatepares($tipo){
        $paresBinarios = [];
        //genera pares de numeros binarios
        for ($j = 0; $j < 2; $j++) {
            $num_dec = rand(1,20);
            $numeroBinario = decbin($num_dec);
            $paresBinarios[] = $numeroBinario;
        }

        //verifica si el primer numero en el arreglo $paresBinarios es mayor al segundo, si es asi los cambia
        //de lugar, esto para evitar resultados negativos
        if($paresBinarios[0] < $paresBinarios[1]){
            $temp = $paresBinarios[0];
            $paresBinarios[0] = $paresBinarios[1];
            $paresBinarios[1] = $temp;
        }

        //dependiendo de la operacion que entre a la funcion por medio de la variable $tipo es la operacion
        // que se realiza para obtener el resultado y asignarle su operador correspondinte
        switch($tipo){
            case "suma":         
                $res = bindec($paresBinarios[0]) + bindec($paresBinarios[1]) ;
                $ope = "+";
            break;
            case "resta":
                $res = bindec($paresBinarios[0]) - bindec($paresBinarios[1]) ;
                $ope = "-";
            break;
            case "multi":
                $res = bindec($paresBinarios[0]) * bindec($paresBinarios[1]) ;
                $ope = "x";
            break;
            case "div":
                $res = bindec($paresBinarios[0]) / bindec($paresBinarios[1]) ;
                $ope = "/";
            break;
        }

        //el resultado se pasa a binario
        $res_bin = decbin($res);

        //se retornan los valores necesarios para crear las preguntas en la vista
        //el numero 1, numero 2, operador y resultado para despues revisar las respuestas del usuario
        return ["num1" => $paresBinarios[0], "num2" => $paresBinarios[1], "res" => $res_bin, "ope" => $ope];
    }

    public function page1() {
        $userId = Auth::user()->id;
    
        // Verifica si ya existe una evaluación en la sesión
        if (session()->has('current_evaluation_id')) {
            $evaluationId = session('current_evaluation_id');
            $evaluacion = Evaluacione::find($evaluationId);
    
            // Si la evaluación no se encuentra (por ejemplo, si fue eliminada), crea una nueva evaluación
            if (!$evaluacion) {
                session()->forget('current_evaluation_id');
                return redirect()->route('evaluaciones.page1');
            }
        } else {
            // Crear una nueva evaluación
            //consulta el no. de evaluaciones que ya tiene el usuari
            $no_eva = Evaluacione::where('user_id', $userId)->count();

            //datos que mandaremos para crear un nuevo registro de evaluacion
            $data = [
                "user_id" => $userId,
                //le sumamos 1 al numero de evaluaciones que tiene ya el usuario creadas
                //de este modo los nombres de cada prueba son "Prueba1", "Prueba2", etc.
                "name" => "Prueba" . ($no_eva + 1)
            ];
            
            //crea la evaluacion y almacenamos el id del nuevo registro creado en la variable $evaluacion
            $evaluacion = Evaluacione::create($data);

            //guarda una variable de sesion con el id de la evaluacion para que cuando refresque la pantalla
            // estando en la evaluacion no se cambien los valores que ya tiene ni genere otra evluacion
            session(['current_evaluation_id' => $evaluacion->id]);
    
            //crea 5 preguntas por cada operacion matematica, manda el tipo a la funcion generatepares para que 
            //le regrese todo lo que debe contener la pregunta y aqui solo insertarla a la tabla preguntas
            foreach (['suma', 'resta', 'multi', 'div'] as $tipo) {
                for ($j = 0; $j < 5; $j++) {
                    $preguntas = $this->generatepares($tipo);
                    $pregunta = new Pregunta();
                    $pregunta->num1 = $preguntas['num1'];
                    $pregunta->num2 = $preguntas['num2'];
                    $pregunta->ope = $preguntas['ope'];
                    $pregunta->res = $preguntas['res'];
                    //accedemos al id de la nueva evaluacion creada anteriormente para que las preguntas 
                    // esten relacionadas a esa evaluacion creada
                    $pregunta->evaluacione_id = $evaluacion->id;
                    $pregunta->save();
                }
            }
        }
    
        // Recupera las preguntas que tengan el mismo id de evaluacion y las pagina, 5 en cada pagina
        $preguntas_vi = Pregunta::where('evaluacione_id', '=', $evaluacion->id)->paginate(5);
    
        return view('page1', ["preg" => $preguntas_vi]);
    }
    
    public function guardares(Request $request){
        session()->forget('current_evaluation_id');
        
        return $request->all();
        // return redirect()->route('dashboard'); 
    }

    public function getev(){
        return view('evaluaciones');
    }
}
