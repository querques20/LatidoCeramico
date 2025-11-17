<?php
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/ConfigAdmin.php';
ConfigAdmin::ensureSetup();
$cfg = ConfigAdmin::getAll();
function v($k,$d=''){ global $cfg; return htmlspecialchars($cfg[$k] ?? $d); }
?>
<div class="container-fluid">
  <h2 class="h4 mb-4">Configuración</h2>

  <form action="actions/guardar_configuracion_acc.php" method="post" enctype="multipart/form-data">
    <div class="row g-3">
      <div class="col-lg-6">
        <div class="card card-admin p-3 h-100">
          <h5 class="mb-3">Datos del sitio</h5>
          <div class="mb-2">
            <label class="form-label">Nombre del sitio</label>
            <input type="text" name="site_name" value="<?= v('site_name','Latido Cerámico') ?>" class="form-control" />
          </div>
          <div class="mb-2">
            <label class="form-label">Email de notificaciones</label>
            <input type="email" name="contact_email" value="<?= v('contact_email') ?>" class="form-control" />
          </div>
          <div class="row g-2">
            <div class="col-sm-6 mb-2">
              <label class="form-label">Teléfono</label>
              <input type="text" name="contact_phone" value="<?= v('contact_phone') ?>" class="form-control" />
            </div>
            <div class="col-sm-6 mb-2">
              <label class="form-label">WhatsApp (link completo)</label>
              <input type="text" name="whatsapp_link" value="<?= v('whatsapp_link') ?>" class="form-control" placeholder="https://wa.me/549..." />
            </div>
          </div>
          <div class="mb-2">
            <label class="form-label">Instagram URL</label>
            <input type="url" name="instagram_url" value="<?= v('instagram_url') ?>" class="form-control" placeholder="https://instagram.com/tu_cuenta" />
          </div>
          <div class="mb-2">
            <label class="form-label">QR de WhatsApp</label>
            <?php $qr = $cfg['whatsapp_qr'] ?? ''; ?>
            <?php if($qr): ?>
              <div class="d-flex align-items-center gap-3 mb-2">
                <img src="/assets/img/qr/<?= htmlspecialchars($qr) ?>" alt="QR WhatsApp" style="width:96px;height:96px;object-fit:cover;border:1px solid #ddd;border-radius:8px;" />
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="whatsapp_qr_borrar" value="1" id="qrDel">
                  <label class="form-check-label" for="qrDel">Eliminar QR actual</label>
                </div>
              </div>
            <?php endif; ?>
            <input type="file" name="whatsapp_qr" class="form-control" accept="image/*" />
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="card card-admin p-3 h-100">
          <h5 class="mb-3">Catálogo y Checkout</h5>
          <div class="mb-2">
            <label class="form-label">Expiración carrito (minutos)</label>
            <input type="number" name="carrito_exp_min" min="5" max="1440" value="<?= v('carrito_exp_min','120') ?>" class="form-control" />
          </div>
          <div class="mb-2">
            <label class="form-label">Modo mantenimiento</label>
            <select name="maintenance_mode" class="form-select">
              <option value="0" <?= v('maintenance_mode','0')==='1'?'':'selected' ?>>Desactivado</option>
              <option value="1" <?= v('maintenance_mode','0')==='1'?'selected':'' ?>>Activado</option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <div class="text-end mt-3">
      <button class="btn btn-primary">Guardar cambios</button>
    </div>
  </form>
</div>
