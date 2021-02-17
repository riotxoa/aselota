<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\PLEN_Ejercicio;

use Illuminate\Support\Facades\Log;

class PLEN_EjercicioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      $request->user()->authorizeRoles(['admin', 'plen_gestor']);

      $ejercicios = PLEN_Ejercicio::select('plen_ejercicios.*', 'plen_tipos_ejercicio.desc as tipo_name', 'plen_subtipos_ejercicio.desc as subtipo_name')
        ->leftJoin('plen_tipos_ejercicio', 'plen_ejercicios.tipo_ejercicio_id', '=', 'plen_tipos_ejercicio.id')
        ->leftJoin('plen_subtipos_ejercicio', 'plen_ejercicios.subtipo_ejercicio_id', '=', 'plen_subtipos_ejercicio.id')
        ->orderBy('plen_ejercicios.order', 'asc')
        ->orderBy('plen_ejercicios.name', 'asc')
        ->get();

      return response()->json($ejercicios, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        $data = json_decode($request->get('form'));

        if( $data->id ) {
          return $this->update($request, $data);
        } else {
          return $this->insert($request, $data);
        }
    }

    private function insert(Request $request, $data)
    {
        $item = new PLEN_Ejercicio([
          'order' => $data->order,
          'name' => $data->name,
          'desc' => $data->desc,
          'tipo_ejercicio_id' => $data->tipo_ejercicio_id,
          'subtipo_ejercicio_id' => $data->subtipo_ejercicio_id,
          'video' => $data->video,
          'material' => $data->material,
        ]);

        if($request->file('photo')) {
          $path = $request->file('photo')->storeAs('public/ejercicios', $data->fileName);
          $item->imagen = Storage::url($path);
        }

        $item->save();

        return $this->index($request);
    }

    private function update(Request $request, $data)
    {
      $item = PLEN_Ejercicio::find($data->id);

      $item->order = $data->order;
      $item->name = $data->name;
      $item->desc = $data->desc;
      $item->tipo_ejercicio_id = $data->tipo_ejercicio_id;
      $item->subtipo_ejercicio_id = $data->subtipo_ejercicio_id;
      $item->video = $data->video;
      $item->material = $data->material;

      if($request->file('photo')) {
        $path = $request->file('photo')->storeAs('public/ejercicios', $data->fileName);
        $item->imagen = Storage::url($path);
      }

      $item->save();

      return $this->index($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'plen_gestor']);

        PLEN_Ejercicio::destroy($id);

        return $this->index($request);
        // return response()->json("EJERCICIO ELIMINADO", 200);
    }
}
