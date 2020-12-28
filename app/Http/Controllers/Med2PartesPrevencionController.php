<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Med2InformesPPre;
use App\Med2PPrevencion;
use App\Pelotari;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Log;

class Med2PartesPrevencionController extends Med2PartesController
{

    public function store(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $pelotari_id = $request->get('pelotari_id');
      $parte = $request->get('p_prevencion');
      $retorno = array();

      $new_parte = new Med2PPrevencion([
        'pelotari_id' => $pelotari_id,
        'fecha_asistencia' => $parte['fecha_asistencia'],
        'historia' => $parte['historia'],
        'reconocimiento' => $parte['reconocimiento'],
        'fecha_rec' => $parte['fecha_rec'],
        'analiticas' => $parte['analiticas'],
        'fecha_ana' => $parte['fecha_ana'],
        'pruebas' => $parte['pruebas'],
        'fecha_pru' => $parte['fecha_pru']
      ]);

      $new_parte->save();

      $retorno['parte_enfermedad_id'] = $new_parte->id;

      return response()->json($this->getPartesPelotari( $pelotari_id ), 200);
    }

    public function update(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PPrevencion::find($id);

      $new_parte = $request->get('p_prevencion');

      $retorno = array();

      $parte->fecha_asistencia = $new_parte['fecha_asistencia'];
      $parte->historia = $new_parte['historia'];
      $parte->reconocimiento = $new_parte['reconocimiento'];
      $parte->fecha_rec = $new_parte['fecha_rec'];
      $parte->analiticas = $new_parte['analiticas'];
      $parte->fecha_ana = $new_parte['fecha_ana'];
      $parte->pruebas = $new_parte['pruebas'];
      $parte->fecha_pru = $new_parte['fecha_pru'];

      $parte->save();

      return response()->json($this->getPartesPelotari( $parte->pelotari_id ), 200);
    }

    public function destroy(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PPrevencion::find($id);

      /* 1. ELIMINAR Informes asociados */
      $informes = Med2InformesPPre::where('parte_id', $id)->get();
      foreach( $informes as $informe ) {
        $this->deleteInforme($request, $informe->id);
      }

      /* 2. ELIMINAR Parte de Enfermedad */
      Med2PPrevencion::destroy($id);

      return response()->json($this->getPartesPelotari( $parte->pelotari_id ), 200);
    }

    public function getInformes(Request $request, $parte_id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $parte = Med2PPrevencion::find($parte_id);
      if( $parte && $parte->informes ) {
        return response()->json($parte->informes);
      } else {
        return response()->json(array());
      }
    }

    public function uploadInforme(Request $request, $parte_id)
    {
        $request->user()->authorizeRoles(['admin', 'medico']);

        $parte = Med2PPrevencion::find($parte_id);
        $pelotari = Pelotari::find($parte->pelotari_id);

        $file = Input::file('informe');
        $filename = $file->getClientOriginalName();

        if( $request->file('informe') ) {
          $path = $request->file('informe')->storeAs("informes/prevencion/" . $pelotari->alias . "/" . $parte_id, $filename);

          $informe = new Med2InformesPPre([
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

        $informe = Med2InformesPPre::find($request->get('id'));

        $informe->desc = $request->get('desc');

        $informe->save();

        return $this->getInformes($request, $parte_id);

    }

    public function deleteInforme(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $informe = Med2InformesPPre::find($id);
      $parte = Med2PPrevencion::find($informe->parte->id);
      $storage = storage_path($informe->path);

      Med2InformesPPre::destroy($informe->id);

      unlink(str_replace('storage//storage', 'storage/app', $storage));

      return $this->getInformes($request, $parte->id);
    }

    public function downloadInforme(Request $request, $id)
    {

      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $item = Med2InformesPPre::find($id);
      $filePath = str_replace('/storage/informes/', '', $item->path);
      $fileName = $item->name;

      $headers = array(
         'Content-Type: application/octet-stream',
      );

      return response()->download(storage_path('app/informes/' . $filePath), $fileName, $headers);
    }

}
