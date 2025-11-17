<?php
require_once __DIR__ . '/Conexion.php';

class Secciones
{
    public static function ensureSetup(): void
    {
        $pdo = (new Conexion())->getConexion();
        $pdo->exec("CREATE TABLE IF NOT EXISTS secciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            slug VARCHAR(100) NOT NULL UNIQUE,
            nombre VARCHAR(100) NOT NULL,
            titulo VARCHAR(180) NULL,
            descripcion TEXT NULL,
            nav TINYINT(1) NOT NULL DEFAULT 0,
            orden DECIMAL(5,2) NOT NULL DEFAULT 999.00,
            activo TINYINT(1) NOT NULL DEFAULT 1,
            view_file VARCHAR(120) NULL,
            meta_title VARCHAR(180) NULL,
            meta_description VARCHAR(255) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB CHARSET=utf8mb4;");

        try {
            $cols = $pdo->query('SHOW COLUMNS FROM secciones')->fetchAll(PDO::FETCH_COLUMN);
            $has = fn(string $c) => in_array($c, $cols, true);

            if (!$has('nav')) {
                $pdo->exec("ALTER TABLE secciones ADD COLUMN nav TINYINT(1) NOT NULL DEFAULT 0");
            }
            if (!$has('orden')) {
                $pdo->exec("ALTER TABLE secciones ADD COLUMN orden DECIMAL(5,2) NOT NULL DEFAULT 999.00");
            }
            if (!$has('activo')) {
                $pdo->exec("ALTER TABLE secciones ADD COLUMN activo TINYINT(1) NOT NULL DEFAULT 1");
            }
            if (!$has('view_file')) {
                $pdo->exec("ALTER TABLE secciones ADD COLUMN view_file VARCHAR(120) NULL");
            }
            if (!$has('meta_title')) {
                $pdo->exec("ALTER TABLE secciones ADD COLUMN meta_title VARCHAR(180) NULL");
            }
            if (!$has('meta_description')) {
                $pdo->exec("ALTER TABLE secciones ADD COLUMN meta_description VARCHAR(255) NULL");
            }
        } catch (\Throwable $e) {
        }


        $count = (int)$pdo->query('SELECT COUNT(*) FROM secciones')->fetchColumn();
        if ($count === 0) {
            $rutaJson = __DIR__ . '/../data/secciones.json';
            if (is_file($rutaJson)) {
                $json = json_decode(@file_get_contents($rutaJson), true) ?: [];
                $ins = $pdo->prepare('INSERT INTO secciones (slug, nombre, titulo, nav, orden, activo, view_file) VALUES (:slug,:nombre,:titulo,:nav,:orden,1,:view)');
                foreach ($json as $row) {
                    $ins->execute([
                        'slug' => $row['slug'],
                        'nombre' => $row['nombre'] ?? $row['slug'],
                        'titulo' => $row['titulo'] ?? null,
                        'nav' => !empty($row['nav']) ? 1 : 0,
                        'orden' => $row['orden'] ?? 999,
                        'view' => ($row['slug'] ?? '') . '.php',
                    ]);
                }
            }
        }
    }

    protected static function cargar(): array
    {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT * FROM secciones WHERE activo=1 ORDER BY orden');
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function slugsValidos(): array
    {
        $slugs = array_map(fn($s) => strtolower($s['slug']), self::cargar());
        $extra = ['login','registro','logout'];
        $todos = array_unique(array_merge($slugs, $extra));
        return $todos ?: ['inicio'];
    }

    public static function enNavegacion(): array
    {
        return array_values(array_filter(self::cargar(), fn($s) => (int)($s['nav'] ?? 0) === 1));
    }

    public static function porSlug(string $slug): ?array
    {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT * FROM secciones WHERE slug = :slug AND activo=1 LIMIT 1');
        $st->execute(['slug' => $slug]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public static function tituloPara(string $slug, string $fallback = 'Página'): string
    {
        $s = self::porSlug($slug);
        return $s['titulo'] ?? $s['nombre'] ?? $fallback;
    }
}
