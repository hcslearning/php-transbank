<?php

require_once 'lib/PagoWebpay.php';

class Config {
    const HOST              = 'http://localhost:8000';
    const URL_RETORNO       = "/confirmacion.php";
    const URL_FINAL         = "/comprobante.php";
    const ENVIRONMENT       = PagoWebpay::ENV_TESTING;
    const CODIGO_COMERCIO   = ;
    const PRIVATE_KEY       = ;
    const PUBLIC_CERT       = ;
    const WEBPAY_CERT       = ;
}
