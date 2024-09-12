<?php

namespace App\Http\Controllers;

use App\Models\CampanasActas;
use App\Models\GestoreActas;
use App\Models\Historial_pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Pdf extends Controller
{
    // Metodo para guardar los pdf en el servidor y guardarlos en la base de datos.
    public function SavePDFServerDB($nombre_pdf, $dataPDF,$fecha,Request $request ,$opcion = null, $n_caso = null, $operacion = null){
        $tipo_acta = null;
        switch ($opcion) {
            case 1:
                $tipo_acta = 'acta_operacion';
                break;
            case 2:
                $tipo_acta = 'acta_entrega';
                break;
            case 3:
                $tipo_acta = 'acta_retornos';
                break;
            
        }
        // Validar si existe el pdf
        if(Historial_pdf::where('numero_caso',$n_caso)->exists()){
            return Historial_pdf::where('numero_caso',$n_caso)->get();
        }

        $horas = Carbon::now()->format('s.u');
        $salidaPDF = $dataPDF->output();
        $rutaPdf = public_path('pdf/');
        $nombrePdf = $nombre_pdf.'_'.$fecha.'_'.$horas.'.pdf';
        file_put_contents($rutaPdf.$nombrePdf,$salidaPDF);
        $rutaURL = 'pdf/'.$nombrePdf;
        // Guardar en la base de datos
        $sesion_inicio = $request->session()->get('gestor_session')->id;

        $historial = new Historial_pdf();
        $historial->tipo_acta = $tipo_acta;
        $historial->ruta_pdf = $rutaURL;
        $historial->fk_id_gestor = $sesion_inicio;
        // Validar que la llave de la camapaña se encuentre en la DB.
        if($operacion != null){
            $historial->fk_id_camp = CampanasActas::where('nombre_camp',$operacion)->first()->id;          
        }
        $historial->fecha_creacion = $fecha;
        $historial->numero_caso = $n_caso;
        $historial->save();


    
    }
    // Inicio de sesion vista
    public function Login(Request $request){
        // Validar si hay una sesion iniciada
        if($request->session()->has('gestor_session')){
            $response = response(view('home'))->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            return $response;
        }
        return response(view('login'))->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }

    // Procesar el inicio de sesion
    public function InicioSesionValidate(Request $request){
        // Validar si existe el usuario
        if(GestoreActas::where('cedula',$request->input('cedula'))->exists()){
            $usuario = GestoreActas::where('cedula',$request->input('cedula'))->first();
            // Validar si el usuario ya asigno contraseña
            if($usuario->password == ''){
                $usuario->password = md5($request->input('password')); // Encryptar la clave
                $usuario->save();
                return 1;
                // Si se asigno clave posteriormente inicia ya la cuenta
            }else if($usuario->password != '' && $usuario->usuario_activo){
                // Validar si la clave ingresada coincide con la de la BD
                if($usuario->password == md5($request->input('password'))){
                    $request->session()->put('gestor_session',$usuario);
                    return 2;
                }
                // Si no entra a la condicion significa que no digito bien la contraseña o no esta activo el usuario
                return 4;
            }
        }
        return 0; // No existe el usuario en la base de datos
    }



    // Vista principal de los formularios
    public function Home(Request $request){
        if($request->session()->has('gestor_session')){
            return view('actas');
        }
        return response(view('login'))->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    }


    public function index(){
        return redirect()->route('home');
    }

    // Generar PDF de la operacion
    public function pdfOperacion(Request $request){
        ini_set('max_execution_time', 120); 
        $nombre_gestor_bodega = strtoupper($request->nombre_gestor_bodega);
        // Datos basicos
        $nombre_encargado = strtoupper($request->input('nombre_encargado'));
        $documento_encargado = $request->input('documento_encargado');
        $correo_encargado = $request->input('correo_encargado');
        // N° Caso
        $n_caso = $request->input('n_caso');
        // Motivos
        $motivo_solicitud = $request->input('motivo_solicitud');
        $op_solicitante = $request->input('op_solicitante');
        $est_entrega_nuevoActivo = $request->input('est_entrega_nuevoActivo');
        $est_recibido_activo = $request->input('est_recibido_activo');
        // Fecha automatizada
        $fecha_entrega = Carbon::now()->toDateString();
        // Datos equipos recogidos
        $data_recogido = $request->input('data_recogido');
        // Datos equipos entregados
        $data_entregado = $request->input('data_entregado');
        // Observaciones
        $observaciones = $request->input('observaciones');
        // Firmas
        $nombre_gestor = strtoupper($request->input('nombre_gestor'));
        $cargo_operacion = strtoupper($request->input('cargo_operacion'));
        $nombre_operacion = strtoupper($request->input('nombre_operacion'));

        // Recoger las firmas 
        $firma1 = $request->input('firma1');
        $firma2 = $request->input('firma2');
        $firma3 = $request->input('firma3');

        // Decodificar las dos firmas
        $data = $this->encodeImagen($firma1, $firma2, $firma3);

        $ruta1 = $data['ruta1'];
        $ruta2 = $data['ruta2'];
        $ruta3 = $data['ruta3'];
        $rutaLogo = $data['rutaLogo'];

        $opciones = new Options();
        $opciones->set('isHtml5ParserEnabled', true);
        // $opciones->set('isRemoteEnabled', true);


        try{
            $pdf = new Dompdf($opciones);   
            // Compactar todas la variables para enviarlas al PDF
            $vista = view('pdf_operacion',compact('nombre_encargado',
            'documento_encargado',
            'nombre_gestor_bodega',
            'correo_encargado',
            'n_caso',
            'motivo_solicitud',
            'op_solicitante',
            'est_entrega_nuevoActivo',
            'est_recibido_activo',
            'fecha_entrega',
            'data_recogido',
            'data_entregado',
            'observaciones',
            'nombre_gestor',
            'cargo_operacion',
            'nombre_operacion',
            'ruta1',
            'ruta2',
            'ruta3',
            'rutaLogo'));

            $pdf->loadHtml($vista);
            $pdf->render();
            // Guardar el pdf en el servidor y base de datos.
            $this->SavePDFServerDB($op_solicitante,$pdf,$fecha_entrega,$request,1,$n_caso,$op_solicitante);

        }catch(Exception $e){
            error_log($e->getMessage());
        }

        
        return $pdf->stream('prueba.pdf');
    }

    // Generar PDF de los retornos
    public function pdfRetorno(Request $request){
        ini_set('max_execution_time', 120); 
        $dispositivo = $request->dispositivo;
        $tipoEscritorio = $request->tipoescritorio;
        
        $numeroCaso = $request->numeroCaso;
        $nombres = $request->nombres;
       
        $campana = $request->campana;
        $correoPersonal = $request->correoPersonal;
        $correoJefe = $request->correoJefe;
        $serialDispositivo = $request->serialDispositivo;
        $activoDispositivo = $request->activoDispositivo;
        $estadoDispositivo = $request->estadoDispositivo;
        $diadema = $request->diadema;
        $marcaDiadema = $request->marcaDiadema;
        $serialDiadema = $request->serialDiadema;
        $estadoDiadema = $request->estadoDiadema;
        $raton = $request->raton;
        $estadoRaton = $request->estadoRaton;
        $teclado = $request->teclado;
        $estadoTeclado = $request->estadoTeclado;
        $camara = $request->camara;
        $serialCpu = $request->serialCpu;
        $activoCpu = $request->activoCpu;
        $estadoCpu = $request->estadoCpu;
        $serialMonitor = $request->serialMonitor;
        $activoMonitor = $request->activoMonitor;
        $estadoMonitor = $request->estadoMonitor;
        $segundoMonitor = $request->segundoMonitor;
        $serialMonitor2 = $request->serialMonitor2;
        $activoMonitor2 = $request->activoMonitor2;
        $tieneDiadema = $request->tieneDiadema;
        $diademaSerial = $request->Diademaserial;
        $observaciones = $request->observaciones;
        $nombreRecibe = $request->NombreRecibe;
        $firma1 = $request->firma1;
        $firma2 = $request->firma2;

        // Modelos monitor_dispositivo

        $marcaDispositivo = $request->marcaDispositivo;
        $modeloDispositivo = $request->modeloDispositivo;
        $marcaMonitor = $request->marcaMonitor;
        $modeloMonitor = $request->modeloMonitor;
        $marcaMonitor2 = $request->marcaMonitor2;
        $modeloMonitor2 = $request->modeloMonitor2;



        // Decodificar las dos firmas
        $data = $this->encodeImagen($firma1, $firma2);

        $ruta1 = $data['ruta1'];
        $ruta2 = $data['ruta2'];
        $rutaLogo = $data['rutaLogo'];


        // Compactar todas la variables para enviarlas al PDF
        $datos_pdf = compact('dispositivo', 'tipoEscritorio', 
        'numeroCaso', 'nombres', 'campana', 'correoPersonal', 
        'correoJefe', 'serialDispositivo', 'activoDispositivo', 
        'estadoDispositivo', 'diadema', 'serialDiadema', 'raton', 
        'estadoRaton', 'teclado', 'estadoTeclado', 'camara', 'serialCpu', 
        'activoCpu', 'estadoCpu', 'serialMonitor', 'activoMonitor', 'estadoMonitor',
         'segundoMonitor', 'serialMonitor2', 'activoMonitor2', 'tieneDiadema',  'marcaDiadema', 'estadoDiadema',
          'observaciones', 'nombreRecibe', 'ruta1', 'ruta2','rutaLogo','marcaDispositivo','modeloDispositivo'
        ,'marcaMonitor','modeloMonitor','marcaMonitor2','modeloMonitor2');

        $opciones = new Options();
        $opciones->set('isHtml5ParserEnabled', true);

        try{
            $pdf = new Dompdf($opciones);   
            $vista = view('pdf_retornos',$datos_pdf);

            $pdf->loadHtml($vista);
            $pdf->render();

            // Guardar el pdf en el servidor y base de datos.
            $this->SavePDFServerDB($nombres.'_A_'.$nombreRecibe,$pdf,Carbon::now()->toDateString(),$request,3,$numeroCaso,$campana);

        }catch(Exception $e){
            error_log($e->getMessage());
        }

        // Finalizar la descarga
        return $pdf->stream('retorno.pdf');

    }



    // Generar PDF de Entregas
    public function pdfEntrega(Request $request){
        ini_set('max_execution_time', 120);
        $dispositivo = $request->dispositivo;
        $tipoEscritorio = $request->Tipoescritorio;
        $numeroCaso = $request->numeroCaso;
        $fechaEntrega = $request->fechaEntrega;
        $nombres = $request->nombres;
        $observaciones = $request-> observaciones;
        $campana = $request->campana;
        $cedula = $request->cedula;
        $expedicionCedula = $request->expedicionCedula;
        $concepto = $request-> concepto;
        $correoPersonal = $request->correoPersonal;
        $correoJefe = $request->correoJefe;
        $marcaDispositivo = $request -> marcaDispositivo;
        $modeloDispositivo = $request -> modeloDispositivo;
        $serialDispositivo = $request->serialDispositivo;
        $activoDispositivo = $request->activoDispositivo;
        $estadoDispositivo = $request->estadoDispositivo;
        $serialEquipoAnterior = $request->serialEquipoAnterior;
        $activoEquipoAnterior = $request->activoEquipoAnterior;
        $diadema = $request->diadema;
        $marcaDiadema = $request->marcaDiadema;
        $serialDiadema = $request->serialDiadema;
        $estadoDiadema = $request->estadoDiadema;
        $raton = $request->raton;
        $estadoRaton = $request->estadoRaton;
        $teclado = $request->teclado;
        $estadoTeclado = $request->estadoTeclado;
        $marcaMonitor = $request -> marcaMonitor;
        $modeloMonitor = $request -> modeloMonitor;
        $serialMonitor = $request->serialMonitor;
        $activoMonitor = $request->activoMonitor;
        $estadoMonitor = $request->estadoMonitor;
        $segundoMonitor = $request->segundoMonitor;
        $serialMonitor2 = $request->serialMonitor2;
        $activoMonitor2 = $request->activoMonitor2;
        $tieneDiadema = $request->tieneDiadema;
        $IMEI = $request->IMEI;
        $IMEI2 = $request->IMEI2;
        $diademaSerial = $request->Diademaserial;
        $observaciones = $request->observaciones;
        $nombreRecibe = $request->NombreRecibe;
        $firma1 = $request->firma1;
        $firma2 = $request->firma2;
        // Decodificar las dos firmas
        $data = $this->encodeImagen($firma1, $firma2);

        $ruta1 = $data['ruta1'];
        $ruta2 = $data['ruta2'];
        $rutaLogo = $data['rutaLogo'];

       

         // Compactar todas la variables para enviarlas al PDF
         $datos_pdf = compact('dispositivo', 'tipoEscritorio', 'fechaEntrega',
         'numeroCaso', 'nombres', 'campana', 'correoPersonal','cedula', 'concepto','serialEquipoAnterior','activoEquipoAnterior',
         'correoJefe', 'serialDispositivo', 'activoDispositivo', 'expedicionCedula','IMEI','IMEI2',
         'estadoDispositivo', 'diadema', 'serialDiadema', 'raton', 'observaciones',
         'estadoRaton', 'teclado', 'estadoTeclado',  'serialMonitor', 'activoMonitor', 'estadoMonitor',
          'segundoMonitor',  'marcaDiadema', 'estadoDiadema',
           'observaciones', 'nombreRecibe', 'ruta1', 'ruta2','rutaLogo','marcaDispositivo','modeloDispositivo'
         ,'marcaMonitor','modeloMonitor');

         $opciones = new Options();
        $opciones->set('isHtml5ParserEnabled', true);

        try{
            $pdf = new Dompdf($opciones);   
            $vista = view('pdf_Entregas',$datos_pdf);

            $pdf->loadHtml($vista);
            $pdf->render();

            // Guardar el pdf en el servidor y base de datos.
            $this->SavePDFServerDB($nombres.'_A_'.$nombreRecibe,$pdf,Carbon::now()->toDateString(),$request,2,$numeroCaso,$campana);

        }catch(Exception $e){
            error_log($e->getMessage());
            return response()->json($e->getMessage());
        }

        // Finalizar la descarga
        return $pdf->stream('entrega.pdf');

    }

    // Funcion para codificar imagenes de las firmas y logo de la compañia
    public function encodeImagen($firma1, $firma2, $firma3 = null){
        $firma1 = str_replace('data:image/png;base64,', '', $firma1);
        $firma2 = str_replace('data:image/png;base64,', '', $firma2);
        $firma3 = str_replace('data:image/png;base64,', '', $firma3);

        $firma1 = str_replace(' ', '+', $firma1);
        $firma2 = str_replace(' ', '+', $firma2);
        $firma3 = str_replace(' ', '+', $firma3);

        $firmaImg1 = base64_decode($firma1);
        $firmaImg2 = base64_decode($firma2);
        $firmaImg3 = base64_decode($firma3);

        // Guardar imagenes en el servidor
        $ruta1 = public_path(sprintf('firmas\%s.png','Firma1'));
        $ruta2 = public_path(sprintf('firmas\%s.png','Firma2'));
        $ruta3 = public_path(sprintf('firmas\%s.png','Firma3'));
        $rutaLogo = public_path('images\americas.png');

        file_put_contents($ruta1, $firmaImg1);
        file_put_contents($ruta2, $firmaImg2);
        file_put_contents($ruta3, $firmaImg3);

        $ruta1 = File::get($ruta1);
        $ruta1 = base64_encode($ruta1);

        $ruta2 = File::get($ruta2);
        $ruta2 = base64_encode($ruta2);
        
        $ruta3 = File::get($ruta3);
        $ruta3 = base64_encode($ruta3);

        $rutaLogo = File::get($rutaLogo);
        $rutaLogo = base64_encode($rutaLogo);

        return compact('ruta1', 'ruta2', 'ruta3', 'rutaLogo');
    }


    // Validar el rol para permitir acciones de administrador
    public function registroCamGestor(Request $request){
        if($request->session()->has('gestor_session')){
            $usuario = $request->session()->get('gestor_session');
            if($usuario->rol == 'administrador'){
                return true;
            }
        }        
        return false;
    }
    // Pagina de administrador
    public function adminPage(Request $request){
        if($request->session()->has('gestor_session')){
           return view('registro_camps_gestores');
        }
        return abort(403);
    }

    // Destrozar la sesion al cerrar
    public function SignOut(Request $request){

        $request->session()->flush();

        return true;
    }

    // Destrozar la sesion al cerrar , automatizadamente
    public function SignOutAuto(Request $request){

        if($request->session()->has('gestor_session')){
            $request->session()->flush();   
            return true;
        }
        return false;
    }

    
}
