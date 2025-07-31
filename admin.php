<?php
require_once 'config.php';

$mensaje_exito = '';
$mensaje_error = '';

// Procesar formulario de nuevo libro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_libro'])) {
    $titulo = limpiarDatos($_POST['titulo'] ?? '');
    $autor_id = $_POST['autor_id'] ?? '';
    $isbn = limpiarDatos($_POST['isbn'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $descripcion = limpiarDatos($_POST['descripcion'] ?? '');
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? '';
    $genero = limpiarDatos($_POST['genero'] ?? '');
    $editorial = limpiarDatos($_POST['editorial'] ?? '');
    $paginas = $_POST['paginas'] ?? '';
    
    // Validaciones básicas
    if (empty($titulo) || empty($autor_id) || empty($precio) || empty($stock)) {
        $mensaje_error = "Los campos título, autor, precio y stock son obligatorios.";
    } else {
        try {
            $sql = "INSERT INTO libros (titulo, autor_id, isbn, precio, stock, descripcion, fecha_publicacion, genero, editorial, paginas) 
                    VALUES (:titulo, :autor_id, :isbn, :precio, :stock, :descripcion, :fecha_publicacion, :genero, :editorial, :paginas)";
            
            $stmt = $pdo->prepare($sql);
            $resultado = $stmt->execute([
                ':titulo' => $titulo,
                ':autor_id' => $autor_id,
                ':isbn' => $isbn,
                ':precio' => $precio,
                ':stock' => $stock,
                ':descripcion' => $descripcion,
                ':fecha_publicacion' => $fecha_publicacion,
                ':genero' => $genero,
                ':editorial' => $editorial,
                ':paginas' => $paginas
            ]);
            
            if ($resultado) {
                $mensaje_exito = "¡Libro agregado exitosamente!";
                // Limpiar formulario
                $_POST = array();
            }
        } catch(PDOException $e) {
            $mensaje_error = "Error al agregar el libro: " . $e->getMessage();
        }
    }
}

// Procesar formulario de nuevo autor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_autor'])) {
    $nombre = limpiarDatos($_POST['nombre'] ?? '');
    $apellido = limpiarDatos($_POST['apellido'] ?? '');
    $nacionalidad = limpiarDatos($_POST['nacionalidad'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $biografia = limpiarDatos($_POST['biografia'] ?? '');
    
    if (empty($nombre) || empty($apellido)) {
        $mensaje_error = "El nombre y apellido del autor son obligatorios.";
    } else {
        try {
            $sql = "INSERT INTO autores (nombre, apellido, nacionalidad, fecha_nacimiento, biografia) 
                    VALUES (:nombre, :apellido, :nacionalidad, :fecha_nacimiento, :biografia)";
            
            $stmt = $pdo->prepare($sql);
            $resultado = $stmt->execute([
                ':nombre' => $nombre,
                ':apellido' => $apellido,
                ':nacionalidad' => $nacionalidad,
                ':fecha_nacimiento' => $fecha_nacimiento,
                ':biografia' => $biografia
            ]);
            
            if ($resultado) {
                $mensaje_exito = "¡Autor agregado exitosamente!";
            }
        } catch(PDOException $e) {
            $mensaje_error = "Error al agregar el autor: " . $e->getMessage();
        }
    }
}

// Obtener lista de autores
try {
    $stmt = $pdo->query("SELECT id, nombre, apellido FROM autores ORDER BY apellido, nombre");
    $autores = $stmt->fetchAll();
} catch(PDOException $e) {
    $autores = [];
}

// Obtener últimos libros agregados
try {
    $stmt = $pdo->query("
        SELECT l.*, a.nombre, a.apellido 
        FROM libros l 
        LEFT JOIN autores a ON l.autor_id = a.id 
        ORDER BY l.created_at DESC 
        LIMIT 10
    ");
    $ultimos_libros = $stmt->fetchAll();
} catch(PDOException $e) {
    $ultimos_libros = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Librería Online</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- CSS personalizado -->
    <link href="css/estilos.css" rel="stylesheet">
</head>
<body>
    <!-- Navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin_libros.php">
                <i class="fas fa-tools me-2"></i>
                Panel Administrativo
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="index.php" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>Ver Sitio
                </a>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <!-- Mensajes -->
        <?php if (!empty($mensaje_exito)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $mensaje_exito; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($mensaje_error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i><?php echo $mensaje_error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Formulario para agregar libro -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Libro
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="titulo" class="form-label">Título del libro *</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="autor_id" class="form-label">Autor *</label>
                                    <select class="form-select" id="autor_id" name="autor_id" required>
                                        <option value="">Seleccionar autor...</option>
                                        <?php foreach($autores as $autor): ?>
                                            <option value="<?php echo $autor['id']; ?>">
                                                <?php echo htmlspecialchars($autor['nombre'] . ' ' . $autor['apellido']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="isbn" class="form-label">ISBN</label>
                                    <input type="text" class="form-control" id="isbn" name="isbn" placeholder="978-XXXXXXXXX">
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="precio" class="form-label">Precio *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="stock" class="form-label">Stock *</label>
                                    <input type="number" class="form-control" id="stock" name="stock" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="genero" class="form-label">Género</label>
                                    <input type="text" class="form-control" id="genero" name="genero" placeholder="Ej: Novela, Ensayo...">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="editorial" class="form-label">Editorial</label>
                                    <input type="text" class="form-control" id="editorial" name="editorial">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="paginas" class="form-label">Páginas</label>
                                    <input type="number" class="form-control" id="paginas" name="paginas">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="fecha_publicacion" class="form-label">Fecha de publicación</label>
                                <input type="date" class="form-control" id="fecha_publicacion" name="fecha_publicacion">
                            </div>
                            
                            <div class="mb-3">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Descripción del libro..."></textarea>
                            </div>
                            
                            <button type="submit" name="agregar_libro" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>Agregar Libro
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Panel lateral -->
            <div class="col-lg-4">
                <!-- Formulario para agregar autor -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>Agregar Autor
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="apellido" class="form-label">Apellido *</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="nacionalidad" class="form-label">Nacionalidad</label>
                                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad">
                            </div>
                            
                            <div class="mb-3">
                                <label for="fecha_nacimiento" class="form-label">Fecha de nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                            </div>
                            
                            <div class="mb-3">
                                <label for="biografia" class="form-label">Biografía</label>
                                <textarea class="form-control" id="biografia" name="biografia" rows="3"></textarea>
                            </div>
                            
                            <button type="submit" name="agregar_autor" class="btn btn-success">
                                <i class="fas fa-user-plus me-1"></i>Agregar Autor
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Enlaces útiles -->
                <div class="card shadow">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-link me-2"></i>Enlaces Útiles
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="libros.php" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-book me-1"></i>Ver Libros
                            </a>
                            <a href="autores.php" target="_blank" class="btn btn-outline-success">
                                <i class="fas fa-users me-1"></i>Ver Autores
                            </a>
                            <a href="http://localhost/phpmyadmin/" target="_blank" class="btn btn-outline-warning">
                                <i class="fas fa-database me-1"></i>phpMyAdmin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Últimos libros agregados -->
        <?php if (!empty($ultimos_libros)): ?>
        <div class="row mt-5">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clock me-2"></i>Últimos Libros Agregados
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Autor</th>
                                        <th>Precio</th>
                                        <th>Stock</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($ultimos_libros as $libro): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($libro['titulo']); ?></td>
                                        <td><?php echo htmlspecialchars($libro['nombre'] . ' ' . $libro['apellido']); ?></td>
                                        <td><?php echo formatearPrecio($libro['precio']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $libro['stock'] > 0 ? 'bg-success' : 'bg-danger'; ?>">
                                                <?php echo $libro['stock']; ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatearFecha($libro['created_at']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>