<?php $title_page = 'Oficina Virtual'; $active_page = 'oficina'?>
@extends('layouts.front')
@section('content')
<div class="row text-center">
    <div class="col-sm-4 col-sm-offset-4 text-center">
      @if ( isset($_GET['err']) )
        <div class="alert alert-danger large-margin-top">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong>Usuario o contraseña invalido</strong>
        </div>
      @endif
    </div>
    <div class="col-sm-4 col-sm-offset-4">
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Iniciar Sessión de Oficina Virtual</h3>
        </div>
        <div class="panel-body">
          <form method="POST" action="/auth/login">
            {!! csrf_field() !!}
            <div class="form-group text-left">
              <label for="user">Usuario</label>
              <input type="text" class="form-control" id="user" name="user" value="{{ old('user') }}">
            </div>

            <div class="form-group text-left">
              <label for="password">Contraseña</label>
              <input type="password" class="form-control" id="password" name="password">
            </div>

            <div class="form-group">
              <input type="checkbox" name="remember"> Recuérdame
            </div>

            <div>
              <button type="submit">Iniciar Sessión</button>
            </div>
          </form>
        </div>
      </div>
    </div>        
</div>
@endsection