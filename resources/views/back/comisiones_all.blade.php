@extends('layouts.back')
@section('content')
<div>
  <?php 
    //start period
    $periodo = 1;
    $total_ganancias = 0;
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
  @foreach($weeks_info as $week)
    <?php     
      //gather all patrocinios for this week
      $week_patrocinios = []; $week_patr_ganancias = 0;
      foreach( $comsPatr as $patr ){
        if( $patr->created_at >= $week['week_domingo'] && $patr->created_at <= $week['week_sabado']){
          array_push($week_patrocinios, $patr);
          $week_patr_ganancias += $patr->amount;
        }
      }

      //gather all multiples for this week
      $week_multiples = []; $week_mult_ganancias = 0; $week_mult_null = 0;
      foreach( $comsMult as $mlt ){
        if( $mlt->created_at >= $week['week_domingo'] && $mlt->created_at <= $week['week_sabado']){
          array_push($week_multiples, $mlt);
          $week_mult_ganancias += $mlt->amount;
          if($mlt->amount == 0.00){
            $week_mult_null++;
          }
        }
      }

      //gather all the Asignados for this week
      $week_asigs = []; $week_asig_ganancias = 0;
      foreach( $comsAsig as $as ){
        if( $as->created_at >= $week['week_domingo'] && $as->created_at <= $week['week_sabado']){
          array_push($week_asigs, $as);
          $week_asig_ganancias += $as->amount;
        }
      }

      //gather all the bono20s for this week
      $week_bono20s = [];  $week_bono_ganancias = 0;
      foreach( $comsBono as $bn ){
        if( $bn->created_at >= $week['week_domingo'] && $bn->created_at <= $week['week_sabado']){
          array_push($week_bono20s, $bn);
          $week_bono_ganancias += $bn->amount;
        }
      }

      $week_ganancias_total = $week_patr_ganancias + $week_mult_ganancias + $week_asig_ganancias + $week_bono_ganancias;
      $total_ganancias += $week_ganancias_total;
    ?>
    <div class="col-sm-12">
      <button type="button" class="btn btn-lg btn-default com-periodo-label" data-toggle="collapse" data-target="#week-{{$week['week_num']}}">
        Periodo - {{ date_create($week['week_domingo'])->format("d") }} de {{ $trans_months[date_create($week['week_domingo'])->format("F")] }} al {{ date_create($week['week_sabado'])->format("d") }} de {{ $trans_months[date_create($week['week_sabado'])->format("F")] }} {{ date_create($week['week_sabado'])->format("Y") }}
        <span class="badge pull-right">${{ number_format($week_ganancias_total, 2, '.',',') }}</span>
      </button>
    </div>
    <br>
    <br>
    <div id="week-{{$week['week_num']}}" class="collapse">
      <table class="table table-hover comisiones-main-table">
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
    <?php $periodo++;?>
  @endforeach
  <div class="col-sm-12">
    <div class="com-periodo-label com-ganancias-total">
      <span class="badge pull-right">${{ number_format($total_ganancias, 2, '.',',') }}</span>
      <strong class="pull-right com-total-text">Total de Ganancias:</strong> &nbsp; 
    </div>
  </div>
</div>
@endsection