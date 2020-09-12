<?php
// Paso 1: configura y envía redirección para pago en Transbank

require_once '../vendor/autoload.php';

use config\Config;
use lib\PagoWebpayFactory;
use lib\Util;

// variables transaccion
$host           = Config::HOST;
$urlRetorno     = Config::HOST . Config::URL_RETORNO;
$urlFinal       = Config::HOST . Config::URL_FINAL;
$monto          = intval( $_GET['monto'] );
$numeroOrden    = intval( date('Ymdhi') ); // en UTC (sin zona horaria)
$sessionId      = intval( date('ihdmY') ); // en UTC (sin zona horaria)

// redirige al pago
$webpay = PagoWebpayFactory::createInstance();
$obj    = $webpay->getTokenAndUrlPago($monto, $numeroOrden, $sessionId, $urlRetorno, $urlFinal);

// log
Util::logServer($obj);

$title  = 'Pagar';
$body   = $webpay->getHtmlRedirectForm($obj->url, $obj->token);
require_once '../view/layout.php';
?>