<?php
  //array to hold sorted users list by id
  $users_arr = [];
  $left_count = 0;
  $right_count = 0;
  //variable to keep track of the sistems steps (logical flow when building tree)
  $step = 0;
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
  
  

  //========================== TREE CONSTRUCTOR FUNCTIONS ==========================
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



  //========================== SIDES CONSTRUCTOR FUNCTIONS ==========================
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

  //========================== LISTADO CONSTRUCTOR FUNCTIONS ==========================
  function makeListadoRows($users_arr){
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
  }

?>

<script type="text/javascript">
  var cur_user = {{ $cur_user->id }};
  var cur_mults = [
    <?php 
      $cur_mults_count = count($mults_data);
      $counter = 1;
      foreach($mults_data as $user_obj){
        echo '{
          "user_id" : '.$user_obj->user_id.',
          "multiples" : ['.implode(',',$user_obj->multiples).']
        }';
        if($counter < $cur_mults_count){
          echo ',';
        }
      }
    ?>
  ];

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

<div id="office-panel-red" class="hidden">
  <!-- Nav tabs -->
  <ul id="red-nav-tabs" class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a class="red-nav-tab" href="#arbol" aria-controls="arbol" role="tab" data-toggle="tab">Árbol</a></li>
    <li role="presentation"><a class="red-nav-tab" href="#lados" aria-controls="lados" role="tab" data-toggle="tab">Lados</a></li>
    <li role="presentation"><a class="red-nav-tab" href="#listado" aria-controls="listado" role="tab" data-toggle="tab">Listado</a></li>
  </ul>
  <!-- /Nav tabs -->


  <!-- TAB PANELS -->
  <div class="tab-content">
    <!-- Zoom Controls -->
    <div id="red-map-controls" class="text-right hidden">
      <button id="map-zoomout" class="btn btn-sm btn-warning"> - </button>
      <button id="map-scrollbutton" class="btn btn-sm btn-primary">Centrar</button>
      <button id="map-zoomin" class="btn btn-sm btn-warning"> + </button>
    </div>
    <!-- /Zoom Controls -->

    <!-- TAB PANEL TREE VIEW (ARBOL)-->
    <div id="arbol" class="tab-pane active" role="tabpanel">
      <div id="red-map-wrap" class="row text-center red-map-wrap">
        <article id="main-red-box" class="red-box">
          <label class="label label-warning red-socio">{{ $cur_user->user }}</label>
          <?php makeUserSection($cur_user->id,$tree,0);?>
        </article>
      </div>
    </div>
    <!-- /TAB PANEL TREE VIEW-->
    
    <!-- TAB PANEL SIDES (LADOS) -->
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
    </div>
    <!-- /TAB PANEL SIDES (LADOS) -->


    <!-- TAB PANEL ORDERED LIST (LISTADO) -->
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
                  makeListadoRows($users_arr);
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- /TAB PANEL ORDERED LIST (LISTADO) -->

    <!-- COUNTS SUMMARY -->
    <div class="side-counts">
        <?php 
          global $left_count;
          global $right_count;
          $total_count = $left_count+$right_count;
          $patr_count = count($comsPatr);
          echo '<h4>
                  <label class="label label-success">Izquierdo: '.$left_count.'</label> | 
                  <label class="label label-primary">Derecho: '.$right_count.'</label> | 
                  <label class="label label-default">Total: '.$total_count.'</label> |
                  <label class="label label-info">Patrocinios: '.$patr_count.'</label> 
                </h4>';
                // the multiples total count is appended to the h4 by javascript
        ?>
    </div>
    <!-- /COUNTS SUMMARY -->
  </div>
</div>