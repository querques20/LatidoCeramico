<?php
if (session_status() === PHP_SESSION_NONE) session_start();
unset($_SESSION['usuario_publico']);
$next = isset($_GET['next']) ? trim($_GET['next']) : '';
header('Location: ' . ($next !== '' ? $next : '/'));
exit;