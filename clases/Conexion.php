<?php
class Conexion
{
    private PDO $conexion;

    private static function dsn(): string
    {
        $host = getenv('MYSQL_HOST') ?: 'latidoceramico_db';
        $port = getenv('MYSQL_PORT') ?: '3306';
        $db   = getenv('MYSQL_DATABASE') ?: 'latidoceramico';
        return "mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4";
    }

    public function __construct()
    {
        $user = getenv('MYSQL_USER') ?: 'latido';
        $pass = getenv('MYSQL_PASSWORD') ?: 'latido123';
        try {
            $this->conexion = new PDO(self::dsn(), $user, $pass);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log('[DB] Error de conexión: ' . $e->getMessage());
            self::renderFriendlyDbError();
            exit(1);
        }
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }

    // Presenta un mensaje simple y amable si falla la DB sin exponer datos sensibles.
    // Mantiene compatibilidad CLI devolviendo texto plano.
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
            . '<style>body{font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#faf8ff;}
            .wrap{max-width:680px;margin:12vh auto;padding:24px;border:1px solid #e6e3ef;border-radius:12px;background:rgba(255,255,255,.85);}h1{font-size:20px;margin:0 0 8px;color:#1f2937}p{color:#4b5563;margin:6px 0}</style>'
            . '<body><div class="wrap">'
            . '<h1>Estamos teniendo un problema técnico</h1>'
            . '<p>No pudimos conectar con la base de datos en este momento.</p>'
            . '<p>Por favor volvé a intentar en unos minutos. Si el problema persiste, contactanos.</p>'
            . '</div></body></html>';
    }
}
