<?php
class ConfigAdmin {
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
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT valor FROM ajustes WHERE clave = :k LIMIT 1');
        $st->execute(['k'=>$clave]);
        $val = $st->fetchColumn();
        if ($val === false) return $default;
        return $val;
    }

    public static function set(string $clave, $valor): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('INSERT INTO ajustes (clave, valor) VALUES (:k,:v) ON DUPLICATE KEY UPDATE valor = VALUES(valor)');
        return $st->execute(['k'=>$clave,'v'=>$valor]);
    }

    public static function getAll(): array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->query('SELECT clave, valor FROM ajustes');
        $rows = $st->fetchAll(PDO::FETCH_KEY_PAIR);
        return $rows ?: [];
    }

    public static function delete(string $clave): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('DELETE FROM ajustes WHERE clave = :k');
        return $st->execute(['k' => $clave]);
    }
}
?>