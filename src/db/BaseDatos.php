<?php

namespace db;

use db\PDOFactory;

class BaseDatos {

    public static function crearTabla() {
        try {
            $pdo = PDOFactory::createPDO();
            $sql = <<<SQL
CREATE TABLE pagos(
    id SERIAL PRIMARY KEY,
    token VARCHAR(255) UNIQUE,
    cliente VARCHAR(255),
    orden_compra BIGINT,
    monto FLOAT,
    fecha DATE,
    estado VARCHAR(255),
    tarjeta VARCHAR(255),
    codigo_autorizacion VARCHAR(255),
    tarjeta_expiracion VARCHAR(50),
    accounting_date VARCHAR(50),
    transaction_date VARCHAR(50),
    vci VARCHAR(10),
    tipo_pago VARCHAR(5),
    cuotas INT,
    codigo_comercio BIGINT
);
SQL;
            $pdo->exec($sql);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            $pdo = null;
        }
    }

    public static function dropTabla() {
        try {
            $pdo = PDOFactory::createPDO();
            $sql = "DROP TABLE pagos;";
            $pdo->exec($sql);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            $pdo = null;
        }
    }

    public static function insertarPago($cliente, $ordenCompra, $monto, $fecha, $token, $estado, $tarjeta, $codigoAutorizacion, $tarjetaExpiracion, $accountingDate, $transactionDate, $vci, $tipoPago, $cuotas, $codigoComercio) {
        try {
            $pdo = PDOFactory::createPDO();
            $sql = <<<SQL
INSERT INTO 
    pagos(cliente, orden_compra, monto, fecha, token, estado, tarjeta, codigo_autorizacion, tarjeta_expiracion, accounting_date, transaction_date, vci, tipo_pago, cuotas, codigo_comercio) 
    VALUES(:cliente, :orden_compra, :monto, :fecha, :token, :estado, :tarjeta, :codigo_autorizacion, :tarjeta_expiracion, :accounting_date, :transaction_date, :vci, :tipo_pago, :cuotas, :codigo_comercio);
SQL;
            $statement = $pdo->prepare($sql);
            $statement->bindParam(":cliente", $cliente);
            $statement->bindParam(":orden_compra", $ordenCompra);
            $statement->bindParam(":monto", $monto);
            $statement->bindParam(":fecha", $fecha);
            $statement->bindParam(":token", $token);
            $statement->bindParam(":estado", $estado);
            $statement->bindParam(":tarjeta", $tarjeta);
            $statement->bindParam(":codigo_autorizacion", $codigoAutorizacion);
            $statement->bindParam(":tarjeta_expiracion", $tarjetaExpiracion);
            $statement->bindParam(":accounting_date", $accountingDate);
            $statement->bindParam(":transaction_date", $transactionDate);
            $statement->bindParam(":vci", $vci);
            $statement->bindParam(":tipo_pago", $tipoPago);
            $statement->bindParam(":cuotas", $cuotas);
            $statement->bindParam(":codigo_comercio", $codigoComercio);

            $res = $statement->execute();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            $pdo = null;
        }
    }

    public static function update($token, $estado, $tarjeta = null, $tarjetaExpiracion = null, $codigoAutorizacion = null, $accountingDate = null, $transactionDate = null, $vci = null, $tipoPago = null, $cuotas = null, $codigoComercio = null) {
        try {
            $pdo = PDOFactory::createPDO();
            $sql = <<<SQL
UPDATE pagos 
    SET tarjeta = :tarjeta, tarjeta_expiracion = :tarjeta_expiracion, codigo_autorizacion = :codigo_autorizacion, accounting_date = :accounting_date, transaction_date = :transaction_date,
        vci = :vci, tipo_pago = :tipo_pago, cuotas = :cuotas, codigo_comercio = :codigo_comercio, estado = :estado
    WHERE token = :token
SQL;
            $statement = $pdo->prepare($sql);
            $statement->bindParam(":token", $token);
            $statement->bindParam(":estado", $estado);
            $statement->bindParam(":tarjeta", $tarjeta);
            $statement->bindParam(":codigo_autorizacion", $codigoAutorizacion);
            $statement->bindParam(":tarjeta_expiracion", $tarjetaExpiracion);
            $statement->bindParam(":accounting_date", $accountingDate);
            $statement->bindParam(":transaction_date", $transactionDate);
            $statement->bindParam(":vci", $vci);
            $statement->bindParam(":tipo_pago", $tipoPago);
            $statement->bindParam(":cuotas", $cuotas);
            $statement->bindParam(":codigo_comercio", $codigoComercio);

            $res = $statement->execute();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            $pdo = null;
        }
    }

    public static function findByToken($token) {
        try {
            $pdo = PDOFactory::createPDO();
            $sql = "SELECT * FROM pagos WHERE token = :token";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(":token", $token);
            $statement->execute();
            return $statement->fetchObject();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            $pdo = null;
        }
        return false;
    }
    
    public static function findByOrdenCompra($oc) {
        try {
            $pdo = PDOFactory::createPDO();
            $sql = "SELECT * FROM pagos WHERE orden_compra = :orden_compra";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(":orden_compra", $oc);
            $statement->execute();
            return $statement->fetchObject();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        } finally {
            $pdo = null;
        }
        return false;
    }
    
}
