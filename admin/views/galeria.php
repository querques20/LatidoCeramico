<?php
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/GaleriaAdmin.php';
require_once __DIR__ . '/../includes/functions.php';
GaleriaAdmin::ensureSetup();
$items = GaleriaAdmin::todas();
?>
<div class="container-fluid">
  <h2 class="h4 mb-4">Galería</h2>

  <?php if(!empty($_SESSION['mensaje_exito'])): ?><div class="alert alert-success"><?= $_SESSION['mensaje_exito']; unset($_SESSION['mensaje_exito']); ?></div><?php endif; ?>
  <?php if(!empty($_SESSION['mensaje_error'])): ?><div class="alert alert-danger"><?= $_SESSION['mensaje_error']; unset($_SESSION['mensaje_error']); ?></div><?php endif; ?>

  <div class="row g-3">
    <div class="col-lg-4">
      <div class="card card-admin p-3">
        <h5 class="mb-3">Agregar imagen</h5>
        <form action="actions/crear_galeria_acc.php" method="post" enctype="multipart/form-data">
          <div class="mb-2">
            <label class="form-label">Imagen</label>
            <input type="file" class="form-control" name="imagen" accept="image/*" required>
            <div class="form-text">Se guardará en assets/img/galeria/</div>
          </div>
          <div class="row g-2">
            <div class="col-6">
              <label class="form-label">Orden</label>
              <input type="number" class="form-control" name="orden" value="0" min="0">
            </div>
            <div class="col-6">
              <label class="form-label">Visible</label>
              <select class="form-select" name="visible">
                <option value="1" selected>Sí</option>
                <option value="0">No</option>
              </select>
            </div>
          </div>
          <div class="text-end mt-3">
            <button class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-lg-8">
      <div class="card card-admin p-3">
        <h5 class="mb-3">Imágenes cargadas</h5>
        <?php if(!$items): ?>
          <div class="text-muted">Aún no hay imágenes.</div>
        <?php else: ?>
          <div class="row g-3">
            <?php foreach($items as $it): ?>
              <div class="col-md-6 col-xl-4">
                <div class="border rounded overflow-hidden">
                  <?php $__base = rtrim($_SERVER['DOCUMENT_ROOT'] ?? realpath(__DIR__ . '/../../'), '/\\');
                        $__rutaAbs = $__base . '/assets/img/galeria/' . $it['imagen'];
                        $__existe = is_file($__rutaAbs);
                  ?>
                  <div class="ratio ratio-1x1 bg-light position-relative" style="background:#f8f9fa">
                    <img src="/assets/img/galeria/<?= htmlspecialchars($it['imagen']) ?>" alt="Imagen de galería" class="w-100 h-100 object-fit-cover">
                    <?php if(!$__existe): ?>
                      <span class="position-absolute top-0 start-0 m-1 badge bg-warning text-dark" title="Archivo no encontrado en el servidor">Archivo faltante</span>
                    <?php endif; ?>
                  </div>
                  <div class="p-2">
                    <div class="d-flex align-items-center justify-content-end">
                      <span class="badge bg-<?= $it['visible']? 'success':'secondary' ?>"><?= $it['visible']? 'Visible':'Oculta' ?></span>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                      <a class="btn btn-sm btn-outline-primary" href="?sec=editar_galeria&id=<?= (int)$it['id'] ?>">Editar</a>
                      <form action="actions/cambiar_visible_galeria_acc.php" method="post" class="m-0">
                        <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                        <input type="hidden" name="visible" value="<?= $it['visible']?0:1 ?>">
                        <button class="btn btn-sm btn-outline-secondary" title="Cambiar visibilidad">Visibilidad</button>
                      </form>
                      <button class="btn btn-sm btn-outline-danger ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#delgal<?= (int)$it['id'] ?>">Borrar</button>
                    </div>
                    <div class="collapse mt-2" id="delgal<?= (int)$it['id'] ?>">
                      <div class="d-flex justify-content-between align-items-center">
                        <span>¿Eliminar esta imagen?</span>
                        <div>
                          <form action="actions/borrar_galeria_acc.php" method="post" class="d-inline m-0">
                            <input type="hidden" name="id" value="<?= (int)$it['id'] ?>">
                            <input type="hidden" name="imagen" value="<?= htmlspecialchars($it['imagen']) ?>">
                            <button class="btn btn-danger btn-sm">Sí, eliminar</button>
                          </form>
                          <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#delgal<?= (int)$it['id'] ?>">Cancelar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
