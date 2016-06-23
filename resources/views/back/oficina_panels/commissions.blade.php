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