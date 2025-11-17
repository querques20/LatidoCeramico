<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/clases/Usuario.php';
Usuario::ensureSetup();

$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$email = trim($_POST['email'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$password = $_POST['password'] ?? '';
$next = trim($_POST['next'] ?? '');
if($next !== '' && (!str_starts_with($next, '/') || preg_match('~://~', $next))) {
  $next = '';
}

if($nombre === '' || $apellido === '' || $email === '' || $telefono === '' || $password === '') {
  $_SESSION['flash_error'] = 'Todos los campos son obligatorios.';
  header('Location: /registro' . ($next? ('?next=' . urlencode($next)) : '')); exit;
}
if(!preg_match('/^[^@\s]+@[^@\s]+\.[^@\s]+$/', $email)) {
  $_SESSION['flash_error'] = 'Email inválido.';
  header('Location: /registro' . ($next? ('?next=' . urlencode($next)) : '')); exit;
}
if(strlen($password) < 4) {
  $_SESSION['flash_error'] = 'La contraseña debe tener al menos 4 caracteres.';
  header('Location: /registro' . ($next? ('?next=' . urlencode($next)) : '')); exit;
}
if(Usuario::existeEmail($email)) {
  $_SESSION['flash_error'] = 'Ya existe una cuenta con ese email.';
  header('Location: /registro' . ($next? ('?next=' . urlencode($next)) : '')); exit;
}
Usuario::crear($nombre, $apellido, $email, $password, $telefono);
$user = Usuario::buscarPorEmail($email);
$_SESSION['usuario_publico'] = [
  'id' => (int)$user['id'],
  'nombre' => $user['nombre'],
  'email' => $user['email']
];
header('Location: ' . ($next !== '' ? $next : '/')); exit;