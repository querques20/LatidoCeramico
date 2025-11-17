<?php
session_start();
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/UsuarioAdmin.php';


UsuarioAdmin::ensureSetup();

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$_SESSION['old_email'] = $email;

if ($email === '' || $password === '') {
    $_SESSION['mensaje_error'] = 'Completa ambos campos';
    header('Location: ../login.php');
    exit;
}

$usuario = UsuarioAdmin::buscarPorEmail($email);
if (!$usuario) {
    $_SESSION['mensaje_error'] = 'Credenciales inválidas';
    header('Location: ../login.php');
    exit;
}

if (!password_verify($password, $usuario->getPasswordHash() ?? '')) {
    $_SESSION['mensaje_error'] = 'Credenciales inválidas';
    header('Location: ../login.php');
    exit;
}
$_SESSION['admin_user_id'] = $usuario->getId();
$_SESSION['admin_user_email'] = $usuario->getEmail();
$_SESSION['admin_user_nombre'] = $usuario->getNombre();
unset($_SESSION['old_email']);
$usuario->registrarLogin();
$_SESSION['mensaje_exito'] = 'Bienvenido ' . htmlspecialchars($usuario->getNombre() ?? '');
header('Location: ../index.php');
exit;
?>