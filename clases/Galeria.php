<?php
require_once __DIR__ . '/Conexion.php';

class Galeria {
    public static function ensureSetup(): void {
        try {
            $pdo = (new Conexion())->getConexion();
            $pdo->exec("CREATE TABLE IF NOT EXISTS galeria (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                imagen VARCHAR(255) NOT NULL,
                orden INT UNSIGNED NOT NULL DEFAULT 0,
                visible TINYINT(1) NOT NULL DEFAULT 1,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX(visible), INDEX(orden)
            ) ENGINE=InnoDB CHARSET=utf8mb4;");
            try {
                $cols = $pdo->query("SHOW COLUMNS FROM galeria")->fetchAll(PDO::FETCH_COLUMN);
                $drops = [];
                if (in_array('titulo', $cols)) $drops[] = 'DROP COLUMN titulo';
                if (in_array('descripcion', $cols)) $drops[] = 'DROP COLUMN descripcion';
                if ($drops) { $pdo->exec("ALTER TABLE galeria " . implode(', ', $drops)); }
            } catch (Throwable $e2) {}
        } catch (Throwable $e) { }
    }

    public static function visibles(): array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->query('SELECT id, imagen, orden, visible, created_at FROM galeria WHERE visible=1 ORDER BY orden ASC, id DESC');
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
