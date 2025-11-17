<?php
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/CategoriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';
CategoriaAdmin::ensureSetup();
$categorias = CategoriaAdmin::todasAdmin();
?>
<div class="container-fluid">
  <h2 class="h4 mb-4">Categorías</h2>

  <div class="row g-3 mb-4">
    <div class="col-md-5">
      <div class="card card-admin p-3 h-100">
        <h5 class="mb-3">Crear categoría</h5>
        <form action="actions/crear_categoria_acc.php" method="post">
          <div class="mb-2">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required />
          </div>
          <div class="mb-2">
            <label class="form-label">Visible</label>
            <select name="visible" class="form-select">
              <option value="1" selected>Sí</option>
              <option value="0">No</option>
            </select>
          </div>
          <button class="btn btn-primary">Crear</button>
        </form>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card card-admin p-3 h-100">
        <h5 class="mb-3">Listado</h5>
        <?php if(!$categorias): ?>
          <div class="alert alert-secondary py-2">No hay categorías.</div>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Slug</th>
                <th>Visible</th>
                <th>Orden</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($categorias as $c): $cid=(int)$c['id']; ?>
              <tr>
                <td><?= $cid ?></td>
                <td><?= htmlspecialchars($c['nombre']) ?></td>
                <td><code><?= htmlspecialchars($c['slug']) ?></code></td>
                <td><?= ((int)$c['visible'])?'<span class="badge text-bg-success">Sí</span>':'<span class="badge text-bg-secondary">No</span>' ?></td>
                <td><?= (int)($c['orden'] ?? 0) ?></td>
                <td class="text-end">
                  <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#edit<?= $cid ?>">Editar</button>
                  <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#del<?= $cid ?>">Borrar</button>
                </td>
              </tr>
              <tr class="collapse" id="edit<?= $cid ?>">
                <td colspan="6">
                  <form action="actions/editar_categoria_acc.php" method="post" class="row g-2 align-items-end">
                    <input type="hidden" name="id" value="<?= $cid ?>" />
                    <div class="col-md-3">
                      <label class="form-label">Nombre</label>
                      <input type="text" name="nombre" value="<?= htmlspecialchars($c['nombre']) ?>" class="form-control" required />
                    </div>
                    <div class="col-md-3">
                      <label class="form-label">Slug</label>
                      <input type="text" name="slug" value="<?= htmlspecialchars($c['slug']) ?>" class="form-control" />
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Visible</label>
                      <select name="visible" class="form-select">
                        <option value="1" <?= ((int)$c['visible'])?'selected':''; ?>>Sí</option>
                        <option value="0" <?= ((int)$c['visible'])?'' :'selected'; ?>>No</option>
                      </select>
                    </div>
                    <div class="col-md-2">
                      <label class="form-label">Orden</label>
                      <input type="number" name="orden" value="<?= (int)($c['orden'] ?? 0) ?>" class="form-control" />
                    </div>
                    <div class="col-md-2 text-end">
                      <button class="btn btn-primary">Guardar</button>
                    </div>
                  </form>
                </td>
              </tr>
              <tr class="collapse" id="del<?= $cid ?>">
                <td colspan="6">
                  <div class="d-flex justify-content-between align-items-center">
                    <span>¿Eliminar la categoría "<?= htmlspecialchars($c['nombre']) ?>"?</span>
                    <div>
                      <form action="actions/borrar_categoria_acc.php" method="post" class="d-inline m-0">
                        <input type="hidden" name="id" value="<?= $cid ?>" />
                        <button class="btn btn-danger btn-sm">Sí, eliminar</button>
                      </form>
                      <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#del<?= $cid ?>">Cancelar</button>
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
