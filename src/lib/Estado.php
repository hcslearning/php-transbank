<?php

namespace lib;

class Estado {

    const POR_PAGAR     = "POR_PAGAR"; // inicio del proceso
    const PAGADO        = "PAGADO"; 
    const RECHAZADO     = "RECHAZADO"; // tarjeta rechazada
    const SALIDA        = "SALIDA"; // comprador se sale del proceso antes de terminar
    const TIMEOUT       = "TIMEOUT"; // comprador no alcanza a completar los datos antes del tiempo maximo
    
}
