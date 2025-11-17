<?php

class CategoriaAdmin
{
    private ?int $id = null;
    private ?string $nombre = null;
    private ?string $slug = null;
    private ?int $visible = 1;
    private ?int $orden = 0;

    public function getId(): ?int { return $this->id; }
    public function getNombre(): ?string { return $this->nombre; }
    public function getSlug(): ?string { return $this->slug; }
    public function getVisible(): bool { return (bool)$this->visible; }

    public static function ensureSetup(): void
    {
        $pdo = (new Conexion())->getConexion();
        $pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(120) NOT NULL,
            slug VARCHAR(150) NOT NULL UNIQUE,
            visible TINYINT(1) NOT NULL DEFAULT 1,
            orden INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB CHARSET=utf8mb4;");
        try { $pdo->exec("ALTER TABLE categorias ADD COLUMN visible TINYINT(1) NOT NULL DEFAULT 1"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE categorias ADD COLUMN orden INT UNSIGNED NOT NULL DEFAULT 0"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE categorias ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"); } catch (Throwable $e) {}
    }

    public static function todas(): array
    {
        $conexion = (new Conexion())->getConexion();
        $st = $conexion->prepare('SELECT id, nombre, slug FROM categorias WHERE visible=1 ORDER BY orden, nombre');
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function todasAdmin(): array
    {
        $conexion = (new Conexion())->getConexion();
        $st = $conexion->prepare('SELECT id, nombre, slug, visible, orden, created_at FROM categorias ORDER BY orden, nombre');
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function insertar(string $nombre, string $slug, int $visible = 1): int
    {
        $conexion = (new Conexion())->getConexion();
        $st = $conexion->prepare('INSERT INTO categorias (nombre, slug, visible, created_at) VALUES (:nombre, :slug, :visible, NOW())');
        $st->execute(['nombre' => $nombre, 'slug' => $slug, 'visible' => $visible]);
        return (int)$conexion->lastInsertId();
    }

    public static function actualizar(int $id, string $nombre, string $slug, int $visible, int $orden = 0): bool
    {
        $conexion = (new Conexion())->getConexion();
        $st = $conexion->prepare('UPDATE categorias SET nombre=:n, slug=:s, visible=:v, orden=:o WHERE id=:id');
        return $st->execute(['n'=>$nombre,'s'=>$slug,'v'=>$visible,'o'=>$orden,'id'=>$id]);
    }

    public static function cambiarVisible(int $id, int $visible): bool
    {
        $conexion = (new Conexion())->getConexion();
        $st = $conexion->prepare('UPDATE categorias SET visible=:v WHERE id=:id');
        return $st->execute(['v'=>$visible,'id'=>$id]);
    }

    public static function eliminar(int $id): bool
    {
        $conexion = (new Conexion())->getConexion();
        try {
            $st = $conexion->prepare('DELETE FROM categorias WHERE id=:id');
            return $st->execute(['id'=>$id]);
        } catch (Throwable $e) {
            return false;
        }
    }
}
?>