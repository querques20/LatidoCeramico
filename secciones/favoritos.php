<?php
require_once 'clases/Productos.php';
?>
<section class="py-10">
  <div class="mb-4 text-gray-700 text-sm">Tus productos guardados se muestran aquí (se guardan en este dispositivo).</div>
  <div id="fav-empty" class="hidden rounded-xl border border-amber-200 bg-amber-50 text-amber-900 p-4">
    No tenés favoritos aún. Agregá desde el catálogo apretando el corazón.
  </div>
  <div id="fav-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"></div>
</section>
<script>
(function(){
  const KEY = 'latido_favs_v1';
  const grid = document.getElementById('fav-grid');
  const empty = document.getElementById('fav-empty');
  let favs = [];
  try { favs = JSON.parse(localStorage.getItem(KEY) || '[]'); } catch { favs = []; }

  const PRODUCTS = <?php echo json_encode(\Productos::todos(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>;
  const findMatch = (fav) => {
    let m = PRODUCTS.find(p => String(p.id) === String(fav.id));
    if (m) return m;
    const name = (fav.nombre||'').toLowerCase().trim();
    if (!name) return null;
    m = PRODUCTS.find(p => (p.nombre||'').toLowerCase().trim() === name);
    return m || null;
  };

  if (Array.isArray(favs) && favs.length) {
    let changed = false;
    favs = favs.map(f => {
      const m = findMatch(f);
      if (m && String(m.id) !== String(f.id)) changed = true;
      return m ? { ...f, id: m.id, precio: m.precio, imagen: m.imagen, descripcion: f.descripcion || m.descripcion } : f;
    });
    if (changed) localStorage.setItem(KEY, JSON.stringify(favs));
  }

  if(!favs.length){ empty.classList.remove('hidden'); return; }

  const fmt = (n)=> `$${(n||0).toLocaleString('es-AR')}`;
  const resolveImg = (img) => {
    if (!img) return '';
    if (/^https?:\/\//.test(img)) return img; 
    if (img.startsWith('/')) return img;       
    if (img.startsWith('assets/')) return '/' + img; 
    return `/assets/img/productos/${img}`;      
  };

  grid.innerHTML = favs.map(p => {
    const src = resolveImg(p.imagen || '');
    return `
    <article class="rounded-xl border border-white/50 bg-white/70 backdrop-blur overflow-hidden" data-nombre="${(p.nombre||'').replace(/"/g,'&quot;')}">
      <div class="aspect-[4/3] bg-purple-100 flex items-center justify-center">
        <img src="${src}" alt="${p.nombre}" class="w-full h-full object-cover"/>
      </div>
      <div class="p-4">
        <h3 class="font-medium text-gray-900 mb-1">${p.nombre}</h3>
        <div class="text-sm text-gray-700 mb-2">${p.descripcion||''}</div>
        <div class="text-gray-900 font-medium">${fmt(p.precio)}</div>
        <div class="mt-3 flex items-center gap-2">
          <a href="?seccion=detalle-producto&id=${encodeURIComponent(p.id)}" data-role="fav-view" class="inline-flex items-center rounded-full border border-white/50 bg-white/70 backdrop-blur px-3 py-1.5 text-sm text-gray-900 hover:shadow-sm active:scale-[.99] transition">Ver</a>
          <button class="btn-fav-toggle inline-flex items-center rounded-full border border-rose-200 bg-rose-50/80 text-rose-700 px-3 py-1.5 text-sm hover:bg-rose-50 active:scale-[.99] transition" data-id="${p.id}">Quitar</button>
        </div>
      </div>
    </article>`;
  }).join('');

  grid.addEventListener('click', (e) => {
    const btn = e.target.closest('.btn-fav-toggle');
    if (btn) {
      const id = btn.getAttribute('data-id');
      favs = favs.filter(f => String(f.id) !== String(id));
      localStorage.setItem(KEY, JSON.stringify(favs));
      location.reload();
      return;
    }

    const link = e.target.closest('a[data-role="fav-view"]');
    if (link) {
      const url = new URL(link.getAttribute('href'), location.href);
      const id = String(url.searchParams.get('id')||'');
      const card = link.closest('article');
      const nombre = (card?.getAttribute('data-nombre')||'').toLowerCase().trim();
      const byId = PRODUCTS.find(p => String(p.id) === id);
      if (!byId && nombre) {
        const m = PRODUCTS.find(p => (p.nombre||'').toLowerCase().trim() === nombre);
        if (m) {
          e.preventDefault();
          location.href = `?seccion=detalle-producto&id=${encodeURIComponent(m.id)}`;
        }
      }
    }
  });
})();
</script>
