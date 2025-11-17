<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../admin/classes/Conexion.php';
require_once __DIR__ . '/../clases/Carrito.php';

$items = [];
$input = file_get_contents('php://input');
if ($input) {
    $decoded = json_decode($input, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        if (isset($decoded['items']) && is_array($decoded['items'])) {
            $items = $decoded['items'];
        } elseif (is_array($decoded)) {
            $items = $decoded;
        }
    }
}
if (!$items) {
    if (isset($_POST['items']) && is_array($_POST['items'])) {
        $items = $_POST['items'];
    } elseif (isset($_POST['carrito_json'])) {
        try { $items = json_decode($_POST['carrito_json'], true) ?: []; } catch (Throwable $e) { $items = []; }
    } elseif (isset($_POST['items'])) {
        try { $items = json_decode($_POST['items'], true) ?: []; } catch (Throwable $e) { $items = []; }
    }
}
if (!$items && $input) {
    $parsed = [];
    parse_str($input, $parsed);
    if (isset($parsed['items'])) {
        try { $items = json_decode($parsed['items'], true) ?: []; } catch (Throwable $e) { $items = []; }
    } elseif (isset($parsed['carrito_json'])) {
        try { $items = json_decode($parsed['carrito_json'], true) ?: []; } catch (Throwable $e) { $items = []; }
    }
}
$norm = [];
if (is_array($items)) {
    foreach ($items as $it) {
        $pid = (int)($it['id'] ?? $it['producto_id'] ?? 0);
        $cant = max(1, (int)($it['cantidad'] ?? 1));
        if ($pid > 0) $norm[] = ['producto_id'=>$pid, 'cantidad'=>$cant];
    }
}
if (!empty($norm)) {
    try {
        $pdo = (new Conexion())->getConexion();
        $ids = array_column($norm, 'producto_id');
        $in  = implode(',', array_fill(0, count($ids), '?'));
        $st  = $pdo->prepare("SELECT id, stock FROM productos WHERE id IN ($in)");
        $st->execute($ids);
        $stocks = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) { $stocks[(int)$r['id']] = (int)$r['stock']; }
        $aj = [];
        foreach ($norm as &$n) {
            $disp = $stocks[$n['producto_id']] ?? 0;
            if ($disp <= 0) { $n['cantidad'] = 0; $aj[] = ['id'=>$n['producto_id'],'disp'=>0]; }
            else if ($n['cantidad'] > $disp) { $n['cantidad'] = $disp; $aj[] = ['id'=>$n['producto_id'],'disp'=>$disp]; }
        }
        unset($n);
        $norm = array_values(array_filter($norm, fn($x) => $x['cantidad'] > 0));
    } catch (Throwable $e) {
    }
}

$usuarioId = isset($_SESSION['usuario_publico']['id']) ? (int)$_SESSION['usuario_publico']['id'] : null;
$sessionId = session_id();

Carrito::ensureSetup();
$carritoId = Carrito::upsertActivoPorSession($sessionId, $norm, $usuarioId);

$count = 0; foreach ($norm as $i) { $count += (int)$i['cantidad']; }
echo json_encode([
    'ok' => $carritoId > 0,
    'carrito_id' => $carritoId,
    'count' => $count,
    'session_id' => $sessionId,
]);
