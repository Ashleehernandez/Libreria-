<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería Online - Inicio</title>
    
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
                        <a class="nav-link active" href="index.php">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="libros.php">
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

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold text-white mb-4">
                        Bienvenido a nuestra Librería Online
                    </h1>
                    <p class="lead text-white mb-4">
                        Descubre una amplia colección de libros de los mejores autores latinoamericanos. 
                        Sumérgete en historias fascinantes y encuentra tu próxima lectura favorita.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="libros.php" class="btn btn-warning btn-lg">
                            <i class="fas fa-book me-2"></i>Ver Libros
                        </a>
                        <a href="autores.php" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-users me-2"></i>Conocer Autores
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <i class="fas fa-book-open display-1 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Sección de estadísticas -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <?php
                try {
                    // Contar libros
                    $stmt = $pdo->query("SELECT COUNT(*) as total_libros FROM libros");
                    $total_libros = $stmt->fetch()['total_libros'];
                    
                    // Contar autores
                    $stmt = $pdo->query("SELECT COUNT(*) as total_autores FROM autores");
                    $total_autores = $stmt->fetch()['total_autores'];
                    
                    // Calcular stock total
                    $stmt = $pdo->query("SELECT SUM(stock) as total_stock FROM libros");
                    $total_stock = $stmt->fetch()['total_stock'];
                    
                } catch(PDOException $e) {
                    $total_libros = 0;
                    $total_autores = 0;
                    $total_stock = 0;
                }
                ?>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-book text-primary fa-3x mb-3"></i>
                            <h3 class="fw-bold"><?php echo $total_libros; ?></h3>
                            <p class="text-muted">Libros Disponibles</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-user-edit text-success fa-3x mb-3"></i>
                            <h3 class="fw-bold"><?php echo $total_autores; ?></h3>
                            <p class="text-muted">Autores Destacados</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <i class="fas fa-warehouse text-warning fa-3x mb-3"></i>
                            <h3 class="fw-bold"><?php echo $total_stock; ?></h3>
                            <p class="text-muted">Ejemplares en Stock</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Libros destacados -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Libros Destacados</h2>
            <div class="row">
                <?php
                try {
                    // Obtener los 3 libros con mayor stock
                    $stmt = $pdo->query("
                        SELECT l.*, a.nombre, a.apellido 
                        FROM libros l 
                        LEFT JOIN autores a ON l.autor_id = a.id 
                        ORDER BY l.stock DESC 
                        LIMIT 3
                    ");
                    $libros_destacados = $stmt->fetchAll();
                    
                    foreach($libros_destacados as $libro):
                ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title text-primary"><?php echo htmlspecialchars($libro['titulo']); ?></h5>
                                <p class="card-text">
                                    <strong>Autor:</strong> <?php echo htmlspecialchars($libro['nombre'] . ' ' . $libro['apellido']); ?><br>
                                    <strong>Género:</strong> <?php echo htmlspecialchars($libro['genero']); ?><br>
                                    <strong>Precio:</strong> <span class="text-success"><?php echo formatearPrecio($libro['precio']); ?></span>
                                </p>
                                <p class="card-text text-muted">
                                    <?php echo htmlspecialchars(substr($libro['descripcion'], 0, 100)) . '...'; ?>
                                </p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <span class="badge bg-success">Stock: <?php echo $libro['stock']; ?></span>
                            </div>
                        </div>
                    </div>
                <?php 
                    endforeach;
                } catch(PDOException $e) {
                    echo '<div class="col-12"><p class="text-center text-muted">Error al cargar los libros destacados.</p></div>';
                }
                ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="libros.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-eye me-2"></i>Ver Todos los Libros
                </a>
            </div>
        </div>
    </section>

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
