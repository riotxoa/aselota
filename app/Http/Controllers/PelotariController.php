<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use App\Pelotari;

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
          $items = $items->leftJoin('contratos as c1', 'pelotaris.id', '=', 'c1.pelotari_id')
                         ->whereDate('c1.fecha_ini', '<=', $fecha)
                         ->whereDate('c1.fecha_fin', '>=', $fecha)
                         ->where('c1.deleted_at', null)->addSelect('c1.fecha_ini as fecha_contrato', 'c1.coste', 'c1.coste_no_gar', 'c1.garantia');
        }

        if($request->get('fecha_ini')) {
          $get_partidos_jugados = true;

          $fecha_ini = $request->get('fecha_ini');
          $fecha_fin = $request->get('fecha_fin');
          $items = $items->leftJoin('contratos as c2', 'pelotaris.id', '=', 'c2.pelotari_id')
                         ->whereDate('c2.fecha_ini', '<=', $fecha_fin)
                         ->whereDate('c2.fecha_fin', '>=', $fecha_ini)
                         ->whereNull('c2.deleted_at')->addSelect('c2.fecha_ini as fecha_contrato', 'c2.coste', 'c2.coste_no_gar', 'c2.garantia');
        }

        $items = $items->orderBy('alias')
                       ->get();

        if($get_partidos_jugados) {
          $fecha = ( $request->get('fecha') ? $request->get('fecha') : $request->get('fecha_fin'));
          foreach($items as $key => $item) {
            $items[$key]->partidos_jugados = Pelotari::get_partidos_jugados_contrato($item->id, $fecha);
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
