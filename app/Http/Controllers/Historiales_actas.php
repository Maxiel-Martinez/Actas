<?php

namespace App\Http\Controllers;

use App\Models\Historial_pdf;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;

class Historiales_actas extends Controller
{
    
    // Acceder a las ultimas 4 actas generadas por el gestor seleccionado
    public function AccederDatos($id){
        return Historial_pdf::where('fk_id_gestor',$id)->orderBy('id','desc')->take(4)->get();
    }
    
    // Opcion que permite volver a descargar
    public function DownloadAgainPDF($id){
        $ruta = public_path(Historial_pdf::find($id)->ruta_pdf);
        return response()->download($ruta,'prueba.pdf');
    }

    // Buscar en el historial por medio del numero de caso.
    public function BuscarHistorialBasic($id,Request $request){
        // Tomar los ultimos 20
        $historial = Historial_pdf::where('numero_caso','like','%'.$id.'%')->take(20)->get();
        return response()->json($historial);

    }
    // Buscar en el historial por medio del numero de caso y fechas.
    public function BuscarHistorialHard($f_inicio, $f_fin, $id_gestor){
        // Tomar los ultimos 20
        $historial = Historial_pdf::whereBetween('fecha_creacion',
        [$f_inicio,$f_fin])->where('fk_id_gestor',$id_gestor)->orderBy('fecha_creacion','DESC')->take(30)->get();
        return response()->json($historial);

    }


    // Buscar en el historial por medio de la campaña
    public function BuscarHistorialCamp($fk_cam){
        // Tomar los ultimos 20
        try {
            $historial = Historial_pdf::where('fk_id_camp',$fk_cam)->take(20)->get();
        } catch (Exception $th) {
            error_log($th);
            return response()->json([]);
        }
        return response()->json($historial);
    }


    // Actas generadas por el usuario
    public function historialMyActas(Request $request){
        if($request->session()->has('gestor_session')){
            return view('my_documents');
        }
        return redirect()->route('inicio_sesion');
    }

    // Buscar las actas sobre el gestor que inicio sesion
    public function showMyDocuments(Request $request){
        return response()->json(Historial_pdf::where('fk_id_gestor',$request->id)->orderBy('fecha_creacion','DESC')->take(12)->get());
    }



}
