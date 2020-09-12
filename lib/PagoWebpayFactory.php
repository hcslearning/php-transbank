<?php

class PagoWebpayFactory {
    // __construct($environment = "TESTING", $codigoComercio = null, $privateKey = null, $publicCert = null, $webpayCert = null) {
    public static function createInstance(): PagoWebpay {
        return new PagoWebpay(Config::ENVIRONMENT, Config::CODIGO_COMERCIO, Config::PRIVATE_KEY, Config::PUBLIC_CERT, Config::WEBPAY_CERT);
    }
    
}
