<?php
require_once 'clases/Productos.php';

$cats_param = $_GET['cat'] ?? '';
if (is_string($cats_param) && $cats_param !== '') {
    $once = rawurldecode($cats_param);
    $twice = rawurldecode($once);
    $decoded = (stripos($once, '%2C') !== false) ? $twice : $once;
    $cats_selected = array_values(array_filter(array_map('trim', explode(',', $decoded)), fn($v) => $v !== ''));
} else {
    $cats_selected = [];
}

$todosLosProductos = Productos::todos();
$productosIndividuales = $todosLosProductos;

if (!empty($cats_selected)) {
    $productosIndividuales = array_filter($productosIndividuales, function($p) use ($cats_selected) {
        return in_array($p['categoria'], $cats_selected);
    });
}
$cats = Productos::categorias();
?>
<section class="py-10">
    <div class="flex gap-8">
        <aside class="w-64 flex-shrink-0">
            <div>
                <h3 class="text-lg font-medium mb-4">Filtrar por categoría</h3>
                <div class="space-y-3">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" 
                               class="rounded border-gray-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0" 
                               value="" 
                               <?= empty($cats_selected) ? 'checked' : '' ?>
                               onchange="filtrarCategorias()">
                        <span class="ml-3 text-sm">Todas las categorías</span>
                    </label>
                    <?php foreach ($cats as $c): ?>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   class="rounded border-gray-300 text-amber-500 focus:ring-amber-500 focus:ring-offset-0" 
                                   value="<?= htmlspecialchars($c) ?>" 
                                   <?= in_array($c, $cats_selected) ? 'checked' : '' ?>
                                   onchange="filtrarCategorias()">
                            <span class="ml-3 text-sm capitalize"><?= htmlspecialchars($c) ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
        <main class="flex-1">
            <div class="mb-12" style="min-height: 600px;">
                <h2 class="font-serif text-2xl mb-6">Productos</h2>
                <?php if (!$productosIndividuales): ?>
                    <div class="rounded-xl border border-amber-200 bg-amber-50 text-amber-900 p-4">
                        <div class="flex items-start gap-2">
                            <svg viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5 mt-0.5">
                                <path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm0 14a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm1-8v6h-2V8h2z"/>
                            </svg>
                            <div class="text-sm">
                                No encontramos productos para la<?= count($cats_selected) > 1 ? 's' : '' ?> categoría<?= count($cats_selected) > 1 ? 's' : '' ?>
                                <?php if(!empty($cats_selected)): ?>
                                    <strong><?= htmlspecialchars(implode(', ', array_map('ucfirst', $cats_selected))) ?></strong>
                                <?php else: ?>
                                    seleccionada<?= count($cats_selected) > 1 ? 's' : '' ?>
                                <?php endif; ?>.
                                <a href="/productos" class="underline">Limpiar filtro</a> o probá con otra categoría.
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" role="list" aria-label="Lista de productos">
                        <?php foreach ($productosIndividuales as $p): ?>
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
                                        <?php if ((int)($p['stock'] ?? 0) <= 0): ?>
                                            <span class="ml-2 inline-flex items-center rounded-full bg-gray-200 px-2 py-0.5 text-[12px] text-gray-700 align-middle">Sin stock</span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ((int)($p['stock'] ?? 0) > 0 && (int)$p['stock'] <= 3): ?>
                                        <div class="text-xs text-amber-700 mb-2">Últimas <?= (int)$p['stock'] ?> unidades</div>
                                    <?php endif; ?>
                                    <div class="flex items-center justify-between gap-2">
                                        <button class="btn-add-cart inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-3.5 py-1.5 text-sm shadow hover:opacity-95 active:scale-[.99] transition focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 <?= ((int)($p['stock'] ?? 0) <= 0) ? 'opacity-50 cursor-not-allowed' : '' ?>"
                                                data-id="<?= htmlspecialchars($p['id']) ?>" 
                                                data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                                data-precio="<?= htmlspecialchars($p['precio']) ?>"
                                            aria-label="Agregar <?= htmlspecialchars($p['nombre']) ?> al carrito"
                                            <?= ((int)($p['stock'] ?? 0) <= 0) ? 'disabled aria-disabled="true" title="Sin stock"' : '' ?>>
                                            <?= ((int)($p['stock'] ?? 0) <= 0) ? 'Sin stock' : 'Agregar' ?>
                                        </button>
                                        <div class="flex items-center gap-2">
                                            <button class="btn-fav inline-flex items-center justify-center h-9 w-9 rounded-full border border-white/50 bg-white/70 backdrop-blur ring-1 ring-white/30 hover:shadow-sm focus:shadow-sm focus:outline-none focus:ring-2 focus:ring-rose-500"
                                                    title="Agregar a favoritos"
                                                    aria-label="Agregar <?= htmlspecialchars($p['nombre']) ?> a favoritos"
                                                    data-id="<?= htmlspecialchars($p['id']) ?>"
                                                    data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                                    data-precio="<?= htmlspecialchars($p['precio']) ?>"
                                                    data-imagen="/assets/img/productos/<?= htmlspecialchars($p['imagen']) ?>"
                                                    data-descripcion="<?= htmlspecialchars($p['descripcion']) ?>">
                                                <svg viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 text-rose-600" aria-hidden="true">
                                                    <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.22 2.53C11.09 5.01 12.76 4 14.5 4 17 4 19 6 19 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                                </svg>
                                                <span class="sr-only">Favorito</span>
                                            </button>
                                            <a href="/producto/<?= urlencode($p['slug']) ?>"
                                               class="inline-flex items-center rounded-full border border-white/50 bg-white/70 backdrop-blur px-3.5 py-1.5 text-sm text-gray-900 hover:shadow-sm active:scale-[.99] transition focus:outline-none focus:ring-2 focus:ring-gray-500"
                                               aria-label="Ver detalles de <?= htmlspecialchars($p['nombre']) ?>">
                                                Ver
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="border-t border-gray-200 pt-12">
                <div class="text-center">
                    <div class="inline-flex flex-col items-center gap-4 rounded-2xl border border-gray-200 bg-gradient-to-br from-green-50 to-emerald-50 p-8">
                        <h3 class="font-serif text-xl text-gray-900">¿Buscás ofertas especiales?</h3>
                        <p class="text-gray-600">Explorá nuestros combos y promociones con descuentos exclusivos</p>
                        <a href="/combos" 
                           class="inline-flex items-center rounded-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-2.5 hover:opacity-95 active:scale-[.99] transition focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Ver combos y promociones
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
    function filtrarCategorias() {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const todosCheckbox = document.querySelector('input[value=""]');
        if (event.target.value === '') {
            if (event.target.checked) {
                checkboxes.forEach(cb => {
                    if (cb.value !== '') cb.checked = false;
                });
                window.location.href = '/productos';
            }
        } else {
            todosCheckbox.checked = false;
            const categoriasSeleccionadas = [];
            checkboxes.forEach(cb => {
                if (cb.value !== '' && cb.checked) {
                    categoriasSeleccionadas.push(cb.value);
                }
            });
            if (categoriasSeleccionadas.length === 0) {
                todosCheckbox.checked = true;
                window.location.href = '/productos';
            } else {
                const catParam = categoriasSeleccionadas.map(encodeURIComponent).join(',');
                window.location.href = '/productos?cat=' + catParam;
            }
        }
    }
    </script>
</section>