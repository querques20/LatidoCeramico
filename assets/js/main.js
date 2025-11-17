/**
 * @author Latido Cerámico
 * @version 1.0
 */

const qs = (s, r = document) => r.querySelector(s);
const qsa = (s, r = document) => Array.from(r.querySelectorAll(s));

(() => {
  const btn = qs('#btn-menu');
  const panel = qs('#mobile-menu');
  const aside = qs('#mobile-menu-aside');
  
  if (!btn || !panel || !aside) return;

 
  const open = () => {
    panel.classList.remove('pointer-events-none');
    document.body.classList.add('overflow-hidden');
    requestAnimationFrame(() => {
      panel.classList.remove('opacity-0');
      const overlay = panel.firstElementChild;
      overlay && overlay.classList.remove('opacity-0');
      aside.classList.remove('-translate-x-full');
    });
  };


  const close = () => {
    panel.classList.add('opacity-0');
    const overlay = panel.firstElementChild;
    overlay && overlay.classList.add('opacity-0');
    aside.classList.add('-translate-x-full');
    setTimeout(() => {
      panel.classList.add('pointer-events-none');
      document.body.classList.remove('overflow-hidden');
    }, 300);
  };


  btn.addEventListener('click', open);
  panel.addEventListener('click', (e) => {
    if (e.target.matches('[data-close-menu]')) close();
  });
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') close();
  });
})();

(() => {
  const KEY = 'latido_favs_v1';
  
  const load = () => { 
    try { 
      return JSON.parse(localStorage.getItem(KEY) || '[]'); 
    } catch { 
      return []; 
    } 
  };
  const save = (arr) => localStorage.setItem(KEY, JSON.stringify(arr));
  
  let favs = load();


  const isFav = (id) => favs.some(p => String(p.id) === String(id));
  

  const toggleFav = (prod) => {
    const id = String(prod.id);
    if (isFav(id)) {
      favs = favs.filter(p => String(p.id) !== id);
    } else {
      favs.push(prod);
    }
    save(favs);
  };


  const paint = (btn, active) => {
    if (!btn) return;
    if (active) {
      btn.classList.add('ring-rose-200', 'bg-rose-50');
    } else {
      btn.classList.remove('ring-rose-200', 'bg-rose-50');
    }
  };


  const bind = (root = document) => {
    qsa('.btn-fav', root).forEach(btn => {
      const prod = {
        id: btn.getAttribute('data-id'),
        nombre: btn.getAttribute('data-nombre'),
        precio: parseFloat(btn.getAttribute('data-precio')) || 0,
        imagen: btn.getAttribute('data-imagen') || '',
        descripcion: btn.getAttribute('data-descripcion') || ''
      };
      paint(btn, isFav(prod.id));
      btn.addEventListener('click', () => { 
        toggleFav(prod); 
        paint(btn, isFav(prod.id)); 
      });
    });
  };


  bind(document);
})();

