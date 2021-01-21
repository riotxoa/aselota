<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use App\User;
use App\Notifications\PelotariNoDisponible;

use Illuminate\Support\Facades\Log;

class Med2PartesController extends Controller
{

    public function getPartesProfesionales(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $pelotaris = $this->getPartesPelotaris(0);

      return response()->json($pelotaris, 200);
    }

    public function getPartesPromesas(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $pelotaris = $this->getPartesPelotaris(1);

      return response()->json($pelotaris, 200);
    }

    private function getPartesPelotaris($promesa = 0) {
      $pelotaris = \App\Pelotari::select('id', 'num_trabajador', 'alias', 'posicion', 'promesa', 'fecha_nac', 'email', 'telefono', 'foto')
        ->where('promesa', $promesa)
        ->orderBy('alias', 'asc')
        ->get();

      foreach( $pelotaris as $pelotari ) {
        $pelotari->accidente = \App\Med2PAccidente::where('pelotari_id', $pelotari->id)->orderBy('fecha_parte', 'desc')->get();
        $pelotari->delta = \App\Med2PDelta::leftJoin('med2_p_accidente', 'med2_p_accidente.id', '=', 'med2_p_delta.p_accidente_id')->where('pelotari_id', $pelotari->id)->orderBy('med2_p_delta.fecha_accidente', 'desc')->get();
        $pelotari->enfermedad = \App\Med2PEnfermedad::where('pelotari_id', $pelotari->id)->orderBy('fecha_asistencia', 'desc')->get();
        $pelotari->fisiologia = \App\Med2PFisiologia::where('pelotari_id', $pelotari->id)->orderBy('fecha_asistencia', 'desc')->get();
        $pelotari->prevencion = \App\Med2PPrevencion::where('pelotari_id', $pelotari->id)->orderBy('fecha_asistencia', 'desc')->get();
      }

      return $pelotaris;
    }

    public function getPartesPelotari( $id )  {
      $pelotari = \App\Pelotari::select('id', 'num_trabajador', 'alias', 'posicion', 'promesa', 'fecha_nac', 'email', 'telefono', 'foto')
        ->where('id', $id)
        ->first();

      $pelotari->accidente = \App\Med2PAccidente::where('pelotari_id', $id)->orderBy('fecha_parte', 'desc')->get();
      $pelotari->delta = \App\Med2PDelta::leftJoin('med2_p_accidente', 'med2_p_accidente.id', '=', 'med2_p_delta.p_accidente_id')->where('pelotari_id', $id)->orderBy('med2_p_delta.fecha_accidente', 'desc')->get();
      $pelotari->enfermedad = \App\Med2PEnfermedad::where('pelotari_id', $id)->orderBy('fecha_asistencia', 'desc')->get();
      $pelotari->fisiologia = \App\Med2PFisiologia::where('pelotari_id', $id)->orderBy('fecha_asistencia', 'desc')->get();
      $pelotari->prevencion = \App\Med2PPrevencion::where('pelotari_id', $id)->orderBy('fecha_asistencia', 'desc')->get();

      return $pelotari;
    }

    public function getNotifications(Request $request) {
      return response()->json("[getNotifications]", 200);
    }

    public function sendNotification(Request $request) {
      Log::debug("[sendNotification] ************");
      Log::debug("[sendNotification][START] hora: " . date('H:i:s'));
      $request->user()->authorizeRoles(['admin', 'medico']);
Log::debug("[sendNotification][01] hora: " . date('H:i:s'));
      $pelotari_id = $request->get('pelotari_id');
      $destinatarios = $request->get('destinatarios');
      $disponible = $request->get('disponible');
      $date_from = $request->get('date_from');
      $date_to = $request->get('date_to');
      $texto = $request->get('texto');
Log::debug("[sendNotification][02] hora: " . date('H:i:s'));
      $notificacion = new \stdClass;
      $notificacion->from = $request->user()->email;
      $notificacion->pelotari_id = $pelotari_id;
      $notificacion->destinatarios = $destinatarios;
      $notificacion->disponible = $disponible;
      $notificacion->date_from = $date_from;
      $notificacion->date_to = $date_to;
      $notificacion->texto = $texto;
Log::debug("[sendNotification][03] hora: " . date('H:i:s'));
      foreach($destinatarios as $key_1 => $destinatario) {
Log::debug("[sendNotification][04][$key_1] hora: " . date('H:i:s'));
        $rol = Role::where('name', '=', $destinatario)->first();
        $usuarios = User::where('role_id', '=', $rol->id)->get();

        foreach( $usuarios as $key => $usuario ) {
Log::debug("[sendNotification][05.1][$key] hora: " . date('H:i:s'));
          $usuario->notify(new PelotariNoDisponible($notificacion));
Log::debug("[sendNotification][05.2][$key] hora: " . date('H:i:s'));
        }
      }
      Log::debug("[sendNotification][END] hora: " . date('H:i:s'));
      Log::debug("[sendNotification] ************");
      return response()->json("Notificación enviada", 200);
    }
}
