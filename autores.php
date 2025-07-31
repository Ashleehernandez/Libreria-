<?php
require_once 'config.php';

// Obtener parámetros de búsqueda
$busqueda = isset($_GET['busqueda']) ? limpiarDatos($_GET['busqueda']) : '';
$nacionalidad_filtro = isset($_GET['nacionalidad']) ? limpiarDatos($_GET['nacionalidad']) : '';

// Construir la consulta SQL
$sql = "SELECT a.*, COUNT(l.id) as total_libros 
        FROM autores a 
        LEFT JOIN libros l ON a.id = l.autor_id 
        WHERE 1=1";

$params = [];

if (!empty($busqueda)) {
    $sql .= " AND (a.nombre LIKE :busqueda OR a.apellido LIKE :busqueda)";
    $params[':busqueda'] = '%' . $busqueda . '%';
}

if (!empty($nacionalidad_filtro)) {
    $sql .= " AND a.nacionalidad = :nacionalidad";
    $params[':nacionalidad'] = $nacionalidad_filtro;
}

$sql .= " GROUP BY a.id ORDER BY a.apellido ASC, a.nombre ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $autores = $stmt->fetchAll();
    
    // Obtener nacionalidades únicas para el filtro
    $stmt_nacionalidades = $pdo->query("SELECT DISTINCT nacionalidad FROM autores WHERE nacionalidad IS NOT NULL ORDER BY nacionalidad");
    $nacionalidades = $stmt_nacionalidades->fetchAll();
    
} catch(PDOException $e) {
    $autores = [];
    $nacionalidades = [];
    $error = "Error al cargar los autores: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autores - Librería Online</title>
    
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
                        <a class="nav-link" href="libros.php">
                            <i class="fas fa-book me-1"></i>Libros
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="autores.php">
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
                        <i class="fas fa-user-edit me-3"></i>Nuestros Autores
                    </h1>
                    <p class="lead text-white">Conoce a los grandes escritores de la literatura latinoamericana</p>
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
                                    <label for="busqueda" class="form-label">Buscar autores:</label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="busqueda" 
                                           name="busqueda" 
                                           placeholder="Nombre o apellido del autor..."
                                           value="<?php echo htmlspecialchars($busqueda); ?>">
                                </div>
                                <div class="col-md-4">
                                    <label for="nacionalidad" class="form-label">Filtrar por nacionalidad:</label>
                                    <select class="form-select" id="nacionalidad" name="nacionalidad">
                                        <option value="">Todas las nacionalidades</option>
                                        <?php foreach($nacionalidades as $nacionalidad): ?>
                                            <option value="<?php echo htmlspecialchars($nacionalidad['nacionalidad']); ?>"
                                                    <?php echo ($nacionalidad_filtro == $nacionalidad['nacionalidad']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($nacionalidad['nacionalidad']); ?>
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
                    Mostrando <?php echo count($autores); ?> autor(es)
                    <?php if (!empty($nacionalidad_filtro) || !empty($busqueda)): ?>
                        - Filtros aplicados:
                        <?php if (!empty($nacionalidad_filtro)): ?>
                            <span class="badge bg-primary">Nacionalidad: <?php echo htmlspecialchars($nacionalidad_filtro); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($busqueda)): ?>
                            <span class="badge bg-secondary">Búsqueda: <?php echo htmlspecialchars($busqueda); ?></span>
                        <?php endif; ?>
                        <a href="autores.php" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-times me-1"></i>Limpiar filtros
                        </a>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- Lista de autores -->
        <div class="row">
            <?php if (empty($autores)): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-user-edit fa-4x text-muted mb-3"></i>
                        <h3 class="text-muted">No se encontraron autores</h3>
                        <p class="text-muted">Intenta con otros términos de búsqueda o filtros.</p>
                        <a href="autores.php" class="btn btn-primary">Ver todos los autores</a>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach($autores as $autor): ?>
                    <div class="col-lg-6 col-md-12 mb-4">
                        <div class="card h-100 shadow-sm autor-card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 text-center mb-3">
                                        <div class="autor-avatar">
                                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <h4 class="card-title text-primary mb-2">
                                            <?php echo htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']); ?>
                                        </h4>
                                        
                                        <div class="autor-info mb-3">
                                            <p class="mb-2">
                                                <i class="fas fa-flag text-muted me-2"></i>
                                                <strong>Nacionalidad:</strong> 
                                                <span class="badge bg-secondary"><?php echo htmlspecialchars($autor['nacionalidad']); ?></span>
                                            </p>
                                            
                                            <?php if (!empty($autor['fecha_nacimiento'])): ?>
                                            <p class="mb-2">
                                                <i class="fas fa-birthday-cake text-muted me-2"></i>
                                                <strong>Fecha de nacimiento:</strong> 
                                                <?php echo formatearFecha($autor['fecha_nacimiento']); ?>
                                            </p>
                                            <?php endif; ?>
                                            
                                            <p class="mb-2">
                                                <i class="fas fa-book text-muted me-2"></i>
                                                <strong>Libros en catálogo:</strong> 
                                                <span class="badge bg-success"><?php echo $autor['total_libros']; ?></span>
                                            </p>
                                        </div>
                                        
                                        <?php if (!empty($autor['biografia'])): ?>
                                        <div class="biografia-autor">
                                            <h6 class="text-muted">Biografía:</h6>
                                            <p class="card-text text-muted small">
                                                <?php echo htmlspecialchars(substr($autor['biografia'], 0, 200)) . '...'; ?>
                                            </p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Miembro desde: <?php echo formatearFecha($autor['created_at']); ?>
                                    </small>
                                    <?php if ($autor['total_libros'] > 0): ?>
                                    <a href="libros.php?busqueda=<?php echo urlencode($autor['nombre'] . ' ' . $autor['apellido']); ?>" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Ver libros
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Estadísticas de autores -->
        <?php if (!empty($autores)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-chart-bar me-2"></i>Estadísticas de Autores
                        </h5>
                        <div class="row text-center">
                            <?php
                            // Calcular estadísticas
                            $total_autores = count($autores);
                            $autores_con_libros = array_filter($autores, function($autor) {
                                return $autor['total_libros'] > 0;
                            });
                            $total_con_libros = count($autores_con_libros);
                            
                            // Nacionalidad más común
                            $nacionalidades_count = [];
                            foreach($autores as $autor) {
                                $nac = $autor['nacionalidad'];
                                $nacionalidades_count[$nac] = ($nacionalidades_count[$nac] ?? 0) + 1;
                            }
                            arsort($nacionalidades_count);
                            $nacionalidad_comun = key($nacionalidades_count);
                            ?>
                            
                            <div class="col-md-4">
                                <h3 class="text-primary"><?php echo $total_autores; ?></h3>
                                <p class="text-muted">Total de Autores</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-success"><?php echo $total_con_libros; ?></h3>
                                <p class="text-muted">Con Libros Publicados</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-warning"><?php echo $nacionalidad_comun; ?></h3>
                                <p class="text-muted">Nacionalidad Predominante</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
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