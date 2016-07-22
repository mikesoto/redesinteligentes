<!-- Modal -->
<div class="modal fade" id="bonos-map-modal" tabindex="-1" role="dialog" aria-labelledby="bonos-map-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="bonos-map-modal-label">Mapa de Bonos 20</h4>
      </div>
      <div class="modal-body">
        <table id="bonos-map-table" class="table table-hover">
          <tr>
            <th>Usuario</th>
            <th>Bono #</th>
            <th>Fecha de Bono 20</th>
            <th>ID Izquierda</th>
            <th>ID Derecha</th>
            <th>ID de disparo (nuevo)</th>
            <th>Patrocinador</th>
            <th>Upline</th>
          </tr>
          <?php 
            foreach($bonos_data as $bon){
              if(!empty($bon->bonos)){
                foreach($bon->bonos as $bono){
                  echo '<tr>
                          <td>
                            '.$bon->user_id.'
                          </td>
                          <td>
                            '.$bono->temp_id.'
                          </td>
                          <td>
                            '.$bono->fecha_sync.'
                          </td>
                          <td>
                            '.$bono->left_id.'
                          </td>
                          <td>
                            '.$bono->right_id.'
                          </td>
                          <td>
                            '.$bono->new_user_id.'
                          </td>
                          <td>
                            '.$bono->patrocinador.'
                          </td>
                          <td>
                            '.$bono->upline.'
                          </td>
                        </tr>';
                }
                echo '<tr style="background-color:#ccc;"><td colspan="8" style="text-align:center;line-height:1px;">&nbsp;</td></tr>';
              }
            }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>