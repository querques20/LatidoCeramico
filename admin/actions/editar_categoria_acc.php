<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/CategoriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';

$id = (int)($_POST['id'] ?? 0);
$nombre = trim($_POST['nombre'] ?? '');
$slug = trim($_POST['slug'] ?? '');
$visible = (int)($_POST['visible'] ?? 1);
$orden = (int)($_POST['orden'] ?? 0);
if ($id<=0 || $nombre==='') {
    $_SESSION['mensaje_error'] = 'Datos insuficientes para editar';
    header('Location: ../index.php?sec=categorias');
    exit;
}
if ($slug==='') { $slug = generar_slug($nombre); }
CategoriaAdmin::ensureSetup();
if (CategoriaAdmin::actualizar($id,$nombre,$slug,$visible?1:0,$orden)) {
    $_SESSION['mensaje_exito'] = 'Categoría actualizada';
} else {
    $_SESSION['mensaje_error'] = 'No se pudo actualizar la categoría';
}
header('Location: ../index.php?sec=categorias');
exit;
