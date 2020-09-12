<?php

class Util {
    
    public static function logServer($obj) {
        if( Config::ENVIRONMENT == PagoWebpay::ENV_PRODUCCION) {
            return;
        } else {
            ob_start();
            var_dump( $obj );
            error_log( ob_get_clean(), 4);
        }
    }
    
}
