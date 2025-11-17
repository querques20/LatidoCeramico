<?php
$cx = new Conexion();
$pdo = $cx->getConexion();

$estado = $_GET['estado'] ?? 'activo';
$valid = ['activo','convertido','expirado','todos'];
if (!in_array($estado, $valid)) $estado = 'convertido';

$where = $estado === 'todos' ? '' : 'WHERE c.estado = :estado';
$sql = "SELECT c.id, c.usuario_id, c.session_id, c.estado, c.created_at, c.updated_at, COUNT(ci.id) AS items
        FROM carritos c
        LEFT JOIN carrito_items ci ON ci.carrito_id = c.id
        $where
        GROUP BY c.id
        ORDER BY c.id DESC
        LIMIT 50";

$st = $pdo->prepare($sql);
if ($estado !== 'todos') { $st->bindValue(':estado', $estado); }
$st->execute();
$rows = $st->fetchAll();
?>
<div class="d-flex align-items-center justify-content-between mb-3">
  <h2 class="h4 mb-0"><i class="bi bi-cart me-2"></i>Carritos</h2>
  <div>
    <a class="btn btn-outline-secondary btn-sm <?= $estado==='convertido'?'active':'' ?>" href="?sec=carritos&estado=convertido">Convertidos</a>
    <a class="btn btn-outline-secondary btn-sm <?= $estado==='activo'?'active':'' ?>" href="?sec=carritos&estado=activo">Activos</a>
    <a class="btn btn-outline-secondary btn-sm <?= $estado==='expirado'?'active':'' ?>" href="?sec=carritos&estado=expirado">Expirados</a>
    <a class="btn btn-outline-secondary btn-sm <?= $estado==='todos'?'active':'' ?>" href="?sec=carritos&estado=todos">Todos</a>
  </div>
</div>

<div class="card card-admin">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Session</th>
            <th>Estado</th>
            <th>Items</th>
            <th>Creado</th>
            <th>Actualizado</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
          <tr>
            <td>#<?= (int)$r['id'] ?></td>
            <td><?= $r['usuario_id'] ? ('#'.$r['usuario_id']) : '-' ?></td>
            <td class="text-truncate" style="max-width:160px;">
              <code><?= htmlspecialchars((string)$r['session_id']) ?: '-' ?></code>
            </td>
            <td><span class="badge bg-<?= $r['estado']==='convertido'?'success':($r['estado']==='activo'?'primary':'secondary') ?>"><?= ucfirst($r['estado']) ?></span></td>
            <td><?= (int)$r['items'] ?></td>
            <td><?= htmlspecialchars((string)$r['created_at']) ?></td>
            <td class="small text-muted"><?= htmlspecialchars((string)$r['updated_at']) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-outline-primary" href="?sec=carrito_detalle&id=<?= (int)$r['id'] ?>">Ver</a>
              <?php if ($r['estado']==='activo'): ?>
                <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#confirmExp<?= (int)$r['id'] ?>" aria-expanded="false" aria-controls="confirmExp<?= (int)$r['id'] ?>">Expirar</button>
                <div class="collapse mt-2" id="confirmExp<?= (int)$r['id'] ?>">
                  <div class="d-inline-flex align-items-center gap-2">
                    <span class="small text-muted">¿Expirar carrito?</span>
                    <a class="btn btn-sm btn-danger" href="actions/carrito_expirar_acc.php?id=<?= (int)$r['id'] ?>">Sí</a>
                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#confirmExp<?= (int)$r['id'] ?>">Cancelar</button>
                  </div>
                </div>
              <?php endif; ?>
              <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#confirmReset<?= (int)$r['id'] ?>" aria-expanded="false" aria-controls="confirmReset<?= (int)$r['id'] ?>">Reset</button>
              <div class="collapse mt-2" id="confirmReset<?= (int)$r['id'] ?>">
                <div class="d-inline-flex align-items-center gap-2">
                  <span class="small text-muted">¿Borrar carrito (reset)?</span>
                  <a class="btn btn-sm btn-secondary" href="actions/carrito_reset_acc.php?id=<?= (int)$r['id'] ?>">Sí</a>
                  <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#confirmReset<?= (int)$r['id'] ?>">Cancelar</button>
                </div>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($rows)): ?>
          <tr><td colspan="7" class="text-center text-muted py-4">No hay registros.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
