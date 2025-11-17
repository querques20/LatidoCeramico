<?php
session_start();
require_once __DIR__ . '/../classes/Conexion.php';

if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { $_SESSION['mensaje_error'] = 'ID inválido'; header('Location: ../index.php?sec=carritos'); exit; }

try {
  $pdo = (new Conexion())->getConexion();
  
  $pdo->prepare("DELETE FROM carrito_items WHERE carrito_id=:id")->execute([':id'=>$id]);
  $st = $pdo->prepare("DELETE FROM carritos WHERE id=:id");
  $st->execute([':id'=>$id]);
  $_SESSION['mensaje_exito'] = 'Carrito #'.$id.' eliminado (reset).';
} catch (Throwable $e) {
  $_SESSION['mensaje_error'] = 'No se pudo borrar el carrito.';
}
header('Location: ../index.php?sec=carritos');
exit;
