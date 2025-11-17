<?php $__base = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\'); ?>
<section class="mx-auto max-w-7xl px-6 sm:px-8 lg:px-12 py-16 md:py-20">
    <?php if(!empty($_SESSION['flash_pre'])): $f = $_SESSION['flash_pre']; unset($_SESSION['flash_pre']); ?>
        <div class="mb-6 rounded-xl border <?php echo $f['ok']? 'border-green-200 bg-green-50 text-green-800' : 'border-red-200 bg-red-50 text-red-800'; ?> px-6 py-4">
            <?= htmlspecialchars($f['msg']) ?>
        </div>
    <?php endif; ?>
  
    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-3 text-gray-800 mb-10 max-w-2xl">
        <li class="flex items-center">
            <span class="inline-block h-2 w-2 rounded-full bg-orange-500 mr-3"></span>
            Cupos reducidos: 6 por clase
        </li>
        <li class="flex items-center">
            <span class="inline-block h-2 w-2 rounded-full bg-purple-500 mr-3"></span>
            Materiales incluidos
        </li>
        <li class="flex items-center">
            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 mr-3"></span>
            Ambiente creativo y lúdico
        </li>
    </ul>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div class="bg-white/70 p-6 md:p-8 rounded-xl border border-gray-200">
            <h2 class="font-serif text-2xl mb-4">Preinscripción</h2>
            <form action="<?= htmlspecialchars($__base) ?>/procesar-preinscripcion.php" method="post" class="space-y-4" id="form-pre">
                <div>
                    <label class="block text-sm mb-2" for="nino">Niño/a</label>
                    <input class="w-full rounded-lg border border-black/10 px-3 py-2.5" type="text" id="nino" name="nino"
                        required />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-2" for="edad">Edad</label>
                        <input class="w-full rounded-lg border border-black/10 px-3 py-2.5" type="number" id="edad"
                            name="edad" min="4" max="13" required />
                    </div>
                    <div>
                        <label class="block text-sm mb-2" for="turno">Turno preferido</label>
                        <select class="w-full rounded-lg border border-black/10 px-3 py-2.5" id="turno" name="turno"
                            required>
                            <option value="">Seleccionar…</option>
                            <option>Miércoles 17:00–18:30</option>
                            <option>Sábados 10:00–11:30</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm mb-2" for="adulto">Adulto responsable</label>
                    <input class="w-full rounded-lg border border-black/10 px-3 py-2.5" type="text" id="adulto" name="adulto"
                        required />
                </div>
                <div>
                    <label class="block text-sm mb-2" for="email">Email</label>
                    <input class="w-full rounded-lg border border-black/10 px-3 py-2.5" type="email" id="email" name="email"
                        required />
                </div>
                <button class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-4 py-2 shadow hover:opacity-95 active:scale-[.99] transition"
                    type="submit">Enviar preinscripción</button>
            </form>
        </div>

        <div class="text-gray-800">
            <h2 class="font-serif text-2xl mb-4">Sobre las clases</h2>
            <p class="mb-4">Grupos chicos, tiempos amables y propuestas que combinan juego y técnica. Empezamos por lo
                esencial —pellizco, rollos y planchas— y desde ahí cada niña/o explora su universo: animales, vasijas,
                personajes, objetos de uso cotidiano.</p>
            <p class="mb-6">Valoramos el proceso por encima del resultado: equivocarse, probar y volver a intentar es
                parte del aprendizaje. Nos interesa que se vayan con orgullo de lo hecho y ganas de volver.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="rounded-xl border border-gray-200 bg-white/70 p-6">
                    <h3 class="font-medium mb-2">Datos clave</h3>
                    <ul class="text-gray-800 space-y-2 text-sm">
                        <li>Edad sugerida: 4 a 13 años</li>
                        <li>Cupos reducidos (máx. 6)</li>
                        <li>Materiales, horneado y esmaltado incluidos</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-gray-200 bg-white/70 p-6">
                    <h3 class="font-medium mb-2">Qué se trabaja</h3>
                    <ul class="text-gray-800 space-y-2 text-sm">
                        <li>Motricidad fina y coordinación</li>
                        <li>Planificación y paciencia</li>
                        <li>Creatividad y expresión personal</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../clases/Galeria.php'; Galeria::ensureSetup(); $gal = Galeria::visibles(); ?>
<section class="mx-auto max-w-7xl px-6 sm:px-8 lg:px-12 py-16 md:py-20">
    <h2 class="font-serif text-2xl mb-6 text-center">Galería del taller</h2>
    <p class="text-gray-700 text-center mb-8 max-w-2xl mx-auto">Un vistazo al día a día: explorando texturas, creando formas y celebrando cada logro junto a nuevos amigos.</p>
    <?php if($gal): ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php foreach($gal as $g): ?>
                <figure class="aspect-square rounded-xl overflow-hidden border border-gray-200 bg-white/70 shadow-sm">
                    <img src="/assets/img/galeria/<?= htmlspecialchars($g['imagen']) ?>" alt="Imagen de galería" class="w-full h-full object-cover" loading="lazy" />
                    <figcaption class="sr-only">Imagen de galería</figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="aspect-square rounded-xl bg-gradient-to-br from-amber-200 via-orange-200 to-rose-200 shadow-sm"></div>
            <div class="aspect-square rounded-xl bg-gradient-to-br from-purple-200 via-pink-200 to-orange-200 shadow-sm"></div>
            <div class="aspect-square rounded-xl bg-gradient-to-br from-emerald-200 via-teal-200 to-blue-200 shadow-sm"></div>
            <div class="aspect-square rounded-xl bg-gradient-to-br from-yellow-200 via-amber-200 to-orange-200 shadow-sm"></div>
            <div class="aspect-square rounded-xl bg-gradient-to-br from-rose-200 via-pink-200 to-purple-200 shadow-sm"></div>
            <div class="aspect-square rounded-xl bg-gradient-to-br from-sky-200 via-blue-200 to-indigo-200 shadow-sm"></div>
            <div class="aspect-square rounded-xl bg-gradient-to-br from-violet-200 via-purple-200 to-pink-200 shadow-sm"></div>
            <div class="aspect-square rounded-xl bg-gradient-to-br from-green-200 via-emerald-200 to-teal-200 shadow-sm"></div>
        </div>
        <div class="text-center mt-8">
            <p class="text-sm text-gray-600 mb-4">Las imágenes reales del taller serán agregadas próximamente.</p>
            <a href="?seccion=contacto" class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-4 py-2 shadow hover:opacity-95 active:scale-[.99] transition">¿Tenés preguntas? Contactanos</a>
        </div>
    <?php endif; ?>
</section>