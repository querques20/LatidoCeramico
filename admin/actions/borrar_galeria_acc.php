<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/GaleriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$imagen = $_POST['imagen'] ?? '';
if ($id <= 0) { $_SESSION['mensaje_error'] = 'ID inválido'; header('Location: ../index.php?sec=galeria'); exit; }

try {
    GaleriaAdmin::eliminar($id);
    if ($imagen) eliminar_imagen($imagen, 'assets/img/galeria');
    $_SESSION['mensaje_exito'] = 'Imagen eliminada';
} catch (Throwable $e) {
    $_SESSION['mensaje_error'] = 'No se pudo eliminar';
}
header('Location: ../index.php?sec=galeria');
exit;
