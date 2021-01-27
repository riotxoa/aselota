<style>
  a {
    color:#94c11f;
    text-decoration:none;
  }
  a:visited {
    color:#94c11f;
  }
  h2, h4 {
    line-height:1.2;
    text-align: center;
    text-transform:uppercase;
  }
  h2 {
    margin-block-end:0;
  }
  h4 {
    margin-block-end:2rem;
    margin-block-start:0.5rem;
  }
  p {
    color:black;
  }
  ul {
    line-height:1.5;
    padding-left:3rem;
  }
  .center {
    text-align:center;
  }
  .container {
    background-color: #efefef;
    font-family:arial;
    font-size:14px;
    line-height:1.5;
    padding:4rem 10rem;
  }
  .gray {
    color:gray;
  }
  .green {
    color:#94c11f;
  }
  .logo {
    display:block;
    margin:0 auto;
    margin-bottom: 2.5rem;
  }
  .quote {
    background-color: #fafafa;
    border:1px solid #e0e0e0;
    font-family:courier;
    font-size:12px;
    margin:2rem 3rem;
    padding:1rem 2rem;
    text-align:justify;
  }
  .red {
    color:#f50000;
  }
  .wrapper {
    background-color: #ffffff;
    margin:0 auto;
    max-width:650px;
    padding:3rem 2.5rem 0.5rem 2.5rem;
  }
</style>

<div class="container">
  <div class="wrapper">
    <img src="https://gestion.asegarce.com:8444/img/logo-enpresa-login.png" alt="Baiko Pilota" title="Baiko Pilota" class="logo" width="150px">
    <h2>NOTIFICACIÓN DEL DEPARTAMENTO MÉDICO</h2>
    @if( $disponible == 1 )
      <h4 class="green">Situación médica de {{ $alias }}</h4>
      <p>El Departamento Médico de Baiko Pilota le envía la siguiente actualización de la situación médica del pelotari <strong>{{ $alias }}</strong>:</p>
      <p class="quote">{{ $texto }}</p>
      <!-- <p>El pelotari sigue activo y disponible para su convocatoria en festivales, entrenamientos, etc.</p> -->
    @else
      <h4 class="red">{{ $alias }} NO DISPONIBLE</h4>
      <!-- <p>El Departamento Médico de Baiko Pilota comunica que el pelotari <strong>{{ $alias }}</strong> no estará disponible para su convocatoria en festivales, entrenamientos, etc. en el siguiente rango de fechas:</p> -->
      <p>El Departamento Médico de Baiko Pilota comunica que el pelotari <strong>{{ $alias }}</strong> no estará disponible para su convocatoria durante el siguiente rango de fechas:</p>
      <ul>
        <li>Desde: <strong>{{ $date_from }}</strong></li>
        <li>Hasta: <strong>{{ $date_to }}</strong></li>
      </ul>
      @if( $texto )
        <p>Esta es la información adicional disponible sobre su situación:</p>
        <p class="quote">{{ $texto }}</p>
      @endif
    @endif
    <p>Este mensaje se lo ha enviado el Departamento Médico de Baiko Pilota desde la aplicación <a href="http://gestion.asegarce.com"><strong>Baiko&nbsp;PilotAPP</strong></a>. Para cualquier aclaración o ampliación de información contacte con dicho departamento.</p>
    <br>
    <hr/>
    <p class="center gray"><small><strong><a href="http://gestion.asegarce.com">Baiko&nbsp;PilotAPP</a>&nbsp;&copy;&nbsp;<?php echo date('Y'); ?></strong></small></p>
  </div>
</div>
