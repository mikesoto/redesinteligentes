<div id="office-panel-balance" class="panel panel-warning hidden">
  <div class="panel-heading">
    <h3 class="panel-title">Balance</h3>
  </div>
  <div class="panel-body">
    <?php 
      $total_earned = $GLOBALS['total_ganancias'];
      $total_reserved = $GLOBALS['total_reserved'];
    ?>
    <div class="col-sm-12">
      <p>Total de Ganancias: ${{ number_format($total_earned, 2, '.',',') }}</p>
      <p>Total en Reserva: ${{ number_format($total_reserved, 2, '.',',') }}</p>
      <p>Retirados: $0.00</p>
      <p>Balance Disponible: ${{ number_format( ($total_earned - $total_reserved) , 2, '.',',') }}</p>
    </div>
  </div>
</div>