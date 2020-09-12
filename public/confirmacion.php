<?php

// Paso 2: Comprueba que transaccion haya sido correcta y persiste info

require_once '../vendor/autoload.php';

use lib\PagoWebpayFactory;
use lib\Util;
use lib\Estado;

$token = $_POST['token_ws'];

// redirige al pago
$webpay     = PagoWebpayFactory::createInstance();
$body       = $webpay->confirmarTransaccion($token, function($token, $result, $output){

    Util::logServer( $result );
    Util::logServer( $output );
    
    // persistir en BD resultado
    db\BaseDatos::update($token, Estado::PAGADO, $result->cardDetail->cardNumber, $result->cardDetail->cardExpirationDate,
            $output->authorizationCode, $result->accountingDate, $result->transactionDate, $result->VCI, $output->paymentTypeCode, $output->sharesNumber, $output->commerceCode);
    
}, function($token, $result, $output){
    // error callback
    db\BaseDatos::update($token, Estado::RECHAZADO);
    return <<<EOT
<br />
Las posibles causas de este rechazo son:
<ul>
    <li> Error en el ingreso de los datos de su tarjeta de Crédito o Débito (fecha y/o código de seguridad).</li>
    <li> Su tarjeta de Crédito o Débito no cuenta con saldo suficiente.</li>
    <li> Tarjeta aún no habilitada en el sistema financiero.</li>
</ul>
EOT;
});

$title  = 'Confirmación';
require_once '../view/layout.php';