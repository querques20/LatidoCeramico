<?php
require_once __DIR__ . '/Conexion.php';

class Usuario {
    public static function ensureSetup(): void {
        $pdo = (new Conexion())->getConexion();
        // Crear con un esquema compatible si no existe (alineado a la DB actual)
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

    public static function crear(string $nombre, ?string $apellido, string $email, string $password, ?string $telefono = null): bool {
        $pdo = (new Conexion())->getConexion();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $st = $pdo->prepare('INSERT INTO usuarios (nombre, apellido, email, password_hash, telefono) VALUES (:n,:a,:e,:p,:t)');
        return $st->execute(['n'=>$nombre,'a'=>$apellido,'e'=>$email,'p'=>$hash,'t'=>$telefono]);
    }

    public static function buscarPorEmail(string $email): ?array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT * FROM usuarios WHERE email=:e LIMIT 1');
        $st->execute(['e'=>$email]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function existeEmail(string $email): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT COUNT(*) FROM usuarios WHERE email=:e');
        $st->execute(['e'=>$email]);
        return ((int)$st->fetchColumn())>0;
    }

    public static function registrarLogin(int $id): void {
        $pdo = (new Conexion())->getConexion();
        // Actualizamos updated_at como marca de último acceso en el esquema actual
        $st = $pdo->prepare('UPDATE usuarios SET updated_at = NOW() WHERE id=:id');
        $st->execute(['id'=>$id]);
    }
}
?>