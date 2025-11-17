<?php


class ProductoAdmin
{
    private ?int $id = null;
    private ?int $categoria_id = null;
    private ?string $nombre = null;
    private ?string $slug = null;
    private ?string $descripcion = null;
    private ?float $precio = null;
    private ?float $precio_original = null;
    private ?int $descuento_porcentaje = null;
    private ?int $es_combo = null;
    private int $stock = 0;
    private ?string $sku = null;
    private ?string $imagen_principal = null;
    private int $activo = 1;
    private ?string $created_at = null;
    private ?string $updated_at = null;
   
    private ?string $meta_title = null;
    private ?string $meta_description = null;

    public ?string $categoria_nombre = null;

    
    public function getId(): ?int { return $this->id; }
    public function getCategoriaId(): ?int { return $this->categoria_id; }
    public function getNombre(): ?string { return $this->nombre; }
    public function getSlug(): ?string { return $this->slug; }
    public function getDescripcion(): ?string { return $this->descripcion; }
    public function getPrecio(): ?float { return $this->precio; }
    public function getPrecioOriginal(): ?float { return $this->precio_original; }
    public function getDescuentoPorcentaje(): ?int { return $this->descuento_porcentaje; }
    public function getEsCombo(): ?int { return $this->es_combo; }
    public function getStock(): int { return $this->stock; }
    public function getSku(): ?string { return $this->sku; }
    public function getImagenPrincipal(): ?string { return $this->imagen_principal; }
    public function getActivo(): bool { return (bool)$this->activo; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getUpdatedAt(): ?string { return $this->updated_at; }
    public function getMetaTitle(): ?string { return $this->meta_title; }
    public function getMetaDescription(): ?string { return $this->meta_description; }

    
    public function setId(int $id): void { $this->id = $id; }


    public function todosProductos(): array
    {
        $conexion = (new Conexion())->getConexion();
        $query = "SELECT p.*, c.nombre AS categoria_nombre
                  FROM productos p
                  JOIN categorias c ON c.id = p.categoria_id
                  ORDER BY p.created_at DESC";
        $st = $conexion->prepare($query);
        $st->setFetchMode(PDO::FETCH_CLASS, self::class);
        $st->execute();
        return $st->fetchAll();
    }

    
    public function productosPorCategoria(int $categoria_id): array
    {
        $conexion = (new Conexion())->getConexion();
        $query = "SELECT p.*, c.nombre AS categoria_nombre
                  FROM productos p
                  JOIN categorias c ON c.id = p.categoria_id
                  WHERE p.categoria_id = :cat
                  ORDER BY p.nombre";
        $st = $conexion->prepare($query);
        $st->setFetchMode(PDO::FETCH_CLASS, self::class);
        $st->execute(['cat' => $categoria_id]);
        return $st->fetchAll();
    }

    
    public static function obtenerPorId(int $id): ?ProductoAdmin
    {
        $conexion = (new Conexion())->getConexion();
        $query = "SELECT p.*, c.nombre AS categoria_nombre
                  FROM productos p
                  JOIN categorias c ON c.id = p.categoria_id
                  WHERE p.id = :id";
        $st = $conexion->prepare($query);
        $st->setFetchMode(PDO::FETCH_CLASS, self::class);
        $st->execute(['id' => $id]);
        $producto = $st->fetch();
        return $producto !== false ? $producto : null;
    }

    
    public static function insertar(
        string $nombre,
        string $slug,
        string $descripcion,
        float $precio,
        int $categoria_id,
        ?string $imagen_principal,
        int $stock = 0,
        ?string $sku = null,
        int $activo = 1
    ): int {
        $conexion = (new Conexion())->getConexion();
        $query = "INSERT INTO productos
                 (nombre, slug, descripcion, precio, categoria_id, imagen_principal,
                  stock, sku, activo, created_at)
                 VALUES
                 (:nombre, :slug, :descripcion, :precio, :categoria_id, :imagen_principal,
                  :stock, :sku, :activo, NOW())";
        $st = $conexion->prepare($query);
        $st->execute([
            'nombre' => $nombre,
            'slug' => $slug,
            'descripcion' => $descripcion,
            'precio' => $precio,
            'categoria_id' => $categoria_id,
            'imagen_principal' => $imagen_principal,
            'stock' => $stock,
            'sku' => $sku,
            'activo' => $activo,
        ]);
        return (int)$conexion->lastInsertId();
    }


    public function actualizar(
        string $nombre,
        string $slug,
        string $descripcion,
        float $precio,
        int $categoria_id,
        ?string $imagen_principal = null,
        int $stock = 0,
        ?string $sku = null,
        int $activo = 1
    ): bool {
        $conexion = (new Conexion())->getConexion();
        if ($imagen_principal === null) {
            $query = "UPDATE productos SET
                      nombre=:nombre, slug=:slug, descripcion=:descripcion, precio=:precio,
                      categoria_id=:categoria_id,
                      stock=:stock, sku=:sku, activo=:activo,
                      updated_at=NOW()
                      WHERE id=:id";
            $params = compact('nombre','slug','descripcion','precio','categoria_id','stock','sku','activo');
            $params['id'] = $this->id;
        } else {
            $query = "UPDATE productos SET
                      nombre=:nombre, slug=:slug, descripcion=:descripcion, precio=:precio,
                      categoria_id=:categoria_id, imagen_principal=:imagen_principal,
                      stock=:stock, sku=:sku, activo=:activo,
                      updated_at=NOW()
                      WHERE id=:id";
            $params = compact('nombre','slug','descripcion','precio','categoria_id','imagen_principal','stock','sku','activo');
            $params['id'] = $this->id;
        }
        $st = $conexion->prepare($query);
        return $st->execute($params);
    }


    public function eliminar(): bool
    {
        $conexion = (new Conexion())->getConexion();
        $st = $conexion->prepare('DELETE FROM productos WHERE id=:id');
        return $st->execute(['id' => $this->id]);
    }


    public static function obtenerCategorias(): array
    {
        $conexion = (new Conexion())->getConexion();
        $st = $conexion->prepare('SELECT id, nombre FROM categorias WHERE visible=1 ORDER BY nombre');
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
