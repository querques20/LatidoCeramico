<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/GaleriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';

try {
    GaleriaAdmin::ensureSetup();
    $orden = isset($_POST['orden']) ? (int)$_POST['orden'] : 0;
    $visible = isset($_POST['visible']) ? (int)$_POST['visible'] : 1;
   

    if (!isset($_FILES['imagen'])) throw new Exception('Falta la imagen');
    $nombre = subir_imagen($_FILES['imagen'], 'assets/img/galeria');
    if ($nombre === false) throw new Exception('No se pudo subir la imagen');

    GaleriaAdmin::crear($nombre, $orden, $visible);
    $_SESSION['mensaje_exito'] = 'Imagen creada';
} catch (Throwable $e) {
    $_SESSION['mensaje_error'] = 'Error: ' . $e->getMessage();
}
header('Location: ../index.php?sec=galeria');
exit;
