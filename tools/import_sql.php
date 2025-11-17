<?php
// Uso: php tools/import_sql.php "ruta/al/archivo.sql" [dbname] [host] [user] [pass]
// Por defecto: dbname=latidoceramico, host=localhost, user=root, pass=""

if ($argc < 2) {
    fwrite(STDERR, "Uso: php tools/import_sql.php <archivo.sql> [dbname] [host] [user] [pass]\n");
    exit(1);
}

$file = $argv[1];
$dbname = $argv[2] ?? 'latidoceramico';
$host = $argv[3] ?? 'localhost';
$user = $argv[4] ?? 'root';
$pass = $argv[5] ?? '';

if (!is_file($file)) {
    fwrite(STDERR, "No existe el archivo: {$file}\n");
    exit(1);
}

function execPdo(PDO $pdo, string $sql): void {
    $sql = trim($sql);
    if ($sql === '') return;
    $pdo->exec($sql);
}

try {
    // Crear base si no existe usando mysqli (para permitir multi_query luego)
    $mysqliBoot = @new mysqli($host, $user, $pass);
    if ($mysqliBoot->connect_errno) {
        throw new RuntimeException('Conexión fallida: ' . $mysqliBoot->connect_error);
    }
    $mysqliBoot->set_charset('utf8mb4');
    if (!$mysqliBoot->query("CREATE DATABASE IF NOT EXISTS `{$dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
        throw new RuntimeException('Error creando DB: ' . $mysqliBoot->error);
    }
    $mysqliBoot->close();

    // Conectar a la base objetivo
    $mysqli = @new mysqli($host, $user, $pass, $dbname);
    if ($mysqli->connect_errno) {
        throw new RuntimeException('Conexión DB fallida: ' . $mysqli->connect_error);
    }
    $mysqli->set_charset('utf8mb4');

    $raw = file_get_contents($file);
    if ($raw === false) {
        throw new RuntimeException('No se pudo leer el archivo SQL');
    }
    // Remover BOM
    $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
    // Eliminar USE ...; ya que estamos conectados
    $raw = preg_replace('/^\s*USE\s+[^;]+;\s*$/im', '', $raw);

    // Ejecutar con multi_query para respetar los ';' del archivo
    if (!$mysqli->multi_query($raw)) {
        throw new RuntimeException('Error al ejecutar SQL: ' . $mysqli->error);
    }
    // Consumir todos los resultados (requerido por multi_query)
    do {
        if ($res = $mysqli->store_result()) { $res->free(); }
    } while ($mysqli->more_results() && $mysqli->next_result());
    if ($mysqli->errno) {
        throw new RuntimeException('Error pos-ejecución: ' . $mysqli->error);
    }
    $mysqli->close();

    echo "Importación OK: {$file}\n";
    exit(0);
} catch (Throwable $e) {
    fwrite(STDERR, "Error al importar: " . $e->getMessage() . "\n");
    exit(1);
}
