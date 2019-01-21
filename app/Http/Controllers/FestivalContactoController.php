<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\FestivalContacto;

class FestivalContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'gerente']);

        $id = $request->get('festival_id');

        $contactos = FestivalContacto::where('festival_id', $id)->get();

        return response()->json($contactos, 200);
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

        $contacto = FestivalContacto::where('festival_id', $request->get('festival_id'))->first();

        if($contacto)
          return $this->update($request, $contacto->id);

        $contacto = new FestivalContacto([
          'festival_id' => $request->get('festival_id'),
          'contact_01_name' => $request->get('contact_01_name'),
          'contact_01_desc' => $request->get('contact_01_desc'),
          'contact_01_telephone_1' => $request->get('contact_01_telephone_1'),
          'contact_01_telephone_2' => $request->get('contact_01_telephone_2'),
          'contact_01_email_1' => $request->get('contact_01_email_1'),
          'contact_01_email_2' => $request->get('contact_01_email_2'),
          'contact_02_name' => $request->get('contact_02_name'),
          'contact_02_desc' => $request->get('contact_02_desc'),
          'contact_02_telephone_1' => $request->get('contact_02_telephone_1'),
          'contact_02_telephone_2' => $request->get('contact_02_telephone_2'),
          'contact_02_email_1' => $request->get('contact_02_email_1'),
          'contact_02_email_2' => $request->get('contact_02_email_2'),
          'observaciones' => $request->get('observaciones'),
        ]);

        $contacto->save();

        return response()->json($request  , 200);
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

        $contacto = FestivalContacto::find($id);

        return response()->json($contacto, 200);
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

        $contacto = FestivalContacto::find($id);

        $contacto->festival_id = $request->get('festival_id');
        $contacto->contact_01_name = $request->get('contact_01_name');
        $contacto->contact_01_desc = $request->get('contact_01_desc');
        $contacto->contact_01_telephone_1 = $request->get('contact_01_telephone_1');
        $contacto->contact_01_telephone_2 = $request->get('contact_01_telephone_2');
        $contacto->contact_01_email_1 = $request->get('contact_01_email_1');
        $contacto->contact_01_email_2 = $request->get('contact_01_email_2');
        $contacto->contact_02_name = $request->get('contact_02_name');
        $contacto->contact_02_desc = $request->get('contact_02_desc');
        $contacto->contact_02_telephone_1 = $request->get('contact_02_telephone_1');
        $contacto->contact_02_telephone_2 = $request->get('contact_02_telephone_2');
        $contacto->contact_02_email_1 = $request->get('contact_02_email_1');
        $contacto->contact_02_email_2 = $request->get('contact_02_email_2');
        $contacto->observaciones = $request->get('observaciones');

        $contacto->save();

        return response()->json($contacto, 200);
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

        FestivalContacto::destroy($id);

        return response()->json("CONTACTO REMOVED", 200);
    }
}
