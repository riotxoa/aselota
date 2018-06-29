<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contrato;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'rrhh']);

        $id = $request->get('pelotari_id');

        $items = Contrato::where('pelotari_id', $id)->get();

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
        // $request->user()->authorizeRoles(['admin', 'rrhh']);
        //
        // $data = json_decode($request->get('form'));
        //
        // $item = new Pelotari([
        //   'DNI' => $data->dni,
        //   'nombre' => $data->nombre,
        //   'apellidos' => $data->apellidos,
        //   'alias' => $data->alias,
        //   'posicion' => $data->posicion,
        //   'direccion' => $data->direccion,
        //   'cod_postal' => $data->cod_postal,
        //   'municipio_id' => $data->municipio_id,
        //   'provincia_id' => $data->provincia_id,
        //   'email' => $data->email,
        //   'telefono' => $data->telefono,
        // ]);
        //
        // if($request->file('photo')) {
        //   $path = $request->file('photo')->storeAs('public/avatars', $data->fotoName);
        //   $item->foto = Storage::url($path);
        // }
        //
        // $item->save();
        //
        // return response()->json($item, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
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
        // $request->user()->authorizeRoles(['admin', 'rrhh']);
        //
        // $item = Pelotari::find($id);
        //
        // $data = json_decode($request->get('form'));
        //
        // $item->DNI = $data->dni;
        // $item->nombre = $data->nombre;
        // $item->apellidos = $data->apellidos;
        // $item->alias = $data->alias;
        // $item->posicion = $data->posicion;
        // $item->direccion = $data->direccion;
        // $item->cod_postal = $data->cod_postal;
        // $item->municipio_id = $data->municipio_id;
        // $item->provincia_id = $data->provincia_id;
        // $item->email = $data->email;
        // $item->telefono = $data->telefono;
        //
        // if($request->file('photo')) {
        //   $path = $request->file('photo')->storeAs('public/avatars', $data->fotoName);
        //   $item->foto = Storage::url($path);
        // }
        //
        // $item->save();
        //
        // return response()->json($item, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // $request->user()->authorizeRoles(['admin', 'rrhh']);
        //
        // Pelotari::destroy($id);
        //
        // return response()->json("LARAVEL REMOVED", 200);
    }
}
