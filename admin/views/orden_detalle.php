<?php
require_once __DIR__ . '/../classes/Conexion.php';
$pdo = (new Conexion())->getConexion();
$id = (int)($_GET['id'] ?? 0);
$st = $pdo->prepare('SELECT * FROM ordenes WHERE id=:id LIMIT 1');
$st->execute(['id'=>$id]);
$orden = $st->fetch();
$items = [];
if($orden){

  $colsStmt = $pdo->query('SHOW COLUMNS FROM orden_items');
  $cols = $colsStmt ? $colsStmt->fetchAll(PDO::FETCH_COLUMN) : [];
  if(in_array('nombre_producto',$cols) && in_array('precio_unitario',$cols) && in_array('subtotal',$cols)){
   
    $sti = $pdo->prepare('SELECT nombre_producto AS nombre, precio_unitario AS precio, cantidad, subtotal FROM orden_items WHERE orden_id=:id');
  } else {
  
    $sti = $pdo->prepare('SELECT nombre, precio, cantidad, total AS subtotal FROM orden_items WHERE orden_id=:id');
  }
  $sti->execute(['id'=>$id]);
  $items = $sti->fetchAll();
}
?>
<h1 class="h4 mb-4">Detalle de orden #<?= $orden? (int)$orden['id'] : $id ?></h1>
<?php if(!$orden): ?>
  <div class="alert alert-warning">No se encontró la orden solicitada. <a href="?sec=ordenes" class="alert-link">Volver</a></div>
<?php else: ?>
  <div class="row g-4">
    <div class="col-md-5">
      <div class="card card-admin">
        <div class="card-body">
          <h5 class="card-title mb-3">Datos del cliente</h5>
          <dl class="row mb-0">
            <dt class="col-5">Nombre</dt><dd class="col-7"><?= htmlspecialchars($orden['nombre']) ?></dd>
            <dt class="col-5">Email</dt><dd class="col-7"><?= htmlspecialchars($orden['email']) ?></dd>
            <?php if(!empty($orden['telefono'])): ?><dt class="col-5">Teléfono</dt><dd class="col-7"><?= htmlspecialchars($orden['telefono']) ?></dd><?php endif; ?>
            <dt class="col-5">Dirección</dt><dd class="col-7"><?= htmlspecialchars($orden['direccion']) ?></dd>
            <?php if(!empty($orden['localidad'])): ?><dt class="col-5">Localidad</dt><dd class="col-7"><?= htmlspecialchars($orden['localidad']) ?></dd><?php endif; ?>
            <?php if(!empty($orden['cp'])): ?><dt class="col-5">CP</dt><dd class="col-7"><?= htmlspecialchars($orden['cp']) ?></dd><?php endif; ?>
            <?php if(!empty($orden['notas'])): ?><dt class="col-5">Notas</dt><dd class="col-7"><?= nl2br(htmlspecialchars($orden['notas'])) ?></dd><?php endif; ?>
            <dt class="col-5">Subtotal</dt><dd class="col-7">$<?= number_format((int)$orden['subtotal'],0,',','.') ?></dd>
            <dt class="col-5">Estado</dt><dd class="col-7">
              <form action="actions/orden_estado_acc.php" method="post" class="d-flex align-items-center gap-2">
                <input type="hidden" name="id" value="<?= (int)$orden['id'] ?>">
                <select name="estado" class="form-select form-select-sm w-auto">
                  <?php foreach(['pendiente','pagado','cancelado'] as $est): ?>
                    <option value="<?= $est ?>" <?= $orden['estado']===$est?'selected':'' ?> ><?= ucfirst($est) ?></option>
                  <?php endforeach; ?>
                </select>
                <button class="btn btn-sm btn-outline-primary">Cambiar</button>
              </form>
            </dd>
            <dt class="col-5">Fecha</dt><dd class="col-7"><?= htmlspecialchars($orden['created_at']) ?></dd>
          </dl>
        </div>
      </div>
    </div>
    <div class="col-md-7">
      <div class="card card-admin">
        <div class="card-body">
          <h5 class="card-title mb-3">Ítems</h5>
          <?php if(!$items): ?>
            <p class="text-muted mb-0">Sin ítems.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-sm">
                <thead>
                  <tr>
                    <th>Producto</th>
                    <th class="text-end">Precio</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-end">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($items as $it): ?>
                    <tr>
                      <td><?= htmlspecialchars($it['nombre']) ?></td>
                      <td class="text-end">$<?= is_numeric($it['precio']) ? number_format((float)$it['precio'],2,',','.') : htmlspecialchars((string)$it['precio']) ?></td>
                      <td class="text-center"><?= (int)$it['cantidad'] ?></td>
                      <td class="text-end">$<?= is_numeric($it['subtotal']) ? number_format((float)$it['subtotal'],2,',','.') : htmlspecialchars((string)$it['subtotal']) ?></td>
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
  <div class="mt-4"><a href="?sec=ordenes" class="btn btn-outline-secondary btn-sm">Volver al listado</a></div>
<?php endif; ?>
