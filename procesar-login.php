<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/clases/Usuario.php';
Usuario::ensureSetup();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$next = trim($_POST['next'] ?? '');
if($next !== '' && (!str_starts_with($next, '/') || preg_match('~://~', $next))) {
  $next = '';
}

if($email === '' || $password === '') {
  $_SESSION['flash_error'] = 'Completá email y contraseña.';
  header('Location: /login' . ($next? ('?next=' . urlencode($next)) : ''));
  exit;
}
$user = Usuario::buscarPorEmail($email);
if(!$user || !password_verify($password, $user['password_hash'])) {
  $_SESSION['flash_error'] = 'Credenciales inválidas.';
  header('Location: /login' . ($next? ('?next=' . urlencode($next)) : ''));
  exit;
}
Usuario::registrarLogin((int)$user['id']);
$_SESSION['usuario_publico'] = [
  'id' => (int)$user['id'],
  'nombre' => $user['nombre'],
  'email' => $user['email']
];
header('Location: ' . ($next !== '' ? $next : '/')); exit;