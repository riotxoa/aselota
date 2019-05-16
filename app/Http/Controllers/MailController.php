<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Mail;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MailController extends Controller {

   public function html_email(Request $request) {

    $request->user()->authorizeRoles(['admin', 'gerente']);

    $festival_id = $request->get('festival_id');
    $festival_fecha = $request->get('festival_fecha');
    $festival_fronton = $request->get('festival_fronton');
    $organizador = $request->get('organizador');
    $televisado = $request->get('televisado');
    $partidos = $request->get('partidos');
    $coste_empresa = $request->get('coste_empresa');
    $importe_ideal = $request->get('importe_ideal');
    $importe_sugerido = $request->get('importe_sugerido');
    
    $correo_aviso_margen = $request->get('correo_aviso_margen');

    //parseamos los importes
    $coste_empresa = number_format($coste_empresa, 2, ',', '');
    $importe_ideal = number_format($importe_ideal, 2, ',', '');
    $importe_sugerido = number_format($importe_sugerido, 2, ',', '');
    
    $data = array(
        'festival_id'=>$festival_id,
        'festival_fecha'=>$festival_fecha,
        'festival_fronton'=>$festival_fronton,
        'organizador'=>$organizador,
        'televisado'=>$televisado,
        'partidos'=>$partidos,
        'coste_empresa'=>$coste_empresa,
        'importe_ideal'=>$importe_ideal,
        'importe_sugerido'=>$importe_sugerido
    );

    Mail::send('mail', $data, function($message) use ($correo_aviso_margen) {
        $message->from('info@zurdomantas.com','Baiko Pilota');
        $message->to($correo_aviso_margen, 'Organizador')->subject('Confirmación Importe Festival');
    });
    echo "Correo enviado. Por favor, espera la confirmación del supervisor.";
   }
}