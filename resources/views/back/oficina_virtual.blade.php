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
                  echo '<label class="label label-primary">'.$socio->user.' ('.$socio->upline.') - '.$socio->side.'</label>';
                  $assigned = true;
                  return $socio->id;
                }
              }
            }
            if(!$assigned){
              echo '<label class="label label-default">...</label>';
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
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds24</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds44</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds45</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds25</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds46</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds47</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr16 = makeRedLabel($tree[3],$usr7,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds26</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds48</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds49</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds27</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds50</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds51</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr8 = makeRedLabel($tree[2],$usr3,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr17 = makeRedLabel($tree[3],$usr8,'left');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds28</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds52</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds53</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr18 = makeRedLabel($tree[3],$usr8,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds29</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds54</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds55</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds30</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds56</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds57</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
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
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds31</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds58</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds59</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds60</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds61</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr20 = makeRedLabel($tree[3],$usr9,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr10 = makeRedLabel($tree[2],$usr4,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr21 = makeRedLabel($tree[3],$usr10,'left');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds32</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds62</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds63</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds33</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds64</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds65</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr22 = makeRedLabel($tree[3],$usr10,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds34</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds66</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds67</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds35</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds68</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds69</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                            </section>
                          </article>
                        </section>
                      </article>
                    </section>
                  </article>
                  
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
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds36</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds70</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds71</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds37</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds72</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds73</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr24 = makeRedLabel($tree[3],$usr11,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds38</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds74</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds75</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds39</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds76</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds77</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr12 = makeRedLabel($tree[2],$usr5,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr25 = makeRedLabel($tree[3],$usr12,'left');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds40</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds78</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds79</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds41</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds80</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds81</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr26 = makeRedLabel($tree[3],$usr12,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-primary">fds42</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds82</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds83</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-primary">fds43</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-primary">fds84</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-primary">fds85</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
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
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr28 = makeRedLabel($tree[3],$usr13,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                            </section>
                          </article>
                          <article class="red-box">
                            <?php $usr14 = makeRedLabel($tree[2],$usr6,'right');?>
                            <section class="downlines-container">
                              <article class="red-box">
                                <?php $usr29 = makeRedLabel($tree[3],$usr14,'left');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
                              </article>
                              <article class="red-box">
                                <?php $usr30 = makeRedLabel($tree[3],$usr14,'right');?>
                                <!-- <section class="downlines-container">
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                  <article class="red-box">
                                    <label class="label label-default">...</label>
                                    <section class="downlines-container">
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                      <article class="red-box">
                                        <label class="label label-default">...</label>
                                      </article>
                                    </section>
                                  </article>
                                </section> -->
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