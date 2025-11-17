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

    if (empty($_POST['nombre']) || empty($_POST['descripcion']) || !isset($_POST['precio']) || empty($_POST['categoria_id'])) {
        throw new Exception('Campos obligatorios faltantes');
    }

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = (float) $_POST['precio'];
    $categoria_id = (int) $_POST['categoria_id'];
    $stock = isset($_POST['stock']) ? (int) $_POST['stock'] : 0;
    $sku = isset($_POST['sku']) && $_POST['sku'] !== '' ? substr(trim($_POST['sku']), 0, 64) : null;
    $activo = $producto->getActivo() ? 1 : 0;

    if ($precio <= 0) { throw new Exception('El precio debe ser mayor que 0'); }
    if ($stock < 0) { throw new Exception('Stock inválido'); }

    $slug = $producto->getSlug();
    if ($slug === null || generar_slug($nombre) !== $slug) {
        $pdo = (new Conexion())->getConexion();
        $slug = generar_slug_unico($nombre, $pdo);
    }

    $nombre_imagen_nueva = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK && !empty($_FILES['foto']['name'])) {
        $nombre_imagen_nueva = subir_imagen($_FILES['foto']);
        if ($nombre_imagen_nueva === false) {
            throw new Exception('No se pudo subir la nueva imagen');
        }
    }

    $actualizado = $producto->actualizar(
        $nombre,
        $slug,
        $descripcion,
        $precio,
        $categoria_id,
        $nombre_imagen_nueva,
        $stock,
        $sku,
        $activo
    );

    if (!$actualizado) {
        if ($nombre_imagen_nueva) { eliminar_imagen($nombre_imagen_nueva); }
        throw new Exception('Error al actualizar el producto en la base de datos');
    }


    if ($nombre_imagen_nueva) {
        if ($producto->getImagenPrincipal()) {
            eliminar_imagen($producto->getImagenPrincipal());
        }
    }

    $_SESSION['mensaje_exito'] = "Producto '$nombre' actualizado correctamente";
    header('Location: ../index.php?sec=productos');
    exit;

} catch (Exception $e) {
    $_SESSION['mensaje_error'] = $e->getMessage();
    header('Location: ../index.php?sec=editar_producto&id=' . ($_POST['id'] ?? 0));
    exit;
}
