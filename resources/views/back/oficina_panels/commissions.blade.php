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
          if( $patr->created_at >= $week['week_domingo'] && $patr->created_at <= $week['week_sabado']){
            array_push($week_patrocinios, $patr);
          }
        }
        //gather all patrocinios for this week
        $week_multiples = [];
        foreach( $comsMult as $mlt ){
          if( $mlt->created_at >= $week['week_domingo'] && $mlt->created_at <= $week['week_sabado']){
            array_push($week_multiples, $mlt);
          }
        }
        //gather all the bono20s for this week
        $week_bono20s = [];
        foreach( $comsBono as $bn ){
          if( $bn->created_at >= $week['week_domingo'] && $bn->created_at <= $week['week_sabado']){
            array_push($week_bono20s, $bn);
          }
        }
      ?>
      @if(count($week_patrocinios))
        <?php 
          $trans_months = array(
            'January' => 'enero',
            'February' => 'febrero',
            'March' => 'marzo',
            'April' => 'abril',
            'May' => 'mayo',
            'June' => 'junio',
            'July' => 'julio',
            'August' => 'agosto',
            'September' => 'septiembre',
            'October' => 'octubre',
            'November' => 'noviembre',
            'December' => 'diciembre' 
          );
        ?>
        <div class="col-sm-12 {{ $filtered }}">
          <button type="button" class="btn btn-lg btn-default com-periodo-label" data-toggle="collapse" data-target="#week-{{$week['week_num']}}">
            Periodo - {{ date_create($week['week_domingo'])->format("d") }} de {{ $trans_months[date_create($week['week_domingo'])->format("F")] }} al {{ date_create($week['week_sabado'])->format("d") }} de {{ $trans_months[date_create($week['week_sabado'])->format("F")] }} {{ date_create($week['week_sabado'])->format("Y") }}
          </button>
        </div>
        <br>
        <br>
        <div id="week-{{$week['week_num']}}" class="collapse">
          <table class="table table-hover {{ $filtered }} comisiones-main-table">
            <tr>
              <td colspan="7">
                <a class="btn btn-primary btn-xs toggle-comision-cat" data-toggle="collapse" data-target="#patrocs_row_{{$week['week_num']}}">Patrocinios <span class="badge pull-right">{{ count($week_patrocinios) }}</span></a>
              </td>
            </tr>
            <tbody id="patrocs_row_{{$week['week_num']}}" class="collapse">
              <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Receptor</th>
                <th>Nuevo Asociado</th>
                <th>Patrocinador</th>
                <th>Upline</th>
              </tr>
              @foreach( $week_patrocinios as $comPatr)
              <tr>
                <td>{{ date('d/m/Y', strtotime($comPatr->created_at)) }}</td>
                <td><label class="label label-primary">{{ $comPatr->type }}</label></td>
                <td>${{ $comPatr->amount }}</td>
                <td>{{ $comPatr->rec_user_name }} ({{ $comPatr->user_id }})</td>
                <td>{{ $comPatr->new_user_name }} ({{ $comPatr->new_user_id }})</td>
                <td>{{ $comPatr->pat_user_name }} ({{ $comPatr->patroc_id }})</td>
                <td>{{ $comPatr->upline_user_name }} ({{ $comPatr->upline_id }})</td>
              </tr>
              @endforeach
            </tbody>
              
            <tr>
              <td colspan="7">
                <a class="btn btn-danger btn-xs toggle-comision-cat" data-toggle="collapse" data-target="#mults_row_{{$week['week_num']}}">Multiplos <span class="badge pull-right">{{ count($week_multiples) }}</span></a>
              </td>
            </tr>
            <tbody id="mults_row_{{$week['week_num']}}" class="collapse">
              <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Receptor</th>
                <th>Nuevo Asociado</th>
                <th>Patrocinador</th>
                <th>Upline</th>
              </tr>
              @foreach( $week_multiples as $comMult)
              <?php $color_label = ($comMult->amount == 0.00)? 'label-default' : 'label-danger';?>
              <tr>
                <td>{{ date('d/m/Y', strtotime($comMult->created_at)) }}</td>
                <td><label class="label {{$color_label}}">{{ $comMult->type }}</label></td>
                <td>${{ $comMult->amount }}</td>
                <td>{{ $comMult->rec_user_name }} ({{ $comMult->user_id }})</td>
                <td>{{ $comMult->new_user_name }} ({{ $comMult->new_user_id }})</td>
                <td>{{ $comMult->pat_user_name }} ({{ $comMult->patroc_id }})</td>
                <td>{{ $comMult->upline_user_name }} ({{ $comMult->upline_id }})</td>
              </tr>
              @endforeach
            </tbody>

            <tr>
              <td colspan="7">
                <a class="btn btn-warning btn-xs toggle-comision-cat" data-toggle="collapse" data-target="#bonos_row_{{$week['week_num']}}">Bonos 20 <span class="badge pull-right">{{ count($week_bono20s) }}</span></a>
              </td>
            </tr>
            <tbody id="bonos_row_{{$week['week_num']}}" class="collapse">
              <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Receptor</th>
                <th>Nuevo Asociado</th>
                <th>Patrocinador</th>
                <th>Upline</th>
              </tr>
              @foreach( $week_bono20s as $comBono)
              <tr>
                <td>{{ date('d/m/Y', strtotime($comBono->created_at)) }}</td>
                <td><label class="label label-warning">{{ $comBono->type }}</label></td>
                <td>${{ $comBono->amount }}</td>
                <td>{{ $comBono->rec_user_name }} ({{ $comBono->user_id }})</td>
                <td>{{ $comBono->new_user_name }} ({{ $comBono->new_user_id }})</td>
                <td>{{ $comBono->pat_user_name }} ({{ $comBono->patroc_id }})</td>
                <td>{{ $comBono->upline_user_name }} ({{ $comBono->upline_id }})</td>
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