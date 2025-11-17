<?php
require_once __DIR__ . '/Conexion.php';

class Config {
    public static function ensureSetup(): void {
        $pdo = (new Conexion())->getConexion();
        $pdo->exec("CREATE TABLE IF NOT EXISTS ajustes (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `clave` VARCHAR(100) NOT NULL UNIQUE,
            `valor` TEXT NULL,
            updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB CHARSET=utf8mb4;");
    }

    public static function get(string $clave, $default = null) {
        try {
            $pdo = (new Conexion())->getConexion();
            $st = $pdo->prepare('SELECT valor FROM ajustes WHERE clave = :k LIMIT 1');
            $st->execute(['k'=>$clave]);
            $val = $st->fetchColumn();
            return ($val === false || $val === null) ? $default : $val;
        } catch (Throwable $e) {
            return $default;
        }
    }
}
?>