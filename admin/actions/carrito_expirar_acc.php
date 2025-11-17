<?php
session_start();
require_once __DIR__ . '/../classes/Conexion.php';

if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { $_SESSION['mensaje_error'] = 'ID inválido'; header('Location: ../index.php?sec=carritos'); exit; }

try {
  $pdo = (new Conexion())->getConexion();
  $st = $pdo->prepare("UPDATE carritos SET estado='expirado' WHERE id=:id");
  $st->execute([':id'=>$id]);
  $_SESSION['mensaje_exito'] = 'Carrito #'.$id.' marcado como expirado.';
} catch (Throwable $e) {
  $_SESSION['mensaje_error'] = 'No se pudo expirar el carrito.';
}
header('Location: ../index.php?sec=carrito_detalle&id='.$id);
exit;
