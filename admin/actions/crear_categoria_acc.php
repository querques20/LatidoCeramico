<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/CategoriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';

$nombre = trim($_POST['nombre'] ?? '');
$visible = (int)($_POST['visible'] ?? 1);
if ($nombre === '') {
    $_SESSION['mensaje_error'] = 'Ingresá un nombre de categoría';
    header('Location: ../index.php?sec=categorias');
    exit;
}
$slug = generar_slug($nombre);
CategoriaAdmin::ensureSetup();
CategoriaAdmin::insertar($nombre, $slug, $visible ? 1 : 0);
$_SESSION['mensaje_exito'] = 'Categoría creada';
header('Location: ../index.php?sec=categorias');
exit;
