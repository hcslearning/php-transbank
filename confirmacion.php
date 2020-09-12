<?php

// Paso 2: Comprueba que transaccion haya sido correcta y persiste info

require_once './lib/PagoWebpay.php';

$token = $_POST['token_ws'];

// redirige al pago
$webpay     = new PagoWebpay();
$body       = $webpay->confirmarTransaccion($token, function($result, $output){
    var_dump( $result );
    var_dump( $output );
});

$title  = 'Confirmación';
require_once 'layout/layout.php';
?>