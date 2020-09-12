<?php

// Paso 3: genera comprobante si está todo OK o sino muestra error

require_once './lib/PagoWebpay.php';

// variables transaccion
$host           = 'http://localhost:8000';
$urlRetorno     = "$host/confirmacion.php";
$urlFinal       = "$host/comprobante.php";
$monto          = intval( $_GET['monto'] );
$numeroOrden    = intval( date('Ymdhi') ); // en UTC (sin zona horaria)
$sessionId      = intval( date('ihdmY') ); // en UTC (sin zona horaria)

// redirige al pago
$webpay = new PagoWebpay();
$obj    = $webpay->getTokenAndUrlPago($monto, $numeroOrden, $sessionId, $urlRetorno, $urlFinal);
$title  = 'Pagar';
$body   = $webpay->getHtmlRedirectForm($obj->url, $obj->token);
require_once 'layout/layout.php';
?>