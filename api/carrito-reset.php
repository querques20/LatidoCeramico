<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../admin/classes/Conexion.php';

try {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }
    $sid = isset($_GET['session_id']) && $_GET['session_id'] !== '' ? $_GET['session_id'] : session_id();
    if (empty($sid)) { echo json_encode(['ok'=>false, 'error'=>'no_session']); exit; }

    $pdo = (new Conexion())->getConexion();
    $st = $pdo->prepare('SELECT id FROM carritos WHERE session_id = :sid LIMIT 1');
    $st->execute([':sid'=>$sid]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row) { echo json_encode(['ok'=>true, 'deleted'=>0, 'session_id'=>$sid]); exit; }

    $del = $pdo->prepare('DELETE FROM carritos WHERE id=:id');
    $del->execute([':id'=>$row['id']]);

    echo json_encode(['ok'=>true, 'deleted'=>1, 'session_id'=>$sid]);
} catch (Throwable $e) {
    echo json_encode(['ok'=>false, 'error'=>'exception']);
}
?>
