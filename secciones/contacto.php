<?php 
$__base = rtrim(dirname($_SERVER['PHP_SELF'] ?? ''), '/\\'); 
require_once __DIR__ . '/../clases/Config.php';
Config::ensureSetup();
$TEL = Config::get('contact_phone', '+54 9 11 2345-6789');
$MAIL = Config::get('contact_email', 'hola@latidoceramico@gmail.com');
$WA_LINK = Config::get('whatsapp_link', 'https://wa.me/5491123456789');
$IG_URL = Config::get('instagram_url', 'https://instagram.com/latidoceramico');
$QR_FILE = Config::get('whatsapp_qr', '');
$IG_LABEL = 'Instagram';
?>
<section class="relative bg-gradient-to-br from-purple-50 via-pink-50 to-orange-50 overflow-hidden">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-gradient-to-br from-purple-200/30 to-pink-200/30 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-32 w-80 h-80 bg-gradient-to-br from-amber-200/40 to-orange-200/40 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/3 w-72 h-72 bg-gradient-to-br from-pink-200/35 to-purple-200/35 rounded-full blur-3xl"></div>
    </div>
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-20 sm:py-24 lg:py-28">
        <?php if(!empty($_SESSION['flash_contacto'])): $f = $_SESSION['flash_contacto']; unset($_SESSION['flash_contacto']); ?>
            <div class="mx-auto max-w-4xl mb-16 rounded-2xl border <?php echo $f['ok']? 'border-green-200 bg-green-50/90 text-green-800' : 'border-red-200 bg-red-50/90 text-red-800'; ?> px-8 py-6 backdrop-blur-sm shadow-lg" role="alert">
                <?= htmlspecialchars($f['msg']) ?>
            </div>
        <?php endif; ?>
        <div class="text-center mb-16">
            <div class="inline-block mb-6">
                <h2 class="font-serif text-2xl mb-4">Conectemos</h2>
                <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    ¿Listos para que sus pequeños descubran el maravilloso mundo de la cerámica? 
                    Estamos aquí para responder todas sus preguntas.
                </p>
            </div>
        </div>
        <div class="space-y-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="group">
                    <a href="tel:<?= htmlspecialchars(preg_replace('/[^+0-9]/','',$TEL)) ?>" class="block h-full">
                        <div class="relative h-full bg-white/80 backdrop-blur-lg rounded-3xl p-6 shadow-xl border border-white/50 hover:shadow-2xl hover:scale-105 transition-all duration-500">
                            <div class="text-center pt-2">
                                <h3 class="font-medium text-gray-900 mb-3">WhatsApp Directo</h3>
                                <p class="text-gray-600 mb-4 leading-relaxed text-sm">
                                    Hablemos ahora mismo sobre las clases y horarios disponibles
                                </p>
                                <p class="text-green-700 font-medium"><?= htmlspecialchars($TEL) ?></p>
                                <div class="mt-4 inline-flex items-center gap-2 text-xs text-green-600">
                                    Respuesta inmediata
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="<?= htmlspecialchars($IG_URL) ?>" target="_blank" rel="noopener" class="block h-full">
                        <div class="relative h-full bg-white/80 backdrop-blur-lg rounded-3xl p-6 shadow-xl border border-white/50 hover:shadow-2xl hover:scale-105 transition-all duration-500">
                            <div class="text-center pt-2">
                                <h3 class="font-medium text-gray-900 mb-3">Seguinos</h3>
                                <p class="text-gray-600 mb-4 leading-relaxed text-sm">
                                    Mirá las increíbles creaciones de nuestros pequeños artistas
                                </p>
                                <p class="text-pink-700 font-medium"><?= $IG_LABEL ?></p>
                                <div class="mt-4 inline-flex items-center gap-2 text-xs text-pink-600">
                                    Ver galería
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="group">
                    <a href="mailto:<?= htmlspecialchars($MAIL) ?>" class="block h-full">
                        <div class="relative h-full bg-white/80 backdrop-blur-lg rounded-3xl p-6 shadow-xl border border-white/50 hover:shadow-2xl hover:scale-105 transition-all duration-500">
                            <div class="text-center pt-2">
                                <h3 class="font-medium text-gray-900 mb-3">Correo</h3>
                                <p class="text-gray-600 mb-4 leading-relaxed text-sm">
                                    Para consultas detalladas y coordinación de inscripciones
                                </p>
                                <p class="text-blue-700 font-medium"><?= htmlspecialchars($MAIL) ?></p>
                                <div class="mt-4 inline-flex items-center gap-2 text-xs text-blue-600">
                                    Escribir email
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="grid grid-cols-1 xl:grid-cols-4 gap-12">
                <div class="xl:col-span-3">
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-8 lg:p-10 shadow-2xl border border-white/50">
                        <div class="text-center mb-8">
                            <h2 id="form-contacto" class="font-serif text-2xl mb-4">
                                Contanos tu Proyecto
                            </h2>
                            <p class="text-gray-600 leading-relaxed max-w-2xl mx-auto">
                                Completá el formulario y nos comunicaremos contigo para coordinar la inscripción 
                                o resolver cualquier duda sobre nuestros talleres.
                            </p>
                        </div>
                        <form action="<?= htmlspecialchars($__base) ?>/procesar-contacto.php" method="post" class="space-y-6">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-medium text-gray-900 mb-3" for="nombre">
                                        Nombre y apellido
                                    </label>
                                    <input class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition-all duration-300 bg-white/70" 
                                           type="text" 
                                           id="nombre" 
                                           name="nombre" 
                                           placeholder="Tu nombre completo"
                                           required />
                                </div>
                                <div>
                                    <label class="block font-medium text-gray-900 mb-3" for="email">
                                        Correo electrónico
                                    </label>
                                    <input class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition-all duration-300 bg-white/70" 
                                           type="email" 
                                           id="email" 
                                           name="email" 
                                           placeholder="tu@email.com"
                                           required />
                                </div>
                            </div>
                            <div>
                                <label class="block font-medium text-gray-900 mb-3" for="mensaje">
                                    Contanos qué te interesa
                                </label>
                                <textarea class="w-full rounded-xl border-2 border-gray-200 px-4 py-3 text-gray-900 placeholder-gray-500 focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20 focus:outline-none transition-all duration-300 resize-none bg-white/70" 
                                          id="mensaje" 
                                          name="mensaje" 
                                          rows="5" 
                                          placeholder="Contanos sobre la edad de tu hijo/a, qué tipo de taller te interesa, horarios preferidos, o cualquier pregunta que tengas..."
                                          required></textarea>
                            </div>                          
                            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between pt-6 border-t-2 border-gray-200">
                                <p class="text-sm text-gray-600">
                                    Todos los campos son obligatorios. Te responderemos dentro de las 24 horas.
                                </p>
                                <button class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-amber-500 to-rose-500 text-white font-medium rounded-xl shadow-lg hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2"
                                    type="submit">
                                    Enviar Mensaje
                                </button>
                            </div>
                        </form>
                    </div>
                </div>   
                <div class="xl:col-span-1">
                    <div class="bg-white/80 backdrop-blur-lg rounded-3xl p-6 shadow-2xl border border-white/50 text-center h-full flex flex-col justify-center">
                        <div class="mb-6">
                            <h3 class="font-medium text-gray-900 mb-3">Acceso Directo</h3>
                        </div>
                        <div class="mb-6">
                            <div class="inline-block p-4 bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl border-2 border-green-200 shadow-inner">
                                <?php if ($QR_FILE): ?>
                                    <img src="/assets/img/qr/<?= htmlspecialchars($QR_FILE) ?>" alt="QR de WhatsApp" class="w-40 h-40 object-contain rounded-lg shadow" loading="lazy" />
                                <?php else: ?>
                                    <div class="w-28 h-28 bg-gradient-to-br from-green-200 via-emerald-200 to-teal-200 rounded-xl flex items-center justify-center shadow-lg">
                                        <div class="text-center">
                                            <div class="grid grid-cols-3 gap-1 mb-2">
                                                <div class="w-2 h-2 bg-green-600 rounded-sm"></div>
                                                <div class="w-2 h-2 bg-white rounded-sm"></div>
                                                <div class="w-2 h-2 bg-green-600 rounded-sm"></div>
                                                <div class="w-2 h-2 bg-white rounded-sm"></div>
                                                <div class="w-2 h-2 bg-green-600 rounded-sm"></div>
                                                <div class="w-2 h-2 bg-white rounded-sm"></div>
                                                <div class="w-2 h-2 bg-green-600 rounded-sm"></div>
                                                <div class="w-2 h-2 bg-white rounded-sm"></div>
                                                <div class="w-2 h-2 bg-green-600 rounded-sm"></div>
                                            </div>
                                            <div class="text-xs text-gray-700 font-bold">QR Code</div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>                   
                        <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                            Escaneá con la cámara de tu teléfono para abrir WhatsApp
                        </p>                    
                        <a href="<?= htmlspecialchars($WA_LINK) ?>?text=Hola%20Latido%20Cerámico%2C%20me%20interesa%20saber%20más%20sobre%20las%20clases" 
                           target="_blank" 
                           rel="noopener"
                           class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg shadow-lg hover:shadow-xl hover:scale-105 transition-all duration-300">
                            Abrir WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>