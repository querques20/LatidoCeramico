<?php
session_start();
require_once("../classes/Conexion.php");
require_once("../classes/ProductoAdmin.php");
require_once("../includes/functions.php");

try {
    if (empty($_POST['id'])) {
        throw new Exception('ID inválido');
    }

    $id = (int) $_POST['id'];
    $producto = ProductoAdmin::obtenerPorId($id);
    if (!$producto) {
        throw new Exception('Producto no encontrado');
    }

    $imagen = $producto->getImagenPrincipal();

    if (!$producto->eliminar()) {
        throw new Exception('No se pudo borrar el producto en la base de datos');
    }


    if (!empty($imagen)) {
        eliminar_imagen($imagen);
    }

    $_SESSION['mensaje_exito'] = 'Producto eliminado correctamente';
    header('Location: ../index.php?sec=productos');
    exit;

} catch (Exception $e) {
    $_SESSION['mensaje_error'] = $e->getMessage();
    header('Location: ../index.php?sec=productos');
    exit;
}
