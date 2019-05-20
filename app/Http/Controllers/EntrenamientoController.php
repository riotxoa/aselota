<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entrenamiento;

class EntrenamientoController extends Controller
{
  public function index(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador']);

      $entrenamientos = \App\Entrenamiento::orderBy('fecha', 'desc')->get();

      $entrenamientos = DB::table('entrenamientos')
        ->leftJoin('provincias', 'entrenamientos.provincia_id', '=', 'provincias.id')
        ->leftJoin('municipios', 'entrenamientos.municipio_id', '=', 'municipios.id')
        ->leftJoin('pelotaris', 'entrenamientos.pelotari_id', '=', 'pelotaris.id')
        ->select('entrenamientos.*', 'provincias.name as provincia_name', 'municipios.name as municipio_name', 'pelotaris.alias', 'pelotaris.foto')
        ->orderBy('fecha', 'desc')
        ->get();

      return response()->json($entrenamientos, 200);
  }

  public function getEntrenamiento(Request $request)
  {
    $request->user()->authorizeRoles(['admin', 'entrenador']);

    $id = $request->get('id');

    $item = Entrenamiento::find($id);

    return response()->json($item, 200);
  }

  public function store(Request $request)
  {
      $request->user()->authorizeRoles(['admin', 'entrenador']);

      $item = new Entrenamiento([
        'pelotari_id' => $request->get('pelotari_id'),
        'provincia_id' => $request->get('provincia_id'),
        'municipio_id' => $request->get('municipio_id'),
        'asistencia' => $request->get('asistencia'),
        'duracion' => $request->get('duracion'),
        'fecha' => $request->get('fecha'),
        'hora' => $request->get('hora'),
        'contenido_id' => $request->get('contenido_id'),
        'fronton_id' => $request->get('fronton_id'),
        'pre_entreno' => $request->get('pre_entreno'),
        'contenido' => $request->get('contenido'),
        'post_entreno' => $request->get('post_entreno'),
        'actitud_id' => $request->get('actitud_id'),
        'aprovechamiento_id' => $request->get('aprovechamiento_id'),
        'evolucion_id' => $request->get('evolucion_id'),
        'comentarios' => $request->get('comentarios'),
        'promesa' => $request->get('promesa')
      ]);

      $item->save();

      return response()->json($item, 200);
  }

  public function update(Request $request, $id)
  {
      $request->user()->authorizeRoles(['admin', 'entrenador']);

      $item = Entrenamiento::find($id);

      $item->pelotari_id = $request->get('pelotari_id');
      $item->provincia_id = $request->get('provincia_id');
      $item->municipio_id = $request->get('municipio_id');
      $item->asistencia = $request->get('asistencia');
      $item->duracion = $request->get('duracion');
      $item->fecha = $request->get('fecha');
      $item->hora = $request->get('hora');
      $item->contenido_id = $request->get('contenido_id');
      $item->fronton_id = $request->get('fronton_id');
      $item->pre_entreno = $request->get('pre_entreno');
      $item->contenido = $request->get('contenido');
      $item->post_entreno = $request->get('post_entreno');
      $item->actitud_id = $request->get('actitud_id');
      $item->aprovechamiento_id = $request->get('aprovechamiento_id');
      $item->evolucion_id = $request->get('evolucion_id');
      $item->comentarios = $request->get('comentarios');
      $item->promesa = $request->get('promesa');

      $item->save();

      return response()->json($item, 200);
  }

  public function destroy(Request $request, $id)
  {
    $request->user()->authorizeRoles(['admin', 'entrenador']);

    Entrenamiento::destroy($id);

    return response()->json("ENTRENAMIENTO REMOVED", 200);
  }
}
