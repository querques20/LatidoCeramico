<?php
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/GaleriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';
GaleriaAdmin::ensureSetup();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$item = $id > 0 ? GaleriaAdmin::buscar($id) : null;
if (!$item) { $_SESSION['mensaje_error'] = 'Elemento no encontrado'; header('Location: index.php?sec=galeria'); exit; }
?>
<div class="container-fluid">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="h4 mb-0">Editar imagen de galería</h2>
    <a href="?sec=galeria" class="btn btn-outline-secondary btn-sm">Volver</a>
  </div>
  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card card-admin p-3">
        <div class="ratio ratio-1x1 bg-light">
          <img src="/assets/img/galeria/<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen de galería" class="w-100 h-100 object-fit-cover">
        </div>
      </div>
    </div>
    <div class="col-lg-8">
      <div class="card card-admin p-3">
        <form action="actions/editar_galeria_acc.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="id" value="<?= (int)$item['id'] ?>">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Orden</label>
              <input type="number" class="form-control" name="orden" value="<?= (int)$item['orden'] ?>" min="0">
            </div>
            <div class="col-md-6">
              <label class="form-label">Visible</label>
              <select class="form-select" name="visible">
                <option value="1" <?= ((int)$item['visible']===1?'selected':'') ?>>Sí</option>
                <option value="0" <?= ((int)$item['visible']===0?'selected':'') ?>>No</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Reemplazar imagen (opcional)</label>
              <input type="file" class="form-control" name="imagen" accept="image/*">
            </div>
          </div>
          <div class="text-end mt-3">
            <button class="btn btn-primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
