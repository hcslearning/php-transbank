<?php

namespace db;

use config\Config;
use PDO;

class PDOFactory {

    public static function createPDO(): PDO {
        $url = Config::URL_DB;
        $pdo = new PDO( $url );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } 

}
