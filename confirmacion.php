<?php

// Paso 2: Comprueba que transaccion haya sido correcta y persiste info

require_once './lib/PagoWebpay.php';
require_once './lib/PagoWebpayFactory.php';
require_once './Config.php';
require_once './lib/Util.php';

$token = $_POST['token_ws'];

// redirige al pago
$webpay     = PagoWebpayFactory::createInstance();
$body       = $webpay->confirmarTransaccion($token, function($result, $output){
    Util::logServer( $result );
    Util::logServer( $output );
    
    // persistir en BD resultado
}, function($result, $output){
    // error callback
    
});

$title  = 'Confirmación';
require_once 'layout/layout.php';