<?php
$title = isset($pageTitle) ? $pageTitle : 'Latido Cerámico';
?>
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($title) ?></title>
  <meta name="description" content="Clases de cerámica para niñas y niños (4 a 13 años). Cupos reducidos, ambiente creativo. También productos artesanales." />
  <link rel="icon" href="/assets/img/favicon.svg" type="image/svg+xml" />
 

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <script src="https://cdn.tailwindcss.com"></script>

  <?php
    $css_v = @filemtime(__DIR__ . '/../assets/css/style.css') ?: time();
    $js_v  = @filemtime(__DIR__ . '/../assets/js/main.js') ?: time();
  ?>
  <link rel="stylesheet" href="/assets/css/style.css?v=<?= $css_v ?>" />
  <script src="/assets/js/main.js?v=<?= $js_v ?>" defer></script>
</head>
