<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../admin/classes/Conexion.php';

try {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $currentSession = session_id();

    $pdo = (new Conexion())->getConexion();
    $sessionId = isset($_GET['session_id']) ? trim($_GET['session_id']) : '';

    if ($sessionId !== '') {
        $st = $pdo->prepare("SELECT * FROM carritos WHERE session_id = :sid ORDER BY id DESC LIMIT 1");
        $st->execute([':sid'=>$sessionId]);
    } elseif (!empty($currentSession)) {
        $st = $pdo->prepare("SELECT * FROM carritos WHERE session_id = :sid ORDER BY id DESC LIMIT 1");
        $st->execute([':sid'=>$currentSession]);
    } else {
        $st = $pdo->query("SELECT * FROM carritos ORDER BY id DESC LIMIT 1");
    }
    $carrito = $st->fetch(PDO::FETCH_ASSOC) ?: null;

    if (!$carrito) {
        echo json_encode(['ok'=>false, 'error'=>'no_carrito', 'session_id_used' => $sessionId ?: $currentSession]);
        exit;
    }

    $it = $pdo->prepare("SELECT ci.producto_id, ci.cantidad FROM carrito_items ci WHERE ci.carrito_id = :cid ORDER BY ci.id ASC");
    $it->execute([':cid'=>$carrito['id']]);
    $items = $it->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['ok'=>true, 'session_id_used' => $sessionId ?: $currentSession, 'carrito'=>$carrito, 'items'=>$items]);
} catch (Throwable $e) {
    echo json_encode(['ok'=>false, 'error'=>'exception']);
}
?>
