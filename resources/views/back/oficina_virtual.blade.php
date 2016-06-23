@extends('layouts.back')

<?php $cur_user = Auth::user();?>

@section('content')
<div class="row">
  <!-- WELCOME HEADER -->
  @if($cur_user->id == 1)
  <div class="row header-welcome-backoffice">
    <div class="col-sm-8 text-center">
      <h3>Back Office - Redes Inteligentes</h3>
    </div>
  @else
  <div class="row header-welcome-oficina-virtual">
    <div class="col-sm-8 text-center">
      <h3>Oficina Virtual - {{ $cur_user->nombre }} {{ $cur_user->apellido_paterno }} {{ $cur_user->apellido_materno }}</h3>
    </div>
  @endif
    <div class="col-sm-4 text-right">
      <strong>Usuario:</strong> {{ $cur_user->user }} &nbsp; | &nbsp;
      <a class="btn btn-danger btn-sm" id="logout-btn" href="/logout">Cerrrar Sessión</a>
    </div>
  </div>
  <!-- /WELCOME HEADER -->
  
  <!-- COMMON ERRORS -->
  <div class="row">
    <div class="col-sm-12 text-center">
      <!-- Display Validation Errors -->
      @include('common.errors')
      <!-- Display Messages -->
      @include('common.messages')
    </div>
  </div>
  <!-- /COMMON ERRORS -->

  <hr>

  <!-- OFFICE MAIN CONTENT -->
  <div id="office-main-content" class="col-sm-9 office-main-content">
    <div class="col-sm-12">
      <div id="panel-de-control" class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Panel de Control</h3>
        </div>
        <div class="panel-body text-center">
          <button class="btn btn-primary cpanel-link" id="cpanel-dashboard" data-panelName="dashboard">Dashboard</button>
          <button class="btn btn-primary cpanel-link" id="cpanel-red" data-panelName="red">Red</button>
          <button class="btn btn-primary cpanel-link" id="cpanel-comissiones" data-panelName="comissiones">Comisiones</button>
          <button class="btn btn-default" id="datos-personales-btn" data-toggle="modal" data-target="#datos-personales-modal">Datos Personales</button>
          @if (Auth::user()->id == 1)
            <button class="btn btn-warning" id="register-user-btn" data-toggle="modal" data-target="#register-user-modal">Registrar Socio</button>
          @endif 
          <button class="btn btn-primary cpanel-link" id="cpanel-downloads" data-panelName="downloads">Descargas</button>
        </div>
      </div>
    </div>    
    <div class="col-sm-12">
      @include('back.oficina_panels.dashboard')

      @include('back.oficina_panels.network')

      @include('back.oficina_panels.commissions')
      
      @include('back.oficina_panels.downloads')
    </div> 
  </div>
  <!--/OFFICE MAIN CONTENT-->
  
  <!-- OFFICE SIDEBAR -->
  <div class="col-sm-3">
    @include('back.oficina_panels.notifications')
  </div>
  <!-- /OFFICE SIDEBAR -->
</div>
@if($cur_user->id == 1)
  @include('back.oficina_modals.register_user_modal')
@endif
@endsection