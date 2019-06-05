<?php

namespace App\Http\Controllers;

use App\Exports\CuadroMandoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class CuadroMandoController extends Controller
{
    
    public function export() 
    {
        $fecha_ini = Input::get('fecha_ini', '1900-01-01');
        $fecha_fin = Input::get('fecha_fin', date('Y-m-d'));
        return Excel::download(new CuadroMandoExport($fecha_ini, $fecha_fin), 'pelotaris.xlsx');
    }
}
