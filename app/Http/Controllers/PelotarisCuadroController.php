<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Pelotari;

class PelotarisCuadroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh', 'gerente', 'entrenador', 'intendente', 'prensa', 'medico']);
        
        $fecha_ini = $request->get('fecha_ini');
        $fecha_fin = $request->get('fecha_fin');

        if($fecha_ini==null && $fecha_fin==null){//Cuando se ha realizado una búsqueda sin filtros mostramos todo
          $fecha_ini = date('1900-01-01');
          $fecha_fin = date('Y-m-d');
          $items = $this->getItems($fecha_ini, $fecha_fin, false);

        }else if($fecha_ini!=null && $fecha_fin == null){//Busqueda por CONTRATO. Solo se manda la fecha inicio.
          $items = $this->getItems($fecha_ini, $fecha_fin, true);

        }else{//Cuando se ha realizado una búsqueda entre fechas.
          $items = $this->getItems($fecha_ini, $fecha_fin, false);

        }
        

        return response()->json($items, 200);
    }

    static public function getItems($fecha_ini, $fecha_fin, $byContrato){
      $items = DB::table('pelotaris')
          ->leftJoin('provincias', 'pelotaris.provincia_id', '=', 'provincias.id')
          ->leftJoin('municipios', 'pelotaris.municipio_id', '=', 'municipios.id')
          ->select('pelotaris.*', 'provincias.name as provincia', 'municipios.name as municipio')
          ->where('pelotaris.deleted_at', null);


        $items = $items->orderBy('alias')->get();

        foreach($items as $key => $item) {

          $debug_str = "";

          $partidos = array();
          $partidos_all = array();
          $num_partidos = 0;

          $entrenamientos = array();

          $num_entrenamientos = 0;
          $entrenamientos_asiste = 0;

          $partidos_ganados = 0;

          $retribucion = 0;
          $retribucion_meses = 0;
          $retribucion_d_imagen = 0;
          $retribucion_dieta_partido= 0;
          $retribucion_prima_partido= 0;
          $retribucion_prima_estelar= 0;
          $retribucion_prima_manomanista= 0;
          $retribucion_prima_manomanista_promo= 0;

          //recuperamos los contratos
          $id_pelotari = $item->id;
          
          //SI EL FILTRO ES POR CONTRATO, DEVOLVEMOS SOLO SI EL CONTRATO ESTÁ ENTRE LAS FECHAS
          if($byContrato){
            $contrats = \App\ContratoHeader::where('pelotari_id', $id_pelotari)
            ->where('deleted_at', null)
            ->whereDate('fecha_ini', '<=', $fecha_ini)
            ->whereDate('fecha_fin', '>=', $fecha_ini)
            ->orderBy('fecha_fin', 'desc')
            ->get();
          }else{
            $contrats = \App\ContratoHeader::where('pelotari_id', $id_pelotari)
            ->where('deleted_at', null)
            ->orderBy('fecha_fin', 'desc')
            ->get();
          }

          $last_fecha_min = $fecha_fin;//para que no repita meses de otro tramo mas antiguo en caso de que ya haya llegado al min
          $cierreFechas = false;

          //RECORREMOS LOS CONTRATOS PARA SACAR LOS TRAMOS
          foreach($contrats as $key2 => $contrat) {
            $tramos = \App\Contrato::where('header_id', $contrat->id)->orderBy('fecha_fin', 'desc')->get();
            $comercial = \App\ContratoComercial::where('header_id', $contrat->id)->orderBy('fecha_fin', 'desc')->get();
            $contrat->tramos = $tramos;
            $contrat->comerciales = $comercial;

            //comparamos las fechas del contrato en caso de que se haya realizado el filtro por contrato
            if($byContrato){
              $fecha_fin = date('Y-m-d');//marcamos el límite de la fecha max la actual.

              if(strtotime($fecha_fin)>strtotime($contrat->fecha_fin)){
                $fecha_fin = $contrat->fecha_fin;
              }
              if(strtotime($fecha_ini)>strtotime($contrat->fecha_ini)){
                $fecha_ini = $contrat->fecha_ini;
              }
            }

            //RECORREMOS LOS TRAMOS PARA SACAR LOS PARTIDOS QUE SE HAN JUGADO EN CADA TRAMO
            foreach($tramos as $key3 => $tramo) {

              //PARTIDOS PELOTARI
              $partidos = Pelotari::get_partidos_by_contrato($id_pelotari, $contrat->id, $fecha_ini, $fecha_fin);
              $num_partidos += sizeOf($partidos);

              //ENTRENAMIENTOS DEL PELOTARI
              $entrenamientos_arr = Pelotari::get_entrenamientos($id_pelotari, $fecha_ini, $fecha_fin);
              $num_entrenamientos = sizeof($entrenamientos);
              
              foreach($entrenamientos_arr as $key5 => $entreno) {
                if(!in_array($entreno, $entrenamientos)){
                  array_push($entrenamientos, $entreno);
                  if($entreno->asistencia){
                    $entrenamientos_asiste++;
                  }
                }
              }

              //añadimos a la retribución el pago mensual del pelotari
              //teniendo en cuenta los meses del tramo que estén dentro filtro
              if(strtotime($tramo->fecha_ini)<strtotime($fecha_fin)){
                //si ya ha llegado a la fecha mínima, dejamos de sumar meses

                if(strtotime($tramo->fecha_ini)<strtotime($fecha_ini)){
                  $ts1 = strtotime($fecha_ini);
                }else{
                  $ts1 = strtotime($tramo->fecha_ini);
                }
                if($last_fecha_min==$ts1){
                  $cierreFechas=true;
                }
                $last_fecha_min = $ts1;

                if(strtotime($tramo->fecha_fin)>strtotime($fecha_fin)){
                  $ts2 = strtotime($fecha_fin);
                }else{
                  $ts2 = strtotime($tramo->fecha_fin);
                }
  
                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);
                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);

                $meses = (($year2 - $year1) * 12) + ($month2 - $month1) +1;

                if(!$cierreFechas){
    
                  $retribucion += $meses*$tramo->dieta_mes;
                  $retribucion += $meses*$tramo->d_imagen;

                  $retribucion_meses += $meses*$tramo->dieta_mes;
                  $retribucion_d_imagen += $meses*$tramo->d_imagen;

                  $debug_str = $debug_str . "" . "Meses: " . $meses . " | ";
                  $debug_str = $debug_str . "" . "Fecha ini: " . date('Y-m-d', $ts1) . " | ";
                  $debug_str = $debug_str . "" . "Fecha fin: " . date('Y-m-d', $ts2) . " | ";
                  $debug_str = $debug_str . "" . "Dieta mes: " . $meses*$tramo->dieta_mes . " | ";
                  $debug_str = $debug_str . "" . "D imagen: " . $meses*$tramo->d_imagen . " | ";
                }
              }

              //RECORREMOS CADA PARTIDO, PARA CALCULAR LA RETRIBUCIÓN EN FUNCIÓN AL RESULTADO Y TIPO DE PARTIDO
              foreach($partidos as $key4 => $partido) {
                /*recuperamos los datos del campeonato del partido
                $campeonato = \App\Campeonato::where('id', $partido->campeonato_id)->get();
                $partido->campeonato = $campeonato;*/

                //Dieta partido
                $retribucion += $partido->dieta_partido;
                $retribucion_dieta_partido += $partido->dieta_partido;

                $debug_str = $debug_str . "" . "D partido: " . $partido->dieta_partido . " | ";
                
                //Partido ganado
                $ganado = false;
                if(($partido->color=="A" && $partido->puntos_azul>$partido->puntos_rojo) ||
                   ($partido->color=="R" && $partido->puntos_rojo>$partido->puntos_azul)){
                    $retribucion += $partido->prima_partido;
                    $retribucion_prima_partido += $partido->prima_partido;

                    $debug_str = $debug_str . "" . "prima partido: " . $partido->prima_partido . " | ";

                    $ganado = true;
                    $partidos_ganados++;
                }

                //Partido Estelar
                if($partido->estelar){
                  $retribucion += $partido->prima_estelar;
                  $retribucion_prima_estelar += $partido->prima_estelar;

                  $debug_str = $debug_str . "" . "prima partido: " . $partido->prima_estelar . " | ";

                }

                //Campeonato Manomanista de 1ª
                if($ganado && $partido->campeonato_id==2){
                  if($partido->prima_manomanista){
                    $retribucion += $partido->prima_manomanista;
                    $retribucion_prima_manomanista += $partido->prima_manomanista;

                    $debug_str = $debug_str . "" . "prima partido: " . $partido->prima_manomanista . " | ";
                  }
                }

                //PROMO. Manomanista
                if($ganado && $partido->campeonato_id==7){
                  if($partido->prima_manomanista_promo){
                    $retribucion += $partido->prima_manomanista_promo;
                    $retribucion_prima_manomanista_promo += $partido->prima_manomanista_promo;

                    $debug_str = $debug_str . "" . "prima partido: " . $partido->prima_manomanista_promo . " | ";
                  }
                }

                //añadimos el partido al array de partidos global. Esto es para debugear en consola
                array_push($partidos_all, $partido);
              }
            }
          }

          //for debug
          //$items[$key]->debug_str = $debug_str;
          //$items[$key]->contratos = $contrats;
          //$items[$key]->entrenamientos = $entrenamientos;
          //$items[$key]->partidos = $partidos_all;

          $items[$key]->num_entrenamientos = $num_entrenamientos;
          $items[$key]->entrenamientos_asiste = $entrenamientos_asiste;
          if($num_entrenamientos!=0){
            $items[$key]->ratio_disponibilidad = number_format((($entrenamientos_asiste*100)/$num_entrenamientos),2,",",".") . "%";
          }else{
            $items[$key]->ratio_disponibilidad = "S/E";
          }
          $items[$key]->no_asiste = $num_entrenamientos-$entrenamientos_asiste;

          $items[$key]->partidos_ganados = $partidos_ganados;
          $items[$key]->partidos_jugados = $num_partidos;
          
          setlocale(LC_MONETARY,"es_ES");
          $items[$key]->retribucion = number_format($retribucion,2,",",".") . "€";
          
          $items[$key]->retribucion_meses = number_format($retribucion_meses,2,",",".") . "€";
          $items[$key]->retribucion_d_imagen = number_format($retribucion_d_imagen,2,",",".") . "€";
          $items[$key]->retribucion_dieta_partido = number_format($retribucion_dieta_partido,2,",",".") . "€";
          $items[$key]->retribucion_prima_partido = number_format($retribucion_prima_partido,2,",",".") . "€";
          $items[$key]->retribucion_prima_estelar = number_format($retribucion_prima_estelar,2,",",".") . "€";
          $items[$key]->retribucion_prima_manomanista = number_format($retribucion_prima_manomanista,2,",",".") . "€";
          $items[$key]->retribucion_prima_manomanista_promo = number_format($retribucion_prima_manomanista_promo,2,",",".") . "€";

        }
        return $items;
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
}
