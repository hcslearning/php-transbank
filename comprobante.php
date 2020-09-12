<?php

// Paso 3: genera comprobante si está todo OK o sino muestra error

require_once './lib/PagoWebpay.php';
require_once './lib/PagoWebpayFactory.php';
require_once './Config.php';
require_once './lib/Util.php';

// redirige al pago
$webpay = PagoWebpayFactory::createInstance();
$body   = $webpay->comprobante($_POST, function( $post ){
    Util::logServer( $post );
    return "<h1>Pago Aprobado</h1>";
}, function( $post ){
    Util::logServer( $post );
    return "<h1 style='color: red;'>El pago no se completó porque el usuario canceló el proceso de pago.</h1>";
});

$title  = 'Comprobante';
require_once 'layout/layout.php';
?>