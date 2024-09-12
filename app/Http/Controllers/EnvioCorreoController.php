<?php

namespace App\Http\Controllers;

use App\Mail\SendMails;
use App\Models\GestoreActas;
use App\Models\Historial_pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class EnvioCorreoController extends Controller
{
    // Envio correo para el cambio de inventario y soporte de un elemento
    public function SendInventory(Request $request){
        // Listar los correos a los que se va a enviar la informacion.
        $correos_send = [];

        // Correos del inventario
        $correos_send[] = $request->correo_encargado ?? 'exmapl@abps.com';
        // Correos de retornos y entrega
        $correos_send[] = $request->correoPersonal ?? 'exmapl@abps.com';
        $correos_send[] = $request->correoJefe ?? 'exmapl@abps.com';
        // Correo del gestor que inicio sesion
        $usuario = GestoresActas::getSessionGestor($request);
        // Gestor que entrega como bodega
        if(GestoreActas::where('nombre_gestor',$request->nombre_gestor_bodega)->exists()){
            $usuario_bodega = GestoreActas::where('nombre_gestor',$request->nombre_gestor_bodega)->get();
            $correos_send[] = $usuario_bodega[0]->correo;
        }

        // Llenar la lista con los usuarios que siempre tendran la copia del acta
        $usuarios_copia = GestoresActas::GestorSendMail();
        foreach ($usuarios_copia->original as $gestor_copia) {
            $correos_send[] = $gestor_copia->correo;
        }

        try {
            $pdf = new Pdf();
            // Crear el pdf al guardarlo en la base de datos
            $pdf->pdfOperacion($request);
            $ruta = Historial_pdf::where('numero_caso',$request->n_caso)->get();

            //code...
            $data = [
                'nombre_encargado'=>$request->nombre_encargado,
                'tipo_acta'=>$request->tipo_formulario,
                'nombre_gestor'=>$request->nombre_gestor,
                'cargo_operacion'=>$request->cargo_operacion,
                'op_solicitante'=>$request->op_solicitante,         
                'n_caso'=>$request->n_caso,         
                'ruta_pdf'=>$ruta[0]->ruta_pdf,
            ];

            // Enviar el correo a todos los usuarios del formulario y permitidos a la copia del mismo
            Mail::to($usuario->original->correo)->cc($correos_send)->send(new SendMails($data));

            return true;            
        } catch (\Throwable $th) {
            //throw $th;
            error_log($th->getMessage());
            return false;
        }
    }

}
