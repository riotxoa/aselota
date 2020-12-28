<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Med2InformesPEnf;
use App\Med2PEnfermedad;
use App\Pelotari;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class Med2PartesEnfermedadController extends Med2PartesController
{

    public function store(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $pelotari_id = $request->get('pelotari_id');
      $parte = $request->get('p_enfermedad');
      $retorno = array();

      $new_parte = new Med2PEnfermedad([
        'pelotari_id' => $pelotari_id,
        'fecha_asistencia' => $parte['fecha_asistencia'],
        'fecha_inicio' => $parte['fecha_inicio'],
        'med2_tipo_asistencia_id' => $parte['med2_tipo_asistencia_id'],
        'med2_prueba_id' => $parte['med2_prueba_id'],
        'diagnostico_ini' => $parte['diagnostico_ini'],
        'evolucion' => $parte['evolucion'],
        'tratamiento' => $parte['tratamiento'],
        'fecha_alta' => $parte['fecha_alta']
      ]);

      $new_parte->save();

      $retorno['parte_enfermedad_id'] = $new_parte->id;

      return response()->json($this->getPartesPelotari( $pelotari_id ), 200);
    }

    public function update(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PEnfermedad::find($id);

      $new_parte = $request->get('p_enfermedad');

      $retorno = array();
      Log::debug("[update] new_parte: " . print_r($new_parte,true));
      $parte->fecha_asistencia = $new_parte['fecha_asistencia'];
      $parte->fecha_inicio = $new_parte['fecha_inicio'];
      $parte->med2_tipo_asistencia_id = $new_parte['med2_tipo_asistencia_id'];
      $parte->med2_prueba_id = $new_parte['med2_prueba_id'];
      $parte->diagnostico_ini = $new_parte['diagnostico_ini'];
      $parte->evolucion = $new_parte['evolucion'];
      $parte->tratamiento = $new_parte['tratamiento'];
      $parte->fecha_alta = $new_parte['fecha_alta'];

      $parte->save();

      return response()->json($this->getPartesPelotari( $parte->pelotari_id ), 200);
    }

    public function destroy(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PEnfermedad::find($id);

      /* 1. ELIMINAR Informes asociados */
      $informes = Med2InformesPEnf::where('parte_id', $id)->get();
      foreach( $informes as $informe ) {
        $this->deleteInforme($request, $informe->id);
      }

      /* 2. ELIMINAR Parte de Enfermedad */
      Med2PEnfermedad::destroy($id);

      return response()->json($this->getPartesPelotari( $parte->pelotari_id ), 200);
    }

    public function getInformes(Request $request, $parte_id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PEnfermedad::find($parte_id);
      if( $parte && $parte->informes ) {
        return response()->json($parte->informes);
      } else {
        return response()->json(array());
      }
    }

    public function uploadInforme(Request $request, $parte_id)
    {
        $request->user()->authorizeRoles(['admin', 'medico']);

        $parte = Med2PEnfermedad::find($parte_id);
        $pelotari = Pelotari::find($parte->pelotari_id);

        $file = Input::file('informe');
        $filename = $file->getClientOriginalName();

        if( $request->file('informe') ) {
          $path = $request->file('informe')->storeAs("informes/enfermedad/" . $pelotari->alias . "/" . $parte_id, $filename);

          $informe = new Med2InformesPEnf([
            'name' => $filename,
            'path' => Storage::url($path),
            'parte_id' => $parte_id,
          ]);
          $informe->save();

          return response()->json($path, 200);
        }

        return response()->json([
            'success' => false
        ], 500);
    }

    public function updateInforme(Request $request, $parte_id)
    {
        $request->user()->authorizeRoles(['admin', 'medico']);

        $informe = Med2InformesPEnf::find($request->get('id'));

        $informe->desc = $request->get('desc');

        $informe->save();

        return $this->getInformes($request, $parte_id);

    }

    public function deleteInforme(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $informe = Med2InformesPEnf::find($id);
      $parte = Med2PEnfermedad::find($informe->parte->id);
      $storage = storage_path($informe->path);

      Med2InformesPEnf::destroy($informe->id);

      unlink(str_replace('storage//storage', 'storage/app', $storage));

      return $this->getInformes($request, $parte->id);
    }

    public function downloadInforme(Request $request, $id)
    {

      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $item = Med2InformesPEnf::find($id);
      $filePath = str_replace('/storage/informes/', '', $item->path);
      $fileName = $item->name;

      $headers = array(
         'Content-Type: application/octet-stream',
      );

      return response()->download(storage_path('app/informes/' . $filePath), $fileName, $headers);
    }

}
