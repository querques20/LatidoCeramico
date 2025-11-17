<?php

class SeccionesAdmin {
    private PDO $pdo;

    public function __construct(PDO $pdo) { $this->pdo = $pdo; }

    public static function ensureSetup(PDO $pdo): void {
        $pdo->exec("CREATE TABLE IF NOT EXISTS admin_secciones (\n            id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,\n            slug VARCHAR(60) NOT NULL UNIQUE,\n            nombre VARCHAR(100) NOT NULL,\n            icono VARCHAR(60) NULL,\n            en_menu TINYINT(1) NOT NULL DEFAULT 1,\n            activo TINYINT(1) NOT NULL DEFAULT 1,\n            orden INT UNSIGNED NOT NULL DEFAULT 10,\n            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,\n            INDEX(activo), INDEX(en_menu)\n        ) ENGINE=InnoDB CHARSET=utf8mb4;");

        // Seed mínimo si está vacía
        $count = (int)$pdo->query("SELECT COUNT(*) FROM admin_secciones")->fetchColumn();
        if ($count === 0) {
            $seeds = [
                ['inicio','Dashboard','house-door',1,1,1],
                ['productos','Productos','box-seam',1,1,2],
                ['categorias','Categorías','tags',1,1,3],
                ['usuarios','Usuarios','people',1,1,4],
                ['ordenes','Órdenes','receipt',1,1,5],
                ['carritos','Carritos','cart',1,1,6],
                ['configuracion','Configuración','gear',1,1,99],
                ['orden_detalle','Detalle Orden','receipt',0,1,50],
                ['carrito_detalle','Detalle Carrito','cart',0,1,51],
                ['crear_producto','Crear Producto','plus-circle',0,1,20],
                ['editar_producto','Editar Producto','pencil-square',0,1,21],
                ['borrar_producto','Borrar Producto','trash',0,1,22],
                ['crear_categoria','Crear Categoría','plus-circle',0,1,30],
                ['editar_categoria','Editar Categoría','pencil-square',0,1,31],
                ['borrar_categoria','Borrar Categoría','trash',0,1,32]
            ];
            $ins = $pdo->prepare("INSERT INTO admin_secciones (slug,nombre,icono,en_menu,activo,orden) VALUES (?,?,?,?,?,?)");
            foreach ($seeds as $s) { $ins->execute($s); }
        }

       
        $upserts = [
            ['carritos','Carritos','cart',1,1,6],
            ['carrito_detalle','Detalle Carrito','cart',0,1,51],
            ['galeria','Galería','images',1,1,7],
            ['editar_galeria','Editar Galería','pencil-square',0,1,70]
        ];
        $ins2 = $pdo->prepare("INSERT INTO admin_secciones (slug,nombre,icono,en_menu,activo,orden) VALUES (?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE nombre=VALUES(nombre), icono=VALUES(icono), activo=VALUES(activo), en_menu=VALUES(en_menu), orden=VALUES(orden)");
        foreach ($upserts as $s) { $ins2->execute($s); }
    }

  
    public function obtenerSlugsValidos(): array {
        $rows = $this->pdo->query("SELECT slug FROM admin_secciones WHERE activo=1")->fetchAll();
        return array_map(fn($r) => $r['slug'], $rows);
    }

    
    public function obtenerMenu(): array {
        $rows = $this->pdo->query("SELECT slug,nombre,icono FROM admin_secciones WHERE activo=1 AND en_menu=1 ORDER BY orden ASC")->fetchAll();
        $menu = [];
        foreach ($rows as $r) { $menu[$r['slug']] = ['nombre'=>$r['nombre'],'icono'=>$r['icono']]; }
        return $menu;
    }
}
