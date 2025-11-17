<?php
require_once __DIR__ . '/Conexion.php';

class Ordenes {
    public static function ensureSetup(): void {
        $pdo = (new Conexion())->getConexion();
        // Órdenes
        $pdo->exec("CREATE TABLE IF NOT EXISTS ordenes (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            usuario_id BIGINT UNSIGNED NULL,
            nombre VARCHAR(150) NOT NULL,
            email VARCHAR(150) NOT NULL,
            telefono VARCHAR(50) NULL,
            direccion VARCHAR(200) NOT NULL,
            localidad VARCHAR(120) NULL,
            cp VARCHAR(20) NULL,
            notas TEXT NULL,
            subtotal INT UNSIGNED NOT NULL DEFAULT 0,
            estado ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pagado',
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (usuario_id),
            INDEX (estado),
            INDEX (created_at)
        ) ENGINE=InnoDB CHARSET=utf8mb4;");
       
        $pdo->exec("CREATE TABLE IF NOT EXISTS orden_items (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            orden_id BIGINT UNSIGNED NOT NULL,
            producto_id BIGINT UNSIGNED NULL,
            nombre VARCHAR(150) NOT NULL,
            precio INT UNSIGNED NOT NULL DEFAULT 0,
            cantidad INT UNSIGNED NOT NULL DEFAULT 1,
            total INT UNSIGNED NOT NULL DEFAULT 0,
            created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX (orden_id),
            CONSTRAINT fk_orden_items_orden_front FOREIGN KEY (orden_id) REFERENCES ordenes(id) ON DELETE CASCADE
        ) ENGINE=InnoDB CHARSET=utf8mb4;");

      
        $adds = [
            'ordenes' => [
                "ALTER TABLE ordenes ADD COLUMN IF NOT EXISTS telefono VARCHAR(50) NULL",
                "ALTER TABLE ordenes ADD COLUMN IF NOT EXISTS localidad VARCHAR(120) NULL",
                "ALTER TABLE ordenes ADD COLUMN IF NOT EXISTS cp VARCHAR(20) NULL",
                "ALTER TABLE ordenes ADD COLUMN IF NOT EXISTS notas TEXT NULL",
                "ALTER TABLE ordenes ADD COLUMN IF NOT EXISTS subtotal INT UNSIGNED NOT NULL DEFAULT 0",
                "ALTER TABLE ordenes ADD COLUMN IF NOT EXISTS estado ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pagado'",
                "ALTER TABLE ordenes ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"
            ],
            'orden_items' => [
                "ALTER TABLE orden_items ADD COLUMN IF NOT EXISTS total INT UNSIGNED NOT NULL DEFAULT 0",
                "ALTER TABLE orden_items ADD COLUMN IF NOT EXISTS created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"
            ]
        ];
        foreach($adds as $tbl => $stmts){
            foreach($stmts as $sql){
                try { $pdo->exec($sql); } catch (Throwable $e) { /* MySQL <8.0.29 no admite IF NOT EXISTS: fallback */ }
            }
        }
       
        try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN telefono VARCHAR(50) NULL"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN localidad VARCHAR(120) NULL"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN cp VARCHAR(20) NULL"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN notas TEXT NULL"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN subtotal INT UNSIGNED NOT NULL DEFAULT 0"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN estado ENUM('pendiente','pagado','cancelado') NOT NULL DEFAULT 'pagado'"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE ordenes ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE orden_items ADD COLUMN total INT UNSIGNED NOT NULL DEFAULT 0"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE orden_items ADD COLUMN created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP"); } catch (Throwable $e) {}
    }

    public static function crearOrden(array $cliente, array $items, int $subtotal, ?int $usuarioId = null): int {
        $pdo = (new Conexion())->getConexion();
        $pdo->beginTransaction();
        try {
            
            $verificados = [];
            foreach ($items as $it) {
                $pid = isset($it['id']) ? (int)$it['id'] : (isset($it['producto_id']) ? (int)$it['producto_id'] : 0);
                $cant = max(1,(int)($it['cantidad'] ?? 1));
                if ($pid > 0 && $cant > 0) {
                    $stp = $pdo->prepare("SELECT id, nombre, stock FROM productos WHERE id = :id FOR UPDATE");
                    $stp->execute([':id'=>$pid]);
                    $prow = $stp->fetch(PDO::FETCH_ASSOC);
                    if (!$prow) {
                        throw new RuntimeException('Producto no encontrado (ID ' . $pid . ')');
                    }
                    if ((int)$prow['stock'] < $cant) {
                        throw new RuntimeException('Sin stock suficiente para \'' . ($prow['nombre'] ?? ('ID '.$pid)) . '\'. Disponible: ' . (int)$prow['stock']);
                    }
                    $verificados[] = ['id'=>$pid,'cant'=>$cant];
                }
            }
           
            $qCols = $pdo->query("SHOW COLUMNS FROM ordenes");
            $cols = $qCols ? $qCols->fetchAll(PDO::FETCH_COLUMN) : [];
            $advanced = in_array('codigo',$cols) && in_array('total',$cols);
            if($advanced){
                $codigo = 'ORD-' . date('YmdHis') . '-' . random_int(1000,9999);
                $totalDec = number_format($subtotal,2,'.','');
                $sql = "INSERT INTO ordenes (usuario_id,codigo,nombre,email,telefono,direccion,localidad,cp,notas,subtotal,total,estado,metodo_pago) VALUES (:u,:cod,:n,:e,:t,:d,:l,:cp,:no,:sub,:tot,'pagado','tarjeta')";
                $st = $pdo->prepare($sql);
                $st->execute([
                    'u'=>$usuarioId,
                    'cod'=>$codigo,
                    'n'=>$cliente['nombre'] ?? '',
                    'e'=>$cliente['email'] ?? '',
                    't'=>$cliente['telefono'] ?? null,
                    'd'=>$cliente['direccion'] ?? '',
                    'l'=>$cliente['localidad'] ?? null,
                    'cp'=>$cliente['cp'] ?? null,
                    'no'=>$cliente['notas'] ?? null,
                    'sub'=>$subtotal,
                    'tot'=>$totalDec,
                ]);
            } else {
                $st = $pdo->prepare("INSERT INTO ordenes (usuario_id,nombre,email,telefono,direccion,localidad,cp,notas,subtotal,estado) VALUES (:u,:n,:e,:t,:d,:l,:cp,:no,:s,'pagado')");
                $st->execute([
                    'u'=>$usuarioId,
                    'n'=>$cliente['nombre'] ?? '',
                    'e'=>$cliente['email'] ?? '',
                    't'=>$cliente['telefono'] ?? null,
                    'd'=>$cliente['direccion'] ?? '',
                    'l'=>$cliente['localidad'] ?? null,
                    'cp'=>$cliente['cp'] ?? null,
                    'no'=>$cliente['notas'] ?? null,
                    's'=>$subtotal,
                ]);
            }
            $ordenId = (int)$pdo->lastInsertId();

        
            $qColsItems = $pdo->query("SHOW COLUMNS FROM orden_items");
            $colsItems = $qColsItems ? $qColsItems->fetchAll(PDO::FETCH_COLUMN) : [];
            $itemsAdvanced = in_array('nombre_producto',$colsItems) && in_array('precio_unitario',$colsItems) && in_array('subtotal',$colsItems);
            if($itemsAdvanced){
                $sti = $pdo->prepare("INSERT INTO orden_items (orden_id,producto_id,nombre_producto,precio_unitario,cantidad,subtotal,total) VALUES (:o,:p,:n,:pu,:c,:sub,:tot)" );
                foreach($items as $it){
                    $precio = (float)($it['precio'] ?? 0);
                    $cant = max(1,(int)($it['cantidad'] ?? 1));
                    $sub = $precio * $cant;
                    $sti->execute([
                        'o'=>$ordenId,
                        'p'=> isset($it['id']) ? (int)$it['id'] : null,
                        'n'=> (string)($it['nombre'] ?? ''),
                        'pu'=> number_format($precio,2,'.',''),
                        'c'=> $cant,
                        'sub'=> number_format($sub,2,'.',''),
                        'tot'=> (int)round($sub),
                    ]);
                }
            } else {
                $sti = $pdo->prepare("INSERT INTO orden_items (orden_id,producto_id,nombre,precio,cantidad,total) VALUES (:o,:p,:n,:pr,:c,:tot)");
                foreach($items as $it){
                    $precio = (int)($it['precio'] ?? 0);
                    $cant = max(1,(int)($it['cantidad'] ?? 1));
                    $sti->execute([
                        'o'=>$ordenId,
                        'p'=> isset($it['id']) ? (int)$it['id'] : null,
                        'n'=> (string)($it['nombre'] ?? ''),
                        'pr'=> $precio,
                        'c'=> $cant,
                        'tot'=> $precio * $cant,
                    ]);
                }
            }
    
            if (!empty($verificados)) {
                $upd = $pdo->prepare("UPDATE productos SET stock = GREATEST(0, stock - :c) WHERE id = :id");
                foreach ($verificados as $v) {
                    $upd->execute([':c'=>$v['cant'], ':id'=>$v['id']]);
                }
            }
            $pdo->commit();
            return $ordenId;
        } catch(Throwable $e){
            $pdo->rollBack();
            throw $e;
        }
    }
}
?>