<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" >

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/asegarce.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @guest
          <!-- <p>Debes identificarte para poder acceder a la aplicación</p> -->
        @else
          <nav class="navbar navbar-expand-md navbar-light navbar-laravel p-sm-0">
              <div class="container">
                  <a class="navbar-brand" href="{{ url('/') }}">
                      <!-- {{ config('app.name', 'Laravel') }} -->
                      <img src="/storage/logo-enpresa.png" height="45px" style="padding:5px 0"/>
                  </a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                      <span class="navbar-toggler-icon"></span>
                  </button>

                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                      <!-- Left Side Of Navbar -->
                      <ul class="navbar-nav mr-auto">

                      </ul>

                      <!-- Right Side Of Navbar -->
                      <ul class="navbar-nav ml-auto">
                          <!-- Authentication Links -->
                          @guest
                            <!-- Nothing -->
                          @else
                              <li class="nav-item dropdown">
                                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                      <img src="/storage/{{ Auth::user()->avatar }}" height="30px" alt="Fotografía de {{ Auth::user()->name }}" title="{{ Auth::user()->name }}" class="rounded-circle"/><span class="caret"></span>
                                  </a>

                                  <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                      <div class="dropdown-item d-flex align-items-center">
                                        <img src="/storage/{{ Auth::user()->avatar }}" height="60px" alt="Fotografía de {{ Auth::user()->name }}" title="{{ Auth::user()->name }}" class="profile-img d-inline-block rounded-circle" />
                                        <div class="profile-body text-secondary d-inline-block ml-2">
                                          <h5 class="font-weight-bold mb-1"><small>{{ Auth::user()->name }}</small></h5>
                                          <h6 class="m-0"><small>{{ Auth::user()->email }}</small></h6>
                                        </div>
                                      </div>
                                      <div class="dropdown-divider mt-3 mb-3"></div>
                                      <a class="dropdown-item mt-1" href="/user">
                                          <i class="voyager-person"></i> {{ __('Mi perfil') }}
                                      </a>
                                      <a class="dropdown-item mt-1" href="/">
                                          <i class="voyager-home"></i> {{ __('Inicio') }}
                                      </a>
                                      @if (( $role == 'rrhh' ) or ( $role == 'admin'))
                                        <a class="dropdown-item mt-1" href="/admin">
                                            <i class="voyager-settings"></i> {{ __('Admin') }}
                                        </a>
                                      @endif
                                      <div class="dropdown-divider mt-3 mb-3"></div>
                                      <a class="dropdown-item" href="{{ route('logout') }}"
                                         onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                          <button class="btn btn-danger btn-block"><i class="voyager-power"></i> {{ __('Cerrar sesión') }}</button>
                                      </a>

                                      <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                          @csrf
                                      </form>
                                  </div>
                              </li>
                          @endguest
                      </ul>
                  </div>
              </div>
          </nav>
        @endguest
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
