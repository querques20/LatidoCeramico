<?php 
require_once 'clases/Secciones.php';
require_once 'clases/Usuario.php';
$usuarioLogueado = $_SESSION['usuario_publico'] ?? null;
$navegacion = Secciones::enNavegacion();
?>
<header class="sticky top-0 z-50 supports-[backdrop-filter]:backdrop-blur-lg shadow-sm rounded-b-2xl bg-white/80" role="banner">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="h-16 flex items-center justify-between">
  <a href="/index.php?seccion=inicio" class="flex items-center gap-3 group">
        <img src="/assets/img/logoLT.WEBP" alt="Logotipo de Latido Cerámico" class="h-8 w-8" />
        <span class="font-serif text-xl tracking-wide text-gray-900 group-hover:opacity-80">Latido Cerámico</span>
      </a>
  <nav class="hidden md:flex items-center gap-6" role="navigation" aria-label="Navegación principal">
        <?php foreach($navegacion as $item): 
          $slug = htmlspecialchars($item['slug']); 
          $nombre = htmlspecialchars($item['nombre'] ?? $item['titulo'] ?? $slug); 
          if($slug === 'productos'): ?>
            <div class="relative group">
              <a class="text-gray-700 hover:text-gray-900 flex items-center gap-1" href="/index.php?seccion=<?= $slug ?>">
                <?= $nombre ?>
                <svg class="w-4 h-4 transition-transform group-hover:rotate-180" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
              </a>
              <div class="absolute left-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                <div class="py-2">
                  <a href="/index.php?seccion=productos" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Todos los Productos</a>
                  <a href="/index.php?seccion=combos" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Combos y Promociones</a>
                </div>
              </div>
            </div>
          <?php else: ?>
            <a class="text-gray-700 hover:text-gray-900" href="/index.php?seccion=<?= $slug ?>"><?= $nombre ?></a>
          <?php endif; ?>
        <?php endforeach; ?>
      </nav>
      <div class="flex items-center gap-3">
        <?php if($usuarioLogueado): ?>
          <span class="text-sm text-gray-700">Hola, <strong><?= htmlspecialchars($usuarioLogueado['nombre']) ?></strong></span>
          <a href="/procesar-logout.php" class="text-sm text-gray-600 hover:text-gray-900 underline">Salir</a>
        <?php else: ?>
          <a href="/index.php?seccion=login" class="hidden md:inline text-sm text-gray-600 hover:text-gray-900 underline">Ingresar</a>
          <a href="/index.php?seccion=registro" class="hidden md:inline text-sm text-gray-600 hover:text-gray-900 underline">Registrarme</a>
        <?php endif; ?>
  <button class="btn-open-cart hidden md:inline-flex relative items-center gap-2 rounded-full border border-gray-200 bg-white/80 backdrop-blur px-3 py-1.5 text-sm hover:shadow-sm active:scale-[.98] transition" aria-label="Abrir carrito de compras">
          <span>Carrito</span>
          <span class="cart-count inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-600/90 px-1 text-[11px] text-white" aria-label="Cantidad de productos en el carrito">0</span>
        </button>
  <a href="/index.php?seccion=clases" class="hidden sm:inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-3 py-1.5 shadow hover:opacity-95 active:scale-[.99] transition">Preinscribirme</a>
        <button id="btn-menu" aria-label="Abrir menú de navegación" class="md:hidden inline-flex items-center justify-center h-10 w-10 rounded-xl bg-slate-200/80 text-slate-700 ring-1 ring-slate-300 hover:bg-slate-300/80 hover:text-slate-800 active:scale-95 transition focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-400">
          <span class="sr-only">Abrir menú</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>
</header>
<div id="panel-cart" class="fixed inset-0 z-[60] pointer-events-none opacity-0 transition-opacity duration-300" role="dialog" aria-labelledby="cart-title" aria-modal="true">
  <div class="absolute inset-0 bg-black/20 opacity-0 transition-opacity duration-300" data-close-cart aria-label="Cerrar carrito"></div>
  <aside id="panel-cart-aside" class="absolute right-0 top-0 h-full w-full max-w-md bg-white/80 backdrop-blur-md shadow-xl p-4 flex flex-col gap-4 border-l border-gray-200 translate-x-full transition-transform duration-300">
    <div class="flex items-center justify-between">
      <h2 id="cart-title" class="font-serif text-xl">Tu carrito</h2>
      <button class="h-9 w-9 inline-flex items-center justify-center rounded-full hover:bg-gray-100" data-close-cart aria-label="Cerrar carrito">
        <span class="sr-only">Cerrar</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
          <path d="M6.225 4.811 4.811 6.225 10.586 12l-5.775 5.775 1.414 1.414L12 13.414l5.775 5.775 1.414-1.414L13.414 12l5.775-5.775-1.414-1.414L12 10.586z"/>
        </svg>
      </button>
    </div>
    <div id="cart-items" class="flex-1 overflow-auto divide-y" role="list"></div>
      <div class="space-y-3">
      <div class="flex items-center justify-between text-sm">
        <span>Subtotal</span>
        <span id="cart-subtotal" class="font-medium">$0</span>
      </div>
      <a href="/checkout" class="w-full inline-flex items-center justify-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white py-2.5 shadow hover:opacity-95 active:scale-[.99] transition">Finalizar compra</a>
    </div>
  </aside>
