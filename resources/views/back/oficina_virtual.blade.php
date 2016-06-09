@extends('layouts.front')
@section('content')
<div class="row">
  <div class="row">
    <div class="col-sm-12 text-center">
      <!-- Display Validation Errors -->
      @include('common.errors')
      <!-- Display Messages -->
      @include('common.messages')
    </div>
    <?php $cur_user = Auth::user();?>
    <div class="col-sm-12 text-right">
      <strong>Usuario:</strong> {{ $cur_user->user }} &nbsp; | &nbsp;
      <a class="btn btn-danger btn-sm" id="logout-btn" href="/logout">Cerrrar Sessión</a>
    </div>
  </div>
  <hr>
  <div id="office-main-content" class="col-sm-9 office-main-content">
    <div class="col-sm-12">
      <div id="panel-de-control" class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Panel de Control</h3>
        </div>
        <div class="panel-body text-center">
          <button class="btn btn-primary cpanel-link" id="cpanel-dashboard" data-panelName="dashboard">Dashboard</button>
          <button class="btn btn-info cpanel-link" id="cpanel-red" data-panelName="red">Red</button>
          <button class="btn btn-warning cpanel-link" id="cpanel-comissiones" data-panelName="comissiones">Comisiones</button>
          <button class="btn btn-default" id="datos-personales-btn" data-toggle="modal" data-target="#datos-personales-modal">Datos Personales</button>
          @if (Auth::user()->id == 1)
            <button class="btn btn-success" id="register-user-btn" data-toggle="modal" data-target="#register-user-modal">Registrar Socio</button>
          @endif 
          <button class="btn btn-default" id="advances-options-btn" data-toggle="modal" data-target="#advanced-options-modal">Opciones Avanzadas</button>
        </div>
      </div>
    </div>    
    <div class="col-sm-12">
      
      <div id="office-panel-dashboard" class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Dashboard</h3>
        </div>
        <div class="panel-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Downlines</th>
                <th>Patrocinios</th>
                <th>Ganancias</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ count($downlines) }}</td>
                <td>{{ count($comsPatr)}}</td>
                <td>${{ $ganancias }}</td>
                <td>Bronce</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div id="office-panel-red" class="hidden">
        <!-- Nav tabs -->
        <ul id="red-nav-tabs" class="nav nav-tabs" role="tablist">
          <li role="presentation" class="active"><a class="red-nav-tab" href="#arbol" aria-controls="arbol" role="tab" data-toggle="tab">Árbol</a></li>
          <li role="presentation"><a class="red-nav-tab" href="#lados" aria-controls="lados" role="tab" data-toggle="tab">Lados</a></li>
          <li role="presentation"><a class="red-nav-tab" href="#listado" aria-controls="listado" role="tab" data-toggle="tab">Listado</a></li>
        </ul>


        <?php 
          $leftCount = 0;
          $rightCount = 0;
          function showSideCounts(){ 
            global $leftCount;
            global $rightCount;
            echo '<label class="label label-primary">Izquierdo: '.$leftCount. '</label> | <label class="label label-success">Derecho: '.$rightCount.'</label>';
          }

          function makeRedLabel($arr,$lv_usr,$side){
            $assigned = false;
            if($lv_usr){
              foreach($arr as $socio){
                if($socio->upline == $lv_usr && $socio->side == $side){
                  $color = ($side == 'left')? 'primary' : 'success';
                  echo '<label class="label label-'.$color.'">'.$socio->user.'</label>';
                  $assigned = true;
                  return $socio->id;
                }
              }
            }
            if(!$assigned){
              echo '<label class="label label-default"> </label>';
              return 0;
            }
          }


          function makeUserSection($usr,$tree,$lvl){
              if( isset($tree[$lvl]) ){
                global $leftCount;
                global $rightCount;
                echo '<section class="downlines-container">
                        <article class="red-box">';
                          $usr2 = makeRedLabel($tree[$lvl],$usr,'left');
                          if($usr2 != 0){
                            $leftCount++;
                          }
                          makeUserSection($usr2,$tree,$lvl+1);
                echo '  </article>
                        <article class="red-box">';
                          $usr3 = makeRedLabel($tree[$lvl],$usr,'right');
                          if($usr3 != 0){
                            $rightCount++;
                          }
                          makeUserSection($usr3,$tree,$lvl+1);
                echo '  </article>
                      </section>';
              }
          }
        ?>


        <!-- Tab panes -->
        <div class="tab-content">
          <div id="red-map-controls" class="text-right hidden">
            <button id="map-zoomout" class="btn btn-sm btn-warning"> - </button>
            <button id="map-scrollbutton" class="btn btn-sm btn-primary">>|<</button>
            <button id="map-zoomin" class="btn btn-sm btn-warning"> + </button>
          </div>
          <div id="arbol" class="tab-pane active" role="tabpanel">
            <div id="red-map-wrap" class="row text-center red-map-wrap">
              <article id="main-red-box" class="red-box">
                <label class="label label-warning red-socio">{{ $cur_user->user }}</label>
                <?php makeUserSection($cur_user->id,$tree,0);?>
              </article>
            </div>
          </div><!-- /tabpane arbol-->
          <div class="side-counts">
              <?php showSideCounts(); ?>
          </div>
          <div role="tabpanel" class="tab-pane" id="lados">
            <div class="panel panel-info">
              <div class="panel-body">
                <div class="col-sm-6">
                  <table id="red-table-left" class="table table-hover">
                    <thead>
                      <tr>
                        <th>Lado Izquierdo</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach( $downlines as $downline)
                        @if($downline->side == 'left')
                          <tr id="dl-data-{{$downline->id}}" class="red-item red-user-{{$downline->side}}" data-user-id="{{ $downline->id }}" data-upline="{{ $downline->upline }}" data-status="closed" data-downlines="">
                            <td>
                                {{ $downline->nombre }} {{ $downline->apellido_paterno .' '. $downline->apellido_materno}} (ID:{{ $downline->id }})
                                <span id="load-dl-{{$downline->id}}" class="pull-right hidden">...</span>
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
                <div class="col-sm-6">
                  <table id="red-table-right" class="table table-hover">
                    <thead>
                      <tr>
                        <th>Lado Derecho</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach( $downlines as $downline)
                        @if($downline->side == 'right')
                          <tr id="dl-data-{{$downline->id}}" class="red-item red-user-{{$downline->side}}" data-user-id="{{ $downline->id }}" data-upline="{{ $downline->upline }}" data-status="closed" data-downlines="">
                            <td>
                                {{ $downline->nombre }} {{ $downline->apellido_paterno .' '. $downline->apellido_materno}} (ID:{{ $downline->id }})
                                <span id="load-dl-{{$downline->id}}" class="pull-right hidden">...</span>
                            </td>
                          </tr>
                        @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div><!-- /tabpane lados-->

          <div role="tabpanel" class="tab-pane" id="listado">
            Listado
          </div><!-- /tabpane listado-->
        </div>
      </div>




      
      
      <div id="office-panel-comissiones" class="panel panel-warning hidden">
        <div class="panel-heading">
          <h3 class="panel-title">Comissiones</h3>
        </div>
        <div class="panel-body">
          <?php $periodo = 1;?>
          @foreach($weeks_info as $week)
            <div class="col-sm-3 com-periodo-label">
              <h4>Periodo {{ $periodo }}</h4>
            </div>
            <div class="col-sm-9">
              Semana {{$week['week_num']}} de {{$week['year_num']}} (<strong>{{date_create($week['week_lunes'])->format("d/m/Y")}} - {{date_create($week['week_domingo'])->format("d/m/Y")}}</strong>)
            </div>
            <table class="table table-hover" style="border-bottom:2px solid #ccc;margin-bottom:50px;">
                <thead>
                  <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Nuevo Asociado</th>
                    <th>Patrocinador</th>
                    <th>Upline</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach( $comsPatr as $comPatr)
                    @if( $comPatr->created_at >= $week['week_lunes'] && $comPatr->created_at <= $week['week_domingo'])
                      <tr>
                        <td>{{ $comPatr->created_at }}</td>
                        <td>{{ $comPatr->type }}</td>
                        <td>{{ $comPatr->amount }}</td>
                        <td>{{ $comPatr->new_user_id }}</td>
                        <td>{{ $comPatr->patroc_id }}</td>
                        <td>{{ $comPatr->upline_id }}</td>
                      </tr>
                    @endif
                  @endforeach
                </tbody>
              </table>
            <?php $periodo++;?>
          @endforeach
        </div>
      </div>

    </div> 
  </div><!--/.office-main-content-->
  <div class="col-sm-3">
      <div id="notificaciones-panel" class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">
            Notificaciónes
            <button id="close-notif-btn" type="button" class="close pull-right" data-target="#notificaciones-panel" data-dismiss="alert">
              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
            </button>
          </h3>
        </div>
        <div class="panel-body text-left">
          <div class="alert alert-warning" role="alert">
            Este sistema está todavía en desarrollo. Más funciones se añadirán en breve. 
            Si encuentra algún problema, póngase en contacto con el administrador del sistema: 
            <a href="#">mike@redesinteligentes.com.mx</a>
          </div>
        </div>
      </div>
  </div><!-- /.office-sidebar -->
</div>
@include('back.oficina_modals.register_user_modal')
@endsection