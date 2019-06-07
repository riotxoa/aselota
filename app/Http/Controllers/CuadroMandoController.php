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
        $fecha_ini = Input::get('fecha_ini');
        $fecha_fin = Input::get('fecha_fin');
        $byContrato = false;

        if(($fecha_ini==null && $fecha_fin==null) || ($fecha_ini=="null" && $fecha_fin=="null")){//Cuando se ha realizado una búsqueda sin filtros mostramos todo
            $fecha_ini = date('1900-01-01');
            $fecha_fin = date('Y-m-d');
            $byContrato = false;
        }else if(($fecha_ini!=null && $fecha_fin == null) || ($fecha_ini!="null" && $fecha_fin == "null")){//Busqueda por CONTRATO. Solo se manda la fecha inicio.
            $byContrato = true;
        }else{//Cuando se ha realizado una búsqueda entre fechas.
            $byContrato = false;
        }

        return Excel::download(new CuadroMandoExport($fecha_ini, $fecha_fin, $byContrato), 'CuadroMando.xlsx');
    }
}
