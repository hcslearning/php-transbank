<?php

namespace lib;

use Transbank\Webpay\Configuration;
use Transbank\Webpay\Webpay;
use Transbank\Webpay\WebPayNormal;

class PagoWebpay {
    
    const ENV_TESTING       = "TESTING";
    const ENV_PRODUCCION    = "PRODUCCION";

    private $environment    = self::ENV_TESTING;
    private $codigoComercio = null;
    private $privateKey     = null;
    private $publicCert     = null;
    private $webpayCert     = null;
    
    function __construct($environment = "TESTING", $codigoComercio = null, $privateKey = null, $publicCert = null, $webpayCert = null) {
        $this->environment = $environment;
        if( $this->environment == self::ENV_TESTING ) {
            return;
        } else {
            $this->codigoComercio   = $codigoComercio;
            $this->privateKey       = $privateKey;
            $this->publicCert       = $publicCert;
            $this->webpayCert       = $webpayCert;
        }
    }
    
    // paso 1: crear transaccion - puede ser para testing o de produccion
    function getTransaccion(): WebPayNormal {
        if( $this->environment == self::ENV_TESTING ) {
            // para testing
            $webpay = new Webpay( Configuration::forTestingWebpayPlusNormal() );
        } else {
            // para produccion
            $configuracion = new Configuration();
            $configuracion->setEnvironment( $this->environment );
            $configuracion->setCommerceCode( $this->codigoComercio );
            $configuracion->setPrivateKey( $this->privateKey );
            $configuracion->setPublicCert( $this->publicCert );
            $configuracion->setWebpayCert( $this->webpayCert );
            $webpay = new Webpay( $configuracion );
        }

        return $webpay->getNormalTransaction();
    }

    // paso 2: conseguir una URL y un Token para redirigir al pago
    function getTokenAndUrlPago($monto, $numeroOrden, $sessionId, $urlRetorno, $urlFinal) {
        $transaccion = $this->getTransaccion();
        // $obj->token & $obj->url 
        return $transaccion->initTransaction($monto, $numeroOrden, $sessionId, $urlRetorno, $urlFinal);
    }

    // paso 3: redirigir con URL y TOKEN
    function getHtmlRedirectForm($formAction, $token) {
        return <<<EOT
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <title>Pago con Transbank</title>	
    </head>
    <body>
        <form name="transbankForm" id="transbankForm" action="$formAction" method="post">
            <input type="hidden" name="token_ws" value="$token" />
            <button type="submit">Enviar</button>
        </form>
        <script type="text/javascript">
            window.onload = function() {
                document.forms["transbankForm"].submit();
            };
        </script>
    </body>
</html>
EOT;
    }

    // paso 4: confirmar la transacción
    /**
     * @param string $token Representa a 'token_ws' que viene vía POST desde Transbank 
     */
    function confirmarTransaccion($token, $callback, $errorCallback) {
        $transaccion    = $this->getTransaccion();
        $result         = $transaccion->getTransactionResult($token);
        $output         = $result->detailOutput;
        if( $output->responseCode == 0 ) {
            // Transaccion exitosa, puedes procesar el pedido
            $formAction = $result->urlRedirection;
            
            $callback($result, $output);
            
            return $this->getHtmlRedirectForm($formAction, $token);
        } else {
            return $errorCallback($result, $output);
        }
    }
    
    // paso 5: paso final - comprobante
    function comprobante($arrPost, $callback, $errorCallback) {
        if( !isset($arrPost['token_ws']) ) {
            // transaccion anulada 
            return $errorCallback( $arrPost );
        }
        
        // exito
        return $callback( $arrPost );
    }
}




