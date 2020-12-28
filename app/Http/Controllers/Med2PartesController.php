<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
