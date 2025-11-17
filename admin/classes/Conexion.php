<?php

class Conexion
{
    
    private const HOST = 'latidoceramico_db'; 
    private const DB = 'latidoceramico';
    private const USER = 'latido';
    private const PASS = 'latido123';
    private const DSN = 'mysql:host=' . self::HOST . ';dbname=' . self::DB . ';charset=utf8mb4';
    
    private PDO $conexion;

   
    public function __construct()
    {
        try {
            $this->conexion = new PDO(self::DSN, self::USER, self::PASS);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->ensureOrdenesSetup();
            
            try {
                if (!class_exists('SeccionesAdmin')) {
                    
                    $posible = __DIR__ . DIRECTORY_SEPARATOR . 'SeccionesAdmin.php';
                    if (file_exists($posible)) { require_once $posible; }
                }
                if (class_exists('SeccionesAdmin')) {
                    SeccionesAdmin::ensureSetup($this->conexion);
                }
            } catch (Throwable $e) {
                
            }
        } catch (PDOException $e) {
            error_log('[DB][admin] Error de conexión: ' . $e->getMessage());
            self::renderFriendlyDbError();
            exit(1);
        }
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }

    public function cerrarConexion(): void
    {
        unset($this->conexion);
    }


    private function ensureOrdenesSetup(): void
    {
        $this->conexion->exec("CREATE TABLE IF NOT EXISTS ordenes (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            usuario_id BIGINT UNSIGNED NULL,
            nombre VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            telefono VARCHAR(50) NULL,
            direccion VARCHAR(200) NOT NULL,
            localidad VARCHAR(120) NULL,
            cp VARCHAR(20) NULL,
            notas TEXT NULL,
            subtotal INT UNSIGNED NOT NULL DEFAULT 0,
            estado ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pagado',
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (usuario_id),
            INDEX (estado),
            INDEX (created_at)
        ) ENGINE=InnoDB CHARSET=utf8mb4;");

        $this->conexion->exec("CREATE TABLE IF NOT EXISTS orden_items (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            orden_id BIGINT UNSIGNED NOT NULL,
            producto_id BIGINT UNSIGNED NULL,
            nombre VARCHAR(150) NOT NULL,
            precio INT UNSIGNED NOT NULL DEFAULT 0,
            cantidad INT UNSIGNED NOT NULL DEFAULT 1,
            total INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (orden_id),
            CONSTRAINT fk_orden_items_orden FOREIGN KEY (orden_id) REFERENCES ordenes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8mb4;");

        try { $this->conexion->exec("ALTER TABLE ordenes ADD COLUMN telefono VARCHAR(50) NULL"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE ordenes ADD COLUMN localidad VARCHAR(120) NULL"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE ordenes ADD COLUMN cp VARCHAR(20) NULL"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE ordenes ADD COLUMN notas TEXT NULL"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE ordenes ADD COLUMN subtotal INT UNSIGNED NOT NULL DEFAULT 0"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE ordenes ADD COLUMN estado ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pagado'"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE ordenes ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE orden_items ADD COLUMN total INT UNSIGNED NOT NULL DEFAULT 0"); } catch (Throwable $e) {}
        try { $this->conexion->exec("ALTER TABLE orden_items ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"); } catch (Throwable $e) {}
    }

    private static function renderFriendlyDbError(): void
    {
        if (php_sapi_name() === 'cli') {
            fwrite(STDERR, "No se pudo conectar a la base de datos. Intentalo más tarde.\n");
            return;
        }
        if (!headers_sent()) {
            http_response_code(503);
            header('Content-Type: text/html; charset=utf-8');
        }
        echo '<!DOCTYPE html><html lang="es"><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1" />'
            . '<title>Servicio temporalmente no disponible</title>'
            . '<style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f6f7fb;}
            .wrap{max-width:640px;margin:12vh auto;padding:24px;border:1px solid #e5e7eb;border-radius:12px;background:#fff}h1{font-size:20px;margin:0 0 8px;color:#111827}p{color:#4b5563;margin:6px 0}</style>'
            . '<body><div class="wrap">'
            . '<h1>Panel no disponible momentáneamente</h1>'
            . '<p>Hay un problema al conectar con la base de datos.</p>'
            . '<p>Probá nuevamente en unos minutos o contactá al administrador.</p>'
            . '</div></body></html>';
    }
}
?>