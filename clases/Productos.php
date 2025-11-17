<?php
require_once __DIR__ . '/Conexion.php';

class Productos
{
    protected static function mapFila(array $fila): array
    {
        return [
            'id' => $fila['id'],
            'nombre' => $fila['nombre'],
            'slug' => $fila['slug'],
            'descripcion' => $fila['descripcion'],
            'precio' => (float)$fila['precio'],
            'stock' => (int)$fila['stock'],
            'sku' => $fila['sku'],
            'imagen' => $fila['imagen_principal'], 
            'activo' => (int)$fila['activo'],
            'categoria' => $fila['categoria_nombre'],
            'categoria_id' => $fila['categoria_id'],
            'created_at' => $fila['created_at'],
        ];
    }

    protected static function conexion(): PDO
    {
        return (new Conexion())->getConexion();
    }

    public static function todos(): array
    {
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                JOIN categorias c ON c.id = p.categoria_id
                WHERE p.activo = 1
                ORDER BY p.created_at DESC";
        $st = self::conexion()->prepare($sql);
        $st->execute();
        $filas = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map([self::class, 'mapFila'], $filas);
    }

    public static function porCategoria(?string $categoria): array
    {
        if ($categoria === null || $categoria === '') {
            return self::todos();
        }
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                JOIN categorias c ON c.id = p.categoria_id
                WHERE p.activo = 1 AND LOWER(c.nombre) = LOWER(:cat)
                ORDER BY p.created_at DESC";
        $st = self::conexion()->prepare($sql);
        $st->execute(['cat' => $categoria]);
        $filas = $st->fetchAll(PDO::FETCH_ASSOC);
        return array_map([self::class, 'mapFila'], $filas);
    }

    public static function categorias(): array
    {
        $sql = "SELECT nombre FROM categorias WHERE visible = 1 ORDER BY nombre";
        $st = self::conexion()->prepare($sql);
        $st->execute();
        $filas = $st->fetchAll(PDO::FETCH_COLUMN);
        return $filas ?: [];
    }

    public static function combos(): array
    {
        try {
            $sql = "SELECT p.*, c.nombre AS categoria_nombre
                    FROM productos p
                    JOIN categorias c ON c.id = p.categoria_id
                    WHERE p.activo = 1 AND (p.es_combo = 1)
                    ORDER BY p.created_at DESC";
            $st = self::conexion()->prepare($sql);
            $st->execute();
            $filas = $st->fetchAll(PDO::FETCH_ASSOC);
            return array_map([self::class, 'mapFila'], $filas);
        } catch (Throwable $e) {
            // Si la columna no existe en esta base, devolvemos vacío sin romper
            return [];
        }
    }

    public static function buscarPorId(string $id): ?array
    {
        if ($id === '') return null;
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                JOIN categorias c ON c.id = p.categoria_id
                WHERE p.id = :id AND p.activo = 1";
        $st = self::conexion()->prepare($sql);
        $st->execute(['id' => $id]);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ? self::mapFila($fila) : null;
    }

    public static function buscarPorSlug(string $slug): ?array
    {
        if ($slug === '') return null;
        $sql = "SELECT p.*, c.nombre AS categoria_nombre
                FROM productos p
                JOIN categorias c ON c.id = p.categoria_id
                WHERE p.slug = :slug AND p.activo = 1";
        $st = self::conexion()->prepare($sql);
        $st->execute(['slug' => $slug]);
        $fila = $st->fetch(PDO::FETCH_ASSOC);
        return $fila ? self::mapFila($fila) : null;
    }
}
