<header class="admin-header text-white p-3 shadow">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <h1 class="h4 mb-0">
                    <i class="bi bi-palette me-2"></i>
                    Panel de Administración - Latido Cerámico
                </h1>
            </div>
            <div class="d-flex align-items-center">
                <span class="me-3">
                    <i class="bi bi-person-circle me-1"></i>
                    <?= isset($_SESSION['admin_user_email']) ? htmlspecialchars($_SESSION['admin_user_email']) : 'Invitado'; ?>
                </span>
                <a href="../index.php" class="btn btn-outline-light btn-sm me-2" target="_blank">
                    <i class="bi bi-eye me-1"></i>
                    Ver Sitio
                </a>
                <a href="actions/logout_acc.php" class="btn btn-light btn-sm">
                    <i class="bi bi-box-arrow-right me-1"></i>
                    Salir
                </a>
            </div>
        </div>
    </div>
</header>