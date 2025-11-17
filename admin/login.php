<?php
session_start();

if (!empty($_SESSION['admin_user_id'])) {
    header('Location: index.php');
    exit;
}
require_once __DIR__ . '/classes/Conexion.php';
require_once __DIR__ . '/classes/UsuarioAdmin.php';

UsuarioAdmin::ensureSetup();
$old_email = $_SESSION['old_email'] ?? '';
unset($_SESSION['old_email']);
$mensaje_error = $_SESSION['mensaje_error'] ?? '';
unset($_SESSION['mensaje_error']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Latido Cerámico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .login-card { max-width: 420px; border: 0; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,.2); }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center p-3">
    <div class="card login-card p-4 bg-white">
        <div class="text-center mb-3">
            <i class="bi bi-palette" style="font-size: 2rem; color:#764ba2"></i>
            <h1 class="h4 mt-2 mb-0">Latido Cerámico</h1>
            <div class="text-muted">Panel de Administración</div>
        </div>
        <?php if ($mensaje_error): ?>
            <div class="alert alert-danger py-2">
                <i class="bi bi-exclamation-triangle me-1"></i>
                <?= htmlspecialchars($mensaje_error) ?>
            </div>
        <?php endif; ?>
        <form action="actions/login_acc.php" method="post" novalidate>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($old_email) ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100" type="submit">
                <i class="bi bi-box-arrow-in-right me-1"></i> Ingresar
            </button>
        </form>
        <div class="mt-3 text-center">
            <a href="../index.php" class="text-decoration-none text-muted"><i class="bi bi-house me-1"></i> Volver al sitio</a>
        </div>
    </div>
</body>
</html>
