<?php

namespace App\Http\Controllers;

use App\Mail\ResultadosMail;
use App\Models\Evaluacione;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class EvaluacionesController extends Controller
{
    public function generatepares($tipo){
        $paresBinarios = [];

        for ($j = 0; $j < 2; $j++) {
            $num_dec = rand(1, 100);
            $numerohexa = dechex($num_dec);
            $pareshexa[] = $numerohexa;
        }

       
        if( hexdec($pareshexa[0]) < hexdec($pareshexa[1])){
            $temp = $pareshexa[0];
            $pareshexa[0] = $pareshexa[1];
            $pareshexa[1] = $temp;
        }

        
        switch($tipo){
            case "suma":         
                $res = hexdec($pareshexa[0]) + hexdec($pareshexa[1]) ;
                $ope = "+";
            break;
            case "resta":
                $res = hexdec($pareshexa[0]) - hexdec($pareshexa[1]) ;
                $ope = "-";
            break;
            case "multi":
                $res = hexdec($pareshexa[0]) * hexdec($pareshexa[1]) ;
                $ope = "x";
            break;
            case "div":
                $res = hexdec($pareshexa[0]) / hexdec($pareshexa[1]) ;
                $ope = "/";
            break;
        }


        $res_bin = decbin($res);
       
        return ["num1" => $pareshexa[0], "num2" => $pareshexa[1], "res" => $res_bin, "ope" => $ope];
    }

    public function page1() {

        $userId = Auth::user()->id;
    
        if (session()->has('current_evaluation_id')) {
            $evaluationId = session('current_evaluation_id');
            $evaluacion = Evaluacione::find($evaluationId);
    
            if (!$evaluacion) {
                session()->forget('current_evaluation_id');
                return redirect()->route('evaluaciones.page1');
            }
        } else {
            
            $no_eva = Evaluacione::where('user_id', $userId)->count();

            $data = [
                "user_id" => $userId,
                
                "name" => "Prueba" . ($no_eva + 1)
            ];
            
            $evaluacion = Evaluacione::create($data);

            
            session(['current_evaluation_id' => $evaluacion->id]);
    
           
            foreach (['suma','resta', 'multi', 'div'] as $tipo) {
                for ($j = 0; $j < 5; $j++) {
                    $preguntas = $this->generatepares($tipo);
                    $pregunta = new Pregunta();
                    $pregunta->num1 = $preguntas['num1'];
                    $pregunta->num2 = $preguntas['num2'];
                    $pregunta->ope = $preguntas['ope'];
                    $pregunta->res = $preguntas['res'];
                   
                    $pregunta->evaluacione_id = $evaluacion->id;
                    $pregunta->save();
                }
            }
        }
    
        $preguntas_vi = Pregunta::where('evaluacione_id', '=', $evaluacion->id)->paginate(5);

        $respuestas = Session::get('respuestas', []);
    
        return view('page1', ["preg" => $preguntas_vi, "respuestas" => $respuestas]);
    }
    
    public function guardares(Request $request) {
        $page = $request->input('page', 1);
    
        $respuestas = Session::get('respuestas', []);

        foreach ($request->input('res_usu', []) as $id => $respuesta) {
            $respuestas[$id] = $respuesta;
        }
    
        Session::put('respuestas', $respuestas);
    
        if ($page == 'finalizar') {

            $validator = Validator::make([
                'res_usu' => $respuestas 
            ], [
                'res_usu.*' => ['required', 'regex:/^[01]+$/'],
            ]);
    
        if ($validator->fails()) {
            return redirect()
                    ->route('evaluaciones.page1', ["page" => $page])
                    ->withErrors($validator)->withInput();
        }
            foreach ($respuestas as $id => $respuesta) {
                $pregunta = Pregunta::find($id);
                if($pregunta){
                    $pregunta->res_usu = $respuesta;
                    $respuesta == $pregunta->res ? $pregunta->res_final = 1 : $pregunta->res_final = 0;
                    $pregunta->save();
                }
            }
            
            $id_ev = Session::get('current_evaluation_id');
            $bien = Pregunta::where('res_final', '=', 1)
                                ->where('evaluacione_id', '=', $id_ev)
                                ->count();
            $ev = Evaluacione::findOrFail($id_ev);
            $url = URL::signedRoute('evaluaciones.download', ['id' => $id_ev]);
            $ev->url_firmada = $url;
            $ev->calificacion = $bien."/20";
            $ev->resultados_pdf = "public/pdfs/".$ev->user_id .$ev->name.".pdf";
            $ev->save();

            $preguntas = Pregunta::where('evaluacione_id', "=", $id_ev)->get();
            $pdf = PDF::loadView('respuestasPDF', ["preguntas" => $preguntas, "name_ev" => $ev->name]);
            $pdf->save(storage_path('app/public/pdfs/'.$ev->user_id .$ev->name.".pdf"));

            Mail::to(auth()->user()->email)->send( new ResultadosMail($ev, $ev->resultados_pdf));

            // Limpiar la sesión después de guardar las respuestas
            Session::forget('respuestas');
            Session::forget('current_evaluation_id');
    
            return redirect()->route('dashboard');
        }
    
        // Redirigir al usuario a la página correspondiente
        return redirect()->route('evaluaciones.page1', ['page' => $page]);
    }

    public function getev(){

        if (auth()->user()->role === 'admin') {
            $evaluaciones = Evaluacione::with('user')->get();
        }else{
            $evaluaciones = Evaluacione::where('user_id', '=', auth()->user()->id)->get();
        }

        return view('evaluaciones', ["evaluaciones" => $evaluaciones]);
    }

    public function download($id){
        $evaluacion = Evaluacione::findOrFail($id);
        $fileContent = Storage::get($evaluacion->resultados_pdf);
        return response($fileContent, 200)
            ->header('Content-Type', 'application/pdf');

    }
}
