<section class="py-16">
    <div class="mx-auto max-w-2xl text-center">
        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-rose-100 text-rose-700 mb-4">
            <svg viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6"><path d="M12 2a10 10 0 1 0 .001 20.001A10 10 0 0 0 12 2zm0 14a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm1-8v6h-2V8h2z"/></svg>
        </div>
        <h1 class="font-serif text-3xl mb-2">Ups, no encontramos esa página</h1>
        <p class="text-gray-700 mb-6">La sección
            <?php if(!empty($seccionInvalida)): ?>
                <code class="px-2 py-1 rounded bg-gray-100 border border-gray-200 text-sm"><?= htmlspecialchars($seccionInvalida) ?></code>
            <?php else: ?>
                solicitada
            <?php endif; ?>
            no existe.
        </p>
        <a href="?seccion=inicio" class="inline-flex items-center rounded-full bg-gradient-to-r from-amber-500 to-rose-500 text-white px-4 py-2 shadow hover:opacity-95 active:scale-[.99] transition">Volver al inicio</a>
    </div>
</section>