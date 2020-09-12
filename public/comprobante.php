<?php

// Paso 3: genera comprobante si está todo OK o sino muestra error

require_once '../vendor/autoload.php';

use lib\PagoWebpayFactory;
use lib\Util;
use db\BaseDatos;
use lib\Estado;
use mailer\Mailer;
use config\Config;

// redirige al pago
$webpay = PagoWebpayFactory::createInstance();
$body   = $webpay->comprobante($_POST, function( $post ){
    $token  = filter_input(INPUT_POST, 'token_ws');
    $pago   = BaseDatos::findByToken($token);
    Util::logServer( $post );
    
    $html = <<<OKK
<h1>Pago Aprobado</h1>
<table>
   <tr>
    <td>Orden Compra:</td>
    <td>{$pago->orden_compra}</td>
   </tr>
    
    <tr>
    <td>Código de Autorización:</td>
    <td>{$pago->codigo_autorizacion}</td>
   </tr>
    
    <tr>
    <td>Forma de Pago:</td>
    <td>{$pago->tipo_pago}</td>
   </tr>
    
    <tr>
    <td>Fecha: </td>
    <td>{$pago->fecha}</td>
   </tr>
    
    <tr>
    <td>Número Tarjeta:</td>
    <td>**** **** **** {$pago->tarjeta}</td>
   </tr>
    
    <tr>
    <td>Cuotas:</td>
    <td>{$pago->cuotas}</td>
   </tr>
    
    <tr>
    <td>Monto: </td>
    <td>{$pago->monto}</td>
   </tr>
    
    <tr>
    <td>Estado: </td>
    <td>{$pago->estado}</td>
   </tr>
</table>
OKK;
    
    $text = <<<TEX
Hoy {$pago->fecha} se ha recibido un pago por {$pago->monto} desde la Tarjeta **** **** **** {$pago->tarjeta}
pagado con {$pago->tipo_pago} de la Orden de Compra Número {$pago->orden_compra} y con el siguiente 
código de autorización: {$pago->codigo_autorizacion}.
TEX;

    Mailer::mail(Config::MAIL_TO, "Pago OC $pago->orden_compra por $$pago->monto", $html, $text);
    
    
    return $html;
    
}, function( $post ){
    Util::logServer( $post );
    $oc     = filter_input(INPUT_POST, 'TBK_ORDEN_COMPRA', FILTER_VALIDATE_INT);
    $token  = filter_input(INPUT_POST, 'TBK_TOKEN');
    
    if( $token != null ) {
        // anulado, el usuario sale del proceso
        BaseDatos::update($token, Estado::SALIDA);
        return <<<EOT
<h1 style='color: red;'>Pago del pedido #$oc anulado por el usuario.</h1>
<p>El pago no se completó porque el usuario canceló el proceso de pago.</p>
EOT;
    } else {
        // timeout
        $intentoPago = BaseDatos::findByOrdenCompra( $oc );
        BaseDatos::update($intentoPago->token, Estado::TIMEOUT);
        return <<<EOT
<h1 style='color: red;'>Excedió el tiempo máximo permitido del pedido #$oc.</h1>
<p>Ha excedido el tiempo máximo permitido para completar los datos para el pago. 
Por favor intente nuevamente.</p>
EOT;
    }
    
    
});

$title  = 'Comprobante';
require_once '../view/layout.php';
?>