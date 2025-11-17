<?php
$cx = new Conexion();
$pdo = $cx->getConexion();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { echo '<div class="alert alert-danger">ID inválido</div>'; return; }

$carrito = $pdo->prepare("SELECT * FROM carritos WHERE id = :id LIMIT 1");
$carrito->execute([':id'=>$id]);
$c = $carrito->fetch();
if (!$c) { echo '<div class="alert alert-warning">Carrito no encontrado</div>'; return; }

$items = $pdo->prepare("SELECT ci.*, p.nombre AS producto_nombre FROM carrito_items ci LEFT JOIN productos p ON p.id = ci.producto_id WHERE ci.carrito_id = :id");
$items->execute([':id'=>$id]);
$its = $items->fetchAll();
?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0"><i class="bi bi-cart me-2"></i>Carrito #<?= (int)$c['id'] ?></h2>
  <a class="btn btn-outline-secondary btn-sm" href="?sec=carritos">Volver</a>
</div>

<div class="row g-3">
  <div class="col-md-5">
    <div class="card card-admin">
      <div class="card-body">
        <h5 class="card-title mb-3">Información</h5>
        <dl class="row mb-0">
          <dt class="col-5">Usuario</dt><dd class="col-7"><?= $c['usuario_id'] ? ('#'.$c['usuario_id']) : '-' ?></dd>
          <dt class="col-5">Session</dt><dd class="col-7"><code><?= htmlspecialchars((string)$c['session_id']) ?: '-' ?></code></dd>
          <dt class="col-5">Estado</dt><dd class="col-7"><span class="badge bg-<?= $c['estado']==='convertido'?'success':($c['estado']==='activo'?'primary':'secondary') ?>"><?= ucfirst($c['estado']) ?></span></dd>
          <dt class="col-5">Creado</dt><dd class="col-7"><?= htmlspecialchars((string)$c['created_at']) ?></dd>
          <dt class="col-5">Actualizado</dt><dd class="col-7"><?= htmlspecialchars((string)$c['updated_at']) ?></dd>
        </dl>
        <div class="mt-3 d-flex flex-column gap-2">
          <div class="d-flex gap-2 align-items-center flex-wrap">
            <?php if ($c['estado']==='activo'): ?>
              <button class="btn btn-outline-danger btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#confirmExpCarrito" aria-expanded="false" aria-controls="confirmExpCarrito">
                <i class="bi bi-slash-circle me-1"></i> Expirar
              </button>
            <?php endif; ?>
            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#confirmResetCarrito" aria-expanded="false" aria-controls="confirmResetCarrito">
              <i class="bi bi-trash me-1"></i> Reset
            </button>
          </div>
          <?php if ($c['estado']==='activo'): ?>
          <div class="collapse" id="confirmExpCarrito">
            <div class="d-inline-flex align-items-center gap-2">
              <span class="small text-muted">¿Expirar carrito?</span>
              <a class="btn btn-sm btn-danger" href="actions/carrito_expirar_acc.php?id=<?= (int)$c['id'] ?>">Sí</a>
              <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#confirmExpCarrito">Cancelar</button>
            </div>
          </div>
          <?php endif; ?>
          <div class="collapse" id="confirmResetCarrito">
            <div class="d-inline-flex align-items-center gap-2">
              <span class="small text-muted">¿Borrar carrito (reset)?</span>
              <a class="btn btn-sm btn-secondary" href="actions/carrito_reset_acc.php?id=<?= (int)$c['id'] ?>">Sí</a>
              <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#confirmResetCarrito">Cancelar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-7">
    <div class="card card-admin">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Producto</th><th class="text-end">Cantidad</th></tr></thead>
            <tbody>
              <?php foreach ($its as $it): ?>
              <tr>
                <td>#<?= (int)$it['producto_id'] ?> · <?= htmlspecialchars((string)($it['producto_nombre'] ?? '')) ?></td>
                <td class="text-end">x<?= (int)$it['cantidad'] ?></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($its)): ?>
              <tr><td colspan="2" class="text-center text-muted py-4">Sin items.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
