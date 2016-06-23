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
  <div class="row">
    <div class="col-sm-12 text-center">
      <!-- Display Validation Errors -->
      @include('common.errors')
      <!-- Display Messages -->
      @include('common.messages')
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
          //array to hold sorted users list by id
          $users_arr = [];
          //populate the users array
          foreach($tree as $lvl_group){
            foreach($lvl_group as $soc){
              array_push($users_arr,$soc);
            }
          }  

          //function to compare the ids 
          function compare($a, $b){
            if($a->id < $b->id){
              return -1;
            }
            if($a->id > $b->id){
              return 1;
            }
          }
          //sort the users_arr contents using sort function
          usort($users_arr, "compare");   
     

          //variable to keep track of the sistems steps (logical flow when building tree)
          $step = 0;

          function makeRedLabel($arr,$lv_usr,$side){
            $assigned = false;
            global $step;
            //only execute if the upline (lvl_usr) is not 0
            if($lv_usr){
              //loop through the tree level given
              foreach($arr as $socio){
                //find the child of side given
                if($socio->upline == $lv_usr && $socio->side == $side){
                  $step++;
                  //determine class color
                  $color = ($side == 'left')? 'success' : 'primary';
                  //output the label
                  echo '<label id="label-'.$socio->id.'" class="label label-'.$color.'" data-step="'.$step.'">'.$socio->user.'</label>';
                  //set assigned true
                  $assigned = true;
                  //return child's id
                  return $socio->id;
                }
              }
            }
            if(!$assigned){
              echo '';
              return 0;
            }
          }


          function makeUserSection($usr,$tree,$lvl){
              if( isset($tree[$lvl]) ){
                if($usr){
                  echo '<section class="downlines-container" id="downlines-'.$usr.'">
                          <article class="red-box">';
                            $usr2 = makeRedLabel($tree[$lvl],$usr,'left');
                            makeUserSection($usr2,$tree,$lvl+1);
                  echo '  </article>
                          <article class="red-box">';
                            $usr3 = makeRedLabel($tree[$lvl],$usr,'right');
                            makeUserSection($usr3,$tree,$lvl+1);
                  echo '  </article>
                        </section>';
                }
              }
          }
        ?>


        <!-- Tab panes -->
        <div class="tab-content">
          <div id="red-map-controls" class="text-right hidden">
            <button id="map-zoomout" class="btn btn-sm btn-warning"> - </button>
            <button id="map-scrollbutton" class="btn btn-sm btn-primary">Centrar</button>
            <button id="map-zoomin" class="btn btn-sm btn-warning"> + </button>
          </div>

          <!-- =============================== tabpane arbol ========================= -->
          <div id="arbol" class="tab-pane active" role="tabpanel">
            <div id="red-map-wrap" class="row text-center red-map-wrap">
              <article id="main-red-box" class="red-box">
                <label class="label label-warning red-socio">{{ $cur_user->user }}</label>
                <?php makeUserSection($cur_user->id,$tree,0);?>
              </article>
            </div>
          </div><!-- /tabpane arbol-->
          
          <!-- =============================== tabpane lados ========================= -->
          <?php 
            $left_count = 0;
            $right_count = 0;
            function makeLadoRow($tree,$level,$to_count,$upline,$side){
              $found = false;
              //check if tree level exists
              if( isset($tree[$level]) ){
                //search through all socios in this level
                foreach( $tree[$level] as $socio){
                  //look for the specified side and parent socio
                  if($socio->side == $side && $socio->upline == $upline){
                    //create row for the found user
                    echo '<tr id="lado-row-'.$socio->id.'" class="red-item red-user-'.$side.'">
                            <td>';
                              //indicate the level with - indents 
                              for($i = 0; $i < $level; $i++){
                                echo '- ';
                              }
                              //echo the users info
                              echo ' '.$socio->nombre .' '. $socio->apellido_paterno .' ('. $socio->user .')
                            </td>
                          </tr>';
                    //set found to true
                    $found = true;
                    //increment the count for this side
                    if($to_count == 'L'){
                      global $left_count;
                      $left_count++;
                    }else{
                      global $right_count;
                      $right_count++;
                    }
                    //continue down to the next level
                    makeLadoRow($tree,$level+1,$to_count,$socio->id,'left');
                    makeLadoRow($tree,$level+1,$to_count,$socio->id,'right');
                    //return $socio->id;
                  }
                }
                //socio not found on this level end function
                if(!$found){
                  return false;
                }
              }
            }
          ?>


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
                      <?php 
                        //call the root left user for the left table
                        makeLadoRow($tree,0,'L',$cur_user->id,'left');
                      ?>
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
                      <?php 
                        //call the root right user for the right table
                        makeLadoRow($tree,0,'R',$cur_user->id,'right');
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div><!-- /tabpane lados-->

          <div role="tabpanel" class="tab-pane" id="listado">
            <div class="panel panel-success">
              <div class="panel-body">
                <div class="col-sm-12">
                  <table id="listado-table" class="table table-hover">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Fecha Ingreso</th>
                        <th>Lado</th>
                        <th>Apellido P</th>
                        <th>Apellido M</th>
                        <th>Patrocinador</th>
                        <th>Upline</th>
                        <th>Asignado</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        //display the list
                        foreach($users_arr as $soc){
                            echo '<tr id="listado-row-'.$soc->id.'" class="listado-row">
                                    <td>'.$soc->id.'</td>
                                    <td>'.$soc->user.'</td>
                                    <td>'.$soc->nombre.'</td>
                                    <td>'.$soc->fecha_ingreso.'</td>';
                                    $color = ($soc->side == 'left')? 'success' : 'primary';
                                    $side_txt = ($soc->side == 'left')? 'izquierdo' : 'derecho';
                            echo   '<td><label class="label label-'.$color.'">'.$side_txt.'</label></td>
                                    <td>'.$soc->apellido_paterno.'</td>
                                    <td>'.$soc->apellido_materno.'</td>
                                    <td>'.$soc->patrocinador.'</td>
                                    <td>'.$soc->upline.'</td>
                                    <td>'.$soc->asignado.'</td>
                                  </tr>';
                        }
                         
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div><!-- /tabpane listado-->

          <div class="side-counts">
              <?php 
                global $left_count;
                global $right_count;
                $total_count = $left_count+$right_count;
                echo '<h4>
                        <label class="label label-success">Izquierdo: '.$left_count.'</label> | 
                        <label class="label label-primary">Derecho: '.$right_count.'</label> | 
                        <label class="label label-default">Total: '.$total_count.'</label> 
                      </h4>';
              ?>
          </div>
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

      <div id="office-panel-downloads" class="panel panel-success hidden">
        <div class="panel-heading">
          <h3 class="panel-title">Descargas</h3>
        </div>
        <div class="panel-body">

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-1.jpg)">
            <a href="/descargas/Plan-De-Negocio-1.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.1</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-2.jpg)">
            <a href="/descargas/Plan-De-Negocio-2.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.2</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-3.jpg)">
            <a href="/descargas/Plan-De-Negocio-3.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.3</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-4.jpg)">
            <a href="/descargas/Plan-De-Negocio-4.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.4</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-5.jpg)">
            <a href="/descargas/Plan-De-Negocio-5.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.5</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-6.jpg)">
            <a href="/descargas/Plan-De-Negocio-6.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.6</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-7.jpg)">
            <a href="/descargas/Plan-De-Negocio-7.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.7</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-8.jpg)">
            <a href="/descargas/Plan-De-Negocio-8.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.8</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-9.jpg)">
            <a href="/descargas/Plan-De-Negocio-9.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.9</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-10.jpg)">
            <a href="/descargas/Plan-De-Negocio-10.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.10</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-11.jpg)">
            <a href="/descargas/Plan-De-Negocio-11.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.11</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Plan-De-Negocio-12.jpg)">
            <a href="/descargas/Plan-De-Negocio-12.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Plan de Negocio p.12</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/NutriAccionTriptico.jpg)">
            <a href="/descargas/NutriAccionTriptico.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">NutriAccion Triptico</div>
              </div>
            </a>
          </div>

          <div class="downloads-link" style="background-image:url(/descargas/Redes-Inteligentes-Formato-de-Inscripcion.jpg)">
            <a href="/descargas/Redes-Inteligentes-Formato-de-Inscripcion.jpg" target="_blank">
              <div class="download-title">
                <div class="title-text">Formato de Inscripcion</div>
              </div>
            </a>
          </div>
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

<script type="text/javascript">
  //array of all users sorted by id
  var users_sorted = [
  <?php 
    $arr_length = count($users_arr);
    for($i=0; $i < $arr_length; $i++){
      echo '{
              "id" : '.$users_arr[$i]->id.',
              "user" : "'.$users_arr[$i]->user.'",
              "nombre" : "'.$users_arr[$i]->nombre.'",
              "fecha_ingreso" : "'.$users_arr[$i]->fecha_ingreso.'",
              "apellido_paterno" : "'.$users_arr[$i]->apellido_paterno.'",
              "apellido_materno" : "'.$users_arr[$i]->apellido_materno.'",
              "patrocinador" : '.$users_arr[$i]->patrocinador.',
              "upline" : '.$users_arr[$i]->upline.',
              "asignado" : '.$users_arr[$i]->asignado.'
            }';
      if($i < $arr_length){
        echo ',';
      }
    }
  ?>
  ];
</script>
@include('back.oficina_modals.register_user_modal')
@endsection