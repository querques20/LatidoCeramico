<?php
session_start();
if (empty($_SESSION['admin_user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/UsuarioPublicoAdmin.php';

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    $_SESSION['mensaje_error'] = 'ID inválido';
    header('Location: ../index.php?sec=usuarios');
    exit;
}

if (UsuarioPublicoAdmin::eliminar($id)) {
    $_SESSION['mensaje_exito'] = 'Usuario registrado eliminado';
} else {
    $_SESSION['mensaje_error'] = 'No se pudo eliminar';
}
header('Location: ../index.php?sec=usuarios');
exit;
