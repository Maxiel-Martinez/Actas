<?php

use App\Http\Controllers\CampanasActas;
use App\Http\Controllers\ComponentesController;
use App\Http\Controllers\DevolucionEquipoController;
use App\Http\Controllers\EnvioCorreoController;
use App\Http\Controllers\GestoresActas;
use App\Http\Controllers\Historiales_actas;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\Pdf;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


// Generar pdf
Route::controller(Pdf::class)->group(function(){
    Route::get('/', 'index');
    Route::get('/Actas','Home')->name('home');
    Route::post('/PDF_OP','pdfOperacion')->name('pdf_operacion');
    Route::post('/PDF_G','pdfGestor')->name('pdf_gestor');
    Route::post('/PDF_RTN','pdfRetorno')->name('pdf_retorno');
    Route::post('/PDF_ETN',  'pdfEntrega')->name('pdf_entrega');
    // Rutas de login
    Route::get('/Actas_de_responsabilidad/Login','Login')->name('inicio_sesion');
    Route::post('/Actas_de_responsabilidad/Login/Validate','InicioSesionValidate')->name('validar_inicio_sesion');
    Route::get('/Actas_de_responsabilidad/Login/Destroy','SignOut')->name('cerrar_sesion');
    Route::get('/Actas_de_responsabilidad/Login/DestroyAuto','SignOutAuto')->name('cerrar_sesion_auto');
});

// Acciones con los gestores de la base de datos
Route::controller(GestoresActas::class)->group(function(){
    Route::get('/Actas_de_responsabilidad/Gestores','MostrarGestores')->name('mostrar_gestor');
    Route::get('/Actas_de_responsabilidad/Gestores/Admin','MostrarTodos')->name('mostrar_gestor');
    Route::post('/Actas_de_responsabilidad/Gestores/Registro','AgregarGestores')->name('registro_gestor');
    Route::post('/Actas_de_responsabilidad/Gestores/BuscarGestor','BuscarGestor')->name('buscar_gestor');
    Route::post('/Actas_de_responsabilidad/Gestores/BuscarGestorName','BuscarGestorName')->name('buscar_gestor_nombre');
    Route::delete('/Actas_de_responsabilidad/Gestores/Destroy/{id}','EliminarGestores')->name('borrar_gestor');
    Route::put('/Actas_de_responsabilidad/Gestores/Update/{id}','ModificarGestores')->name('editar_gestor');
    Route::get('/Actas_de_responsabilidad/Gestores/Session','getSessionGestor')->name('inicio_gestor');
    Route::post('/Actas_de_responsabilidad/Gestores/ResetPass/{id}','ResetPassGestor')->name('restablecer_gestor');
    Route::get('/Actas_de_responsabilidad/Gestores/Activar_usuario/{id}','ActivarGestor')->name('activar_gestor');
    Route::get('/Actas_de_responsabilidad/Gestores/BloquearUsuario/{id}','BlockGestor')->name('bloquear_gestor');
    Route::get('/Actas_de_responsabilidad/GestoresAll','MostrarTodos')->name('mostrar_gestorall');
    // Copias de acta a usuarios permitidos por el administrador
    Route::get('/Actas_de_responsabilidad/Gestores/SendCC','GestorSendMail')->name('enviarcopia_correo');
    Route::post('/Actas_de_responsabilidad/Gestores/SendCC/Delete/{id}','DeleteGestorSendMail')->name('delete_enviarcopia_correo');
    Route::post('/Actas_de_responsabilidad/Gestores/SendCC/Add/{id}','AddGestorSendMail')->name('add_enviarcopia_correo');
});

Route::controller(Historiales_actas::class)->group(function(){
    Route::get('/Actas_de_responsabilidad/Gestores/Filtro/{fk_gestor}','BuscarHistorialGestor')->name('historial_gestor');
    // Rutas de historial
    Route::post('/Actas_de_responsabilidad/Historial/{id}','AccederDatos')->name('historial_actas');
    Route::post('/Actas_de_responsabilidad/Historial/DownloadPDF/{id}','DownloadAgainPDF')->name('download_actas');
    Route::post('/Actas_de_responsabilidad/Historial/BuscarCaso/{id}','BuscarHistorialBasic')->name('buscar_actas');
    Route::post('/Actas_de_responsabilidad/Historial/BuscarCasoAvanzado/{f_inicio}/{f_fin}/{id_gestor}','BuscarHistorialHard')->name('buscar_actas');
    Route::get('/Actas_de_responsabilidad/Historial/MyDocuments','historialMyActas')->name('myActas');
    Route::post('/Actas_de_responsabilidad/Historial/MyDocuments/Show','showMyDocuments')->name('showMyActas');
    Route::get('/Actas_de_responsabilidad/Campanas/Filtro/{fk_cam}','BuscarHistorialCamp')->name('historial_cam');
});

// Acciones con las campañas
Route::controller(CampanasActas::class)->group(function(){
    Route::get('/Actas_de_responsabilidad/Campanas','MostrarCam')->name('mostrar_cam');
    Route::get('/Actas_de_responsabilidad/CampanasAll','MostrarCamAll')->name('mostrar_camall');
    Route::post('/Actas_de_responsabilidad/Campanas/Registro','AgregarCam')->name('agregar_cam');
    Route::post('/Actas_de_responsabilidad/Campanas/BuscarCamp','BuscarCamp')->name('buscar_cam');
    Route::delete('/Actas_de_responsabilidad/Campanas/Destroy/{id}','EliminarCam')->name('borrar_cam');
    Route::get('/Actas_de_responsabilidad/Campanas/ActivarCam/{id}','HabilitarCam')->name('activar_cam');
    Route::put('/Actas_de_responsabilidad/Campanas/Update/{id}','ModificarCam')->name('editar_cam');
});
    
// Registro de campañas y registros solo a los que son administradores
Route::middleware('check_admin')->controller(Pdf::class)->group(function(){
    Route::get('/Actas_de_responsabilidad/Registro/Camps_y_gestores','registroCamGestor')->name('registro_camps_gestor');
    Route::get('/Actas_de_responsabilidad/Registro/AdminPage','adminPage')->name('pagina_admin');
});


// Acciones con componentes en la base de datos
Route::controller(ComponentesController::class)->group(function(){
    Route::post('/Actas_de_responsabilidad/Componentes/Agregar_com','AgregarComponente')->name('agregar_componentes');
    Route::get('/Actas_de_responsabilidad/Componentes','MostrarComponente')->name('mostrar_componentes');
    Route::post('/Actas_de_responsabilidad/Componentes/Buscar_com/{id}','BuscarComponente')->name('buscar_componentes');
    Route::put('/Actas_de_responsabilidad/Componentes/Update/{id}','ModificarComponente')->name('editar_componentes');
    Route::delete('/Actas_de_responsabilidad/Componentes/Destroy/{id}','BorrarComponente')->name('borrar_componentes');
});

// Acta de retornos
Route::get('/devolucion-equipo', function () {
    return view('devolucion-equipo');
});



// Envio de correos
Route::controller(EnvioCorreoController::class)->group(function(){
    Route::post('/Actas/Emails','SendInventory')->name('envio_inventario');
});

