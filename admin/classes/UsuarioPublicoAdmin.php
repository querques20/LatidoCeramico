<?php
require_once __DIR__ . '/Conexion.php';

class UsuarioPublicoAdmin {
    public static function ensureSetup(): void {
        $pdo = (new Conexion())->getConexion();
        $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NULL,
            apellido VARCHAR(100) NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            telefono VARCHAR(50) NULL,
            es_admin TINYINT(1) NOT NULL DEFAULT 0,
            activo TINYINT(1) NOT NULL DEFAULT 1,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB CHARSET=utf8mb4;");
    }

    public static function todos(): array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->query('SELECT id, nombre, apellido, email, telefono, activo, created_at, updated_at FROM usuarios ORDER BY id DESC');
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function eliminar(int $id): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        return $st->execute(['id' => $id]);
    }

    public static function existeEmail(string $email): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email = :e');
        $st->execute(['e' => $email]);
        return ((int)$st->fetchColumn()) > 0;
    }

    public static function crear(string $nombre, string $apellido, string $telefono, string $email, string $password): bool {
        $pdo = (new Conexion())->getConexion();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $st = $pdo->prepare('INSERT INTO usuarios (nombre, apellido, telefono, email, password_hash, es_admin, activo) VALUES (:n,:a,:t,:e,:p,0,1)');
        return $st->execute(['n'=>$nombre,'a'=>$apellido,'t'=>$telefono,'e'=>$email,'p'=>$hash]);
    }
}
