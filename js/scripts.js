/**
 * Librería Online - JavaScript personalizado
 * Autor: Ashlee Hernandez 
 */

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    
    // Inicializar todas las funcionalidades
    initNavbar();
    initAnimations();
    initFormValidation();
    initSearchFilters();
    initTooltips();
    initScrollEffects();
    
    console.log('✓ Librería Online - JavaScript inicializado correctamente');
});

/**
 * Funcionalidades de la barra de navegación
 */
function initNavbar() {
    const navbar = document.querySelector('.navbar');
    
    // Efecto de scroll en la navbar
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('navbar-scrolled');
        } else {
            navbar.classList.remove('navbar-scrolled');
        }
    });
    
    // Cerrar navbar móvil al hacer clic en un enlace
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
                navbarToggler.click();
            }
        });
    });
}

/**
 * Animaciones y efectos visuales
 */
function initAnimations() {
    // Animación de fade-in para las cards
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    // Observar todas las cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    
    // Efecto de hover en botones
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

/**
 * Validación de formularios
 */
function initFormValidation() {
    const contactForm = document.getElementById('contactForm');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            if (!validateContactForm()) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
        
        // Validación en tiempo real
        const inputs = contactForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });
    }
}

/**
 * Validar formulario de contacto
 */
function validateContactForm() {
    const nombre = document.getElementById('nombre');
    const correo = document.getElementById('correo');
    const asunto = document.getElementById('asunto');
    const comentario = document.getElementById('comentario');
    
    let isValid = true;
    
    // Validar nombre
    if (!nombre.value.trim()) {
        showFieldError(nombre, 'El nombre es obligatorio');
        isValid = false;
    } else if (nombre.value.trim().length < 2) {
        showFieldError(nombre, 'El nombre debe tener al menos 2 caracteres');
        isValid = false;
    }
    
    // Validar correo
    if (!correo.value.trim()) {
        showFieldError(correo, 'El correo electrónico es obligatorio');
        isValid = false;
    } else if (!isValidEmail(correo.value)) {
        showFieldError(correo, 'El correo electrónico no es válido');
        isValid = false;
    }
    
    // Validar asunto
    if (!asunto.value.trim()) {
        showFieldError(asunto, 'El asunto es obligatorio');
        isValid = false;
    } else if (asunto.value.trim().length < 5) {
        showFieldError(asunto, 'El asunto debe tener al menos 5 caracteres');
        isValid = false;
    }
    
    // Validar comentario
    if (!comentario.value.trim()) {
        showFieldError(comentario, 'El comentario es obligatorio');
        isValid = false;
    } else if (comentario.value.trim().length < 10) {
        showFieldError(comentario, 'El comentario debe tener al menos 10 caracteres');
        isValid = false;
    }
    
    return isValid;
}

/**
 * Validar un campo individual
 */
function validateField(field) {
    const value = field.value.trim();
    const fieldName = field.getAttribute('name');
    
    clearFieldError(field);
    
    switch(fieldName) {
        case 'nombre':
            if (!value) {
                showFieldError(field, 'El nombre es obligatorio');
            } else if (value.length < 2) {
                showFieldError(field, 'El nombre debe tener al menos 2 caracteres');
            }
            break;
            
        case 'correo':
            if (!value) {
                showFieldError(field, 'El correo electrónico es obligatorio');
            } else if (!isValidEmail(value)) {
                showFieldError(field, 'El correo electrónico no es válido');
            }
            break;
            
        case 'asunto':
            if (!value) {
                showFieldError(field, 'El asunto es obligatorio');
            } else if (value.length < 5) {
                showFieldError(field, 'El asunto debe tener al menos 5 caracteres');
            }
            break;
            
        case 'comentario':
            if (!value) {
                showFieldError(field, 'El comentario es obligatorio');
            } else if (value.length < 10) {
                showFieldError(field, 'El comentario debe tener al menos 10 caracteres');
            }
            break;
    }
}

