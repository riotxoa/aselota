@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        @switch($role)
            @case ('admin')
              <home-component user-role="admin"></home-component>
              @break
            @case ('gerente')
              <home-component user-role="gerente"></home-component>
              @break
            @case ('rrhh')
              <home-component user-role="rrhh"></home-component>
              @break
            @case ('entrenador')
              <home-component user-role="entrenador"></home-component>
              @break
            @case ('intendente')
              <home-component user-role="intendente"></home-component>
              @break
            @case ('prensa')
              <home-component user-role="prensa"></home-component>
              @break
            @case ('medico')
              <home-component user-role="medico"></home-component>
              @break
            @case ('plen_gestor')
              <home-component user-role="plen_gestor"></home-component>
              @break
            @case ('plen_entrenador')
              <home-component user-role="plen_entrenador"></home-component>
              @break;
            @default
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header">Dashboard DEFAULT</div>
                      <div class="card-body">
                          @if (session('status'))
                              <div class="alert alert-success" role="alert">
                                  {{ session('status') }}
                              </div>
                          @endif

                          You are logged in! Your role is {{ $role }} !!
                      </div>
                  </div>
              </div>
              @break
        @endswitch
    </div>
</div>
@endsection
