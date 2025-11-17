<?php
class Carrito
{
    public static function ensureSetup(): void
    {
        try {
            $pdo = self::pdo();
            $pdo->exec("CREATE TABLE IF NOT EXISTS carritos (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                usuario_id BIGINT UNSIGNED NULL,
                session_id VARCHAR(100) NULL UNIQUE,
                estado ENUM('activo','convertido','expirado') NOT NULL DEFAULT 'activo',
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX(usuario_id), INDEX(estado)
            ) ENGINE=InnoDB CHARSET=utf8mb4;");

            $pdo->exec("CREATE TABLE IF NOT EXISTS carrito_items (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                carrito_id BIGINT UNSIGNED NOT NULL,
                producto_id BIGINT UNSIGNED NOT NULL,
                cantidad INT UNSIGNED NOT NULL DEFAULT 1,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                INDEX(carrito_id), INDEX(producto_id),
                CONSTRAINT fk_carrito_items_carrito FOREIGN KEY (carrito_id) REFERENCES carritos(id) ON DELETE CASCADE
            ) ENGINE=InnoDB CHARSET=utf8mb4;");
        } catch (Throwable $e) {
        }
    }

    public static function upsertActivoPorSession(string $sessionId, array $items, ?int $usuarioId = null): int
    {
        if (empty($sessionId)) return 0;
        try {
            $pdo = self::pdo();
            $sel = $pdo->prepare("SELECT id, estado FROM carritos WHERE session_id = :sid LIMIT 1");
            $sel->execute([':sid' => $sessionId]);
            $row = $sel->fetch();
            if ($row) {
                $carritoId = (int)$row['id'];
                if (($row['estado'] ?? '') === 'expirado') {
                    return $carritoId;
                }
                if (empty($items)) {
                    $pdo->prepare("DELETE FROM carritos WHERE id=:id")->execute([':id'=>$carritoId]);
                    return 0;
                }
                $pdo->prepare("UPDATE carritos SET usuario_id = :uid, estado='activo' WHERE id=:id")
                    ->execute([':uid'=>$usuarioId, ':id'=>$carritoId]);
            } else {
                
                if (empty($items)) return 0;
                $ins = $pdo->prepare("INSERT INTO carritos (usuario_id, session_id, estado) VALUES (:uid, :sid, 'activo')");
                $ins->execute([':uid'=>$usuarioId, ':sid'=>$sessionId]);
                $carritoId = (int)$pdo->lastInsertId();
            }

            $pdo->prepare("DELETE FROM carrito_items WHERE carrito_id = :id")->execute([':id'=>$carritoId]);
            $insItem = $pdo->prepare("INSERT INTO carrito_items (carrito_id, producto_id, cantidad) VALUES (:cid, :pid, :cant)");
            foreach ($items as $it) {
                $pid = (int)($it['id'] ?? $it['producto_id'] ?? 0);
                $cant = (int)($it['cantidad'] ?? 1);
                if ($pid > 0 && $cant > 0) $insItem->execute([':cid'=>$carritoId, ':pid'=>$pid, ':cant'=>$cant]);
            }
            return $carritoId;
        } catch (Throwable $e) {
            return 0;
        }
    }


    private static function pdo(): PDO
    {
        if (class_exists('Conexion')) {
            $cx = new Conexion();
            return $cx->getConexion();
        }
        $dsn = 'mysql:host=latidoceramico_db;dbname=latidoceramico;charset=utf8mb4';
        return new PDO($dsn, 'latido', 'latido123', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}
?>
