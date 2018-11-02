<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Contrato;
use App\ContratoHeader;
use App\ContratoCampeonato;

class ContratoHeaderController extends Controller
{
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $data = json_decode($request->get('form'));

      $item = new ContratoHeader([
        'pelotari_id' => $data->pelotari_id,
        'name' => $data->name,
        'fecha_ini' => $data->fecha_ini,
        'fecha_fin' => $data->fecha_fin,
      ]);

      if($request->file('file')) {
        $path = $request->file('file')->storeAs('contratos', $data->fileName);
        $item->file = Storage::url($path);
      }

      $item->save();

      return response()->json($request, 200);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $data = json_decode($request->get('form'));

      $item = ContratoHeader::find($id);

      $item->name = $data->name;
      $item->fecha_ini = $data->fecha_ini;
      $item->fecha_fin = $data->fecha_fin;

      if($request->file('file')) {
        $path = $request->file('file')->storeAs('contratos', $data->fileName);
        $item->file = Storage::url($path);
      } else {
        $item->file = "kakakulo";
      }

      $item->save();

      return response()->json($item, 200);
  }

  public function download_contrato(Request $request, $id)
  {
    $request->user()->authorizeRoles(['admin', 'rrhh']);

    $item = ContratoHeader::find($id);
    $fileName = str_replace('/storage/contratos/', '', $item->file);

    $headers = array(
       'Content-Type: application/octet-stream',
    );

    return response()->download(storage_path('app/contratos/' . $fileName), $fileName, $headers);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $tramos = Contrato::where('header_id', $id)->get();

      foreach($tramos as $tramo) {
        Contrato::destroy($tramo->id);
      }

      $campeonatos = ContratoCampeonato::where('header_id', $id)->get();

      foreach($campeonatos as $campeonato) {
        ContratoCampeonato::destroy($campeonato->id);
      }

      ContratoHeader::destroy($id);

      return response()->json("CONTRATO REMOVED", 200);
  }
}