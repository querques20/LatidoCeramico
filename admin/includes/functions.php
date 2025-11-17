<?php

function secciones_validas(): array
{
    try {
        if (class_exists('Conexion')) {
            $cx = new Conexion();
            $pdo = $cx->getConexion();
            if (class_exists('SeccionesAdmin')) {
                $sec = new SeccionesAdmin($pdo);
                $slugs = $sec->obtenerSlugsValidos();
                if (!empty($slugs)) return $slugs;
            }
        }
    } catch (Throwable $e) {}
    return [
        "inicio","productos","categorias","usuarios","ordenes","orden_detalle","galeria","editar_galeria",
        "crear_producto","editar_producto","borrar_producto",
        "crear_categoria","editar_categoria","borrar_categoria","configuracion"
    ];
}

function secciones_menu(): array
{
    try {
        if (class_exists('Conexion')) {
            $cx = new Conexion();
            $pdo = $cx->getConexion();
            if (class_exists('SeccionesAdmin')) {
                $sec = new SeccionesAdmin($pdo);
                $menu = $sec->obtenerMenu();
                if (!empty($menu)) return $menu;
            }
        }
    } catch (Throwable $e) {}
    return [
        "inicio" => ["nombre" => "Dashboard", "icono" => "house-door"],
        "productos" => ["nombre" => "Productos", "icono" => "box-seam"],
        "categorias" => ["nombre" => "Categorías", "icono" => "tags"],
        "usuarios" => ["nombre" => "Usuarios", "icono" => "people"],
        "ordenes" => ["nombre" => "Órdenes", "icono" => "receipt"],
        "galeria" => ["nombre" => "Galería", "icono" => "images"],
        "configuracion" => ["nombre" => "Configuración", "icono" => "gear"]
    ];
}

function formatear_precio(float $precio): string
{
    return '$' . number_format($precio, 0, ',', '.');
}

function subir_imagen(array $archivo, ?string $directorio = null): string|false
{
   
    $baseProyecto = null;
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $baseProyecto = rtrim($_SERVER['DOCUMENT_ROOT'], '/\\');
    }
    if (!$baseProyecto || !is_dir($baseProyecto)) {
        $baseProyecto = realpath(__DIR__ . '/../../') ?: (__DIR__ . '/../../');
        $baseProyecto = rtrim($baseProyecto, '/\\');
    }

    if ($directorio === null) {
        $directorio = $baseProyecto . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'productos' . DIRECTORY_SEPARATOR;
    } else {
       
        $esAbsoluto = str_starts_with($directorio, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:\\|\//', $directorio);
        $directorio = $esAbsoluto
            ? rtrim($directorio, '/\\') . DIRECTORY_SEPARATOR
            : ($baseProyecto . DIRECTORY_SEPARATOR . trim($directorio, '/\\') . DIRECTORY_SEPARATOR);
    }


    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
   
    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'webp'];
    $nombre_original = explode(".", $archivo['name']);
    $extension = strtolower(end($nombre_original));
    
   
    if (!in_array($extension, $extensiones_permitidas)) {
        return false;
    }
    
    
    $nombre_nuevo = time() . '_' . rand(1000, 9999) . '.' . $extension;
    
   
    if (!is_dir($directorio)) {
        if (!@mkdir($directorio, 0755, true) && !is_dir($directorio)) {
            return false;
        }
    }
    
  
    if (move_uploaded_file($archivo['tmp_name'], $directorio . $nombre_nuevo)) {
        @chmod($directorio . $nombre_nuevo, 0644);
        return $nombre_nuevo;
    }
    
    return false;
}

function eliminar_imagen(string $nombre_imagen, ?string $directorio = null): bool
{
    $archivo = basename($nombre_imagen);

    $docRoot = !empty($_SERVER['DOCUMENT_ROOT']) ? rtrim($_SERVER['DOCUMENT_ROOT'], '/\\') : '';
    $baseProyecto = realpath(__DIR__ . '/../../') ?: (__DIR__ . '/../../');
    $baseProyecto = rtrim($baseProyecto, '/\\');

    $bases = [];
    $candidatos = [];
    if ($directorio !== null) {
        $esAbsoluto = str_starts_with($directorio, DIRECTORY_SEPARATOR) || preg_match('/^[A-Za-z]:\\|\//', $directorio);
        $baseDir = $esAbsoluto ? rtrim($directorio, '/\\') : ($baseProyecto . DIRECTORY_SEPARATOR . trim($directorio, '/\\'));
        $candidatos[] = $baseDir . DIRECTORY_SEPARATOR . $archivo;
    } else {
        if ($docRoot) {
            $candidatos[] = $docRoot . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'productos' . DIRECTORY_SEPARATOR . $archivo;
        }
        $candidatos[] = $baseProyecto . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'productos' . DIRECTORY_SEPARATOR . $archivo;
    }
    if (!empty($nombre_imagen)) {
        if (preg_match('/^[A-Za-z]:\\\\|^\//', $nombre_imagen)) {
            if (substr($nombre_imagen, 0, 1) === '/') {
                if ($docRoot) { $candidatos[] = rtrim($docRoot, '/\\') . $nombre_imagen; }
                $candidatos[] = $baseProyecto . DIRECTORY_SEPARATOR . ltrim($nombre_imagen, '/\\');
            } else {
                $candidatos[] = $nombre_imagen;
            }
        }
    }

    if (preg_match('#assets[\\/]+img[\\/]+(.+)#i', $nombre_imagen, $m)) {
        $rel = $m[0];
           if ($docRoot) { $candidatos[] = rtrim($docRoot,'/\\') . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rel); }
           $candidatos[] = $baseProyecto . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rel);
    }


    $candidatos = array_values(array_unique($candidatos));

    $puedeBorrar = true;
    try {
        if (class_exists('Conexion')) {
            $pdo = (new Conexion())->getConexion();
            $st = $pdo->prepare('SELECT COUNT(*) FROM productos WHERE imagen_principal = :n OR imagen_principal = :orig');
            $st->execute(['n' => $archivo, 'orig' => $nombre_imagen]);
            $count = (int)$st->fetchColumn();
            if ($count > 1) { $puedeBorrar = false; }
        }
    } catch (Throwable $e) {}

    $exito = false;
    if ($puedeBorrar) {
        foreach ($candidatos as $ruta) {
            if (is_file($ruta)) {
                $exito = @unlink($ruta) || $exito;
            }
        }
    }
    return $puedeBorrar ? ($exito || true) : true;
}

function generar_slug(string $texto): string
{
    $slug = strtolower(trim($texto));
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug);
    $slug = preg_replace('/[^a-z0-9]+/','-', $slug);
    $slug = preg_replace('/-+/','-', $slug);
    return trim($slug, '-');
}

function generar_slug_unico(string $nombreBase, PDO $pdo): string {
    $base = generar_slug($nombreBase);
    $slug = $base;
    $i = 2;
    $st = $pdo->prepare('SELECT COUNT(*) FROM productos WHERE slug = :slug');
    while (true) {
        $st->execute(['slug' => $slug]);
        $existe = (int)$st->fetchColumn();
        if ($existe === 0) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
        if ($i > 1000) {
            throw new Exception('No se pudo generar un slug único');
        }
    }
}
?>