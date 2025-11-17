<?php


require_once 'clases/Productos.php';

$combos = Productos::combos();
?>

<section class="py-10">
   
    <div class="mb-8 text-center">
        <h1 class="font-serif text-3xl mb-4">Combos y Promociones</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Sets especiales con descuentos exclusivos. Ahorrá comprando productos complementarios juntos. Cada combo está cuidadosamente seleccionado para ofrecerte la mejor experiencia cerámica.</p>
    </div>

    <?php if (!$combos): ?>
        <div class="rounded-xl border border-amber-200 bg-amber-50 text-amber-900 p-6 text-center">
            <div class="flex flex-col items-center gap-3">
                <svg viewBox="0 0 24 24" fill="currentColor" class="h-8 w-8">
                    <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm0 14a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm1-8v6h-2V8h2z"/>
                </svg>
                <div>
                    <h3 class="font-medium mb-1">No hay combos disponibles en este momento</h3>
                    <p class="text-sm">Pero podés explorar nuestros <a href="?seccion=productos" class="underline hover:no-underline">productos individuales</a> mientras preparamos nuevas promociones especiales.</p>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list" aria-label="Lista de combos">
            <?php foreach ($combos as $p): ?>
                <article class="rounded-xl border border-gray-200 bg-white overflow-hidden" role="listitem">
                    <div class="aspect-[4/3] bg-purple-100 flex items-center justify-center">
                        <img src="/assets/img/productos/<?= htmlspecialchars($p['imagen']) ?>"
                             alt="Imagen de <?= htmlspecialchars($p['nombre']) ?>"
                             class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-1"><?= htmlspecialchars($p['nombre']) ?></h3>
                        <p class="text-sm text-gray-700 mb-2"><?= htmlspecialchars($p['descripcion']) ?></p>
                        <div class="text-gray-900 font-medium mb-3" aria-label="Precio">
                            $<?= number_format($p['precio'], 0, ',', '.') ?>
                        </div>
                        <div class="flex items-center justify-between gap-2">
                            <button class="btn-add-cart inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-3.5 py-1.5 text-sm shadow hover:opacity-95 active:scale-[.99] transition"
                                    data-id="<?= htmlspecialchars($p['id']) ?>"
                                    data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                    data-precio="<?= htmlspecialchars($p['precio']) ?>"
                                    aria-label="Agregar <?= htmlspecialchars($p['nombre']) ?> al carrito">
                                Agregar
                            </button>
                            <a href="/producto/<?= urlencode($p['slug']) ?>"
                               class="inline-flex items-center rounded-full border border-white/50 bg-white/70 backdrop-blur px-3.5 py-1.5 text-sm text-gray-900 hover:shadow-sm active:scale-[.99] transition">
                                Ver
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="mt-16 text-center">
        <div class="inline-flex flex-col items-center gap-4 rounded-2xl border border-gray-200 bg-gray-50 p-8">
            <h3 class="font-serif text-xl text-gray-900">¿Buscás productos individuales?</h3>
            <p class="text-gray-600">Explorá nuestra colección completa de productos cerámicos artesanales</p>
            <a href="?seccion=productos" class="inline-flex items-center rounded-full border border-amber-500 bg-amber-500 text-white px-6 py-2.5 hover:bg-amber-600 active:scale-[.99] transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                Ver todos los productos
            </a>
        </div>
    </div>
</section>