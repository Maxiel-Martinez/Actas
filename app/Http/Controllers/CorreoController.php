<?php

namespace App\Http\Controllers;

use App\Mail\CorreoElectronico;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CorreoController extends Controller
{
    public function enviarCorreo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'attachment' => 'file|mimes:pdf,doc,docx|max:2048', // opcional, 2MB max
        ]);

        $data = [
            'subject' => $request->subject,
            'name' => $request->name,
        ];

        $correo = new CorreoElectronico($data);

        // Agregar adjunto si se ha subido un archivo
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('temp');
            $correo->attach(Storage::path($path), [
                'as' => $request->file('attachment')->getClientOriginalName(),
                'mime' => $request->file('attachment')->getMimeType(),
            ]);
        }

        Mail::to($request->email)->send($correo);

        // Eliminar el archivo temporal después de enviar el correo
        if (isset($path)) {
            Storage::delete($path);
        }

        return response()->json(['message' => 'Correo enviado con éxito']);
    }
}