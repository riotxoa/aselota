@extends('layouts.app')

@section('content')
<div class="container login">
    <div class="logo-wrap row justify-content-center">
      <div class="col-md-8">
        <img class="logo" src="{{ asset('img/logo-enpresa-login.png') }}" alt="Baiko Pilota" title="Baiko Pilota"/>
      </div>
    </div>

    <div class="login-form-wrap row justify-content-center">
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" aria-label="{{ __('Login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-sm-7">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-sm-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                            <div class="col-sm-7">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 text-center">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Recordarme') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="submit btn btn-primary">
                                    {{ __('Entrar') }}
                                </button>
                            </div>
                            <div class="col-sm-12 text-center">
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    <small>{{ __('Recordar contraseña') }}</small>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
