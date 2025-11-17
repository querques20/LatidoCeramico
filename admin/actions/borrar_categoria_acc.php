<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/CategoriaAdmin.php';

$id = (int)($_POST['id'] ?? 0);
if ($id<=0) { $_SESSION['mensaje_error']='ID inválido'; header('Location: ../index.php?sec=categorias'); exit; }

if (CategoriaAdmin::eliminar($id)) {
    $_SESSION['mensaje_exito'] = 'Categoría eliminada';
} else {
    $_SESSION['mensaje_error'] = 'No se pudo eliminar (puede estar en uso por productos)';
}
header('Location: ../index.php?sec=categorias');
exit;
