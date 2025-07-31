<?php
require_once 'config.php';

// Obtener parámetros de filtrado
$genero_filtro = isset($_GET['genero']) ? limpiarDatos($_GET['genero']) : '';
$busqueda = isset($_GET['busqueda']) ? limpiarDatos($_GET['busqueda']) : '';

// Construir la consulta SQL
$sql = "SELECT l.*, a.nombre, a.apellido 
        FROM libros l 
        LEFT JOIN autores a ON l.autor_id = a.id 
        WHERE 1=1";

$params = [];

if (!empty($genero_filtro)) {
    $sql .= " AND l.genero = :genero";
    $params[':genero'] = $genero_filtro;
}

if (!empty($busqueda)) {
    $sql .= " AND (l.titulo LIKE :busqueda OR a.nombre LIKE :busqueda OR a.apellido LIKE :busqueda)";
    $params[':busqueda'] = '%' . $busqueda . '%';
}

$sql .= " ORDER BY l.titulo ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $libros = $stmt->fetchAll();
    
    // Obtener géneros únicos para el filtro
    $stmt_generos = $pdo->query("SELECT DISTINCT genero FROM libros WHERE genero IS NOT NULL ORDER BY genero");
    $generos = $stmt_generos->fetchAll();
    
} catch(PDOException $e) {
    $libros = [];
    $generos = [];
    $error = "Error al cargar los libros: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libros - Librería Online</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-book-open me-2"></i>
                Librería Online
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="libros.php">
                            <i class="fas fa-book me-1"></i>Libros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="autores.php">
                            <i class="fas fa-user-edit me-1"></i>Autores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contacto.php">
                            <i class="fas fa-envelope me-1"></i>Contacto
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header de página -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1 class="display-4 fw-bold text-white">
                        <i class="fas fa-book me-3"></i>Catálogo de Libros
                    </h1>
                    <p class="lead text-white">Explora nuestra colección de literatura latinoamericana</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <div class="container my-5">
        <!-- Filtros y búsqueda -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="busqueda" class="form-label">Buscar libros:</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="busqueda" 
                                           name="busqueda" 
                                           placeholder="Título del libro o nombre del autor..."
                                           value="<?php echo htmlspecialchars($busqueda); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="genero" class="form-label">Filtrar por género:</label>
                                    <select class="form-select" id="genero" name="genero">
                                        <option value="">Todos los géneros</option>
                                        <?php foreach($generos as $genero): ?>
                                            <option value="<?php echo htmlspecialchars($genero['genero']); ?>"
                                                    <?php echo ($genero_filtro == $genero['genero']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($genero['genero']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search me-1"></i>Buscar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mostrar errores si los hay -->
        <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Información de resultados -->
        <div class="row mb-3">
            <div class="col-12">
                <p class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Mostrando <?php echo count($libros); ?> libro(s)
                    <?php if (!empty($genero_filtro) || !empty($busqueda)): ?>
                        - Filtros aplicados:
                        <?php if (!empty($genero_filtro)): ?>
                            <span class="badge bg-primary">Género: <?php echo htmlspecialchars($genero_filtro); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($busqueda)): ?>
                            <span class="badge bg-secondary">Búsqueda: <?php echo htmlspecialchars($busqueda); ?></span>
                        <?php endif; ?>
                        <a href="libros.php" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-times me-1"></i>Limpiar filtros
                        </a>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Lista de libros -->
        <div class="row">
            <?php if (empty($libros)): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                        <h3 class="text-muted">No se encontraron libros</h3>
                        <p class="text-muted">Intenta con otros términos de búsqueda o filtros.</p>
                        <a href="libros.php" class="btn btn-primary">Ver todos los libros</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach($libros as $libro): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm libro-card">
                            <div class="card-body">
                                <h5 class="card-title text-primary">
                                    <?php echo htmlspecialchars($libro['titulo']); ?>
                                </h5>
                                
                                <div class="libro-info mb-3">
                                    <p class="mb-2">
                                        <i class="fas fa-user text-muted me-2"></i>
                                        <strong>Autor:</strong> 
                                        <?php echo htmlspecialchars($libro['nombre'] . ' ' . $libro['apellido']); ?>
                                    </p>
                                    
                                    <p class="mb-2">
                                        <i class="fas fa-tags text-muted me-2"></i>
                                        <strong>Género:</strong> 
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($libro['genero']); ?></span>
                                    </p>
                                    
                                    <p class="mb-2">
                                        <i class="fas fa-building text-muted me-2"></i>
                                        <strong>Editorial:</strong> <?php echo htmlspecialchars($libro['editorial']); ?>
                                    </p>
                                    
                                    <p class="mb-2">
                                        <i class="fas fa-calendar text-muted me-2"></i>
                                        <strong>Publicación:</strong> <?php echo formatearFecha($libro['fecha_publicacion']); ?>
                                    </p>
                                    
                                    <p class="mb-2">
                                        <i class="fas fa-file-alt text-muted me-2"></i>
                                        <strong>Páginas:</strong> <?php echo $libro['paginas']; ?>
                                    </p>
                                    
                                    <?php if (!empty($libro['isbn'])): ?>
                                    <p class="mb-2">
                                        <i class="fas fa-barcode text-muted me-2"></i>
                                        <strong>ISBN:</strong> <?php echo htmlspecialchars($libro['isbn']); ?>
                                    </p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="descripcion-libro">
                                    <p class="card-text text-muted">
                                        <?php echo htmlspecialchars(substr($libro['descripcion'], 0, 150)) . '...'; ?>
                                    </p>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="h5 text-success mb-0">
                                            <?php echo formatearPrecio($libro['precio']); ?>
                                        </span>
                                    </div>
                                    <div>
                                        <?php if ($libro['stock'] > 0): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Stock: <?php echo $libro['stock']; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Sin stock
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Librería Online</h5>
                    <p>Tu destino para los mejores libros de literatura latinoamericana.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; 2025 Librería Online. Todos los derechos reservados.</p>
                    <p>Desarrollado por Daniel Parra</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript personalizado -->
    <script src="js/scripts.js"></script>
</body>
</html>