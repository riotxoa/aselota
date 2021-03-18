<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;

// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithMapping;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InformePelotazosDuracionExport implements FromView // FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;
    protected $fecha_ini;
    protected $fecha_fin;
    protected $festivales;
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        // $this->fecha_ini = $data->fecha_ini;
        // $this->fecha_fin = $data->fecha_fin;
        // $this->festivales = $data->fetivales;
    }

    public function map($fila): array
    {
        return [
            $fila->id,
            $fila->DNI,
            $fila->nombre,
            $fila->apellidos,
            $fila->alias,
            $fila->posicion,
            $fila->promesa,
            $fila->retribucion,
            $fila->retribucion_meses,
            $fila->retribucion_d_imagen,
            $fila->partidos_jugados,
            $fila->partidos_ganados,
            $fila->retribucion_dieta_partido,
            $fila->retribucion_prima_partido,
            $fila->retribucion_prima_estelar,
            $fila->retribucion_prima_manomanista,
            $fila->retribucion_prima_manomanista_promo,
            $fila->ratio_disponibilidad,
            $fila->num_entrenamientos,
            $fila->entrenamientos_asiste,
            $fila->no_asiste
        ];
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
            'Promesa',
            'Retribuciónes',
            'Dietas mensuales',
            'Derechos de imagen',
            'Partidos',
            'Ganados',
            'Dieta partidos',
            'Primas partidos',
            'Primas estelar',
            'Primas Manomanista',
            'Primas Manomanista PROMO',
            'Ratio disponibilidad',
            'Entrenamientos',
            'Asiste',
            'No asiste'
        ];
    }

    public function collection()
    {

        $fecha_ini  = $this->fecha_ini;
        $fecha_fin  = $this->fecha_fin;
        $byContrato  = $this->byContrato;

        $res = PelotarisCuadroController::getItems($fecha_ini, $fecha_fin, $byContrato);
        return $res;
    }

    public function view(): View
    {
        return view('informes.pilotakadak-eta-iraupena-xls', $this->data);
    }

}
