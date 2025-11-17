<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!headers_sent()) {
    ob_start();
}

require_once 'clases/Secciones.php';
require_once 'clases/Config.php';

Secciones::ensureSetup();
Config::ensureSetup();
$seccionSolicitada = trim(strtolower($_GET['seccion'] ?? 'inicio'));
if ($seccionSolicitada === 'index') {
    $seccionSolicitada = 'inicio';
}

$slugsValidos = Secciones::slugsValidos();
if (!in_array($seccionSolicitada, $slugsValidos)) {
    $seccionInvalida = $seccionSolicitada;
    $seccion = '404';
} else {
    $seccion = $seccionSolicitada;
}

$maintenance = Config::get('maintenance_mode', '0') === '1';
if ($maintenance) {
    $seccion = 'mantenimiento';
}

$titulo = $seccion === '404' ? 'Página no encontrada' : ($seccion === 'mantenimiento' ? 'Sitio en mantenimiento' : Secciones::tituloPara($seccion, 'Página'));
$pageTitle = 'Latido Cerámico - ' . $titulo;
?>
<!DOCTYPE html>
<html lang="es">
<?php require 'share/head.php'; ?>

<body class="min-h-dvh relative text-gray-900 antialiased flex flex-col">
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-100 via-purple-100 to-rose-100"></div>
        <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-rose-300/50 blur-3xl"></div>
        <div class="absolute top-1/3 -right-24 h-96 w-96 rounded-full bg-amber-300/50 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/4 h-96 w-96 rounded-full bg-indigo-300/50 blur-3xl"></div>
        <div class="absolute top-20 left-1/2 h-80 w-80 rounded-full bg-emerald-300/40 blur-3xl"></div>
        <div class="absolute bottom-1/3 right-1/3 h-72 w-72 rounded-full bg-pink-300/45 blur-3xl"></div>
    </div>
    
    <a href="#contenido" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-white px-4 py-2 rounded shadow z-50">
        Saltar al contenido principal
    </a>
    
    <?php require 'share/header.php'; ?>
    
    <main id="contenido" class="mx-auto max-w-7xl p-4 sm:p-6 mt-10 bg-white/40 backdrop-blur-md border border-white/30 rounded-2xl shadow flex-1 w-full" role="main">
        <h1 class="font-serif text-3xl mb-4"><?= htmlspecialchars($titulo) ?></h1>
        <?php
    $rutaSeccion = 'secciones/' . $seccion . '.php';
        if (file_exists($rutaSeccion)) {
            require $rutaSeccion;
        } else {
            require 'secciones/404.php';
        }
        ?>
    </main>
    
    <?php require 'share/footer.php'; ?>
    
</body>
</html>