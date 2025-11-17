<?php
session_start();
if (empty($_SESSION['admin_user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/UsuarioAdmin.php';

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['mensaje_error'] = 'ID inválido';
    header('Location: ../index.php?sec=usuarios');
    exit;
}
if ($id === (int)($_SESSION['admin_user_id'] ?? 0)) {
    $_SESSION['mensaje_error'] = 'No podés borrarte a vos mismo';
    header('Location: ../index.php?sec=usuarios');
    exit;
}

if (UsuarioAdmin::eliminar($id)) {
    $_SESSION['mensaje_exito'] = 'Usuario eliminado';
} else {
    $_SESSION['mensaje_error'] = 'No se pudo eliminar';
}
header('Location: ../index.php?sec=usuarios');
exit;
?>