<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Models\Role;
use App\User;
use App\Notifications\PelotariNoDisponible;
use Illuminate\Support\Facades\DB;

// use Illuminate\Support\Facades\Log;

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

    public function getNotifications(Request $request, $pelotari_id = false) {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $notifications = DB::table('notifications')
        ->select('notifications.*', 'pelotaris.alias as pelotari_alias', 'to_users.name as to_user_name', 'to_users.email as to_user_email', 'roles.display_name as to_user_role', 'from_users.name as from_user_name', 'from_users.email as from_user_email')
        ->leftJoin('pelotaris', 'pelotaris.id', '=', DB::raw('JSON_EXTRACT(data, "$.pelotari_id")'))
        ->leftJoin('users as to_users', 'to_users.id', '=', 'notifications.notifiable_id')
        ->leftJoin('roles', 'roles.id', '=', 'to_users.role_id')
        ->leftJoin('users as from_users', 'from_users.email', '=', DB::raw('JSON_EXTRACT(data, "$.from")'));

      if( $pelotari_id ) {
        $notifications = $notifications->whereRaw('JSON_EXTRACT(data, "$.pelotari_id") = ' . $pelotari_id);
      }

      $notifications = $notifications->orderBy('created_at', 'desc')->get();

      return response()->json($notifications, 200);
    }

    public function sendNotification(Request $request) {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $pelotari_id = $request->get('pelotari_id');
      $destinatarios = $request->get('destinatarios');
      $disponible = $request->get('disponible');
      $date_from = $request->get('date_from');
      $date_to = $request->get('date_to');
      $texto = $request->get('texto');

      $notificacion = new \stdClass;
      $notificacion->from = $request->user()->email;
      $notificacion->pelotari_id = $pelotari_id;
      $notificacion->destinatarios = $destinatarios;
      $notificacion->disponible = $disponible;
      $notificacion->date_from = $date_from;
      $notificacion->date_to = $date_to;
      $notificacion->texto = $texto;

      foreach($destinatarios as $key_1 => $destinatario) {
        $rol = Role::where('name', '=', $destinatario)->first();
        $usuarios = User::where('role_id', '=', $rol->id)->get();

        foreach( $usuarios as $key => $usuario ) {
          $usuario->notify(new PelotariNoDisponible($notificacion));
        }
      }

      return $this->getNotifications($request, $pelotari_id);
    }

    public function getNotificationsByUserID(Request $request) {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa']);

      $notifications = DB::table('notifications')
        ->select('notifications.*', 'pelotaris.alias as pelotari_alias', 'to_users.name as to_user_name', 'to_users.email as to_user_email', 'roles.display_name as to_user_role', 'from_users.name as from_user_name', 'from_users.email as from_user_email')
        ->leftJoin('pelotaris', 'pelotaris.id', '=', DB::raw('JSON_EXTRACT(data, "$.pelotari_id")'))
        ->leftJoin('users as to_users', 'to_users.id', '=', 'notifications.notifiable_id')
        ->leftJoin('roles', 'roles.id', '=', 'to_users.role_id')
        ->leftJoin('users as from_users', 'from_users.email', '=', DB::raw('JSON_EXTRACT(data, "$.from")'))
        ->where('notifiable_id', $request->user()->id)
        ->where(function ($query) {
          $fecha = date('Y-m-d', strtotime('-1 week'));

          $query->where('read_at', '=', null)
                ->orWhere('read_at', '>=', $fecha);
        })
        ->orderBy('created_at', 'desc')
        ->get();

      return response()->json($notifications, 200);
    }

    public function readNotification(Request $request, $notification_id) {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa']);

      $notification = $request->user()->unreadNotifications()->find($notification_id)->markAsRead();

      return $this->getNotificationsByUserID($request);
    }

    public function unreadNotification(Request $request, $notification_id) {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa']);

      $notification = $request->user()->notifications()->find($notification_id)->update(['read_at' => null]);

      return $this->getNotificationsByUserID($request);
    }

}
