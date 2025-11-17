<?php
session_start();
if (empty($_SESSION['admin_user_id'])) { header('Location: ../login.php'); exit; }
require_once __DIR__ . '/../classes/Conexion.php';
require_once __DIR__ . '/../classes/ConfigAdmin.php';
require_once __DIR__ . '/../includes/functions.php';

$campos = [
  'site_name','contact_email','contact_phone','whatsapp_link','instagram_url',
  'carrito_exp_min','maintenance_mode'
];

foreach ($campos as $c) {
  $val = isset($_POST[$c]) ? trim((string)$_POST[$c]) : '';
  ConfigAdmin::set($c, $val);
}


try {
  $prev = ConfigAdmin::get('whatsapp_qr', '');

  if (!empty($_POST['whatsapp_qr_borrar']) && $prev) {
    eliminar_imagen($prev, 'assets/img/qr');
    ConfigAdmin::delete('whatsapp_qr');
    $prev = '';
  }

  if (!empty($_FILES['whatsapp_qr']) && $_FILES['whatsapp_qr']['error'] === UPLOAD_ERR_OK) {
    $nuevo = subir_imagen($_FILES['whatsapp_qr'], 'assets/img/qr');
    if ($nuevo !== false) {
     
      ConfigAdmin::set('whatsapp_qr', $nuevo);
      if ($prev) eliminar_imagen($prev, 'assets/img/qr');
    }
  }
} catch (Throwable $e) {}

if (class_exists('ConfigAdmin')) {
  if (method_exists('ConfigAdmin', 'delete')) {
    ConfigAdmin::delete('productos_por_pagina');
    ConfigAdmin::delete('home_carousel');
  } else {
    $pdo = (new Conexion())->getConexion();
    $st = $pdo->prepare('DELETE FROM ajustes WHERE clave IN ("productos_por_pagina","home_carousel")');
    $st->execute();
  }
}

$_SESSION['mensaje_exito'] = 'Configuración guardada';
header('Location: ../index.php?sec=configuracion');
exit;
