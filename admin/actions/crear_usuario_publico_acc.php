<?php
session_start();
if (empty($_SESSION['admin_user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/UsuarioPublicoAdmin.php';

$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($nombre === '' || $apellido === '' || $telefono === '' || $email === '' || $password === '') {
    $_SESSION['mensaje_error'] = 'Completá todos los campos del usuario del sitio';
    header('Location: ../index.php?sec=usuarios');
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['mensaje_error'] = 'Email inválido';
    header('Location: ../index.php?sec=usuarios');
    exit;
}
if (strlen($password) < 6) {
    $_SESSION['mensaje_error'] = 'La contraseña debe tener al menos 6 caracteres';
    header('Location: ../index.php?sec=usuarios');
    exit;
}
if (UsuarioPublicoAdmin::existeEmail($email)) {
    $_SESSION['mensaje_error'] = 'Ya existe un usuario del sitio con ese email';
    header('Location: ../index.php?sec=usuarios');
    exit;
}

if (UsuarioPublicoAdmin::crear($nombre, $apellido, $telefono, $email, $password)) {
    $_SESSION['mensaje_exito'] = 'Usuario del sitio creado';
} else {
    $_SESSION['mensaje_error'] = 'No se pudo crear el usuario del sitio';
}
header('Location: ../index.php?sec=usuarios');
exit;
