<?php
require_once 'clases/Secciones.php';
require_once 'clases/Config.php';
$nav = Secciones::enNavegacion();
Config::ensureSetup();
$MAIL = Config::get('contact_email', 'hola@latidoceramico.com');
?>
<footer class="mt-16 bg-white/50 backdrop-blur-sm border-t border-gray-200" role="contentinfo">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <a href="?seccion=inicio" class="inline-flex items-center gap-2 mb-3">
                    <img src="/assets/img/logoLT.webp" alt="Logotipo de Latido Cerámico" class="h-6 w-6" />
                    <span class="font-serif text-xl font-semibold text-gray-900">Latido Cerámico</span>
                </a>
                <p class="text-sm text-gray-600 max-w-md">
                    Talleres de cerámica para niñas y niños en Buenos Aires. Un espacio donde la creatividad no tiene límites y cada pequeña mano puede crear algo único. Los esperamos para compartir momentos especiales llenos de arte, diversión y aprendizaje.
                </p>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 mb-3">Navegación</h3>
                <nav>
                    <ul class="space-y-1 text-sm">
                        <?php foreach ($nav as $item):
                            $slug = htmlspecialchars($item['slug']);
                            $nombre = htmlspecialchars($item['nombre'] ?? $item['titulo'] ?? $slug); ?>
                            <li>
                                <a href="?seccion=<?= $slug ?>" 
                                   class="text-gray-600 hover:text-gray-900 transition-colors">
                                    <?= ucfirst($nombre) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            </div>
            <div>
                <h3 class="font-medium text-gray-900 mb-3">Contacto</h3>
                <div class="space-y-1 text-sm text-gray-600">
                    <p>Buenos Aires, Argentina</p>
                    <a href="mailto:<?= htmlspecialchars($MAIL) ?>" class="block hover:text-gray-900 transition-colors">
                        <?= htmlspecialchars($MAIL) ?>
                    </a>
                    <div class="flex gap-2 mt-3">
                        <a href="#" class="p-2 bg-white rounded-lg border hover:shadow-sm transition-shadow">
                            <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3zm-5 3.5a5.5 5.5 0 1 1 0 11 5.5 5.5 0 0 1 0-11zm0 2a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7zm5.75-.75a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                        </a>
                        <a href="#" class="p-2 bg-white rounded-lg border hover:shadow-sm transition-shadow">
                            <svg class="h-4 w-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 3.5A10 10 0 0 0 4.2 17.3L3 21l3.8-1.2A10 10 0 1 0 20 3.5zM12 20a8 8 0 0 1-4.1-1.1l-.3-.2-2.4.7.7-2.3-.2-.3A8 8 0 1 1 20 12a8 8 0 0 1-8 8zm4-5.2c-.2-.1-1.3-.6-1.4-.6s-.3-.1-.5.1-.5.6-.6.7-.2.2-.4.1a6.6 6.6 0 0 1-2-1.2 7.6 7.6 0 0 1-1.4-1.8c-.1-.2 0-.3.1-.4l.3-.4c.1-.1.1-.3 0-.5l-.6-1.5c-.2-.5-.4-.4-.5-.4h-.4a.8.8 0 0 0-.6.3 2.5 2.5 0 0 0-.8 1.9A4.3 4.3 0 0 0 7 12c0 .3.1.7.2 1s.7 1.5 1.6 2.4 2.3 1.6 2.6 1.8.6.2.8.3h.6a2 2 0 0 0 1.3-.6c.2-.2.5-.6.6-.8s.1-.3.1-.5-.1-.3-.2-.3z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500 text-center">
                © <?= date('Y') ?> Latido Cerámico - Buenos Aires, Argentina
            </p>
        </div>
    </div>
</footer>