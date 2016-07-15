<div id="office-panel-comissiones" class="panel panel-warning hidden">
  <div class="panel-heading">
    <h3 class="panel-title">Comissiones</h3>
  </div>
  <div class="panel-body">
    <?php 
      //start period
      $periodo = 1;
      $filter_period = false;
      if(isset($_GET['p']) && is_numeric($_GET['p'])){
        $filter_period = $_GET['p'];
      }
    ?>
    @foreach($weeks_info as $week)
      <?php 
        //hide any weeks not in the filter period if requested
        $filtered = ($filter_period > 0 && $periodo != $filter_period)? 'hidden' : ''; 
        //gather all patrocinios for this week
        $week_patrocinios = [];
        foreach( $comsPatr as $patr ){
          if( $patr->created_at >= $week['week_lunes'] && $patr->created_at <= $week['week_domingo']){
            array_push($week_patrocinios, $patr);
          }
        }
        //gather all patrocinios for this week
        $week_multiples = [];
        foreach( $comsMult as $mlt ){
          if( $mlt->created_at >= $week['week_lunes'] && $mlt->created_at <= $week['week_domingo']){
            array_push($week_multiples, $mlt);
          }
        }
      ?>
      @if(count($week_patrocinios))
        <div class="col-sm-3 com-periodo-label {{ $filtered }}">
          <h4>Periodo {{ $periodo }}</h4>
        </div>
        <div class="col-sm-4 {{ $filtered }}">
          Semana {{$week['week_num']}} de {{$week['year_num']}} (<strong>{{date_create($week['week_lunes'])->format("d/m/Y")}} - {{date_create($week['week_domingo'])->format("d/m/Y")}}</strong>)
        </div>
        <div class="col-sm-5">
          <button type="button" class="btn btn-info btn-xs show-period-btn" data-toggle="collapse" data-target="#week-{{$week['week_num']}}">Ver Tabla de Periodo</button>
        </div>
        <br>
        <br>
        <div id="week-{{$week['week_num']}}" class="collapse">
          <table class="table table-hover {{ $filtered }}" style="border-bottom:2px solid #ccc;margin-bottom:50px;">
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
              @foreach( $week_patrocinios as $comPatr)
                <tr>
                  <td>{{ date('d/m/Y', strtotime($comPatr->created_at)) }}</td>
                  <td><label class="label label-primary">{{ $comPatr->type }}</label></td>
                  <td>${{ $comPatr->amount }}</td>
                  <td>{{ $comPatr->new_user_name }} ({{ $comPatr->new_user_id }})</td>
                  <td>{{ $comPatr->pat_user_name }} ({{ $comPatr->patroc_id }})</td>
                  <td>{{ $comPatr->upline_user_name }} ({{ $comPatr->upline_id }})</td>
                </tr>
              @endforeach
              @foreach( $week_multiples as $comMult)
                <?php $color_label = ($comMult->amount == 0.00)? 'label-default' : 'label-danger';?>
                <tr>
                  <td>{{ date('d/m/Y', strtotime($comMult->created_at)) }}</td>
                  <td><label class="label {{$color_label}}">{{ $comMult->type }}</label></td>
                  <td>${{ $comMult->amount }}</td>
                  <td>{{ $comMult->new_user_name }} ({{ $comMult->new_user_id }})</td>
                  <td>{{ $comMult->pat_user_name }} ({{ $comMult->patroc_id }})</td>
                  <td>{{ $comMult->upline_user_name }} ({{ $comMult->upline_id }})</td>
                </tr>
              @endforeach
              </tbody>
          </table>
        </div>
      @endif
      <?php $periodo++;?>
    @endforeach
  </div>
</div>