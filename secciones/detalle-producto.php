<?php
require_once 'clases/Productos.php';
$id = $_GET['id'] ?? '';
$slugProducto = $_GET['slug'] ?? '';
if ($slugProducto) {
    $p = Productos::buscarPorSlug($slugProducto);
} else {
    $p = $id ? Productos::buscarPorId($id) : null;
}
?>
<section class="py-10">
    <?php if (!$p): ?>
        <?php http_response_code(404); ?>
        <div class="mx-auto max-w-xl rounded-xl border border-amber-200 bg-amber-50 text-amber-900 p-5">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 inline-flex h-8 w-8 items-center justify-center rounded-full bg-white/80 border border-amber-200">
                    <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4"><path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm0 14a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm1-8v6h-2V8h2z"/></svg>
                </div>
                <div class="flex-1">
                    <h2 class="font-serif text-xl mb-1">Producto no encontrado</h2>
                    <p class="text-sm">No encontramos el producto solicitado.
                        <?php if(!empty($id)): ?>
                            <code class="px-1.5 py-0.5 rounded bg-white/70 border border-amber-200 text-amber-900">id=<?= htmlspecialchars($id) ?></code>
                        <?php elseif(!empty($slugProducto)): ?>
                            <code class="px-1.5 py-0.5 rounded bg-white/70 border border-amber-200 text-amber-900">slug=<?= htmlspecialchars($slugProducto) ?></code>
                        <?php endif; ?>
                    </p>
                    <div class="mt-3">
                        <a href="/productos" class="inline-flex items-center rounded-full border border-white/50 bg-white/70 backdrop-blur px-3 py-1.5 text-gray-900 hover:shadow-sm active:scale-[.99] transition">Volver al catálogo</a>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-purple-100 aspect-[4/3] flex items-center justify-center">
                <img src="/assets/img/productos/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>"
                    class="w-full h-full object-cover" />
            </div>
            <div>
                <h2 class="font-serif text-3xl mb-2"><?= htmlspecialchars($p['nombre']) ?></h2>
                <div class="text-gray-700 mb-3">Categoría: <?= htmlspecialchars(ucfirst($p['categoria'])) ?></div>
                <p class="text-gray-700 mb-4"><?= htmlspecialchars($p['descripcion']) ?></p>
                <div class="text-2xl font-semibold text-gray-900 mb-2">
                    $<?= number_format($p['precio'], 0, ',', '.') ?>
                    <?php if ((int)($p['stock'] ?? 0) <= 0): ?>
                        <span class="ml-2 inline-flex items-center rounded-full bg-gray-200 px-2 py-0.5 text-[12px] text-gray-700 align-middle">Sin stock</span>
                    <?php endif; ?>
                </div>
                <?php if ((int)($p['stock'] ?? 0) > 0 && (int)$p['stock'] <= 3): ?>
                    <div class="text-xs text-amber-700 mb-4">Últimas <?= (int)$p['stock'] ?> unidades</div>
                <?php endif; ?>
                <div class="flex items-center gap-3">
                    <button
                        class="btn-add-cart inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-4 py-2 shadow hover:opacity-95 active:scale-[.99] transition <?= ((int)($p['stock'] ?? 0) <= 0) ? 'opacity-50 cursor-not-allowed' : '' ?>"
                        data-id="<?= htmlspecialchars($p['id']) ?>" data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                        data-precio="<?= htmlspecialchars($p['precio']) ?>"
                        <?= ((int)($p['stock'] ?? 0) <= 0) ? 'disabled aria-disabled="true"' : '' ?>>
                        <?= ((int)($p['stock'] ?? 0) <= 0) ? 'Sin stock' : 'Agregar al carrito' ?>
                    </button>
                    <button class="btn-fav inline-flex items-center justify-center h-10 w-10 rounded-full border border-white/50 bg-white/70 backdrop-blur ring-1 ring-white/30 hover:shadow-sm"
                            title="Agregar a favoritos"
                            data-id="<?= htmlspecialchars($p['id']) ?>"
                            data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                            data-precio="<?= htmlspecialchars($p['precio']) ?>"
                            data-imagen="/assets/img/productos/<?= htmlspecialchars($p['imagen']) ?>"
                            data-descripcion="<?= htmlspecialchars($p['descripcion']) ?>">
                      <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 text-rose-600"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.22 2.53C11.09 5.01 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                      <span class="sr-only">Favorito</span>
                    </button>
                    <a href="/productos"
                        class="inline-flex items-center rounded-full border border-white/50 bg-white/70 backdrop-blur px-4 py-2 text-gray-900 hover:shadow-sm active:scale-[.99] transition">Volver</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>