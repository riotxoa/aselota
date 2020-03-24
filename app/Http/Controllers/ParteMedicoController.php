<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\MedParte;
use App\MedLesion;
use App\MedDelta;

class ParteMedicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $items = DB::table('med_partes')
                 ->select(
                     'med_partes.*',
                     'pelotaris.alias as pelotari_alias',
                     'pelotaris.foto as pelotari_foto',
                     'med_diagnosticos.desc as diagnostico',
                     'med_evoluciones.desc as evolucion',
                     'med_lesiones.observaciones as observaciones'
                   )
                 ->leftJoin('pelotaris', 'med_partes.pelotari_id', '=', 'pelotaris.id')
                 ->leftJoin('med_diagnosticos', 'med_partes.med_diagnostico_id', '=', 'med_diagnosticos.id')
                 ->leftJoin('med_evoluciones', 'med_partes.med_evolucion_id', '=', 'med_evoluciones.id')
                 ->leftJoin('med_lesiones', 'med_partes.id', '=', 'med_lesiones.med_parte_id');

      if( null !== $request->get('filter') ) {
        $in_clause = array();

        $filters = $request->get('filter');

        foreach($filters as $filter) {
          $filter = json_decode($filter);
          if( 'in' == $filter->operator ) {
            $in_clause[$filter->column][] = $filter->value;
          } else {
            $items = $items->where($filter->column, $filter->operator, $filter->value);
          }
        }

        if(count($in_clause)) {
          foreach($in_clause as $key => $clause) {
            $items = $items->whereIn($key, $clause);
          }
        }
      }

      $items = $items->orderBy('med_partes.fecha_parte', 'desc')->get();

      return response()->json($items, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $header = $request->get('header');
      $parte_lesion = $request->get('parte_lesion');
      $parte_delta = $request->get('parte_delta');

      $item_header = new MedParte([
        'fecha_parte' => $header['fecha_parte'], // $request->get('fecha_parte'),
        'fecha_accidente' => $header['fecha_accidente'], // $request->get('fecha_accidente'),
        'fecha_baja' => $header['fecha_baja'], // $request->get('fecha_baja'),
        'fecha_alta' => $header['fecha_alta'], // $request->get('fecha_alta'),
        'fecha_proxima_consulta' => $header['fecha_proxima_consulta'], // $request->get('fecha_proxima_consulta'),
        'fecha_siguiente' => $header['fecha_siguiente'], // $request->get('fecha_siguiente'),
        'pelotari_id' => $header['pelotari_id'], // $request->get('pelotari_id'),
        'med_diagnostico_id' => $header['med_diagnostico_id'], // $request->get('med_diagnostico_id'),
        'med_diagnostico_txt' => $header['med_diagnostico_txt'], // $request->get('med_diagnostico_txt'),
        'med_centro_id' => $header['med_centro_id'], // $request->get('med_centro_id'),
        'med_tipo_asistencia_id' => $header['med_tipo_asistencia_id'], // $request->get('med_tipo_asistencia_id'),
        'med_motivo_alta_id' => $header['med_motivo_alta_id'], // $request->get('med_motivo_alta_id'),
        'med_evolucion_id' => $header['med_evolucion_id'], // $request->get('med_evolucion_id'),
        'med_evolucion_txt' => $header['med_evolucion_txt'], // $request->get('med_evolucion_txt'),
        'med_tratamiento_id' => $header['med_tratamiento_id'], // $request->get('med_tratamiento_id'),
        'med_tratamiento_txt' => $header['med_tratamiento_txt'], // $request->get('med_tratamiento_txt'),
        'is_recaida' => $header['is_recaida'], // $request->get('is_recaida'),
        'is_baja' => $header['is_baja'], // $request->get('is_baja'),
      ]);

      $item_header->save();

      $item_lesion = new MedLesion([
        'med_parte_id' => $item_header->id,
        'med_lesion_desc_id' => $parte_lesion['med_lesion_desc_id'],
        'med_partes_cuerpo_id' => $parte_lesion['med_partes_cuerpo_id'],
        'med_partes_cuerpo_txt' => $parte_lesion['med_partes_cuerpo_txt'],
        'med_medico_id' => $parte_lesion['med_medico_id'],
        'med_medico_txt' => $parte_lesion['med_medico_txt'],
        'med_causante_id' => $parte_lesion['med_causante_id'],
        'med_causante_txt' => $parte_lesion['med_causante_txt'],
        'med_grado_lesion_id' => $parte_lesion['med_grado_lesion_id'],
        'med_tipo_atencion_id' => $parte_lesion['med_tipo_atencion_id'],
        'descripcion' => $parte_lesion['descripcion'],
        'observaciones' => $parte_lesion['observaciones'],
      ]);

      $item_delta = new MedDelta([
        'med_parte_id' => $item_header->id,
        'fecha_recaida' => $parte_delta['fecha_recaida'],
        'fecha_accidente_recaida' => $parte_delta['fecha_accidente_recaida'],
        'fronton_id' => $parte_delta['fronton_id'],
        'hora_at' => $parte_delta['hora_at'],
        'med_lesion_id' => $parte_delta['med_lesion_id'],
        'med_lesion_txt' => $parte_delta['med_lesion_txt'],
        'med_parte_cuerpo_id' => $parte_delta['med_parte_cuerpo_id'],
        'med_medico_id' => $parte_delta['med_medico_id'],
        'med_medico_txt' => $parte_delta['med_medico_txt'],
        'med_tiempo_trabajo_id' => $parte_delta['med_tiempo_trabajo_id'],
        'desc_accidente' => $parte_delta['desc_accidente'],
        'med_causante_id' => $parte_delta['med_causante_id'],
        'med_causante_txt' => $parte_delta['med_causante_txt'],
        'med_grado_lesion_id' => $parte_delta['med_grado_lesion_id'],
        'med_tipo_atencion_id' => $parte_delta['med_tipo_atencion_id'],
        'tiempo_previsto' => $parte_delta['tiempo_previsto'],
        'tiempo_previsto_txt' => $parte_delta['tiempo_previsto_txt'],
      ]);

      $item_lesion->save();
      $item_delta->save();

      // $response = new \stdClass;
      //
      // $response->header = $item_header;
      // $response->parte_lesion = $item_lesion;
      // $response->parte_delta = $item_delta;

      return response()->json($item_header->id, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
      $request->user()->authorizeRoles(['admin', 'medico']);

      $header = $request->get('header');
      $parte_lesion = $request->get('parte_lesion');
      $parte_delta = $request->get('parte_delta');

      // Parte HEADER
      $item_header = MedParte::find($id);

      $item_header->fecha_parte = $header['fecha_parte']; // $request->get('fecha_parte');
      $item_header->fecha_accidente = $header['fecha_accidente']; // $request->get('fecha_accidente');
      $item_header->fecha_baja = $header['fecha_baja']; // $request->get('fecha_baja');
      $item_header->fecha_alta = $header['fecha_alta']; // $request->get('fecha_alta');
      $item_header->fecha_proxima_consulta = $header['fecha_proxima_consulta']; // $request->get('fecha_proxima_consulta');
      $item_header->fecha_siguiente = $header['fecha_siguiente']; // $request->get('fecha_siguiente');
      $item_header->pelotari_id = $header['pelotari_id']; // $request->get('pelotari_id');
      $item_header->med_diagnostico_id = $header['med_diagnostico_id']; // $request->get('med_diagnostico_id');
      $item_header->med_diagnostico_txt = $header['med_diagnostico_txt']; // $request->get('med_diagnostico_txt');
      $item_header->med_centro_id = $header['med_centro_id']; // $request->get('med_centro_id');
      $item_header->med_tipo_asistencia_id = $header['med_tipo_asistencia_id']; // $request->get('med_tipo_asistencia_id');
      $item_header->med_motivo_alta_id = $header['med_motivo_alta_id']; // $request->get('med_motivo_alta_id');
      $item_header->med_evolucion_id = $header['med_evolucion_id']; // $request->get('med_evolucion_id');
      $item_header->med_evolucion_txt = $header['med_evolucion_txt']; // $request->get('med_evolucion_txt');
      $item_header->med_tratamiento_id = $header['med_tratamiento_id']; // $request->get('med_tratamiento_id');
      $item_header->med_tratamiento_txt = $header['med_tratamiento_txt']; // $request->get('med_tratamiento_txt');
      $item_header->is_recaida = $header['is_recaida']; // $request->get('is_recaida');
      $item_header->is_baja = $header['is_baja']; // $request->get('is_baja');

      // Parte LESIÓN
      $item_lesion = MedLesion::where('med_parte_id', $id)->first();

      $item_lesion->med_lesion_desc_id = $parte_lesion['med_lesion_desc_id'];
      $item_lesion->med_partes_cuerpo_id = $parte_lesion['med_partes_cuerpo_id'];
      $item_lesion->med_partes_cuerpo_txt = $parte_lesion['med_partes_cuerpo_txt'];
      $item_lesion->med_medico_id = $parte_lesion['med_medico_id'];
      $item_lesion->med_medico_txt = $parte_lesion['med_medico_txt'];
      $item_lesion->med_causante_id = $parte_lesion['med_causante_id'];
      $item_lesion->med_causante_txt = $parte_lesion['med_causante_txt'];
      $item_lesion->med_grado_lesion_id = $parte_lesion['med_grado_lesion_id'];
      $item_lesion->med_tipo_atencion_id = $parte_lesion['med_tipo_atencion_id'];
      $item_lesion->descripcion = $parte_lesion['descripcion'];
      $item_lesion->observaciones = $parte_lesion['observaciones'];

      // Parte DELTA
      $item_delta = MedDelta::where('med_parte_id', $id)->first();

      $item_delta->fecha_recaida = $parte_delta['fecha_recaida'];
      $item_delta->fecha_accidente_recaida = $parte_delta['fecha_accidente_recaida'];
      $item_delta->fronton_id = $parte_delta['fronton_id'];
      $item_delta->hora_at = $parte_delta['hora_at'];
      $item_delta->med_lesion_id = $parte_delta['med_lesion_id'];
      $item_delta->med_lesion_txt = $parte_delta['med_lesion_txt'];
      $item_delta->med_parte_cuerpo_id = $parte_delta['med_parte_cuerpo_id'];
      $item_delta->med_medico_id = $parte_delta['med_medico_id'];
      $item_delta->med_medico_txt = $parte_delta['med_medico_txt'];
      $item_delta->med_tiempo_trabajo_id = $parte_delta['med_tiempo_trabajo_id'];
      $item_delta->desc_accidente = $parte_delta['desc_accidente'];
      $item_delta->med_causante_id = $parte_delta['med_causante_id'];
      $item_delta->med_causante_txt = $parte_delta['med_causante_txt'];
      $item_delta->med_grado_lesion_id = $parte_delta['med_grado_lesion_id'];
      $item_delta->med_tipo_atencion_id = $parte_delta['med_tipo_atencion_id'];
      $item_delta->tiempo_previsto = $parte_delta['tiempo_previsto'];
      $item_delta->tiempo_previsto_txt = $parte_delta['tiempo_previsto_txt'];

      $item_header->save();
      $item_lesion->save();
      $item_delta->save();

      $response = new \stdClass;

      $response->parte = $item_header;
      $response->parte_lesion = $item_lesion;
      $response->parte_delta = $item_delta;

      return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      MedParte::destroy($id);
      MedLesion::where('med_parte_id', $id)->delete();
      MedDelta::where('med_parte_id', $id)->delete();

      return response()->json("PARTE REMOVED", 200);
    }

    public function getAuxTablesInfo(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      $response = new \stdClass;

      $response->causantes = \App\MedCausante::all();
      $response->centros = \App\MedCentro::all();
      $response->diagnosticos = \App\MedDiagnostico::all();
      $response->evoluciones = \App\MedEvolucion::all();
      $response->grados_lesion = \App\MedGradoLesion::all();
      $response->lesiones_dsc = \App\MedLesionDesc::all();
      $response->medicos = \App\MedMedico::all();
      $response->motivos_alta = \App\MedMotivoAlta::all();
      $response->partes_cuerpo = \App\MedParteCuerpo::all();
      $response->tiempos_trabajo = \App\MedTiempoTrabajo::all();
      $response->tipos_asistencia = \App\MedTipoAsistencia::all();
      $response->tipos_atencion = \App\MedTipoAtencion::all();
      $response->tratamientos = \App\MedTratamiento::all();
      $response->lugares = \App\Fronton::all();

      return response()->json($response, 200);
    }

    public function getParte(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'medico']);

      // Parte HEADER
      $item_header = MedParte::find($id);
      // Parte LESIÓN
      $item_lesion = MedLesion::where('med_parte_id', $id)->first();
      // Parte DELTA
      $item_delta = MedDelta::where('med_parte_id', $id)->first();

      $response = new \stdClass;

      $response->parte = $item_header;
      $response->parte_lesion = $item_lesion;
      $response->parte_delta = $item_delta;

      return response()->json($response, 200);
    }

}
