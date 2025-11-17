<?php
if (session_status() === PHP_SESSION_NONE) { 
    session_start(); 
}
$__base = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\');
$nombre = trim($_POST['nombre'] ?? '');
$email = trim($_POST['email'] ?? '');
$direccion = trim($_POST['direccion'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$localidad = trim($_POST['localidad'] ?? '');
$cp = trim($_POST['cp'] ?? '');
$notas = trim($_POST['notas'] ?? '');
$carrito_json = $_POST['carrito_json'] ?? '[]';
$tarjeta_numero = preg_replace('/\D+/', '', $_POST['tarjeta_numero'] ?? '');
$tarjeta_nombre = trim($_POST['tarjeta_nombre'] ?? '');
$tarjeta_vencimiento = trim($_POST['tarjeta_vencimiento'] ?? '');
$tarjeta_cvv = preg_replace('/\D+/', '', $_POST['tarjeta_cvv'] ?? '');
if (!empty($_SESSION['usuario_publico'])) {
    $email = $_SESSION['usuario_publico']['email'] ?? $email;
    if (empty($nombre)) {
        $nombre = $_SESSION['usuario_publico']['nombre'] ?? $nombre;
    }
}
$errores = [];
if ($nombre === '') {
    $errores[] = 'El nombre es obligatorio';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = 'Email inválido';
}
if ($direccion === '') {
    $errores[] = 'La dirección es obligatoria';
}
if ($tarjeta_numero === '' || strlen($tarjeta_numero) < 13) {
    $errores[] = 'Número de tarjeta inválido';
}
if ($tarjeta_nombre === '') {
    $errores[] = 'El nombre de la tarjeta es obligatorio';
}
if ($tarjeta_vencimiento === '' || !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $tarjeta_vencimiento)) {
    $errores[] = 'Vencimiento inválido (MM/AA)';
}
if ($tarjeta_cvv === '' || strlen($tarjeta_cvv) < 3) {
    $errores[] = 'CVV inválido';
}
$items = [];
try { 
    $items = json_decode($carrito_json, true) ?: []; 
} catch(Exception $e) { 
    $items = []; 
}
$subtotal = 0;
foreach($items as $it) { 
    $subtotal += ($it['precio'] ?? 0) * ($it['cantidad'] ?? 0); 
}
if (!empty($errores) || count($items) === 0) {
    $msg = [];
    if (count($items) === 0) {
        $msg[] = 'Tu carrito está vacío';
    }
    if (!empty($errores)) {
        $msg = array_merge($msg, $errores);
    }
    $_SESSION['flash_checkout'] = [ 
        'ok' => false, 
        'msg' => implode(' · ', $msg) 
    ];
    header('Location: /checkout');
    exit;
}
require_once __DIR__ . '/clases/Ordenes.php';
Ordenes::ensureSetup();
$usuarioId = isset($_SESSION['usuario_publico']['id']) ? (int)$_SESSION['usuario_publico']['id'] : null;
try {
    $ordenId = Ordenes::crearOrden([
        'nombre' => $nombre,
        'email' => $email,
        'telefono' => $telefono,
        'direccion' => $direccion,
        'localidad' => $localidad,
        'cp' => $cp,
        'notas' => $notas,
    ], $items, (int)$subtotal, $usuarioId);
} catch (Throwable $e) {
    $_SESSION['flash_checkout'] = [
        'ok' => false,
        'msg' => 'No pudimos completar la compra: ' . $e->getMessage()
    ];
    header('Location: /checkout');
    exit;
}
$_SESSION['orden'] = [
    'id' => $ordenId,
    'cliente' => [
        'nombre' => $nombre,
        'email' => $email,
    ],
    'items' => $items,
    'subtotal' => $subtotal,
    'fecha' => date('Y-m-d H:i:s'),
];
$_SESSION['flash_checkout'] = [ 
    'ok' => true, 
    'msg' => '¡Gracias por tu compra! Confirmamos tu pedido.' 
];
header('Location: /confirmacion');
exit;