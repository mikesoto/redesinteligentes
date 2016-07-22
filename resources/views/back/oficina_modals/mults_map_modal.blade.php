<!-- Modal -->
<div class="modal fade" id="mults-map-modal" tabindex="-1" role="dialog" aria-labelledby="mults-map-modal-label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mults-map-modal-label">Mapa de Multiplos</h4>
      </div>
      <div class="modal-body">
        <table id="mults-map-table" class="table table-hover">
          <tr>
            <th>Usuario</th>
            <th>Multiplos</th>
          </tr>
          <?php 
            foreach($mults_data as $multd){
              if(!empty($multd->multiples)){
                echo '<tr>
                        <td>
                          '.$multd->user_id.'
                        </td>
                        <td>
                          '.implode(',', $multd->multiples).'
                        </td>
                      </tr>';
              }
            }
          ?>
        </table>
      </div>
    </div>
  </div>
</div>