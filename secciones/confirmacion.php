<?php

?>
<section class="py-10">
  <?php if(!empty($_SESSION['flash_checkout']) && $_SESSION['flash_checkout']['ok']): ?>
    <div class="mb-6 rounded-md border border-green-200 bg-green-50 text-green-800 px-4 py-3">
      <?= htmlspecialchars($_SESSION['flash_checkout']['msg'] ?? 'Pedido confirmado') ?>
    </div>
    <script>
      
      localStorage.removeItem('latido_cart_v1');
    </script>
  <?php endif; unset($_SESSION['flash_checkout']); ?>

  <h2 class="font-serif text-2xl mb-4">¡Gracias por tu compra!</h2>
  <?php if(!empty($_SESSION['orden'])): $o = $_SESSION['orden']; ?>
    <div class="rounded-xl border border-gray-200 bg-white/70 p-6">
      <h3 class="font-medium mb-2">Resumen</h3>
      <ul class="divide-y mb-3">
        <?php foreach(($o['items'] ?? []) as $it): ?>
          <li class="py-2 flex items-center justify-between text-sm">
            <div><?= htmlspecialchars($it['nombre']) ?> <span class="text-gray-500">x<?= (int)($it['cantidad'] ?? 0) ?></span></div>
            <div>$<?= number_format(($it['precio'] ?? 0) * ($it['cantidad'] ?? 0),0,',','.') ?></div>
          </li>
        <?php endforeach; ?>
      </ul>
      <div class="flex items-center justify-between font-medium">
        <span>Total</span>
        <span>$<?= number_format(($o['subtotal'] ?? 0),0,',','.') ?></span>
      </div>
    </div>
  <?php else: ?>
    <p class="text-gray-700">No encontramos información del pedido. Volvé a <a class="underline" href="?seccion=productos">Productos</a>.</p>
  <?php endif; ?>

  <div class="mt-6">
    <a href="?seccion=productos" class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-4 py-2 shadow hover:opacity-95 active:scale-[.99] transition">Seguir comprando</a>
  </div>
</section>