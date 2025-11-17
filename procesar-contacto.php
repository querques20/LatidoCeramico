<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
$__base = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\');
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$mensaje = trim($_POST['mensaje'] ?? '');
$errores = [];
if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'Email inválido';
}
if ($mensaje === '') {
    $errores[] = 'El mensaje es obligatorio';
}
if (empty($errores)) {
    $_SESSION['flash_contacto'] = [ 
        'ok' => true, 
        'msg' => "¡Gracias, $nombre! Te responderemos a $email a la brevedad." 
    ];
} else {
    $_SESSION['flash_contacto'] = [ 
        'ok' => false, 
        'msg' => implode(' · ', $errores) 
    ];
}
header('Location: ' . $__base . '/?seccion=contacto#form-contacto');
exit;