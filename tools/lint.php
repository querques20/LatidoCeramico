<?php
$root = __DIR__ . '/..';
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$errors = [];
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    $path = $file->getPathname();
    if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') continue;
    $rel = str_replace('\\', '/', substr($path, strlen($root)+1));
    if (preg_match('#^vendor/#', $rel)) continue;
    $cmd = escapeshellcmd(PHP_BINARY) . ' -l ' . escapeshellarg($path);
    exec($cmd, $out, $code);
    if ($code !== 0) {
        $errors[] = [
            'file' => $rel,
            'message' => implode("\n", $out)
        ];
    }
    $out = [];
}
if (empty($errors)) {
    echo "OK: Sin errores de sintaxis" . PHP_EOL;
    exit(0);
}
foreach ($errors as $e) {
    echo "--\n" . $e['file'] . "\n" . $e['message'] . "\n";
}
exit(1);
