<?php
require_once("classes/ProductoAdmin.php");

$producto = new ProductoAdmin();
$lista_productos = $producto->todosProductos();
$categorias = ProductoAdmin::obtenerCategorias();

$categoria_filtro = isset($_GET['categoria']) && $_GET['categoria'] !== '' ? (int)$_GET['categoria'] : '';
if ($categoria_filtro !== '') {
    $lista_productos = $producto->productosPorCategoria($categoria_filtro);
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-box-seam text-primary me-2"></i>
        Gestión de Productos
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="?sec=crear_producto" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Crear Producto
        </a>
    </div>
</div>

<div class="card card-admin mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-center">
            <input type="hidden" name="sec" value="productos">
            <div class="col-auto">
                <label class="form-label">Filtrar por categoría:</label>
            </div>
            <div class="col-auto">
                <select name="categoria" class="form-select" onchange="this.form.submit()">
                    <option value="">Todas las categorías</option>
                    <?php foreach ($categorias as $cat): ?>
                        <option value="<?= (int)$cat['id'] ?>" <?= ($categoria_filtro !== '' && (int)$categoria_filtro === (int)$cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php if (!empty($categoria_filtro)): ?>
                <div class="col-auto">
                    <a href="?sec=productos" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Limpiar filtro
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>


<div class="card card-admin shadow">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">
            Lista de Productos 
            <?php if (!empty($categoria_filtro)): ?>
                - Categoría: <span class="text-capitalize"><?= $categoria_filtro ?></span>
            <?php endif; ?>
            <span class="badge bg-secondary ms-2"><?= count($lista_productos) ?> productos</span>
        </h6>
    </div>
    <div class="card-body">
        <?php if (!empty($lista_productos)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Slug</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista_productos as $prod): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-info">#<?= $prod->getId() ?></span>
                                </td>
                                <td>
                                    <?php if ($prod->getImagenPrincipal()): ?>
                                        <img src="../assets/img/productos/<?= htmlspecialchars($prod->getImagenPrincipal()) ?>" 
                                             alt="<?= htmlspecialchars($prod->getNombre()) ?>" 
                                             class="rounded shadow-sm"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="text-muted">Sin imagen</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($prod->getNombre()) ?></strong>
                                </td>
                                <td><small class="text-muted"><?= htmlspecialchars($prod->getSlug()) ?></small></td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize"><?= htmlspecialchars($prod->categoria_nombre ?? '') ?></span>
                                </td>
                                <td>
                                    <strong class="text-success"><?= formatear_precio($prod->getPrecio()) ?></strong>
                                </td>
                                <td><span class="badge bg-<?= $prod->getStock() > 0 ? 'success' : 'secondary' ?>"><?= (int)$prod->getStock() ?></span></td>
                                <td><small class="text-muted"><?= $prod->getCreatedAt() ? date('d/m/Y', strtotime($prod->getCreatedAt())) : '-' ?></small></td>
                                <td>
                                    <div class="btn-group-vertical btn-group-sm" role="group">
                                        <a href="?sec=editar_producto&id=<?= $prod->getId() ?>" 
                                           class="btn btn-warning btn-sm mb-1">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="collapse" data-bs-target="#confirmDelProd<?= $prod->getId() ?>" aria-expanded="false" aria-controls="confirmDelProd<?= $prod->getId() ?>">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </button>
                                        <div class="collapse mt-1" id="confirmDelProd<?= $prod->getId() ?>">
                                            <div class="d-inline-flex align-items-center gap-2">
                                                <span class="small text-muted">¿Confirmar borrado?</span>
                                                <a href="?sec=borrar_producto&id=<?= $prod->getId() ?>" class="btn btn-sm btn-danger">Sí</a>
                                                <button type="button" class="btn btn-sm btn-light" data-bs-toggle="collapse" data-bs-target="#confirmDelProd<?= $prod->getId() ?>">Cancelar</button>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 4rem; color: #d1d3e2;"></i>
                <h4 class="mt-3 text-muted">No hay productos registrados</h4>
                <p class="text-muted">
                    <?php if (!empty($categoria_filtro)): ?>
                        No se encontraron productos en la categoría "<?= $categoria_filtro ?>"
                    <?php else: ?>
                        Comienza creando tu primer producto para el catálogo
                    <?php endif; ?>
                </p>
                <a href="?sec=crear_producto" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>
                    Crear primer producto
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>