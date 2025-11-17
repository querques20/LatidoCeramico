<?php
$next = $_GET['next'] ?? '';
?>
<section class="py-10">
  <div class="mx-auto max-w-md bg-white/80 border border-gray-200 rounded-xl p-6">
    <h2 class="font-serif text-2xl mb-4">Crear cuenta</h2>
    <form action="/procesar-registro.php" method="post" class="space-y-3">
      <?php if(!empty($_SESSION['flash_error'])): ?>
        <div class="rounded border border-red-200 bg-red-50 text-red-800 px-3 py-2 mb-2"><?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?></div>
      <?php endif; ?>
      <input type="hidden" name="next" value="<?= htmlspecialchars($next) ?>" />
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div>
          <label class="block text-sm text-gray-700">Nombre</label>
          <input required type="text" name="nombre" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" />
        </div>
        <div>
          <label class="block text-sm text-gray-700">Apellido</label>
          <input required type="text" name="apellido" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" />
        </div>
      </div>
      <div>
        <label class="block text-sm text-gray-700">Email</label>
        <input required type="email" name="email" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" />
      </div>
      <div>
        <label class="block text-sm text-gray-700">Teléfono</label>
        <input required type="tel" name="telefono" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" />
      </div>
      <div>
        <label class="block text-sm text-gray-700">Contraseña</label>
        <input required minlength="4" type="password" name="password" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" />
      </div>
      <button class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-4 py-2">Registrarme</button>
    </form>
    <p class="mt-4 text-sm text-gray-600">¿Ya tenés cuenta? <a class="underline" href="/login<?= $next? ('?next='.urlencode($next)) : '' ?>">Ingresá</a></p>
  </div>
</section>