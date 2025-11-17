<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/GaleriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) { $_SESSION['mensaje_error'] = 'ID inválido'; header('Location: ../index.php?sec=galeria'); exit; }

try {
    GaleriaAdmin::ensureSetup();
    $current = GaleriaAdmin::buscar($id);
    if (!$current) throw new Exception('Elemento inexistente');


    $orden = isset($_POST['orden']) ? (int)$_POST['orden'] : 0;
    $visible = isset($_POST['visible']) ? (int)$_POST['visible'] : 1;
  

    $nuevoNombre = null;
    if (!empty($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nuevoNombre = subir_imagen($_FILES['imagen'], 'assets/img/galeria');
        if ($nuevoNombre === false) throw new Exception('No se pudo subir la imagen');
    }

    GaleriaAdmin::actualizar($id, $nuevoNombre, $orden, $visible);
    if ($nuevoNombre) {
        
        eliminar_imagen($current['imagen'], 'assets/img/galeria');
    }
    $_SESSION['mensaje_exito'] = 'Cambios guardados';
    header('Location: ../index.php?sec=galeria');
    exit;
} catch (Throwable $e) {
    $_SESSION['mensaje_error'] = 'Error: ' . $e->getMessage();
    header('Location: ../index.php?sec=editar_galeria&id=' . $id);
    exit;
}
