<?php
// Paso 1: configura y envía redirección para pago en Transbank

require_once './lib/PagoWebpay.php';
require_once './lib/PagoWebpayFactory.php';
require_once './Config.php';
require_once './lib/Util.php';

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
require_once 'layout/layout.php';
?>