/**
 * Mostrar error en un campo
 */
function showFieldError(field, message) {
    field.classList.add('is-invalid');
    
    let errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        field.parentNode.appendChild(errorDiv);
    }
    
    errorDiv.textContent = message;
}

/**
 * Limpiar error de un campo
 */
function clearFieldError(field) {
    field.classList.remove('is-invalid');
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');
    if (errorDiv) {
        errorDiv.remove();
    }
}

/**
 * Validar formato de email
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Funcionalidades de búsqueda y filtros
 */
function initSearchFilters() {
    // Auto-submit en filtros de select
    const filterSelects = document.querySelectorAll('select[name="genero"], select[name="nacionalidad"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Pequeño delay para mejor UX
            setTimeout(() => {
                this.closest('form').submit();
            }, 100);
        });
    });
    
    // Limpiar búsqueda con Escape
    const searchInputs = document.querySelectorAll('input[name="busqueda"]');
    searchInputs.forEach(input => {
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                this.focus();
            }
        });
    });
    
    // Highlight de términos de búsqueda
    highlightSearchTerms();
}

/**
 * Resaltar términos de búsqueda en los resultados
 */
function highlightSearchTerms() {
    const urlParams = new URLSearchParams(window.location.search);
    const searchTerm = urlParams.get('busqueda');
    
    if (searchTerm && searchTerm.trim() !== '') {
        const elements = document.querySelectorAll('.card-title, .card-text');
        elements.forEach(element => {
            const html = element.innerHTML;
            const regex = new RegExp(`(${escapeRegExp(searchTerm)})`, 'gi');
            element.innerHTML = html.replace(regex, '<mark class="bg-warning">$1</mark>');
        });
    }
}

/**
 * Escapar caracteres especiales para regex
 */
function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

/**
 * Inicializar tooltips de Bootstrap
 */
function initTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Efectos de scroll
 */
function initScrollEffects() {
    // Smooth scroll para enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Botón de scroll to top
    createScrollToTopButton();
}

/**
 * Crear botón de scroll hacia arriba
 */
function createScrollToTopButton() {
    const scrollBtn = document.createElement('button');
    scrollBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
    scrollBtn.className = 'btn btn-primary position-fixed';
    scrollBtn.style.cssText = `
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    `;
    
    document.body.appendChild(scrollBtn);
    
    // Mostrar/ocultar botón según scroll
    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
            scrollBtn.style.display = 'block';
        } else {
            scrollBtn.style.display = 'none';
        }
    });
    
    // Scroll to top al hacer clic
    scrollBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Utilidades adicionales
 */

// Función para mostrar loading en botones
function showButtonLoading(button, loadingText = 'Cargando...') {
    const originalText = button.innerHTML;
    button.innerHTML = `<span class="loading me-2"></span>${loadingText}`;
    button.disabled = true;
    
    return () => {
        button.innerHTML = originalText;
        button.disabled = false;
    };
}

// Función para mostrar notificaciones toast
function showToast(message, type = 'success') {
    const toastContainer = getOrCreateToastContainer();
    
    const toastHtml = `
        <div class="toast align-items-center text-white bg-${type} border-0" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
    
    // Remover el toast del DOM después de que se oculte
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Crear contenedor de toasts si no existe
function getOrCreateToastContainer() {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container position-fixed top-0 end-0 p-3';
        container.style.zIndex = '1055';
        document.body.appendChild(container);
    }
    return container;
}

// Función para formatear números
function formatNumber(num) {
    return new Intl.NumberFormat('es-DO').format(num);
}

// Función para formatear precios
function formatPrice(price) {
    return new Intl.NumberFormat('es-DO', {
        style: 'currency',
        currency: 'DOP'
    }).format(price);
}

// Debounce para optimizar eventos de scroll/resize
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Exportar funciones para uso global
window.LibreriaOnline = {
    showToast,
    showButtonLoading,
    formatNumber,
    formatPrice,
    debounce
};