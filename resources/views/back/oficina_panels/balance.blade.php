<div id="office-panel-balance" class="panel panel-warning hidden">
  <div class="panel-heading">
    <h3 class="panel-title">Balance</h3>
  </div>
  <div class="panel-body">
    <?php 
      $total_earned = $GLOBALS['total_ganancias'];
    ?>
    <div class="col-sm-12">
      <p>Total de Ganancias: ${{ number_format($total_earned, 2, '.',',') }}</p>
      <p>Total en Reserva: $0.00</p>
      <p>Retirados: $0.00</p>
      <p>Balance Total: $0.00</p>
      <p>Balance Disponible: $0.00</p>
    </div>
  </div>
</div>