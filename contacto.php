<?php
require_once 'config.php';

$mensaje_exito = '';
$mensaje_error = '';
$nombre = '';
$correo = '';
$asunto = '';
$comentario = '';

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener y limpiar los datos del formulario
    $nombre = limpiarDatos($_POST['nombre'] ?? '');
    $correo = limpiarDatos($_POST['correo'] ?? '');
    $asunto = limpiarDatos($_POST['asunto'] ?? '');
    $comentario = limpiarDatos($_POST['comentario'] ?? '');
    
    // Validar los datos
    $errores = [];
    
    if (empty($nombre)) {
        $errores[] = "El nombre es obligatorio.";
    }
    
    if (empty($correo)) {
        $errores[] = "El correo electrónico es obligatorio.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo electrónico no es válido.";
    }
    
    if (empty($asunto)) {
        $errores[] = "El asunto es obligatorio.";
    }
    
    if (empty($comentario)) {
        $errores[] = "El comentario es obligatorio.";
    }
    
    // Si no hay errores, guardar en la base de datos
    if (empty($errores)) {
        try {
            $sql = "INSERT INTO contacto (nombre, correo, asunto, comentario, fecha) VALUES (:nombre, :correo, :asunto, :comentario, NOW())";
            $stmt = $pdo->prepare($sql);
            
            $resultado = $stmt->execute([
                ':nombre' => $nombre,
                ':correo' => $correo,
                ':asunto' => $asunto,
                ':comentario' => $comentario
            ]);
            
            if ($resultado) {
                $mensaje_exito = "¡Gracias por contactarnos! Tu mensaje ha sido enviado correctamente. Te responderemos a la brevedad posible.";
                // Limpiar los campos después del envío exitoso
                $nombre = '';
                $correo = '';
                $asunto = '';
                $comentario = '';
            } else {
                $mensaje_error = "Hubo un error al enviar tu mensaje. Por favor, inténtalo de nuevo.";
            }
            
        } catch(PDOException $e) {
            $mensaje_error = "Error al enviar el mensaje: " . $e->getMessage();
        }
    } else {
        $mensaje_error = "Por favor, corrige los siguientes errores:<br>" . implode('<br>', $errores);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - Librería Online</title>
    
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
                        <a class="nav-link" href="autores.php">
                            <i class="fas fa-user-edit me-1"></i>Autores
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="contacto.php">
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
                        <i class="fas fa-envelope me-3"></i>Contáctanos
                    </h1>
                    <p class="lead text-white">Estamos aquí para ayudarte con cualquier consulta</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <div class="container my-5">
        <div class="row">
            <!-- Formulario de contacto -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-paper-plane me-2"></i>Envíanos un mensaje
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Mostrar mensajes -->
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

                        <form method="POST" action="" id="contactForm">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nombre completo *
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?php echo htmlspecialchars($nombre); ?>"
                                           required>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="correo" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Correo electrónico *
                                    </label>
                                    <input type="email" 
                                           class="form-control" 
                                           id="correo" 
                                           name="correo" 
                                           value="<?php echo htmlspecialchars($correo); ?>"
                                           required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="asunto" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Asunto *
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="asunto" 
                                       name="asunto" 
                                       value="<?php echo htmlspecialchars($asunto); ?>"
                                       placeholder="Ej: Consulta sobre disponibilidad de libro"
                                       required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="comentario" class="form-label">
                                    <i class="fas fa-comment me-1"></i>Mensaje *
                                </label>
                                <textarea class="form-control" 
                                          id="comentario" 
                                          name="comentario" 
                                          rows="6" 
                                          placeholder="Escribe aquí tu consulta, comentario o sugerencia..."
                                          required><?php echo htmlspecialchars($comentario); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Los campos marcados con * son obligatorios.
                                </small>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Enviar mensaje
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Información de contacto -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Información de contacto
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="contact-info">
                            <div class="mb-3">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>Dirección:</strong><br>
                                <small class="text-muted">
                                    Av. Principal 123<br>
                                    Santo Domingo, República Dominicana
                                </small>
                            </div>
                            
                            <div class="mb-3">
                                <i class="fas fa-phone text-primary me-2"></i>
                                <strong>Teléfono:</strong><br>
                                <small class="text-muted">+1 (809) 123-4567</small>
                            </div>
                            
                            <div class="mb-3">
                                <i class="fas fa-envelope text-primary me-2"></i>
                                <strong>Email:</strong><br>
                                <small class="text-muted">info@libreriaonline.com</small>
                            </div>
                            
                            <div class="mb-3">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>Horarios de atención:</strong><br>
                                <small class="text-muted">
                                    Lunes a Viernes: 9:00 AM - 6:00 PM<br>
                                    Sábados: 9:00 AM - 2:00 PM<br>
                                    Domingos: Cerrado
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-question-circle me-2"></i>¿Necesitas ayuda?
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Nuestro equipo está disponible para ayudarte con:
                        </p>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Consultas sobre libros</li>
                            <li><i class="fas fa-check text-success me-2"></i>Información de autores</li>
                            <li><i class="fas fa-check text-success me-2"></i>Disponibilidad de stock</li>
                            <li><i class="fas fa-check text-success me-2"></i>Recomendaciones personalizadas</li>
                            <li><i class="fas fa-check text-success me-2"></i>Soporte técnico</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sección de FAQ -->
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4">Preguntas Frecuentes</h2>
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                ¿Cómo puedo consultar la disponibilidad de un libro?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Puedes consultar la disponibilidad visitando nuestra sección de <a href="libros.php">Libros</a> 
                                donde encontrarás el stock disponible de cada título, o contactándonos directamente.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                ¿Realizan envíos a todo el país?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Sí, realizamos envíos a toda la República Dominicana. Los tiempos de entrega varían 
                                según la ubicación, desde 24 horas en Santo Domingo hasta 3-5 días laborables en el interior.
                            </div>
                        </div>
                    </div>
                    
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                ¿Puedo reservar un libro?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                ¡Por supuesto! Puedes reservar cualquier libro contactándonos a través de este formulario 
                                o llamándonos directamente. Mantenemos tu reserva por 7 días laborables.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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