(() => {
  const STORAGE_KEY = "latido_cart_v1";
  
  const panel = qs("#panel-cart");
  const aside = qs("#panel-cart-aside");
  const btnCartEls = qsa(".btn-open-cart");
  const countBadges = qsa(".cart-count");
  const listEl = qs("#cart-items");
  const subtotalEl = qs("#cart-subtotal");

  const load = () => {
    try {
      return JSON.parse(localStorage.getItem(STORAGE_KEY) || "[]");
    } catch {
      return [];
    }
  };
  
  const save = (items) => localStorage.setItem(STORAGE_KEY, JSON.stringify(items));

  const debounce = (fn, ms = 400) => {
    let t; return (...args) => { clearTimeout(t); t = setTimeout(() => fn(...args), ms); };
  };
  const serializeForSync = () => state.items.map(it => ({ id: Number(it.id) || 0, cantidad: Number(it.cantidad) || 1 }));
  const syncNow = () => {
    const payload = JSON.stringify(serializeForSync());
    fetch('/api/carrito-sync.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      credentials: 'same-origin',
      body: new URLSearchParams({ items: payload })
    }).catch(() => {});
  };
  const syncDebounced = debounce(syncNow, 500);
  

  const fmt = (n) => `$${(n || 0).toLocaleString("es-AR")}`;


  const state = { items: load() };


  const qty = () => state.items.reduce((a, it) => a + (it.cantidad || 0), 0);
  

  const subtotal = () => state.items.reduce((a, it) => a + it.precio * it.cantidad, 0);

  const syncCount = () => {
    const val = qty();
    countBadges.forEach((el) => (el.textContent = val));
  };


  const render = () => {
    if (!listEl) return;
    
    if (state.items.length === 0) {
      listEl.innerHTML = '<div class="p-6 text-center text-gray-600">Tu carrito está vacío.</div>';
    } else {
      listEl.innerHTML = state.items
        .map((it, idx) => `
          <div class="py-3 flex items-center gap-3">
            <div class="h-12 w-12 rounded bg-purple-100 flex items-center justify-center text-xs text-gray-700">${
              it.nombre[0]?.toUpperCase() || "·"
            }</div>
            <div class="flex-1">
              <div class="font-medium text-gray-900">${it.nombre}</div>
              <div class="text-sm text-gray-600">${fmt(it.precio)} · x${it.cantidad}</div>
            </div>
            <div class="flex items-center gap-1">
              <button class="h-7 w-7 rounded-full bg-gray-100 hover:bg-gray-200" data-act="dec" data-idx="${idx}">-</button>
              <button class="h-7 w-7 rounded-full bg-gray-100 hover:bg-gray-200" data-act="inc" data-idx="${idx}">+</button>
              <button class="h-7 w-7 rounded-full hover:bg-red-50 text-red-600" data-act="del" data-idx="${idx}">✕</button>
            </div>
          </div>
        `)
        .join("");
    }
    
    if (subtotalEl) subtotalEl.textContent = fmt(subtotal());
    syncCount();
  };


  const open = () => {
    if (!panel || !aside) return;
    panel.classList.remove('pointer-events-none');
    document.body.classList.add('overflow-hidden');
    requestAnimationFrame(() => {
      panel.classList.remove('opacity-0');
      const overlay = panel.firstElementChild;
      overlay && overlay.classList.remove('opacity-0');
      aside.classList.remove('translate-x-full');
    });
  };
  
  const close = () => {
    if (!panel || !aside) return;
    panel.classList.add('opacity-0');
    const overlay = panel.firstElementChild;
    overlay && overlay.classList.add('opacity-0');
    aside.classList.add('translate-x-full');
    setTimeout(() => {
      panel.classList.add('pointer-events-none');
      document.body.classList.remove('overflow-hidden');
    }, 300);
  };


  const ensureToastRoot = () => {
    let root = qs('#toast-root');
    if (!root) {
      root = document.createElement('div');
      root.id = 'toast-root';
      root.className = 'fixed top-4 right-4 z-50 space-y-2 pointer-events-none';
      document.body.appendChild(root);
    }
    return root;
  };


  const showToast = (msg) => {
    const root = ensureToastRoot();
    const el = document.createElement('div');
    el.className = 'pointer-events-auto select-none max-w-sm rounded-xl bg-white/80 backdrop-blur ring-1 ring-white/50 shadow-lg text-gray-900 px-4 py-3 flex items-start gap-3 transition transform duration-300 ease-out opacity-0 translate-y-2';
    el.innerHTML = `
      <span class="shrink-0 inline-flex h-6 w-6 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">✓</span>
      <div class="text-sm">${msg}</div>
      <button class="ml-3 text-gray-500 hover:text-gray-700" aria-label="Cerrar">✕</button>
    `;
    root.appendChild(el);

  
    requestAnimationFrame(() => {
      el.classList.remove('opacity-0', 'translate-y-2');
    });

   
    const closeToast = () => {
      el.classList.add('opacity-0', 'translate-y-2');
      setTimeout(() => el.remove(), 250);
    };
    
    el.querySelector('button')?.addEventListener('click', closeToast);
    setTimeout(closeToast, 5000);
  };


  if (btnCartEls.length) {
    btnCartEls.forEach((btn) => btn.addEventListener("click", open));
  }
  
  if (panel) {
    panel.addEventListener("click", (e) => {
     
      if (e.target.matches("[data-close-cart]") || e.target.closest("[data-close-cart]")) close();
    });
  
    window.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
  }


  if (listEl) {
    listEl.addEventListener("click", (e) => {
      const btn = e.target.closest("button");
      if (!btn) return;
      
      const act = btn.getAttribute("data-act");
      const idx = parseInt(btn.getAttribute("data-idx"), 10);
      if (Number.isNaN(idx)) return;
      
      const it = state.items[idx];
      if (!it) return;
      
      if (act === "inc") it.cantidad++;
      if (act === "dec") it.cantidad = Math.max(1, it.cantidad - 1);
      if (act === "del") state.items.splice(idx, 1);
      
  save(state.items);
  render();
  syncDebounced();
    });
  }


  const bindAddButtons = (root = document) => {
    qsa(".btn-add-cart", root).forEach((btn) => {
      btn.addEventListener("click", () => {
        if (btn.hasAttribute('disabled') || btn.classList.contains('cursor-not-allowed')) return;
        const id = btn.getAttribute("data-id");
        const nombre = btn.getAttribute("data-nombre");
        const precio = parseFloat(btn.getAttribute("data-precio")) || 0;
        
        const found = state.items.find((it) => it.id === id);
        if (found) {
          found.cantidad++;
        } else {
          state.items.push({ id, nombre, precio, cantidad: 1 });
        }
        
  save(state.items);
  render();
  syncDebounced();
        showToast(`${nombre} se añadió al carrito`);
      });
    });
  };


  render();
  bindAddButtons(document);

  syncDebounced();
})();
