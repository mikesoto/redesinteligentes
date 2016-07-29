      <div id="office-panel-dashboard" class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Dashboard</h3>
        </div>
        <div class="panel-body">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Asignado</th>
                <th>Downlines</th>
                <th>Patrocinios</th>
                <th>Multiplos</th>
                <th>Asignadas</th>
                <th>Bono 20</th>
                <th>Ganancias</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ $asignado->user }}</td>
                <td>{{ count($downlines) }}</td>
                <td>{{ count($comsPatr) }}</td>
                <td id="dash-mult-count"></td>
                <td>{{ count($comsAsig) }}</td>
                <td>{{ count($comsBono) }}</td>
                <td>$<span id="dash-comissions-total">{{ number_format($ganancias) }}</span></td>
                <td>Bronce</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>