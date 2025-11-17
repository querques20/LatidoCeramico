<?php
session_start();
require_once __DIR__ . '/../classes/Conexion.php';


if (empty($_SESSION['admin_user_id'])) {
    header('Location: ../login.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$estado = $_POST['estado'] ?? '';
$permitidos = ['pendiente','pagado','cancelado'];

$next = '../index.php?sec=orden_detalle&id=' . $id;

if ($id <= 0 || !in_array($estado, $permitidos)) {
    $_SESSION['mensaje_error'] = 'Datos inválidos para cambiar estado.';
    header('Location: ' . $next);
    exit;
}

try {
    $pdo = (new Conexion())->getConexion();
    $pdo->beginTransaction();
    $cur = $pdo->prepare('SELECT estado FROM ordenes WHERE id = :id FOR UPDATE');
    $cur->execute([':id'=>$id]);
    $prev = $cur->fetchColumn();
    if ($prev === false) { throw new RuntimeException('Orden inexistente'); }

    if ($prev !== $estado) {
        $st = $pdo->prepare('UPDATE ordenes SET estado = :e WHERE id = :id');
        $st->execute([':e'=>$estado, ':id'=>$id]);

        if ($estado === 'cancelado' && $prev === 'pagado') {
            $its = $pdo->prepare('SELECT producto_id, cantidad FROM orden_items WHERE orden_id = :id AND producto_id IS NOT NULL');
            $its->execute([':id'=>$id]);
            $rows = $its->fetchAll(PDO::FETCH_ASSOC);
            $upd = $pdo->prepare('UPDATE productos SET stock = stock + :c WHERE id = :p');
            foreach($rows as $r){
                $upd->execute([':c'=>(int)$r['cantidad'], ':p'=>(int)$r['producto_id']]);
            }
        } elseif ($estado === 'pagado' && $prev === 'pendiente') {
            $its = $pdo->prepare('SELECT producto_id, cantidad FROM orden_items WHERE orden_id = :id AND producto_id IS NOT NULL');
            $its->execute([':id'=>$id]);
            $rows = $its->fetchAll(PDO::FETCH_ASSOC);
            $upd = $pdo->prepare('UPDATE productos SET stock = GREATEST(0, stock - :c) WHERE id = :p');
            foreach($rows as $r){
                $upd->execute([':c'=>(int)$r['cantidad'], ':p'=>(int)$r['producto_id']]);
            }
        }
    }
    $pdo->commit();
    $_SESSION['mensaje_exito'] = 'Estado actualizado a "' . $estado . '".';
} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) { $pdo->rollBack(); }
    $_SESSION['mensaje_error'] = 'No se pudo actualizar el estado.';
}

header('Location: ' . $next);
exit;
