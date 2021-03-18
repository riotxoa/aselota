<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>PELOTAZOS Y DURACIÓN</title>
    <style>
      body {
        font-family:arial;
      }
      h2, h4 {
        line-height:1;
        text-align:center;
      }
      h2 small {
        font-size:65%;
        font-weight:normal;
      }
      table {
        font-size:14px;
        margin-bottom:2rem;
        width:100%;
      }
      th {
        font-weight:bold;
      }
      table,
      th,
      td {
        padding: 7.5px 10px;
        border: 1px solid black;
        border-collapse: collapse;
      }
      td.blue {
        color:blue;
      }
      td.fecha,
      td.hora {
        text-align:center;
      }
      td.festival {
        background-color:gray;
        color:white;
        width:25%;
      }
      td.partido {
        width: 50%;
      }
      td.partido strong {
        display:inline-block;
        width:115px;
      }
      td.duracion,
      td.pelotazos {
        text-align:right;
        width:12.5%;
      }
      td.puntos {
        text-align:center;
      }
      td.red {
        color:red;
      }
    </style>
  </head>
  <body>
    <h2>PELOTAZOS Y DURACIÓN<br><small><?php echo "Fecha listado: " . date('d-m-Y'); ?></small></h2>
    @if( $fecha_ini or $fecha_fin )
      <h4><?php echo ($fecha_ini ? "Desde: $fecha_ini - " : "") . ($fecha_fin ? "Hasta: $fecha_fin" : ""); ?></h4>
    @endif
    @foreach( $festivales as $festival )
      <table style="border:none;padding:0;">
        <tr>
          <td colspan="4" class="festival">
            <div style="display:inline-block; text-align:left; width:49.5%;">
              <strong>[{{ $loop->iteration }}] Fecha y hora: </strong><?php $fecha = new DateTime($festival->fecha); echo $fecha->format('d-m-Y'); ?> - {{ $festival->hora }}
            </div>
            <div style="display:inline-block; text-align:right; width:49.5%;">
              <strong>Frontón: </strong>{{ $festival->fronton_name }}&nbsp;({{ $festival->fronton_localidad }})
            </div>
          </td>
        </tr>
        @foreach( $festival->partidos as $partido )
          <tr>
            <td colspan="2" class="partido">
              @if( $partido->campeonato )
                <strong>Campeonato: </strong>{{ $partido->campeonato }}<br/>
              @endif
              @if( $partido->tipo_partido )
                <strong>Tipo de partido: </strong>{{ $partido->tipo_partido }}
              @endif
            </td>
            <td class="pelotazos">
              <strong>Pelotazos</strong>
            </td>
            <td class="duracion">
              <strong>Duración</strong>
            </td>
          </tr>
          <tr>
            <td class="red puntos">{{ $partido->puntos_rojo }}</td>
            <td class="red">
              @foreach( $partido->pelotaris_rojo as $pelotari )
                @if( $loop->iteration > 1 )
                 &nbsp;-&nbsp;{{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
                @else
                 {{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
                @endif
              @endforeach
            </td>
            <td rowspan="2" class="pelotazos"><?php echo ( $partido->pelotazos ? $partido->pelotazos . ' pel.' : 'N/D' ); ?></td>
            <td rowspan="2" class="duracion"><?php echo ( $partido->duracion ? $partido->duracion . ' min.' : 'N/D' ); ?></td>
          </tr>
          <tr>
            <td class="blue puntos">{{ $partido->puntos_azul }}</td>
            <td class="blue">
              @foreach( $partido->pelotaris_azul as $pelotari )
                @if( $loop->iteration > 1 )
                 &nbsp;-&nbsp;{{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
                @else
                 {{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
                @endif
              @endforeach
            </td>
          </tr>
        @endforeach
      </table>
    @endforeach
  </body>
</html>
