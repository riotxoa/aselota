<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Med2InformesPFis;
use App\Med2PFisiologia;
use App\Pelotari;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class Med2PartesFisiologiaController extends Med2PartesController
{
    public function getAll(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $partes = Med2PFisiologia::select('pelotaris.id', 'pelotaris.num_trabajador', 'pelotaris.alias', 'pelotaris.posicion', 'pelotaris.promesa', 'pelotaris.fecha_nac', 'pelotaris.email', 'pelotaris.telefono', 'pelotaris.foto', 'med2_p_fisiologia.*')
                ->leftJoin('pelotaris', 'pelotaris.id', '=', 'pelotari_id')
                ->orderBy('fecha_asistencia', 'desc')
                ->get();

      return response()->json($partes, 200);
    }

    public function store(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $pelotari_id = $request->get('pelotari_id');
      $parte = $request->get('p_fisiologia');
      $retorno = array();

      $new_parte = new Med2PFisiologia([
        'pelotari_id' => $pelotari_id,
        'fecha_asistencia' => $parte['fecha_asistencia'],
        'fecha_sintomas' => $parte['fecha_sintomas'],
        'fecha_pe' => $parte['fecha_pe'],
        'observaciones' => $parte['observaciones'],
        'test' => $parte['test'],
        'observaciones_test' => $parte['observaciones_test'],
        'composicion' => $parte['composicion'],
        'observaciones_comp' => $parte['observaciones_comp'],
        'suplementacion' => $parte['suplementacion']
      ]);

      $new_parte->save();

      $retorno['parte_enfermedad_id'] = $new_parte->id;

      return response()->json($this->getPartesPelotari( $pelotari_id ), 200);
    }

    public function update(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PFisiologia::find($id);

      $new_parte = $request->get('p_fisiologia');

      $retorno = array();

      $parte->fecha_asistencia = $new_parte['fecha_asistencia'];
      $parte->fecha_sintomas = $new_parte['fecha_sintomas'];
      $parte->fecha_pe = $new_parte['fecha_pe'];
      $parte->observaciones = $new_parte['observaciones'];
      $parte->test = $new_parte['test'];
      $parte->observaciones_test = $new_parte['observaciones_test'];
      $parte->composicion = $new_parte['composicion'];
      $parte->observaciones_comp = $new_parte['observaciones_comp'];
      $parte->suplementacion = $new_parte['suplementacion'];

      $parte->save();

      return response()->json($this->getPartesPelotari( $parte->pelotari_id ), 200);
    }

    public function destroy(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PFisiologia::find($id);

      /* 1. ELIMINAR Informes asociados */
      $informes = Med2InformesPFis::where('parte_id', $id)->get();
      foreach( $informes as $informe ) {
        $this->deleteInforme($request, $informe->id);
      }

      /* 2. ELIMINAR Parte de Enfermedad */
      Med2PFisiologia::destroy($id);

      return response()->json($this->getPartesPelotari( $parte->pelotari_id ), 200);
    }

    public function getInformes(Request $request, $parte_id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PFisiologia::find($parte_id);
      if( $parte && $parte->informes ) {
        return response()->json($parte->informes);
      } else {
        return response()->json(array());
      }
    }

    public function uploadInforme(Request $request, $parte_id)
    {
        $request->user()->authorizeRoles(['admin', 'medico']);

        $parte = Med2PFisiologia::find($parte_id);
        $pelotari = Pelotari::find($parte->pelotari_id);

        $file = Input::file('informe');
        $filename = $file->getClientOriginalName();

        if( $request->file('informe') ) {
          $path = $request->file('informe')->storeAs("informes/fisiologia/" . $pelotari->alias . "/" . $parte_id, $filename);

          $informe = new Med2InformesPFis([
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

        $informe = Med2InformesPFis::find($request->get('id'));

        $informe->desc = $request->get('desc');

        $informe->save();

        return $this->getInformes($request, $parte_id);

    }

    public function deleteInforme(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $informe = Med2InformesPFis::find($id);
      $parte = Med2PFisiologia::find($informe->parte->id);
      $storage = storage_path($informe->path);

      Med2InformesPFis::destroy($informe->id);

      unlink(str_replace('storage//storage', 'storage/app', $storage));

      return $this->getInformes($request, $parte->id);
    }

    public function downloadInforme(Request $request, $id)
    {

      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $item = Med2InformesPFis::find($id);
      $filePath = str_replace('/storage/informes/', '', $item->path);
      $fileName = $item->name;

      $headers = array(
         'Content-Type: application/octet-stream',
      );

      return response()->download(storage_path('app/informes/' . $filePath), $fileName, $headers);
    }

}
