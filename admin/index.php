<?php
/**
 * @author Latido Cerámico
 */

session_start();

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '1');

require_once("classes/Conexion.php");
require_once("classes/SeccionesAdmin.php");
require_once("includes/functions.php");

if (empty($_SESSION['admin_user_id'])) {
    header('Location: login.php');
    exit;
}


$cx = new Conexion();
SeccionesAdmin::ensureSetup($cx->getConexion());


$seccion = isset($_GET['sec']) ? $_GET['sec'] : 'inicio';


if (!in_array($seccion, secciones_validas())) {
    $vista = '404';
    $title_seccion = "Error 404 - Página no encontrada";
} else {
    $vista = $seccion;
    $title_seccion = ucfirst(strtolower($seccion)) . " - Panel de Administración";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <title><?= $title_seccion; ?> - Latido Cerámico</title>
    <style>
        .admin-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .admin-header {
            background: linear-gradient(90deg, #f093fb 0%, #f5576c 100%);
        }
        .card-admin {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-light">
    <?php require_once "includes/header.php"; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php require_once "includes/sidebar.php"; ?>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <?php if (!empty($_SESSION['mensaje_exito'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        <?= $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (!empty($_SESSION['mensaje_error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <?= $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php 
                $archivo_vista = "views/$vista.php";
                if (file_exists($archivo_vista)) {
                    require_once $archivo_vista;
                } else {
                    require_once "views/404.php";
                }
                ?>
            </main>
        </div>
    </div>
    
  
    <?php require_once "includes/footer.php"; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>