<?php
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/UsuarioAdmin.php';
require_once __DIR__ . '/../classes/UsuarioPublicoAdmin.php';
UsuarioAdmin::ensureSetup();
UsuarioPublicoAdmin::ensureSetup();
$usuarios = UsuarioAdmin::todos();
$usuarios_publico = UsuarioPublicoAdmin::todos();
?>
<div class="container-fluid">
    <h2 class="h4 mb-4">Usuarios</h2>
    
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card card-admin p-3 h-100">
                <h5 class="mb-3">Crear administrador</h5>
                <form action="actions/crear_usuario_acc.php" method="post">
                    <div class="row g-2">
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required />
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" required />
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" required />
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required minlength="6" autocomplete="new-password" />
                        <div class="form-text">Mínimo 6 caracteres.</div>
                    </div>
                    <button class="btn btn-primary">Crear admin</button>
                </form>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-admin p-3 h-100">
                <h5 class="mb-3">Crear usuario del sitio</h5>
                <form action="actions/crear_usuario_publico_acc.php" method="post">
                    <div class="row g-2">
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Nombre</label>
                            <input type="text" name="nombre" class="form-control" required />
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Apellido</label>
                            <input type="text" name="apellido" class="form-control" required />
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" required />
                        </div>
                        <div class="col-sm-6 mb-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required />
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Contraseña</label>
                        <input type="password" name="password" class="form-control" required minlength="6" autocomplete="new-password" />
                        <div class="form-text">Mínimo 6 caracteres.</div>
                    </div>
                    <button class="btn btn-primary">Crear usuario</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card card-admin p-3 h-100">
                <h5 class="mb-3">Administradores</h5>
                <?php if(!$usuarios): ?>
                    <div class="alert alert-secondary py-2">No hay usuarios creados.</div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Último login</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($usuarios as $u): ?>
                            <tr>
                                <td><?= (int)$u['id'] ?></td>
                                <td><?= htmlspecialchars(trim(($u['nombre'] ?? '') . ' ' . ($u['apellido'] ?? ''))) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['ultimo_login'] ?? '-') ?></td>
                                <td class="text-end">
                                    <?php if((int)$u['id'] !== (int)($_SESSION['admin_user_id'] ?? 0)): ?>
                                        <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#confirmDelAdmin<?= (int)$u['id'] ?>" aria-expanded="false" aria-controls="confirmDelAdmin<?= (int)$u['id'] ?>">Borrar</button>
                                        <div class="collapse mt-2" id="confirmDelAdmin<?= (int)$u['id'] ?>">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                <span class="small text-muted">¿Confirmar?</span>
                                                <form action="actions/borrar_usuario_acc.php" method="post" class="d-inline">
                                                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>" />
                                                    <button class="btn btn-sm btn-danger">Sí, borrar</button>
                                                </form>
                                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#confirmDelAdmin<?= (int)$u['id'] ?>">Cancelar</button>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge text-bg-secondary">Yo</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-admin p-3 h-100">
                <h5 class="mb-3">Usuarios registrados del sitio</h5>
                <?php if(!$usuarios_publico): ?>
                    <div class="alert alert-secondary py-2">No hay usuarios registrados.</div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Activo</th>
                                <th>Creado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($usuarios_publico as $u): ?>
                            <tr>
                                <td><?= (int)$u['id'] ?></td>
                                <td><?= htmlspecialchars(trim(($u['nombre'] ?? '') . ' ' . ($u['apellido'] ?? ''))) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['telefono'] ?? '-') ?></td>
                                <td><?= ((int)($u['activo'] ?? 1)) ? '<span class="badge text-bg-success">Sí</span>' : '<span class="badge text-bg-secondary">No</span>' ?></td>
                                <td><?= htmlspecialchars($u['created_at'] ?? '-') ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#confirmDelPublico<?= (int)$u['id'] ?>" aria-expanded="false" aria-controls="confirmDelPublico<?= (int)$u['id'] ?>">Borrar</button>
                                    <div class="collapse mt-2" id="confirmDelPublico<?= (int)$u['id'] ?>">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <span class="small text-muted">¿Confirmar?</span>
                                            <form action="actions/borrar_usuario_publico_acc.php" method="post" class="d-inline">
                                                <input type="hidden" name="id" value="<?= (int)$u['id'] ?>" />
                                                <button class="btn btn-sm btn-danger">Sí, borrar</button>
                                            </form>
                                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#confirmDelPublico<?= (int)$u['id'] ?>">Cancelar</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>