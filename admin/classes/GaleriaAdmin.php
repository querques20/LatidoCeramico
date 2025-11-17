<?php
class GaleriaAdmin {
    public static function ensureSetup(): void {
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
            $toDrop = [];
            if (in_array('titulo', $cols)) $toDrop[] = 'DROP COLUMN titulo';
            if (in_array('descripcion', $cols)) $toDrop[] = 'DROP COLUMN descripcion';
            if ($toDrop) {
                $pdo->exec("ALTER TABLE galeria " . implode(', ', $toDrop));
            }
        } catch (Throwable $e) {  }
    }

    public static function todas(): array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->query('SELECT id, imagen, orden, visible, created_at FROM galeria ORDER BY orden ASC, id DESC');
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function visibles(): array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->query('SELECT id, imagen, orden, visible, created_at FROM galeria WHERE visible = 1 ORDER BY orden ASC, id DESC');
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function crear(string $imagen, int $orden = 0, int $visible = 1): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('INSERT INTO galeria (imagen, orden, visible) VALUES (:i,:o,:v)');
        return $st->execute([':i'=>$imagen, ':o'=>$orden, ':v'=>$visible]);
    }

    public static function buscar(int $id): ?array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT id, imagen, orden, visible, created_at FROM galeria WHERE id = :id');
        $st->execute([':id'=>$id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function actualizar(int $id, ?string $imagen, int $orden, int $visible): bool {
        $pdo = (new Conexion())->getConexion();
        if ($imagen !== null && $imagen !== '') {
            $st = $pdo->prepare('UPDATE galeria SET imagen=:i, orden=:o, visible=:v WHERE id=:id');
            return $st->execute([':i'=>$imagen, ':o'=>$orden, ':v'=>$visible, ':id'=>$id]);
        } else {
            $st = $pdo->prepare('UPDATE galeria SET orden=:o, visible=:v WHERE id=:id');
            return $st->execute([':o'=>$orden, ':v'=>$visible, ':id'=>$id]);
        }
    }

    public static function eliminar(int $id): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('DELETE FROM galeria WHERE id = :id');
        return $st->execute([':id'=>$id]);
    }

    public static function setVisible(int $id, int $visible): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('UPDATE galeria SET visible = :v WHERE id = :id');
        return $st->execute([':v'=>$visible, ':id'=>$id]);
    }
}
?>