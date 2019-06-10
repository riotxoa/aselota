<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Festivale;
use App\FestivalCoste;
use App\FestivalFacturacion;
use App\FestivalContacto;
use App\FestivalPartidoMarcadore;
use App\FestivalPartidoTanteo;
use App\FestivalPartidoPelotari;
use App\FestivalPartido;
use App\User;
use TCG\Voyager\Models\Role;

class FestivaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);

        $items = DB::table('festivales')
          ->leftJoin('frontones', 'festivales.fronton_id', '=', 'frontones.id')
          ->leftJoin('provincias', 'frontones.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'frontones.municipio_id', '=', 'municipios.id')
          ->leftJoin('estado_festivals', 'festivales.estado_id', '=', 'estado_festivals.id')
          ->leftJoin('festival_contactos', 'festivales.id', '=', 'festival_contactos.festival_id')
          ->select(
              'festivales.*',
              DB::raw('IF(festivales.television, "Sí", "No") AS television_txt'),
              'provincias.id as provincia_id',
              'provincias.name as provincia',
              'municipios.id as municipio_id',
              'municipios.name as municipio',
              'frontones.name as fronton',
              'estado_festivals.name as estado',
              'festival_contactos.contact_01_name',
              'festival_contactos.contact_01_desc',
              'festival_contactos.contact_01_email_1',
              'festival_contactos.contact_01_email_2',
              'festival_contactos.contact_01_telephone_1',
              'festival_contactos.contact_01_telephone_2',
              'festival_contactos.contact_02_name',
              'festival_contactos.contact_02_desc',
              'festival_contactos.contact_02_email_1',
              'festival_contactos.contact_02_email_2',
              'festival_contactos.contact_02_telephone_1',
              'festival_contactos.contact_02_telephone_2'
            );

          if( null !== $request->get('filter') ) {
            $in_clause = array();

            $items = $items->leftJoin('festival_facturacion', 'festivales.id', '=', 'festival_facturacion.festival_id')
                           ->leftJoin('festival_partidos', 'festivales.id', '=', 'festival_partidos.festival_id')
                           ->leftJoin('festival_partido_pelotaris', 'festival_partidos.id', '=', 'festival_partido_pelotaris.festival_partido_id')
                           ->leftJoin('pelotaris', 'festival_partido_pelotaris.pelotari_id', '=', 'pelotaris.id')
                           ->groupBy('festivales.id')
                           ->groupBy('festival_contactos.id');

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

          if( $request->user()->hasAnyRole( array("Intendente", "Prensa") ) ) {
            $items = $items->where('festivales.estado_id', '>', 1); // Estado !== 'Estimación'
          }

          $items = $items->where('festivales.deleted_at', null)
          ->orderBy('festivales.fecha', 'desc')
          ->get();

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
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $item = new Festivale([
          'fecha' => $request->get('fecha'),
          'hora' => $request->get('hora'),
          'fronton_id' => $request->get('fronton_id'),
          'television' => $request->get('television'),
          'television_txt' => $request->get('television_txt'),
          'organizador' => $request->get('organizador'),
          'estado_id' => $request->get('estado_id'),
          'fecha_presu' => $request->get('fecha_presu'),
        ]);

        $item->save();

        // Costes
        $costes = new FestivalCoste([
          'festival_id' => $item->id
        ]);
        $costes->save();

        // Facturación
        $facturacion = new FestivalFacturacion([
          'festival_id' => $item->id
        ]);
        $facturacion->save();

        // Contactos
        $contactos = new FestivalContacto([
          'festival_id' => $item->id
        ]);
        $contactos->save();

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

        $item = DB::table('festivales')
          ->leftJoin('frontones', 'festivales.fronton_id', '=', 'frontones.id')
          ->leftJoin('provincias', 'frontones.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'frontones.municipio_id', '=', 'municipios.id')
          ->select(
              'festivales.*',
              'provincias.id as provincia_id',
              'provincias.name as provincia',
              'municipios.id as municipio_id',
              'municipios.name as municipio',
              'frontones.name as fronton'
            )
          ->where('festivales.id', $id)
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
        $request->user()->authorizeRoles(['admin', 'gerente', 'intendente']);

        $header = $request->get('header');
        $costes = $request->get('costes');
        $facturacion = $request->get('facturacion');
        $contactos = $request->get('contactos');

        $fest_header = Festivale::find($id);

        $fest_header->fecha = $header['fecha'];
        $fest_header->hora = $header['hora'];
        $fest_header->fronton_id = $header['fronton_id'];
        $fest_header->television = $header['television'];
        $fest_header->television_txt = $header['television_txt'];
        $fest_header->organizador = $header['organizador'];
        $fest_header->estado_id = $header['estado_id'];
        $fest_header->fecha_presu = $header['fecha_presu'];

        $fest_header->save();

        // $fest_costes = FestivalCoste::find($fest_header->costes()->first()->id);

        $fest_costes = $fest_header->costes;

        if( $fest_costes ) {
          $fest_costes = FestivalCoste::find($fest_costes->first()->id);

          $fest_costes->coste_empresa = (array_key_exists('coste_empresa', $costes) ? $costes['coste_empresa'] : 0);
          $fest_costes->sanidad = $costes['sanidad'];
          $fest_costes->num_auxiliares = $costes['num_auxiliares'];
          $fest_costes->num_taquilleros = $costes['num_taquilleros'];
          $fest_costes->importe_venta = $costes['importe_venta'];
          $fest_costes->aportacion = $costes['aportacion'];
          $fest_costes->num_espectadores = $costes['num_espectadores'];
          $fest_costes->ingreso_taquilla = $costes['ingreso_taquilla'];
          $fest_costes->ingreso_ayto = $costes['ingreso_ayto'];
          $fest_costes->ingreso_otros = $costes['ingreso_otros'];
          $fest_costes->cliente_id = $costes['cliente_id'];
          $fest_costes->cliente_txt = $costes['cliente_txt'];
          $fest_costes->porcentaje = $costes['porcentaje'];
        } else {
          $fest_costes = new FestivalCoste([
            'festival_id' => $id,
            'coste_empresa' => (array_key_exists('coste_empresa', $costes) ? $costes['coste_empresa'] : 0),
            'sanidad' => $costes['sanidad'],
            'num_auxiliares' => $costes['num_auxiliares'],
            'num_taquilleros' => $costes['num_taquilleros'],
            'importe_venta' => $costes['importe_venta'],
            'aportacion' => $costes['aportacion'],
            'num_espectadores' => $costes['num_espectadores'],
            'ingreso_taquilla' => $costes['ingreso_taquilla'],
            'ingreso_ayto' => $costes['ingreso_ayto'],
            'ingreso_otros' => $costes['ingreso_otros'],
            'cliente_id' => $costes['cliente_id'],
            'cliente_txt' => $costes['cliente_txt'],
            'porcentaje' => $costes['porcentaje'],
          ]);
        }
        $fest_costes->save();

        // $fest_facturacion = FestivalFacturacion::find($fest_header->facturacion()->first()->id);
        $fest_facturacion = $fest_header->facturacion;

        if( $fest_facturacion ) {
          $fest_facturacion = FestivalFacturacion::find($fest_facturacion->first()->id);

          $fest_facturacion->fpago_id = $facturacion['fpago_id'];
          $fest_facturacion->fecha = $facturacion['fecha'];
          $fest_facturacion->importe = $facturacion['importe'];
          $fest_facturacion->enviar_id = $facturacion['enviar_id'];
          $fest_facturacion->observaciones = $facturacion['observaciones'];
          $fest_facturacion->pagado = $facturacion['pagado'];
          $fest_facturacion->seguimiento = $facturacion['seguimiento'];
        } else {
          $fest_facturacion = new FestivalFacturacion([
            'festival_id' => $id,
            'fpago_id' => $facturacion['fpago_id'],
            'fecha' => $facturacion['fecha'],
            'importe' => $facturacion['importe'],
            'enviar_id' => $facturacion['enviar_id'],
            'observaciones' => $facturacion['observaciones'],
            'pagado' => $facturacion['pagado'],
            'seguimiento' => $facturacion['seguimiento'],
          ]);
        }
        $fest_facturacion->save();

        $fest_contactos = $fest_header->contactos;

        if( $fest_contactos ) {
          $fest_contactos = FestivalContacto::find($fest_contactos->first()->id);

          $fest_contactos->contact_01_name = $contactos['contact_01_name'];
          $fest_contactos->contact_01_desc = $contactos['contact_01_desc'];
          $fest_contactos->contact_01_telephone_1 = $contactos['contact_01_telephone_1'];
          $fest_contactos->contact_01_telephone_2 = $contactos['contact_01_telephone_2'];
          $fest_contactos->contact_01_email_1 = $contactos['contact_01_email_1'];
          $fest_contactos->contact_01_email_2 = $contactos['contact_01_email_2'];
          $fest_contactos->contact_02_name = $contactos['contact_02_name'];
          $fest_contactos->contact_02_desc = $contactos['contact_02_desc'];
          $fest_contactos->contact_02_telephone_1 = $contactos['contact_02_telephone_1'];
          $fest_contactos->contact_02_telephone_2 = $contactos['contact_02_telephone_2'];
          $fest_contactos->contact_02_email_1 = $contactos['contact_02_email_1'];
          $fest_contactos->contact_02_email_2 = $contactos['contact_02_email_2'];
          $fest_contactos->observaciones = $contactos['observaciones'];
        } else {
          $fest_contactos = new FestivalContacto([
            'festival_id' => $id,
            'contact_01_name' => $contactos['contact_01_name'],
            'contact_01_desc' => $contactos['contact_01_desc'],
            'contact_01_telephone_1' => $contactos['contact_01_telephone_1'],
            'contact_01_telephone_2' => $contactos['contact_01_telephone_2'],
            'contact_01_email_1' => $contactos['contact_01_email_1'],
            'contact_01_email_2' => $contactos['contact_01_email_2'],
            'contact_02_name' => $contactos['contact_02_name'],
            'contact_02_desc' => $contactos['contact_02_desc'],
            'contact_02_telephone_1' => $contactos['contact_02_telephone_1'],
            'contact_02_telephone_2' => $contactos['contact_02_telephone_2'],
            'contact_02_email_1' => $contactos['contact_02_email_1'],
            'contact_02_email_2' => $contactos['contact_02_email_2'],
            'observaciones' => $contactos['observaciones'],
          ]);
        }
        $fest_contactos->save();

        return response()->json($fest_header, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $festival = Festivale::find($id);

        $fest_costes = $festival->costes;
        $fest_facturacion = $festival->facturacion;
        $fest_contactos = $festival->contactos;
        $fest_partidos = $festival->partidos;

        if( $fest_costes ) {
          FestivalCoste::destroy($fest_costes->id);
        }
        if( $fest_facturacion ) {
          FestivalFacturacion::destroy($fest_facturacion->id);
        }
        if( $fest_contactos ) {
          FestivalContacto::destroy($fest_contactos->id);
        }

        if( $fest_partidos ) {

          foreach( $fest_partidos as $partido ) {

            $part_marcadores = $partido->marcadores;
            $part_tanteos = $partido->tanteos;
            $part_pelotaris = $partido->pelotaris;

            if( $part_marcadores ) {
              foreach( $part_marcadores as $marcador ) {
                FestivalPartidoMarcadore::destroy($marcador->id);
              }
            }
            if( $part_tanteos ) {
              foreach( $part_tanteos as $tanteo ) {
                FestivalPartidoTanteo::destroy($tanteo->id);
              }
            }
            if( $part_pelotaris ) {
              foreach( $part_pelotaris as $pelotari ) {
                FestivalPartidoPelotari::destroy($pelotari->id);
              }
            }

            FestivalPartido::destroy($partido->id);

          }

        }

        Festivale::destroy($id);

        return response()->json("FESTIVAL REMOVED", 200);
    }

    public function getCalendarMonth(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'gerente']);

      $month = $request->get('month');
      $pelotaris = $request->get('pelotaris');

      $calendar = DB::table('pelotaris')
        ->leftJoin('festival_partido_pelotaris', 'festival_partido_pelotaris.pelotari_id', '=', 'pelotaris.id')
        ->leftJoin('festival_partidos', 'festival_partidos.id', '=', 'festival_partido_pelotaris.festival_partido_id')
        ->leftJoin('festivales', 'festivales.id', '=', 'festival_partidos.festival_id')
        ->leftJoin('frontones', 'frontones.id', '=', 'festivales.fronton_id')
        ->leftJoin('municipios', 'municipios.id', '=', 'frontones.municipio_id')
        ->leftJoin('campeonatos', 'campeonatos.id', '=', 'festival_partidos.campeonato_id')
        ->leftJoin('tipo_partidos', 'tipo_partidos.id', '=', 'festival_partidos.tipo_partido_id')
        ->select(
            'pelotaris.id',
            'pelotaris.alias',
            'festival_partido_pelotaris.festival_partido_id',
            'festival_partido_pelotaris.asiste',
            'festival_partido_pelotaris.sustituto_id',
            'festival_partidos.festival_id',
            'festival_partidos.orden',
            'festival_partidos.estelar',
            'festival_partidos.campeonato_id',
            'campeonatos.name as campeonato_name',
            'tipo_partidos.name as tipo_partido_name',
            'festival_partidos.fase',
            'festivales.fecha',
            DB::raw('DAYOFMONTH(festivales.fecha) as day'),
            DB::raw('MONTH(festivales.fecha) as month'),
            DB::raw('YEAR(festivales.fecha) as year'),
            'festivales.hora',
            'festivales.fronton_id',
            'frontones.name as fronton_name',
            'municipios.name as municipio_name',
            'festivales.television'
          );

      if( $month ) {
        $calendar = $calendar->whereMonth('festivales.fecha', $month);
      } else {
        $calendar = $calendar->whereMonth('festivales.fecha', date("m"));
      }

      $calendar = $calendar->whereNull('pelotaris.deleted_at')
                           ->orderBy('pelotaris.alias')
                           ->get();


      return response()->json($calendar, 200);
    }
}
