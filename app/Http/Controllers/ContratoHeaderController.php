<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

      $item = new ContratoHeader([
        'pelotari_id' => $request->get('pelotari_id'),
        'name' => $request->get('name'),
        'fecha_ini' => $request->get('fecha_ini'),
        'fecha_fin' => $request->get('fecha_fin'),
      ]);

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

      $item = ContratoHeader::find($id);

      $item->name = $request->get('name');
      $item->fecha_ini = $request->get('fecha_ini');
      $item->fecha_fin = $request->get('fecha_fin');

      $item->save();

      return response()->json($item, 200);
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
