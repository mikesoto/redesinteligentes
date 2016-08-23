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
      $GLOBALS['total_ganancias'] = 0;
      $GLOBALS['total_reserved'] = 0;
      $GLOBALS['is_active'] = false;
      $active_pats_left = 0;
      $active_pats_right = 0;
      $cut_date = date("Y-m-d", strtotime("-4 months"));
      foreach($GLOBALS['patrs_side_left'] as $pl){
        //check if user on left side is a ptrocinio of current user
        if($pl->patrocinador == $cur_user->id){
          //check if user was registered in the last 4 months
          if(strtotime($pl->fecha_ingreso) >= strtotime($cut_date)){
            //increment the left pats count
            $active_pats_left++;
          }
        }
      }
      foreach($GLOBALS['patrs_side_right'] as $pl){
        //check if user on right side is a ptrocinio of current user
        if($pl->patrocinador == $cur_user->id){
          //check if user was registered in the last 4 months
          if(strtotime($pl->fecha_ingreso) >= strtotime($cut_date)){
            //increment the right pats count
            $active_pats_right++;
          }
        }
      }

    //show the alert for current active pats
    if($active_pats_left > 0 && $active_pats_right > 0){
      $GLOBALS['is_active'] = true;
      echo '
        <div class="alert alert-success" role="alert">
          <p>Usted tiene por lo menos 1 patrocinio en cada lado en los últimos 4 meses.</p>
          <p>Por lo tanto, todas sus comisiones estarán disponibles para retiro.</p>
        </div>';
    }else{
      echo '
        <div class="alert alert-danger" role="alert">
          <p>Usted no tiene al menos 1 patrocinio en cada lado en los últimos 4 meses.</p>
          <p>Por lo tanto, sus comisiones de Multiplos, Asignados, y Bono20 no estarán disponibles para retiro.</p>
          <p>Una vez que cumpla con estos requisitos, usted será capaz de retirar todas sus comisiones.</p>
        </div>';
    }
    ?>


    @foreach($weeks_info as $week)
      <?php     
        //hide any weeks not in the filter period if requested
        $filtered = ($filter_period > 0 && $periodo != $filter_period)? 'hidden' : ''; 

        //gather all patrocinios for this week
        $week_patrocinios = []; $week_patr_ganancias = 0; $week_patr_reserved = 0;
        foreach( $comsPatr as $patr ){
          if( $patr->created_at >= $week['week_domingo'] && $patr->created_at <= $week['week_sabado']){
            array_push($week_patrocinios, $patr);
            $week_patr_ganancias += $patr->amount;
            if(!$GLOBALS['is_active']){
              $week_patr_reserved += $patr->amount;
            }
          }
        }

        //gather all patrocinios for this week
        $week_multiples = []; $week_mult_ganancias = 0; $week_mult_reserved = 0; $week_mult_null = 0;
        foreach( $comsMult as $mlt ){
          if( $mlt->created_at >= $week['week_domingo'] && $mlt->created_at <= $week['week_sabado']){
            array_push($week_multiples, $mlt);
            $week_mult_ganancias += $mlt->amount;
            if(!$GLOBALS['is_active']){
              $week_mult_reserved += $mlt->amount;
            }
            if($mlt->amount == 0.00){
              $week_mult_null++;
            }
          }
        }
        //gather all the Asignados for this week
        $week_asigs = []; $week_asig_ganancias = 0; $week_asig_reserved = 0;
        foreach( $comsAsig as $as ){
          if( $as->created_at >= $week['week_domingo'] && $as->created_at <= $week['week_sabado']){
            array_push($week_asigs, $as);
            $week_asig_ganancias += $as->amount;
            if(!$GLOBALS['is_active']){
              $week_asig_reserved += $as->amount;
            }
          }
        }
        //gather all the bono20s for this week
        $week_bono20s = [];  $week_bono_ganancias = 0; $week_bono_reserved = 0;
        foreach( $comsBono as $bn ){
          if( $bn->created_at >= $week['week_domingo'] && $bn->created_at <= $week['week_sabado']){
            array_push($week_bono20s, $bn);
            $week_bono_ganancias += $bn->amount;
            if(!$GLOBALS['is_active']){
              $week_bono_reserved += $bn->amount;
            }
          }
        }
        $week_ganancias_total = $week_patr_ganancias + $week_mult_ganancias + $week_asig_ganancias + $week_bono_ganancias;
        $week_reserved_total = $week_patr_reserved + $week_mult_reserved + $week_asig_reserved + $week_bono_reserved;
        //only add to the grand total if the period is not filtered (hidden)
        if($filtered != 'hidden'){
          $GLOBALS['total_ganancias'] += $week_ganancias_total;
          $GLOBALS['total_reserved'] += $week_reserved_total;
        }
      ?>
      @if(count($week_patrocinios) || count($week_multiples) || count($week_asigs) || count($week_bono20s) ) 
      
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
            <span class="badge pull-right">${{ number_format($week_ganancias_total, 2, '.',',') }} @if($week_reserved_total) @endif</span>
          </button>
        </div>
        <br>
        <br>
        <div id="week-{{$week['week_num']}}" class="collapse">
          <table class="table table-hover {{ $filtered }} comisiones-main-table">
            <tr>
              <td colspan="7">
                <a class="btn btn-primary btn-xs toggle-comision-cat" data-toggle="collapse" data-target="#patrocs_row_{{$week['week_num']}}">Patrocinios: &nbsp; ${{ number_format($week_patr_ganancias, 2, '.', ',') }} <span class="badge pull-right">{{ count($week_patrocinios) }} </span></a>
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
              <tr>
                <td colspan="2">
                  <strong>Ganancias Patrocinios:</strong>
                </td>
                <td colspan="5">
                  ${{ number_format($week_patr_ganancias, 2, '.', ',') }} 
                </td>
              </tr>
            </tbody>
              
            <tr>
              <td colspan="7">
                <a class="btn btn-danger btn-xs toggle-comision-cat" data-toggle="collapse" data-target="#mults_row_{{$week['week_num']}}">Multiplos: &nbsp; &nbsp; &nbsp;${{ number_format($week_mult_ganancias, 2, '.', ',') }} <span class="badge pull-right">{{ count($week_multiples) }} @if($week_mult_null) - {{ $week_mult_null }} @endif</span></a>
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
              <tr>
                <td colspan="2">
                  <strong>Ganancias Multiplos:</strong>
                </td>
                <td colspan="5">
                  ${{ number_format($week_mult_ganancias, 2, '.', ',') }} 
                </td>
              </tr>
            </tbody>

            <tr>
              <td colspan="7">
                <a class="btn btn-success btn-xs toggle-comision-cat" data-toggle="collapse" data-target="#asigs_row_{{$week['week_num']}}">Asignados: &nbsp; ${{ number_format($week_asig_ganancias, 2, '.', ',') }} <span class="badge pull-right">{{ count($week_asigs) }}</span></a>
              </td>
            </tr>
            <tbody id="asigs_row_{{$week['week_num']}}" class="collapse">
              <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Receptor</th>
                <th>Nuevo Asociado</th>
                <th>Patrocinador</th>
                <th>Upline</th>
              </tr>
              @foreach( $week_asigs as $comAsig)
              <tr>
                <td>{{ date('d/m/Y', strtotime($comAsig->created_at)) }}</td>
                <td><label class="label label-success">{{ $comAsig->type }}</label></td>
                <td>${{ $comAsig->amount }}</td>
                <td>{{ $comAsig->rec_user_name }} ({{ $comAsig->user_id }})</td>
                <td>{{ $comAsig->new_user_name }} ({{ $comAsig->new_user_id }})</td>
                <td>{{ $comAsig->pat_user_name }} ({{ $comAsig->patroc_id }})</td>
                <td>{{ $comAsig->upline_user_name }} ({{ $comAsig->upline_id }})</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="2">
                  <strong>Ganancias Asignados:</strong>
                </td>
                <td colspan="5">
                  ${{ number_format($week_asig_ganancias, 2, '.', ',') }} 
                </td>
              </tr>
            </tbody>

            <tr>
              <td colspan="7">
                <a class="btn btn-warning btn-xs toggle-comision-cat" data-toggle="collapse" data-target="#bonos_row_{{$week['week_num']}}">Bonos 20: &nbsp; &nbsp; ${{ number_format($week_bono_ganancias, 2, '.', ',') }} <span class="badge pull-right">{{ count($week_bono20s) }}</span></a>
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
              <tr>
                <td colspan="2">
                  <strong>Ganancias Bono 20:</strong>
                </td>
                <td colspan="5">
                  ${{ number_format($week_bono_ganancias, 2, '.', ',') }} 
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      @endif
      <?php $periodo++;?>
    @endforeach
    <div class="col-sm-12">
      <div class="com-periodo-label com-ganancias-total">
        <span class="badge pull-right">${{ number_format($GLOBALS['total_ganancias'], 2, '.',',') }}</span>
        <strong class="pull-right com-total-text">Total de Ganancias:</strong> &nbsp; 
      </div>
    </div>
  </div>
</div>