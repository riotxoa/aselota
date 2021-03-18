<table>
  <thead>
    <tr>
      <th><strong>Fecha</strong></th>
      <th><strong>Hora</strong></th>
      <th><strong>Frontón</strong></th>
      <th><strong>Tipo partido</strong></th>
      <th><strong>Campeonato</strong></th>
      <th><strong>Equipo Rojo</strong></th>
      <th><strong>Equipo Azul</strong></th>
      <th><strong>Tantos Rojo</strong></th>
      <th><strong>Tantos Azul</strong></th>
      <th><strong>Pelotazos</strong></th>
      <th><strong>Duración</strong></th>
    </tr>
  </thead>
  <tbody>
    @foreach( $festivales as $festival )
      @foreach( $festival->partidos as $partido )
        <tr>
          <td><?php $fecha = new DateTime($festival->fecha); echo $fecha->format('d-m-Y'); ?></td>
          <td>{{ $festival->hora }}</td>
          <td>{{ $festival->fronton_name }}&nbsp;({{ $festival->fronton_localidad }})</td>
          <td>{{ $partido->tipo_partido }}</td>
          <td>{{ $partido->campeonato }}</td>
          <td>
            @foreach( $partido->pelotaris_rojo as $pelotari )
              @if( $loop->iteration > 1 )
               &nbsp;-&nbsp;{{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
              @else
               {{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
              @endif
            @endforeach
          </td>
          <td>
            @foreach( $partido->pelotaris_azul as $pelotari )
              @if( $loop->iteration > 1 )
               &nbsp;-&nbsp;{{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
              @else
               {{ $pelotari->alias }}<?php echo ( $pelotari->is_sustituto ? '<sup><small>*SUST.</small></sup>' : '' ); ?>
              @endif
            @endforeach
          </td>
          <td>{{ $partido->puntos_rojo }}</td>
          <td>{{ $partido->puntos_azul }}</td>
          <td><?php echo ( $partido->pelotazos ? $partido->pelotazos . ' pel.' : 'N/D' ); ?></td>
          <td><?php echo ( $partido->duracion ? $partido->duracion . ' min.' : 'N/D' ); ?></td>
        </tr>
      @endforeach
    @endforeach
  </tbody>
</table>
