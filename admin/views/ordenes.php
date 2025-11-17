<?php
require_once __DIR__ . '/../classes/Conexion.php';
$pdo = (new Conexion())->getConexion();
$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;
$total = (int)$pdo->query('SELECT COUNT(*) FROM ordenes')->fetchColumn();
$pages = max(1, (int)ceil($total / $perPage));
$st = $pdo->prepare('SELECT id,nombre,email,subtotal,estado,created_at FROM ordenes ORDER BY id DESC LIMIT :off,:lim');
$st->bindValue(':off', $offset, PDO::PARAM_INT);
$st->bindValue(':lim', $perPage, PDO::PARAM_INT);
$st->execute();
$ordenes = $st->fetchAll();
?>
<h1 class="h3 mb-4">Órdenes</h1>
<div class="card card-admin mb-4">
  <div class="card-body">
    <?php if(!$ordenes): ?>
      <p class="text-muted m-0">No hay órdenes registradas.</p>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm align-middle">
          <thead>
            <tr>
              <th>ID</th>
              <th>Cliente</th>
              <th>Email</th>
              <th>Subtotal</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($ordenes as $o): ?>
              <tr>
                <td><?= (int)$o['id'] ?></td>
                <td><?= htmlspecialchars($o['nombre']) ?></td>
                <td class="text-muted small"><?= htmlspecialchars($o['email']) ?></td>
                <td><span class="badge bg-secondary">$<?= number_format((int)$o['subtotal'],0,',','.') ?></span></td>
                <td>
                  <span class="badge bg-<?php echo $o['estado']==='cancelado'?'danger':($o['estado']==='pendiente'?'warning':'success'); ?>"><?= htmlspecialchars($o['estado']) ?></span>
                </td>
                <td class="small text-muted"><?= htmlspecialchars($o['created_at']) ?></td>
                <td><a class="btn btn-sm btn-outline-primary" href="?sec=orden_detalle&id=<?= (int)$o['id'] ?>">Ver</a></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php if($pages > 1): ?>
        <nav class="mt-2">
          <ul class="pagination pagination-sm">
            <?php for($i=1;$i<=$pages;$i++): ?>
              <li class="page-item <?= $i===$page?'active':'' ?>"><a class="page-link" href="?sec=ordenes&p=<?= $i ?>"><?= $i ?></a></li>
            <?php endfor; ?>
          </ul>
        </nav>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
