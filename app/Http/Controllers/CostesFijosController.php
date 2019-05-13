<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\CostesFijos;

class CostesFijosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $costes = CostesFijos::orderBy('id', 'desc')->get();

        return response()->json($costes, 200);
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

        $costes = CostesFijos::orderBy('id', 'desc')->get();

        if($costes)
          return $this->update($request, $costes->id);

        $costes = new CostesFijos([
          'id' => $request->get('id'),
          'descripcion' => $request->get('descripcion'),
          'coste' => $request->get('coste')
        ]);

        $costes->save();

        return response()->json($costes, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $costes = CostesFijos::find($id);

        return response()->json($costes, 200);
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
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $costes = CostesFijos::find($id);

        $costes->descripcion = $request->get('descripcion');
        $costes->coste = $request->get('coste');

        $costes->save();

        return response()->json($costes, 200);
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

        CostesFijos::destroy($id);

        return response()->json("COSTE FIJO REMOVED", 200);
    }
}
