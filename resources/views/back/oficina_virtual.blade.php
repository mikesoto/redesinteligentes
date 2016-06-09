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
          function makeRedLabel($arr,$lv_usr,$side){
            $assigned = false;
            if($lv_usr){
              foreach($arr as $socio){
                if($socio->upline == $lv_usr && $socio->side == $side){
                  echo '<label class="label label-primary">'.$socio->user.'</label>';
                  $assigned = true;
                  return $socio->id;
                }
              }
            }
            if(!$assigned){
              echo '<label class="label label-default">--</label>';
              return 0;
            }
          }
        ?>


        <!-- Tab panes -->
        <div class="tab-content">
          <div id="red-map-controls" class="hidden">
            <button id="map-zoomout" class="btn btn-sm btn-warning"> - </button>
            <button id="map-scrollbutton" class="btn btn-sm btn-primary">>|<</button>
            <button id="map-zoomin" class="btn btn-sm btn-warning"> + </button>
          </div>
          <div id="arbol" class="tab-pane active" role="tabpanel">
            <div id="red-map-wrap" class="row text-center red-map-wrap">
              <article id="main-red-box" class="red-box">
                <label class="label label-warning red-socio">{{ $cur_user->user }}</label>
                <section class="downlines-container">
                  <!-- current user left -->
                  <article class="red-box">
                    <?php $usr1 = makeRedLabel($tree[0],$cur_user->id,'left');?>
                    <section class="downlines-container">
                      <article class="red-box">
                        <?php $usr3 = makeRedLabel($tree[1],$usr1,'left');?>
                        <section class="downlines-container">
                          <article class="red-box">
                            <?php $usr7 = makeRedLabel($tree[2],$usr3,'left');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr15 = makeRedLabel($tree[3],$usr7,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr31 = makeRedLabel($tree[4],$usr15,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr63 = makeRedLabel($tree[5],$usr31,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr64 = makeRedLabel($tree[5],$usr31,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr32 = makeRedLabel($tree[4],$usr15,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr65 = makeRedLabel($tree[5],$usr32,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr66 = makeRedLabel($tree[5],$usr32,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr16 = makeRedLabel($tree[3],$usr7,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr33 = makeRedLabel($tree[4],$usr16,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr67 = makeRedLabel($tree[5],$usr33,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr68 = makeRedLabel($tree[5],$usr33,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr34 = makeRedLabel($tree[4],$usr16,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr69 = makeRedLabel($tree[5],$usr34,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr70 = makeRedLabel($tree[5],$usr34,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr8 = makeRedLabel($tree[2],$usr3,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr17 = makeRedLabel($tree[3],$usr8,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr35 = makeRedLabel($tree[4],$usr17,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr71 = makeRedLabel($tree[5],$usr35,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr72 = makeRedLabel($tree[5],$usr35,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr36 = makeRedLabel($tree[4],$usr17,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr73 = makeRedLabel($tree[5],$usr36,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr74 = makeRedLabel($tree[5],$usr36,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr18 = makeRedLabel($tree[3],$usr8,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr37 = makeRedLabel($tree[4],$usr18,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr75 = makeRedLabel($tree[5],$usr37,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr76 = makeRedLabel($tree[5],$usr37,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr38 = makeRedLabel($tree[4],$usr18,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr77 = makeRedLabel($tree[5],$usr38,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr78 = makeRedLabel($tree[5],$usr38,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                        </section>
                      </article>
                      <article class="red-box">
                        <?php $usr4 = makeRedLabel($tree[1],$usr1,'right');?>
                        <section class="downlines-container">
                          <article class="red-box">
                            <?php $usr9 = makeRedLabel($tree[2],$usr4,'left');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr19 = makeRedLabel($tree[3],$usr9,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr39 = makeRedLabel($tree[4],$usr19,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr79 = makeRedLabel($tree[5],$usr39,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr80 = makeRedLabel($tree[5],$usr39,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr40 = makeRedLabel($tree[4],$usr19,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr81 = makeRedLabel($tree[5],$usr40,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr82 = makeRedLabel($tree[5],$usr40,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr20 = makeRedLabel($tree[3],$usr9,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr41 = makeRedLabel($tree[4],$usr20,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr83 = makeRedLabel($tree[5],$usr41,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr84 = makeRedLabel($tree[5],$usr41,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr42 = makeRedLabel($tree[4],$usr20,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr85 = makeRedLabel($tree[5],$usr42,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr86 = makeRedLabel($tree[5],$usr42,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr10 = makeRedLabel($tree[2],$usr4,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr21 = makeRedLabel($tree[3],$usr10,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr43 = makeRedLabel($tree[4],$usr21,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr87 = makeRedLabel($tree[5],$usr43,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr88 = makeRedLabel($tree[5],$usr43,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr44 = makeRedLabel($tree[4],$usr21,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr89 = makeRedLabel($tree[5],$usr44,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr90 = makeRedLabel($tree[5],$usr44,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr22 = makeRedLabel($tree[3],$usr10,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr45 = makeRedLabel($tree[4],$usr22,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr91 = makeRedLabel($tree[5],$usr45,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr92 = makeRedLabel($tree[5],$usr45,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr46 = makeRedLabel($tree[4],$usr22,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr93 = makeRedLabel($tree[5],$usr46,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr94 = makeRedLabel($tree[5],$usr46,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                        </section>
                      </article>
                    </section>
                  </article>
                  <!-- current user right -->
                  <article class="red-box">
                    <?php $usr2 = makeRedLabel($tree[0],$cur_user->id,'right');?>
                    <section class="downlines-container">
                      <article class="red-box">
                        <?php $usr5 = makeRedLabel($tree[1],$usr2,'left');?>
                        <section class="downlines-container">
                          <article class="red-box">
                            <?php $usr11 = makeRedLabel($tree[2],$usr5,'left');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr23 = makeRedLabel($tree[3],$usr11,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr47 = makeRedLabel($tree[4],$usr23,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr93 = makeRedLabel($tree[5],$usr47,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr95 = makeRedLabel($tree[5],$usr47,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr48 = makeRedLabel($tree[4],$usr23,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr96 = makeRedLabel($tree[5],$usr48,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr97 = makeRedLabel($tree[5],$usr48,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr24 = makeRedLabel($tree[3],$usr11,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr49 = makeRedLabel($tree[4],$usr24,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr98 = makeRedLabel($tree[5],$usr49,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr99 = makeRedLabel($tree[5],$usr49,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr50 = makeRedLabel($tree[4],$usr24,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr100 = makeRedLabel($tree[5],$usr50,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr101 = makeRedLabel($tree[5],$usr50,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr12 = makeRedLabel($tree[2],$usr5,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr25 = makeRedLabel($tree[3],$usr12,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr51 = makeRedLabel($tree[4],$usr25,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr102 = makeRedLabel($tree[5],$usr51,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr103 = makeRedLabel($tree[5],$usr51,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr52 = makeRedLabel($tree[4],$usr25,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr104 = makeRedLabel($tree[5],$usr52,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr105 = makeRedLabel($tree[5],$usr52,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr26 = makeRedLabel($tree[3],$usr12,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr53 = makeRedLabel($tree[4],$usr26,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr106 = makeRedLabel($tree[5],$usr53,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr107 = makeRedLabel($tree[5],$usr53,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr54 = makeRedLabel($tree[4],$usr26,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr108 = makeRedLabel($tree[5],$usr54,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr109 = makeRedLabel($tree[5],$usr54,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                        </section>
                      </article>
                      <article class="red-box">
                        <?php $usr6 = makeRedLabel($tree[1],$usr2,'right');?>
                        <section class="downlines-container">
                          <article class="red-box">
                            <?php $usr13 = makeRedLabel($tree[2],$usr6,'left');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr27 = makeRedLabel($tree[3],$usr13,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr55 = makeRedLabel($tree[4],$usr27,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr110 = makeRedLabel($tree[5],$usr55,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr111 = makeRedLabel($tree[5],$usr55,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr56 = makeRedLabel($tree[4],$usr27,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr112 = makeRedLabel($tree[5],$usr56,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr113 = makeRedLabel($tree[5],$usr56,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr28 = makeRedLabel($tree[3],$usr13,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr57 = makeRedLabel($tree[4],$usr28,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr114 = makeRedLabel($tree[5],$usr57,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr115 = makeRedLabel($tree[5],$usr57,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr58 = makeRedLabel($tree[4],$usr28,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr116 = makeRedLabel($tree[5],$usr58,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr117 = makeRedLabel($tree[5],$usr58,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr14 = makeRedLabel($tree[2],$usr6,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr29 = makeRedLabel($tree[3],$usr14,'left');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr59 = makeRedLabel($tree[4],$usr29,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr118 = makeRedLabel($tree[5],$usr59,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr119 = makeRedLabel($tree[5],$usr59,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr60 = makeRedLabel($tree[4],$usr29,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr120 = makeRedLabel($tree[5],$usr60,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr121 = makeRedLabel($tree[5],$usr60,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                              <article class="red-box">
                                <?php $usr30 = makeRedLabel($tree[3],$usr14,'right');?>
                                <section class="downlines-container">
                                  <article class="red-box">
                                    <?php $usr61 = makeRedLabel($tree[4],$usr30,'left');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr122 = makeRedLabel($tree[5],$usr61,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr123 = makeRedLabel($tree[5],$usr61,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <?php $usr62 = makeRedLabel($tree[4],$usr30,'right');?>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <?php $usr124 = makeRedLabel($tree[5],$usr62,'left');?>
                                      </article>
                                      <article class="red-box">
                                        <?php $usr125 = makeRedLabel($tree[5],$usr62,'right');?>
                                      </article>
                                    </section>
                                  </article>
                                </section>
                              </article>
                            </section>
                          </article>
                        </section>
                      </article>
                    </section>
                  </article>
                </section>
              </article>


            </div>
          </div><!-- /tabpane arbol-->

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