<?php
// Paso 1: configura y envía redirección para pago en Transbank

require_once '../vendor/autoload.php';

use config\Config;
use lib\PagoWebpayFactory;
use lib\Util;
use db\BaseDatos;
use lib\Estado;

// variables transaccion
$host           = Config::HOST;
$urlRetorno     = Config::HOST . Config::URL_RETORNO;
$urlFinal       = Config::HOST . Config::URL_FINAL;
$monto          = intval( $_GET['monto'] );
$numeroOrden    = intval( date('YmdHi') ); // en UTC (sin zona horaria)
$sessionId      = intval( date('iHdmY') ); // en UTC (sin zona horaria)

// redirige al pago
$webpay = PagoWebpayFactory::createInstance();
$obj    = $webpay->getTokenAndUrlPago($monto, $numeroOrden, $sessionId, $urlRetorno, $urlFinal);

// log
Util::logServer($obj);

// persiste en BD
$fecha  = date('Y-m-d');
$estado = Estado::POR_PAGAR;
BaseDatos::insertarPago(null, $numeroOrden, $monto, $fecha, $obj->token, $estado, null, null, null, null, null, null, null, null, null);

$title  = 'Pagar';
$body   = $webpay->getHtmlRedirectForm($obj->url, $obj->token);
require_once '../view/layout.php';
?>