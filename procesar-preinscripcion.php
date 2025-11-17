<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
$__base = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\');
$nino = trim($_POST['nino'] ?? '');
$edad = (int)($_POST['edad'] ?? 0);
$turno = trim($_POST['turno'] ?? '');
$adulto = trim($_POST['adulto'] ?? '');
$email = trim($_POST['email'] ?? '');
$errores = [];
if ($nino === '') {
    $errores[] = 'El nombre del niño/a es obligatorio';
}
if ($edad < 4 || $edad > 13) {
    $errores[] = 'La edad debe estar entre 4 y 13 años';
}
if ($turno === '') {
    $errores[] = 'Seleccioná un turno';
}
if ($adulto === '') {
    $errores[] = 'El adulto responsable es obligatorio';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'Email inválido';
}
if (empty($errores)) {
    $_SESSION['flash_pre'] = [ 
        'ok' => true, 
        'msg' => "¡Gracias por la preinscripción! Te contactaremos a $email." 
    ];
} else {
    $_SESSION['flash_pre'] = [ 
        'ok' => false, 
        'msg' => implode(' · ', $errores) 
    ];
}
header('Location: ' . $__base . '/?seccion=clases');
exit;