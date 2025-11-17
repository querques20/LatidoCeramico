<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/GaleriaAdmin.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$visible = isset($_POST['visible']) ? (int)$_POST['visible'] : 1;
if ($id <= 0) { $_SESSION['mensaje_error'] = 'ID inválido'; header('Location: ../index.php?sec=galeria'); exit; }

try {
    GaleriaAdmin::setVisible($id, $visible);
    $_SESSION['mensaje_exito'] = 'Visibilidad actualizada';
} catch (Throwable $e) {
    $_SESSION['mensaje_error'] = 'No se pudo cambiar';
}
header('Location: ../index.php?sec=galeria');
exit;
