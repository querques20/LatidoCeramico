<?php

class UsuarioAdmin {
    private ?int $id = null;
    private ?string $nombre = null;
    private ?string $apellido = null;
    private ?string $email = null;
    private ?string $password_hash = null;
    private ?string $telefono = null;
    private ?string $created_at = null;
    private ?string $ultimo_login = null;

    public function getId(): ?int { return $this->id; }
    public function getNombre(): ?string { return $this->nombre; }
    public function getApellido(): ?string { return $this->apellido; }
    public function getEmail(): ?string { return $this->email; }
    public function getPasswordHash(): ?string { return $this->password_hash; }
    public function getTelefono(): ?string { return $this->telefono; }

    
    public static function ensureSetup(): void {
        $pdo = (new Conexion())->getConexion();
        $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios_admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            apellido VARCHAR(100) NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            telefono VARCHAR(50) NULL,
            password_hash VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            ultimo_login DATETIME NULL
        ) ENGINE=InnoDB CHARSET=utf8mb4;");
        try { $pdo->exec("ALTER TABLE usuarios_admin ADD COLUMN apellido VARCHAR(100) NULL"); } catch (Throwable $e) {}
        try { $pdo->exec("ALTER TABLE usuarios_admin ADD COLUMN telefono VARCHAR(50) NULL"); } catch (Throwable $e) {}
        $st = $pdo->query('SELECT COUNT(*) FROM usuarios_admin');
        if ((int)$st->fetchColumn() === 0) {
            $hash = password_hash('admin123', PASSWORD_DEFAULT);
            $ins = $pdo->prepare('INSERT INTO usuarios_admin (nombre, email, password_hash) VALUES (:n,:e,:p)');
            $ins->execute(['n' => 'Administrador', 'e' => 'admin@latido.local', 'p' => $hash]);
        }
    }


    public static function buscarPorEmail(string $email): ?UsuarioAdmin {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT * FROM usuarios_admin WHERE email = :e LIMIT 1');
        $st->setFetchMode(PDO::FETCH_CLASS, self::class);
        $st->execute(['e' => $email]);
        $u = $st->fetch();
        return $u !== false ? $u : null;
    }

    public function registrarLogin(): void {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('UPDATE usuarios_admin SET ultimo_login = NOW() WHERE id = :id');
        $st->execute(['id' => $this->id]);
    }

    
    public static function todos(): array {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->query('SELECT id, nombre, apellido, email, telefono, created_at, ultimo_login FROM usuarios_admin ORDER BY id DESC');
        return $st->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public static function existeEmail(string $email): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('SELECT COUNT(*) FROM usuarios_admin WHERE email = :e');
        $st->execute(['e' => $email]);
        return ((int)$st->fetchColumn()) > 0;
    }

  
    public static function crear(string $nombre, string $apellido, string $telefono, string $email, string $password): bool {
        $pdo = (new Conexion())->getConexion();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $st = $pdo->prepare('INSERT INTO usuarios_admin (nombre, apellido, telefono, email, password_hash) VALUES (:n,:a,:t,:e,:p)');
        return $st->execute(['n' => $nombre, 'a' => $apellido, 't' => $telefono, 'e' => $email, 'p' => $hash]);
    }

  
    public static function eliminar(int $id): bool {
        $pdo = (new Conexion())->getConexion();
        $st = $pdo->prepare('DELETE FROM usuarios_admin WHERE id = :id');
        return $st->execute(['id' => $id]);
    }
}
