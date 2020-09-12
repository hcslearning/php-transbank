<?php

// Paso 3: genera comprobante si está todo OK o sino muestra error

require_once '../vendor/autoload.php';

use lib\PagoWebpayFactory;
use lib\Util;

// redirige al pago
$webpay = PagoWebpayFactory::createInstance();
$body   = $webpay->comprobante($_POST, function( $post ){
    Util::logServer( $post );
    return "<h1>Pago Aprobado</h1>";
}, function( $post ){
    Util::logServer( $post );
    $pid = $_POST['TBK_ORDEN_COMPRA'];
    return <<<EOT
<h1 style='color: red;'>Pago del pedido #$pid anulado por el usuario.</h1>
<p>El pago no se completó porque el usuario canceló el proceso de pago.</p>
EOT;
});

$title  = 'Comprobante';
require_once '../view/layout.php';
?>