</div>
<div id="mobile-menu" class="fixed inset-0 z-[55] pointer-events-none opacity-0 transition-opacity duration-300 md:hidden" role="dialog" aria-labelledby="mobile-menu-title" aria-modal="true">
  <div class="absolute inset-0 bg-black/20 opacity-0 transition-opacity duration-300" data-close-menu aria-label="Cerrar menú"></div>
  <aside id="mobile-menu-aside" class="absolute left-0 top-0 h-full w-full max-w-xs bg-white/90 backdrop-blur-md shadow-xl p-4 flex flex-col gap-4 border-r border-gray-200 -translate-x-full transition-transform duration-300">
    <div class="flex items-center justify-between">
      <h2 id="mobile-menu-title" class="font-serif text-lg">Menú</h2>
      <button class="h-9 w-9 inline-flex items-center justify-center rounded-full hover:bg-gray-100" data-close-menu aria-label="Cerrar menú">
        <span class="sr-only">Cerrar</span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-5 w-5" aria-hidden="true">
          <path d="M6.225 4.811 4.811 6.225 10.586 12l-5.775 5.775 1.414 1.414L12 13.414l5.775 5.775 1.414-1.414L13.414 12l5.775-5.775-1.414-1.414L12 10.586z"/>
        </svg>
      </button>
    </div>
    <nav class="mt-2 space-y-1" role="navigation" aria-label="Navegación móvil">
  <button class="btn-open-cart w-full mb-1 inline-flex items-center justify-between rounded-lg border border-gray-200 bg-white/80 backdrop-blur px-3 py-2 text-sm hover:shadow-sm active:scale-[.98] transition" aria-label="Abrir carrito de compras">
        <span>Carrito</span>
        <span class="cart-count inline-flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-600/90 px-1 text-[11px] text-white" aria-label="Cantidad de productos en el carrito">0</span>
      </button>
  <?php foreach($navegacion as $item): 
        $slug = htmlspecialchars($item['slug']); 
        $nombre = htmlspecialchars($item['nombre'] ?? $item['titulo'] ?? $slug); 
        if($slug === 'productos'): ?>
          <div class="space-y-1">
            <a class="block px-3 py-2 rounded-lg text-gray-800 hover:bg-gray-100 font-medium" href="/index.php?seccion=<?= $slug ?>"><?= $nombre ?></a>
            <div class="ml-4 space-y-1">
              <a class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100" href="/index.php?seccion=productos">Todos los Productos</a>
              <a class="block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100" href="/index.php?seccion=combos">Combos y Promociones</a>
            </div>
          </div>
        <?php else: ?>
          <a class="block px-3 py-2 rounded-lg text-gray-800 hover:bg-gray-100" href="/index.php?seccion=<?= $slug ?>"><?= $nombre ?></a>
        <?php endif; ?>
      <?php endforeach; ?>
      <a href="/index.php?seccion=clases" class="mt-2 inline-flex items-center justify-center w-full rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white py-2 shadow hover:opacity-95 active:scale-[.99] transition">Preinscribirme</a>
      <?php if($usuarioLogueado): ?>
        <div class="mt-4 px-3 py-2 rounded-lg bg-emerald-50 text-emerald-700 text-xs">
          Sesión: <?= htmlspecialchars($usuarioLogueado['email']) ?> <a href="/procesar-logout.php" class="underline ml-1">Salir</a>
        </div>
      <?php else: ?>
        <div class="mt-4 flex gap-2">
          <a href="/login" class="flex-1 text-center px-3 py-2 rounded-lg border border-gray-300 text-sm hover:bg-gray-100">Ingresar</a>
          <a href="/registro" class="flex-1 text-center px-3 py-2 rounded-lg bg-gradient-to-r from-amber-500 to-rose-500 text-white text-sm">Registrarme</a>
        </div>
      <?php endif; ?>
    </nav>
  </aside>
</div>
