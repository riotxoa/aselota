<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

use App\FestivalFacturacion;

class FestivalFacturacionController extends Controller
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

        $costes = FestivalFacturacion::where('festival_id', $id)->orderBy('id', 'desc')->get();

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

        $data = json_decode($request->get('form'));

        $facturacion = ($data->id ? FestivalFacturacion::find($data->id) : false);
        if( $facturacion ) {
          return $this->update($request, $facturacion->id);
        }

        $facturacion = FestivalFacturacion::where('festival_id', $data->festival_id)->first();

        $facturacion = new FestivalFacturacion([
          'festival_id' => $data->festival_id,
          'fpago_id' => $data->fpago_id,
          'fecha' => $data->fecha,
          'importe' => $data->importe,
          'enviar_id' => $data->enviar_id,
          'observaciones' => $data->observaciones,
          'pagado' => $data->pagado,
          'seguimiento' => $data->seguimiento,
          'explotacion_id' => $data->explotacion_id,
        ]);

        if($request->file('file_factura')) {
          $path = $request->file('file_factura')->storeAs('facturas', $data->file_name);
          $facturacion->file_factura = Storage::url($path);
        }

        $facturacion->save();

        return response()->json($facturacion, 200);
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

        $facturacion = FestivalFacturacion::find($id);

        return response()->json($facturacion, 200);
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

        $data = json_decode($request->get('form'));

        $facturacion = FestivalFacturacion::find($id);

        $facturacion->fpago_id = $data->fpago_id;
        $facturacion->fecha = $data->fecha;
        $facturacion->importe = $data->importe;
        $facturacion->enviar_id = $data->enviar_id;
        $facturacion->observaciones = $data->observaciones;
        $facturacion->pagado = $data->pagado;
        $facturacion->seguimiento = $data->seguimiento;
        $facturacion->explotacion_id = $data->explotacion_id;

        if($request->file('file_factura')) {
          $path = $request->file('file_factura')->storeAs('facturas', $data->file_name);
          $facturacion->file_factura = Storage::url($path);
        }

        $facturacion->save();

        return response()->json($facturacion, 200);
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

        FestivalFacturacion::destroy($id);

        return response()->json("FACTURACION REMOVED", 200);
    }

    public function download_factura(Request $request, $id)
    {
      $request->user()->authorizeRoles(['admin', 'gerente']);

      $item = FestivalFacturacion::find($id);
      $fileName = str_replace('/storage/facturas/', '', $item->file_factura);

      $headers = array(
         'Content-Type: application/octet-stream',
      );

      return response()->download(storage_path('app/facturas/' . $fileName), $fileName, $headers);
    }
}
