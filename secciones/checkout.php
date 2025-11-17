<?php

if (session_status() === PHP_SESSION_NONE) session_start();
if(empty($_SESSION['usuario_publico'])) {
    header('Location: /login?next=' . urlencode('/checkout'));
    exit;
}
$__base = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\');
$usuario = $_SESSION['usuario_publico'] ?? null;
$prefNombre = $usuario['nombre'] ?? '';
$prefEmail = $usuario['email'] ?? '';
?>
<section class="py-8">
    <?php if(!empty($_SESSION['flash_checkout'])): $f = $_SESSION['flash_checkout']; unset($_SESSION['flash_checkout']); ?>
        <div class="mb-6 rounded-md border <?php echo $f['ok']? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800'; ?> px-4 py-3" role="alert" aria-live="polite">
            <?= htmlspecialchars($f['msg']) ?>
        </div>
    <?php endif; ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white/70 border border-gray-200 rounded-xl p-6">
            <h2 class="font-serif text-2xl mb-4">Datos del comprador</h2>
            <form action="<?= htmlspecialchars($__base) ?>/procesar-checkout.php" method="post" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700" for="nombre">Nombre y apellido</label>
                        <input required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" type="text"
                            id="nombre" name="nombre" autocomplete="name" value="<?= htmlspecialchars($prefNombre) ?>" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700" for="email">Email</label>
                        <input required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" type="email"
                            id="email" name="email" autocomplete="email" value="<?= htmlspecialchars($prefEmail) ?>" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700" for="telefono">Teléfono</label>
                        <input class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" type="tel" id="telefono"
                            name="telefono" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700" for="direccion">Dirección</label>
                        <input required class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" type="text"
                            id="direccion" name="direccion" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700" for="localidad">Localidad</label>
                        <input class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" type="text"
                            id="localidad" name="localidad" />
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700" for="cp">Código Postal</label>
                        <input class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" type="text" id="cp"
                            name="cp" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-700" for="notas">Notas</label>
                    <textarea class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2" id="notas" name="notas"
                        rows="3" placeholder="Indicaciones de entrega, horarios, etc."></textarea>
                </div>

                                <div class="pt-2">
                                    <h3 class="font-serif text-xl mb-2">Pago con tarjeta</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm text-gray-700" for="tarjeta_numero">Número de tarjeta</label>
                                            <input required inputmode="numeric" autocomplete="cc-number" maxlength="23"
                                                         class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 tracking-widest"
                                                         type="text" id="tarjeta_numero" name="tarjeta_numero" placeholder="4242 4242 4242 4242" />
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-700" for="tarjeta_nombre">Nombre en la tarjeta</label>
                                            <input required autocomplete="cc-name" class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                                         type="text" id="tarjeta_nombre" name="tarjeta_nombre" placeholder="Como figura en la tarjeta" />
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm text-gray-700" for="tarjeta_vencimiento">Vencimiento</label>
                                                <input required inputmode="numeric" autocomplete="cc-exp" maxlength="5"
                                                             class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                                             type="text" id="tarjeta_vencimiento" name="tarjeta_vencimiento" placeholder="MM/AA" />
                                            </div>
                                            <div>
                                                <label class="block text-sm text-gray-700" for="tarjeta_cvv">CVV</label>
                                                <input required inputmode="numeric" autocomplete="off" maxlength="4"
                                                             class="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                                                             type="password" id="tarjeta_cvv" name="tarjeta_cvv" placeholder="3 o 4 dígitos" />
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">No almacenamos los datos completos de tu tarjeta. Se usan solo para validar este pedido (demo).</p>
                                </div>
                <input type="hidden" id="carrito_json" name="carrito_json" />
                <button
                    class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-4 py-2 shadow hover:opacity-95 active:scale-[.99] transition">Confirmar
                    pedido</button>
            </form>
        </div>
        <aside class="bg-white/70 border border-gray-200 rounded-xl p-6">
            <h3 class="font-serif text-xl mb-4">Resumen</h3>
            <div id="checkout-resumen" class="space-y-3 text-sm"></div>
            <div class="mt-4 flex items-center justify-between">
                <span class="text-gray-600">Subtotal</span>
                <span id="checkout-subtotal" class="font-medium">$0</span>
            </div>
        </aside>
    </div>
</section>
<script>

    (function () {
        const STORAGE_KEY = 'latido_cart_v1';
        const resumenEl = document.getElementById('checkout-resumen');
        const subtotalEl = document.getElementById('checkout-subtotal');
        const hidden = document.getElementById('carrito_json');
        let items = [];
        try { items = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); } catch { items = []; }
        const fmt = (n) => `$${(n || 0).toLocaleString('es-AR')}`;
        if (items.length === 0) {
            resumenEl.innerHTML = '<div class="text-gray-600">Tu carrito está vacío. Volvé a <a class="underline" href="?seccion=productos">Productos</a>.</div>';
        } else {
            resumenEl.innerHTML = items.map(it => `
      <div class=\"flex items-center justify-between\">
        <div>${it.nombre} <span class=\"text-gray-500\">x${it.cantidad}</span></div>
        <div>${fmt(it.precio * it.cantidad)}</div>
      </div>
    `).join('');
        }
        const subtotal = items.reduce((a, it) => a + it.precio * it.cantidad, 0);
        subtotalEl.textContent = fmt(subtotal);
        hidden.value = JSON.stringify(items);
    })();
        (function(){
            const num = document.getElementById('tarjeta_numero');
            const exp = document.getElementById('tarjeta_vencimiento');
            if(num){
                num.addEventListener('input', ()=>{
                    let v = num.value.replace(/[^0-9]/g,'').slice(0,19);
                    v = v.replace(/(.{4})/g,'$1 ').trim();
                    num.value = v;
                });
            }
            if(exp){
                exp.addEventListener('input', ()=>{
                    let v = exp.value.replace(/[^0-9]/g,'').slice(0,4);
                    if(v.length >= 3) v = v.slice(0,2) + '/' + v.slice(2);
                    exp.value = v;
                });
            }
        })();
</script>