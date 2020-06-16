<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Pelotari;
use App\Nomina;

class PelotariController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);

        $get_partidos_jugados = false;

        $items = DB::table('pelotaris')
          ->leftJoin('provincias', 'pelotaris.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'pelotaris.municipio_id', '=', 'municipios.id')
          ->select('pelotaris.*', 'provincias.name as provincia', 'municipios.name as municipio')
          ->where('pelotaris.deleted_at', null);

        if($request->get('fecha')) {
          $get_partidos_jugados = true;

          $fecha = $request->get('fecha');
          $three_months_ago = date('Y-m-d', strtotime("-3 Months"));

          $items = $items->leftJoin('contratos as c1', 'pelotaris.id', '=', 'c1.pelotari_id')
                         ->leftJoin('contratos_comercial as cc1', 'c1.header_id', '=', 'cc1.header_id')
                         ->whereDate('c1.fecha_ini', '<=', $today)
                         ->whereDate('c1.fecha_fin', '>=', $three_months_ago)
                         ->whereDate('cc1.fecha_ini', '<=', $fecha)
                         ->whereDate('cc1.fecha_fin', '>=', $three_months_ago)
                         ->whereNull('c1.deleted_at')->addSelect('c1.fecha_ini as fecha_contrato', 'cc1.coste', 'c1.garantia');
        }

        if($request->get('fecha_ini')) {
          $get_partidos_jugados = true;

          $fecha_ini = $request->get('fecha_ini');
          $fecha_fin = $request->get('fecha_fin');

          // $items = $items->leftJoin('contratos as c2', 'pelotaris.id', '=', 'c2.pelotari_id')
          //                ->leftJoin('contratos_comercial as cc2', 'c2.header_id', '=', 'cc2.header_id')
          //                ->whereDate('c2.fecha_ini', '<=', $fecha_fin)
          //                ->whereDate('c2.fecha_fin', '>=', $fecha_ini)
          //                ->whereDate('cc2.fecha_ini', '<=', $fecha_fin)
          //                ->whereDate('cc2.fecha_fin', '>=', $fecha_ini)
          //                ->whereNull('c2.deleted_at')->addSelect('c2.fecha_ini as fecha_contrato', 'cc2.coste', 'c2.garantia');

          $items = $items->leftJoin('contratos as c2', 'pelotaris.id', '=', 'c2.pelotari_id')
                         ->whereDate('c2.fecha_ini', '<=', $fecha_fin)
                         ->whereDate('c2.fecha_fin', '>=', $fecha_ini)
                         ->whereNull('c2.deleted_at')->addSelect('c2.fecha_ini as fecha_contrato', 'c2.garantia');
        }

        $items = $items->orderBy('alias')
                       ->get();

        if($get_partidos_jugados) {
          $fecha = ( $request->get('fecha') ? $request->get('fecha') : $request->get('fecha_fin'));
          foreach($items as $key => $item) {
            $items[$key]->partidos_jugados = Pelotari::get_partidos_jugados_contrato($item->id, $fecha);
            $items[$key]->partidos_jugados_last_year = Pelotari::get_partidos_jugados_ano($item->id, date('Y') - 1);
            $items[$key]->partidos_jugados_this_year = Pelotari::get_partidos_jugados_ano($item->id, date('Y'));
          }
        }

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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $data = json_decode($request->get('form'));

        $item = new Pelotari([
          'DNI' => $data->dni,
          'num_trabajador' => $data->num_trabajador,
          'nombre' => $data->nombre,
          'apellidos' => $data->apellidos,
          'alias' => $data->alias,
          'posicion' => $data->posicion,
          'direccion' => $data->direccion,
          'cod_postal' => $data->cod_postal,
          'municipio_id' => $data->municipio_id,
          'provincia_id' => $data->provincia_id,
          'email' => $data->email,
          'telefono' => $data->telefono,
          'num_ss' => $data->num_ss,
          'fecha_nac' => $data->fecha_nac,
          'telefono_2' => $data->telefono_2,
          'telefono_3' => $data->telefono_3,
          'iban' => $data->iban,
          'num_hijos' => $data->num_hijos,
          'promesa' => $data->promesa
        ]);

        if($request->file('photo')) {
          $path = $request->file('photo')->storeAs('public/avatars', $data->fotoName);
          $item->foto = Storage::url($path);
        }

        $item->save();

        return response()->json($item, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);

        $item = DB::table('pelotaris')
          ->leftJoin('provincias', 'pelotaris.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'pelotaris.municipio_id', '=', 'municipios.id')
          ->select('pelotaris.*', 'provincias.name as provincia', 'municipios.name as municipio')
          ->where('pelotaris.id', $id)
          ->first();

        return response()->json($item, 200);
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
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $item = Pelotari::find($id);

        $data = json_decode($request->get('form'));

        $item->DNI = $data->dni;
        $item->num_trabajador = $data->num_trabajador;
        $item->nombre = $data->nombre;
        $item->apellidos = $data->apellidos;
        $item->alias = $data->alias;
        $item->posicion = $data->posicion;
        $item->direccion = $data->direccion;
        $item->cod_postal = $data->cod_postal;
        $item->municipio_id = $data->municipio_id;
        $item->provincia_id = $data->provincia_id;
        $item->email = $data->email;
        $item->telefono = $data->telefono;
        $item->num_ss = $data->num_ss;
        $item->fecha_nac = $data->fecha_nac;
        $item->telefono_2 = $data->telefono_2;
        $item->telefono_3 = $data->telefono_3;
        $item->iban = $data->iban;
        $item->num_hijos = $data->num_hijos;
        $item->promesa = $data->promesa;

        if($request->file('photo')) {
          $path = $request->file('photo')->storeAs('public/avatars', $data->fotoName);
          $item->foto = Storage::url($path);
        }

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

        Pelotari::destroy($id);

        return response()->json("LARAVEL REMOVED", 200);
    }

    public function getNominas(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $fecha_ini = $request->get('fecha_ini');
        $fecha_fin = $request->get('fecha_fin');
        $overwrite = filter_var( $request->get('overwrite'), FILTER_VALIDATE_BOOLEAN);

        $month = date("m", strtotime($fecha_ini));
        $year  = date("Y", strtotime($fecha_ini));

        $fases = $this->get_fases_month($month, $year);

        $created = ( $overwrite ? 0 : Nomina::where('mes', $month)->where('ano', $year)->count() );

        if( $created == 0 ) {

          $items = DB::table('pelotaris')
                  ->select(
                      'pelotaris.*',
                      'c2.header_id as contrato_id',
                      'c2.fecha_ini as fecha_ini_contrato',
                      'c2.fecha_fin as fecha_fin_contrato',
                      'c2.garantia',
                      'c2.dieta_mes as dieta_basica',
                      'c2.dieta_partido',
                      'c2.prima_partido as coste_partido',
                      'c2.prima_partido_no_gar as coste_partido_2',
                      'c2.prima_estelar',
                      'c2.prima_manomanista',
                      'c2.prima_manomanista_promo'
                    )
                  ->leftJoin('contratos as c2', 'pelotaris.id', '=', 'c2.pelotari_id')
                  ->whereDate('c2.fecha_ini', '<=', $fecha_fin)
                  ->whereDate('c2.fecha_fin', '>=', $fecha_ini)
                  ->whereNull('c2.deleted_at')
                  ->whereNull('pelotaris.deleted_at')
                  ->orderBy('alias')
                  ->get();

          foreach($items as $key => $item) {
            if( $item->fecha_fin_contrato > $request->get('fecha_fin') ) {
              $fecha = $request->get('fecha_fin');
            } else if( $item->fecha_fin_contrato <= $request->get('fecha_fin') ) {
              $fecha = $item->fecha_fin_contrato;
            }
            $item->partidos_jugados = Pelotari::get_partidos_jugados_contrato($item->id, $fecha);
            $item->fases = $this->get_fases_pelotari($fases, $item, $fecha_ini, $fecha_fin);

            $item->partidos_convocado = Pelotari::get_partidos_convocados_mes($item->id, $month, $year);
            $item->partidos_asistencia = Pelotari::get_partidos_asistencia_mes($item->id, $month, $year);
            $item->estelares = Pelotari::get_partidos_estelares_mes($item->id, $month, $year);

            $item->total_dietas = ($item->partidos_asistencia * $item->dieta_partido) + $item->dieta_basica;
            $item->total_estelares = $item->estelares * $item->prima_estelar;
            $item->total_partidos = $this->get_total_partidos($item);
            $item->total_torneos = $this->get_total_primas_campeonato($item, $fases);
            $item->total_pelotari = $item->total_dietas + $item->total_partidos + $item->total_estelares + $item->total_torneos;

            $nomina = Nomina::updateOrCreate(
              [ 'pelotari_id' => $item->id, 'mes' => $month, 'ano' => $year ],
              [
                'alias' => $item->alias,
                'fecha_ini_contrato' => $item->fecha_ini_contrato,
                'partidos_jugados' => $item->partidos_jugados,
                'partidos_garantia' => $item->garantia,
                'partidos_convocado' => $item->partidos_convocado,
                'partidos_asistencia' => $item->partidos_asistencia,
                'estelares' => $item->estelares,
                'campeonatos' => json_encode($item->fases),
                'dieta_basica' => $item->dieta_basica,
                'dieta_partido' => $item->dieta_partido,
                'coste_partido' => $item->coste_partido,
                'coste_partido_2' => ($item->coste_partido_2 ? $item->coste_partido_2 : $item->coste_partido),
                'prima_estelar' => $item->prima_estelar,
                'prima_manomanista' => $item->prima_manomanista,
                'prima_manomanista_promo' => $item->prima_manomanista_promo,
                'total_dietas' => $item->total_dietas,
                'total_estelares' => $item->total_estelares,
                'total_partidos' => $item->total_partidos,
                'total_torneos' => $item->total_torneos,
                'total_pelotari' => $item->total_pelotari
              ]
            );
          }

        }

        $response = array();

        $response['fases'] = $fases;
        $response['nominas'] = Nomina::where('mes', $month)->where('ano', $year)->get(); // $items;
        $response['created'] = ( $created > 0 ? Nomina::select('updated_at')->where('mes', $month)->where('ano', $year)->orderBy('updated_at', 'desc')->first() : false);

        return response()->json($response, 200);
    }

    private function get_fases_month( $month, $year ) {
      $fases = DB::table('festival_partidos as partidos')
                ->select('partidos.campeonato_id', 'campeonatos.name', 'partidos.fase')
                ->leftJoin('festivales', 'festivales.id', '=', 'partidos.festival_id')
                ->leftJoin('campeonatos', 'campeonatos.id', '=', 'partidos.campeonato_id')
                ->whereMonth('festivales.fecha', $month)
                ->whereYear('festivales.fecha', $year)
                ->whereNotNull('partidos.campeonato_id')
                ->whereNotNull('partidos.fase')
                ->groupBy('partidos.campeonato_id', 'campeonatos.name', 'partidos.fase')
                ->orderBy('partidos.campeonato_id')
                ->get();

      return $fases;
    }


    private function get_fases_pelotari( $fases, $item, $fecha_ini, $fecha_fin ) {

      $month = date("m", strtotime($fecha_ini));
      $year  = date("Y", strtotime($fecha_ini));

      $fases_pelotari = array();

      foreach( $fases as $key => $fase ) {
        $fases_pelotari[$fase->campeonato_id]['name'] = $fase->name;
        $fases_pelotari[$fase->campeonato_id]['fases'][$fase->fase] = array(
          'partidos' => $this->get_partidos_fase($fase, $item),
          'prima' => $this->get_prima_fase($fase, $item, $fecha_ini, $fecha_fin),
          'fechas' => $this->get_fechas_fase($fase, $item),
        );
      }
      return $fases_pelotari;
    }

    private function get_partidos_fase( $fase, $item ) {
      // Recuperamos la fecha de la última final del campeonato en cuestión
      $last_final = $this->get_last_final($fase->campeonato_id);

      $partidos = DB::table('festival_partido_pelotaris as p_pelotaris')
                  ->select('p_pelotaris.*')
                  ->leftJoin('pelotaris', 'pelotaris.id', '=', 'p_pelotaris.pelotari_id')
                  ->leftJoin('festival_partidos as partidos', 'partidos.id', '=', 'p_pelotaris.festival_partido_id')
                  ->leftJoin('festivales', 'festivales.id', '=', 'partidos.festival_id')
                  ->where('pelotaris.id', '=', $item->id)
                  ->whereDate('festivales.fecha', '>', $last_final)
                  ->whereDate('festivales.fecha', '<=', $item->fecha_fin_contrato)
                  ->whereDate('festivales.fecha', '>=', $item->fecha_ini_contrato)
                  ->where('p_pelotaris.asiste', 1)
                  ->where('partidos.campeonato_id', '=', $fase->campeonato_id)
                  ->where('partidos.fase', '=', $fase->fase)
                  ->count();

      return $partidos;
    }

    private function get_prima_fase( $fase, $item, $fecha_ini, $fecha_fin ) {

      $column = 'c_campeonatos.' . $fase->fase;

      $prima = DB::table('contrato_campeonatos as c_campeonatos')
                ->select($column)
                ->leftJoin('contratos_header as c_header', 'c_header.id', '=', 'c_campeonatos.header_id')
                ->leftJoin('pelotaris', 'pelotaris.id', '=', 'c_header.pelotari_id')
                ->where('c_campeonatos.campeonato_id', $fase->campeonato_id)
                ->whereDate('c_header.fecha_ini', '<=', $fecha_fin)
                ->whereDate('c_header.fecha_fin', '>=', $fecha_ini)
                ->where('pelotaris.id', $item->id)
                ->value($column);

      return ($prima ? $prima : 0);
    }

    private function get_fechas_fase( $fase, $item ) {
      // Recuperamos la fecha de la última final del campeonato en cuestión
      $last_final = $this->get_last_final($fase->campeonato_id);

      $fechas = DB::table('festival_partido_pelotaris as p_pelotaris')
                  ->select(DB::raw('festivales.fecha, false as incluido, frontones.name as fronton, municipios.name as municipio'))
                  ->leftJoin('pelotaris', 'pelotaris.id', '=', 'p_pelotaris.pelotari_id')
                  ->leftJoin('festival_partidos as partidos', 'partidos.id', '=', 'p_pelotaris.festival_partido_id')
                  ->leftJoin('festivales', 'festivales.id', '=', 'partidos.festival_id')
                  ->leftJoin('frontones', 'frontones.id', '=', 'festivales.fronton_id')
                  ->leftJoin('municipios', 'municipios.id', '=', 'frontones.municipio_id')
                  ->where('pelotaris.id', '=', $item->id)
                  ->whereDate('festivales.fecha', '>', $last_final)
                  ->whereDate('festivales.fecha', '<=', $item->fecha_fin_contrato)
                  ->whereDate('festivales.fecha', '>=', $item->fecha_ini_contrato)
                  ->where('p_pelotaris.asiste', 1)
                  ->where('partidos.campeonato_id', '=', $fase->campeonato_id)
                  ->where('partidos.fase', '=', $fase->fase)
                  ->get();

      return $fechas;
    }

    private function get_last_final( $campeonato_id ) {
      return DB::table('festival_partidos as partidos')
              ->select('festivales.fecha')
              ->leftJoin('festivales', 'festivales.id', '=', 'partidos.festival_id')
              ->where('partidos.campeonato_id', '=', $campeonato_id)
              ->where('partidos.fase', '=', 'final')
              ->orderBy('festivales.fecha', 'desc')
              ->value('festivales.fecha');
    }

    private function get_total_primas_campeonato( $item, $fases ) {
      $total = 0;

      foreach( $fases as $key => $fase ) {
        $incluidos = 0;

        foreach( $item->fases[$fase->campeonato_id]['fases'][$fase->fase]['fechas'] as $val ) {
          $incluidos += ($val->incluido ? 1 : 0);
        }

        $total += $incluidos * $item->fases[$fase->campeonato_id]['fases'][$fase->fase]['prima'];
        $item->fases[$fase->campeonato_id]['fases'][$fase->fase]['incluidos'] = $incluidos;
      }

      return $total;
    }

    private function get_total_partidos( $item ) {
      $total_partidos = 0;

      if ($item->partidos_jugados > $item->garantia) {
        $partidos_past_month = $item->partidos_jugados - $item->partidos_asistencia;
        $partidos_pre_garantia = ($partidos_past_month < $item->garantia ? $item->garantia - $partidos_past_month : 0);
        $partidos_post_garantia = $item->partidos_asistencia - $partidos_pre_garantia;

        $prima_1 = $partidos_pre_garantia * $item->coste_partido;
        $prima_2 = $partidos_post_garantia * $item->coste_partido_2;

        $total_partidos = $prima_1 + $prima_2;
      } else {
        $total_partidos = $item->partidos_asistencia * $item->coste_partido;
      }

      return $total_partidos;
    }

    public function updateNomina( Request $request, $id ) {
      $request->user()->authorizeRoles(['admin', 'rrhh']);

      $item = Nomina::find($id);

      $item->campeonatos = json_encode($request->get('campeonatos'));
      $item->total_torneos = $request->get('total_torneos');
      $item->total_pelotari = $request->get('total_pelotari');
      $item->save();

      return response()->json($item, 200);
    }
}
