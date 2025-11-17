<?php
session_start();
require_once("../classes/Conexion.php");
require_once("../classes/ProductoAdmin.php");
require_once("../classes/CategoriaAdmin.php");
require_once("../includes/functions.php");

try {
    if (empty($_POST['nombre']) || empty($_POST['descripcion']) || !isset($_POST['precio']) || empty($_POST['categoria_id'])) {
        throw new Exception("Campos obligatorios faltantes");
    }

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $categoria_id = (int) $_POST['categoria_id'];
    $precio = (float) $_POST['precio'];
    $stock = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
    $sku = isset($_POST['sku']) && $_POST['sku'] !== '' ? substr(trim($_POST['sku']), 0, 64) : null;
    $activo = 1;

    if ($precio <= 0) { throw new Exception("El precio debe ser mayor que 0"); }
    if ($stock < 0) { throw new Exception("Stock inválido"); }

    $pdo = (new Conexion())->getConexion();
    $slug = generar_slug_unico($nombre, $pdo);


    if (!isset($_FILES['foto'])) {
        throw new Exception("Debe seleccionar una imagen");
    }

    $nombre_imagen = subir_imagen($_FILES['foto']);
    if ($nombre_imagen === false) {
        throw new Exception("No se pudo subir la imagen. Verifique el formato y el tamaño");
    }


    $idNuevo = ProductoAdmin::insertar(
        $nombre,
        $slug,
        $descripcion,
        $precio,
        $categoria_id,
        $nombre_imagen,
        $stock,
        $sku,
        $activo
    );

    if ($idNuevo <= 0) {
        eliminar_imagen($nombre_imagen);
        throw new Exception("Error al guardar el producto en la base de datos");
    }

    $_SESSION['mensaje_exito'] = "Producto '$nombre' creado correctamente";
    header('Location: ../index.php?sec=productos');
    exit;

} catch (Exception $e) {
    $_SESSION['mensaje_error'] = $e->getMessage();
    header('Location: ../index.php?sec=crear_producto');
    exit;
}
?>