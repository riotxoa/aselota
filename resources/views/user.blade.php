@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
      <profile-component user-id="{{ Auth::user()->id }}" user-name="{{ Auth::user()->name }}" user-email="{{ Auth::user()->email }}" user-avatar="/storage/{{ Auth::user()->avatar }}"></profile-component>
    </div>
</div>
@endsection
