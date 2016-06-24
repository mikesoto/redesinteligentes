@extends('layouts.back')
<?php 
  $session_usr = Auth::user();
?>
@section('content')
<div class="container">
  <!-- WELCOME HEADER -->
  @if($session_usr->id == 1)
  <div class="row header-welcome-backoffice">
    <div class="col-sm-8 text-center">
      <h3>Back Office - Redes Inteligentes</h3>
    </div>
  @else
  <div class="row header-welcome-oficina-virtual">
    <div class="col-sm-8 text-center">
      <h3>Oficina Virtual - {{ $session_usr->nombre }} {{ $session_usr->apellido_paterno }} {{ $session_usr->apellido_materno }}</h3>
    </div>
  @endif
    <div class="col-sm-4 text-right">
      <strong>Usuario:</strong> {{ $session_usr->user }} &nbsp; | &nbsp;
      <a class="btn btn-danger btn-sm" id="logout-btn" href="/logout">Cerrrar Sessión</a>
    </div>
  </div>
  <!-- /WELCOME HEADER -->
</div>

<div class="row"> 
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
    <!-- FILTERS PANEL (ADMIN ONLY) -->
    @if ($session_usr->id == 1)
      <?php $filt_color = (isset($_GET['u']) || isset($_GET['p']))? 'danger' : 'default'; ?>
      <div id="filters-container" class="col-sm-12">
        <div id="panel-de-control" class="panel panel-{{$filt_color}}">
          <div class="panel-heading">
            <?php 
              if(isset($_GET['u'])){
                echo '<h3 class="panel-title text-center">Filtrado por usuario '.$cur_user->id.' - '.$cur_user->nombre.' '.$cur_user->apellido_paterno.' '.$cur_user->apellido_materno.'</h3>';
              }else{
                echo '<h3 class="panel-title">Filtrar por usuario y/o Periodo</h3>';
              }
            ?>
            
          </div>
          <div class="panel-body">
            <form id="filters-form" method="GET" action="/oficina-virtual">
              
              <div class="col-sm-2 text-right">
                <label for="filterusr">ID de usuario: </label>
              </div>
              <div class="col-sm-2">
                <input type="text" class="form-control" id="filterusr" name="u" size="4" value="{{(isset($_GET['u']) || isset($_GET['p']))? $cur_user->id : ''}}">
              </div>

              <div class="col-sm-1 text-right">
                <label for="filterperiod">Periodo: </label>
              </div>
              <div class="col-sm-3">
                <select class="form-control" id="filterperiod" name="p">
                  <option value="todos">Todos</option>
                  <?php
                    $periodo = 1;
                    foreach($weeks_info as $week){
                      $sel = (isset($_GET["p"]) && $_GET["p"] == $periodo)? "selected" : "";
                      echo '<option value="'.$periodo.'" '.$sel.'>'.$periodo.' <strong>('.date_create($week['week_lunes'])->format("d/m").' - '.date_create($week['week_domingo'])->format("d/m").')</strong></option>';
                      $periodo++;
                    }
                  ?>
                </select>
              </div>

              <div class="col-sm-2 text-right">
                <button type="submit" class="btn btn-primary">Filtrar Vista</button>
              </div>
              <div class="col-sm-2">
                <a href="/oficina-virtual" class="btn btn-default">Quitar Filtros</a>
              </div>
            </form>
          </div>
        </div>
      </div>   
    @endif
    <!-- /FILTERS PANEL -->

    <!-- CONTROL PANEL BUTTONS -->
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
          @if ($session_usr->id == 1)
            <button class="btn btn-warning" id="register-user-btn" data-toggle="modal" data-target="#register-user-modal">Registrar Socio</button>
          @endif 
          <button class="btn btn-primary cpanel-link" id="cpanel-downloads" data-panelName="downloads">Descargas</button>
        </div>
      </div>
    </div>   
    <!-- /CONTROL PANEL BUTTONS -->

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
@if($session_usr->id == 1)
  @include('back.oficina_modals.register_user_modal')
@endif
@endsection