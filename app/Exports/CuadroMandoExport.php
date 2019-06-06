<?php

namespace App\Exports;

use App\Pelotari;
use App\Http\Controllers\PelotarisCuadroController;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;

class CuadroMandoExport implements FromQuery, WithHeadings
{
    use Exportable;
    protected $fecha_ini;
    protected $fecha_fin;

    public function __construct($fecha_ini, $fecha_fin)
    {
        $this->fecha_ini = $fecha_ini;
        $this->fecha_fin = $fecha_fin;
    }

    public function headings(): array
    {
        return [
            'ID',
            'DNI',
            'Nombre',
            'Apellidos',
            'Alias',
            'Posición',
            'Dirección',
            'Cod. postal',
            'Municipio ID',
            'Provincia ID',
            'Email',
            'Teléfono',
            'Fecha creación',
            'Fecha actualización',
            'Fecha borrado',
            'Foto',
            'Num SS',
            'Fecha nacimiento',
            'Teléfono 2',
            'Teléfono 3',
            'IBAN',
            'Num hijos',
            'Promesa'
        ];
    }

    public function query()
    {
        $fecha_ini  = $this->fecha_ini;
        $fecha_fin  = $this->fecha_fin;
        //return Pelotari::cuadro_export($fecha_ini, $fecha_fin);
        return Pelotari::query();
    }

}   
