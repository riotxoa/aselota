<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Festivale;
use PDF; // https://www.positronx.io/laravel-pdf-tutorial-generate-pdf-with-dompdf-in-laravel/

class InformesController extends Controller
{

    public function GetPilotakadakEtaIraupena()
    {
      // $pdf = \App::make('dompdf.wrapper');
      // $pdf->loadHTML('<h1>Test</h1>');
      // return $pdf->download('invoice.pdf');

      $data = array(
        'title' => 'IZENBURUA',
        'desc' => 'AZALPENA',
      );
      $pdf = PDF::loadView('informes.pilotakadak-eta-iraupena', $data);
      return $pdf->download('invoice.pdf');
    }
}
