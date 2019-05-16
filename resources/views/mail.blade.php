<h1>Revisar importe del festival</h1>
<p>Por favor, revisa el importe de venta sugerido por el organizador del festival lo antes posible.</p>
<ul style="list-style:none;padding: 0px;">
    <li style="margin-left:0px;"><b>Fecha festival:</b> {{ $festival_fecha }}</li>
    <li style="margin-left:0px;"><b>Frontón:</b> {{ $festival_fronton }}</li>
    @if ($organizador=="gugeu")
    <li style="margin-left:0px;"><b>Organizador:</b> Baiko Pilota</li>
    @else
    <li style="margin-left:0px;"><b>Organizador:</b> ASPE</li>
    @endif
    @if ($televisado==1)
        <li style="margin-left:0px;"><b>Televisado:</b> SI</li>
    @else
        <li style="margin-left:0px;"><b>Televisado:</b> NO</li>
    @endif
</ul>
<h2>Partidos</h2>
@foreach($partidos as $partido)
    <h3> {{$partido['orden']}} Partido</h3>
    <ul style="list-style: none;">
        @if ($partido['estelar']==1)
            <li><b>Estelar:</b> SI</li>
        @else
            <li><b>Estelar:</b> NO</li>
        @endif
        @if ($partido['campeonato_id']==null || $partido['campeonato_id']=="")
            <li><b>Campeonato:</b> SIN CAMPEONATO</li>
        @else
            <li><b>Campeonato:</b> {{$partido['campeonato_name']}} ({{$partido['campeonato_id']}})</li>
        @endif
        <li><b>Pelotaris:</b></li>
        <ul style="list-style: none;">
            @if (isset($partido['pelotari_1']))
                @if (isset($partido['pelotari_1']['asegarce']) && $partido['pelotari_1']['asegarce']==1)
                    <li style="color: blue;"><b>Pelotari 1:</b> {{$partido['pelotari_1']['alias']}}</li>
                @else
                    <li style="color: red;"><b>Pelotari 1:</b> {{$partido['pelotari_1']['alias']}}</li>
                @endif
            @endif
            @if (isset($partido['pelotari_2']))
                @if (isset($partido['pelotari_2']['asegarce']) && $partido['pelotari_2']['asegarce']==1)
                    <li style="color: blue;"><b>Pelotari 2:</b> {{$partido['pelotari_2']['alias']}}</li>
                @else
                    <li style="color: red;"><b>Pelotari 2:</b> {{$partido['pelotari_2']['alias']}}</li>
                @endif
            @endif
            @if (isset($partido['pelotari_3']))
                @if (isset($partido['pelotari_3']['asegarce']) && $partido['pelotari_3']['asegarce']==1)
                    <li style="color: blue;"><b>Pelotari 3:</b> {{$partido['pelotari_3']['alias']}}</li>
                @else
                    <li style="color: red;"><b>Pelotari 3:</b> {{$partido['pelotari_3']['alias']}}</li>
                @endif
            @endif
            @if (isset($partido['pelotari_4']))
                @if (isset($partido['pelotari_4']['asegarce']) && $partido['pelotari_4']['asegarce']==1)
                    <li style="color: blue;"><b>Pelotari 4:</b> {{$partido['pelotari_4']['alias']}}</li>
                @else
                    <li style="color: red;"><b>Pelotari 4:</b> {{$partido['pelotari_4']['alias']}}</li>
                @endif
            @endif
        </ul>
    </ul>
@endforeach
<br>
<h2>Costes</h2>
<ul style="list-style:none;">
    <li><b>Coste empresa:</b> {{ $coste_empresa }}€</li>
    <li><b>Importe venta ideal:</b> {{ $importe_ideal }}€</li>
    <li><b>Importe venta propuesto:</b> {{ $importe_sugerido }}€</li>
</ul>