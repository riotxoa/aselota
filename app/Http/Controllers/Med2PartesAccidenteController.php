<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Med2InformesPAcc;
use App\Med2PAccidente;
use App\Med2PDelta;
use App\Pelotari;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class Med2PartesAccidenteController extends Med2PartesController
{
    public function getAll(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $partes = Med2PAccidente::select('pelotaris.id', 'pelotaris.num_trabajador', 'pelotaris.alias', 'pelotaris.posicion', 'pelotaris.promesa', 'pelotaris.fecha_nac', 'pelotaris.email', 'pelotaris.telefono', 'pelotaris.foto', 'med2_p_accidente.*')
                ->leftJoin('pelotaris', 'pelotaris.id', '=', 'pelotari_id')
                ->orderBy('fecha_parte', 'desc')
                ->get();

      return response()->json($partes, 200);
    }

    public function store(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $pelotari_id = $request->get('pelotari_id');
      $p_accidente = $request->get('p_accidente');
      $p_delta = $request->get('p_delta');
      $retorno = array();

      $new_parte_acc = new Med2PAccidente([
        'pelotari_id' => $pelotari_id,
        'fecha_parte' => $p_accidente['fecha_parte'],
        'fecha_accidente' => $p_accidente['fecha_accidente'],
        'is_recaida' => $p_accidente['is_recaida'],
        'is_baja' => $p_accidente['is_baja'],
        'med2_centro_id' => $p_accidente['med2_centro_id'],
        'med2_tipo_asistencia_id' => $p_accidente['med2_tipo_asistencia_id'],
        'med2_causante_id' => $p_accidente['med2_causante_id'],
        'med2_prueba_id' => $p_accidente['med2_prueba_id'],
        'parte_cuerpo' => $p_accidente['parte_cuerpo'],
        'diagnostico_ini' => $p_accidente['diagnostico_ini'],
        'diagnostico_obs' => $p_accidente['diagnostico_obs'],
        'evolucion' => $p_accidente['evolucion'],
        'tratamiento' => $p_accidente['tratamiento']
      ]);

      $new_parte_acc->save();

      $retorno['parte_accidente_id'] = $new_parte_acc->id;

      if($new_parte_acc['is_baja']) {
        $new_parte_delta = new Med2PDelta([
          'p_accidente_id' => $new_parte_acc->id,
          'fecha_accidente' => $p_delta['fecha_accidente'],
          'fecha_baja' => $p_delta['fecha_baja'],
          'fecha_recaida' => $p_delta['fecha_recaida'],
          'fecha_accidente_rec' => $p_delta['fecha_accidente_rec'],
          'med2_lugar_id' => $p_delta['med2_lugar_id'],
          'med2_causante_id' => $p_delta['med2_causante_id'],
          'med2_tiempo_trabajo_id' => $p_delta['med2_tiempo_trabajo_id'],
          'parte_cuerpo' => $p_delta['parte_cuerpo'],
          'descripcion_lesion' => $p_delta['descripcion_lesion'],
          'med2_grado_lesion_id' => $p_delta['med2_grado_lesion_id'],
          'med2_medico_id' => $p_delta['med2_medico_id'],
          'med2_medico_txt' => $p_delta['med2_medico_txt'],
          'med2_tipo_asistencia_id' => $p_delta['med2_tipo_asistencia_id'],
          'hora_at' => $p_delta['hora_at'],
          'tiempo_previsto' => $p_delta['tiempo_previsto']
        ]);

        $new_parte_delta->save();

        $retorno['parte_delta_id'] = $new_parte_delta->id;
      }

      $pelotari = $this->getPartesPelotari( $pelotari_id );

      return response()->json($pelotari, 200);
    }

    public function update(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PAccidente::find($id);
      $delta = Med2PDelta::where('p_accidente_id', $id)->first();

      $new_accidente = $request->get('p_accidente');
      $new_delta = $request->get('p_delta');
      $retorno = array();

      $parte->fecha_parte = $new_accidente['fecha_parte'];
      $parte->fecha_accidente = $new_accidente['fecha_accidente'];
      $parte->is_recaida = $new_accidente['is_recaida'];
      $parte->is_baja = $new_accidente['is_baja'];
      $parte->med2_centro_id = $new_accidente['med2_centro_id'];
      $parte->med2_tipo_asistencia_id = $new_accidente['med2_tipo_asistencia_id'];
      $parte->med2_causante_id = $new_accidente['med2_causante_id'];
      $parte->med2_prueba_id = $new_accidente['med2_prueba_id'];
      $parte->parte_cuerpo = $new_accidente['parte_cuerpo'];
      $parte->diagnostico_ini = $new_accidente['diagnostico_ini'];
      $parte->diagnostico_obs = $new_accidente['diagnostico_obs'];
      $parte->evolucion = $new_accidente['evolucion'];
      $parte->tratamiento = $new_accidente['tratamiento'];

      $parte->save();

      if($new_accidente['is_baja']) {
        if( !$delta ) {
          $delta = new Med2PDelta();
        }
        $delta->p_accidente_id = $parte->id;
        $delta->fecha_accidente = $new_delta['fecha_accidente'];
        $delta->fecha_baja = $new_delta['fecha_baja'];
        $delta->fecha_recaida = $new_delta['fecha_recaida'];
        $delta->fecha_accidente_rec = $new_delta['fecha_accidente_rec'];
        $delta->med2_lugar_id = $new_delta['med2_lugar_id'];
        $delta->med2_causante_id = $new_delta['med2_causante_id'];
        $delta->med2_tiempo_trabajo_id = $new_delta['med2_tiempo_trabajo_id'];
        $delta->parte_cuerpo = $new_delta['parte_cuerpo'];
        $delta->descripcion_lesion = $new_delta['descripcion_lesion'];
        $delta->med2_grado_lesion_id = $new_delta['med2_grado_lesion_id'];
        $delta->med2_medico_id = $new_delta['med2_medico_id'];
        $delta->med2_medico_txt = $new_delta['med2_medico_txt'];
        $delta->med2_tipo_asistencia_id = $new_delta['med2_tipo_asistencia_id'];
        $delta->hora_at = $new_delta['hora_at'];
        $delta->tiempo_previsto = $new_delta['tiempo_previsto'];

        $delta->save();
      } else {
        if( $delta ) {
          Med2PDelta::destroy($delta->id);
        }
      }

      $pelotari = $this->getPartesPelotari( $parte->pelotari_id );

      return response()->json($pelotari, 200);
    }

    public function destroy(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PAccidente::find($id);

      /* 1. ELIMINAR Informes asociados */
      $informes = Med2InformesPAcc::where('parte_id', $id)->get();
      foreach( $informes as $informe ) {
        // Med2InformesPAcc::destroy($informe->id);
        // Storage::disk('public')->delete($informe->path);
        $this->deleteInforme($request, $informe->id);
      }

      /* 2. ELIMINAR Parte Delta asociado */
      $delta = Med2PDelta::where('p_accidente_id', $id)->first();
      if( $delta ) {
        Med2PDelta::destroy($delta->id);
      }

      /* 3. ELIMINAR Parte de Accidente */
      Med2PAccidente::destroy($id);

      return response()->json($this->getPartesPelotari( $parte->pelotari_id ), 200);
    }

    public function getInformes(Request $request, $parte_id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PAccidente::find($parte_id);
      if( $parte && $parte->informes ) {
        return response()->json($parte->informes);
      } else {
        return response()->json(array());
      }
    }

    public function uploadInforme(Request $request, $parte_id)
    {
        $request->user()->authorizeRoles(['admin', 'medico']);

        // $path = $request->file('file')->storeAs('contratos', $data->fileName);
        // $item->file = Storage::url($path);

        $parte = Med2PAccidente::find($parte_id);
        $pelotari = Pelotari::find($parte->pelotari_id);

        $file = Input::file('informe');
        $filename = $file->getClientOriginalName();

        if( $request->file('informe') ) {
          $path = $request->file('informe')->storeAs("informes/accidente/" . $pelotari->alias . "/" . $parte_id, $filename);

          $informe = new Med2InformesPAcc([
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

        $informe = Med2InformesPAcc::find($request->get('id'));

        $informe->desc = $request->get('desc');

        $informe->save();

        return $this->getInformes($request, $parte_id);

    }

    public function deleteInforme(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $informe = Med2InformesPAcc::find($id);
      $parte = Med2PAccidente::find($informe->parte->id);
      $storage = storage_path($informe->path);

      Med2InformesPAcc::destroy($informe->id);

      unlink(str_replace('storage//storage', 'storage/app', $storage));

      return $this->getInformes($request, $parte->id);
    }

    public function downloadInforme(Request $request, $id)
    {

      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $item = Med2InformesPAcc::find($id);
      $filePath = str_replace('/storage/informes/', '', $item->path);
      $fileName = $item->name;

      $headers = array(
         'Content-Type: application/octet-stream',
      );

      return response()->download(storage_path('app/informes/' . $filePath), $fileName, $headers);
    }

}
