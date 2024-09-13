<?php

namespace App\Http\Controllers;

use App\Models\GestoreActas;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GestoresActas extends Controller
{
    // Agregar gestores
    public function AgregarGestores(Request $request){

        // Validar si ya existe el correo
        $gestor = GestoreActas::where('cedula',$request->input('cedula'))->exists();
        if($gestor){
            return response()->json(true);
        }else{
            // Instancia de modelo gestor
            $gestor = new GestoreActas();
            $gestor->cedula = $request->input('cedula');
            $gestor->nombre_gestor = $request->input('nombre');
            $gestor->correo = $request->input('correo');
            $gestor->rol = $request->input('rol');
            $gestor->password = '';
            $gestor->usuario_activo = true;
            $gestor->save();
            return response()->json(false);
        }

    }
    // Modificar
    public function ModificarGestores($id, Request $request){
        $gestor = GestoreActas::find($id);
        $gestor->cedula = $request->input('cedula');
        $gestor->nombre_gestor = $request->input('nombre_gestor');
        $gestor->correo = $request->input('correo');
        $gestor->rol = $request->input('rol');
        $gestor->save();
        return true;
    }
    // Inactivar usuario
    public function EliminarGestores($id){
        $gestores = GestoreActas::find($id);
        $gestores->usuario_activo = false;
        $gestores->save();
        return response()->json(true);
    }
    // Activar usuario
    public function ActivarGestor($id){
        $gestores = GestoreActas::find($id);
        $gestores->usuario_activo = true;
        $gestores->save();
        return response()->json(true);
    }

    // Bloquear usuario por intentos en el login
    public function BlockGestor($id){
        try{
            $gestores = GestoreActas::where('cedula',$id)->first();
            $gestores->usuario_activo = false;
            $gestores->save();
        }catch(Exception $e){
            error_log($e);
        }
        return response()->json(true);
    }

    // Mostrar a todos los gestores de la base de datos
    public function MostrarGestores(){
        $gestores = GestoreActas::where('usuario_activo',true)->orderBy('nombre_gestor','ASC')->get();
        // Devolver la respuesta en formato JSON
        return response()->json($gestores);
    }

    // Mostrar a todos los que conforman la base de datos
    public function MostrarTodos(Request $request){
        $gestores = GestoreActas::orderBy('nombre_gestor','ASC')->get();
        // Devolver la respuesta en formato JSON
        return response()->json($gestores);
    }

    // Buscar a un gestor en especifico al filtrarlo en vue
    public function BuscarGestor(Request $request){
        $gestor = GestoreActas::find($request->input('id'));
        return response()->json($gestor);
    }

    // Buscar a un gestor por nombre
    public function BuscarGestorName(Request $request){
        $gestor = GestoreActas::where('nombre_gestor',$request->input('nombre_persona'))->first();
        return response()->json($gestor);
    }

    // Saber que usuario inicio sesion
    public static function getSessionGestor(Request $request){
        $usuario = $request->session()->get('gestor_session');
        return response()->json($usuario);
    }

    // Restablecer contraseña
    public function ResetPassGestor($id){
        $gestor = GestoreActas::find($id);
        $gestor->password = '';
        $gestor->save();
        return true;
    }

    // Mostrar usuarios que tienen permitido la copia a correo
    public static function GestorSendMail(){
        return response()->json(GestoreActas::where('copia_correo',true)->get());
    }
    // Quitar usuarios que tenian permitida la copia.
    public static function DeleteGestorSendMail($id){
        $gestor = GestoreActas::find($id);
        $gestor->copia_correo = false;
        $gestor->save();
        return response()->json(true);
    }

    // Agregar usuarios que se les permitira la copia.
    public function AddGestorSendMail($id){
        $gestor = GestoreActas::find($id);
        $gestor->copia_correo = true;
        $gestor->save();
        return response()->json(true);
    }

}
