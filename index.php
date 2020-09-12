<?php ob_start(); ?>
<form action="pagar.php" method="get">
    Monto: <input name="monto" value="10" type="number" min="10" max="1990999" /> <br />
    <button type="submit">Pagar</button>
</form>
<?php 
$title = "Pago con Webpay";
$body = ob_get_clean();
require_once 'layout/layout.php';
