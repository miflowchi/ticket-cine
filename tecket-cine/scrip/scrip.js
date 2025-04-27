// Función para cambiar el fondo del navbar al hacer scroll
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Inicializar sliders y carruseles si existen
    initializeMovieSliders();
    handlePurchaseRedirect();
});

// Función para inicializar los sliders de películas
function initializeMovieSliders() {
    const sliders = document.querySelectorAll('.movie-slider');
    
    sliders.forEach(slider => {
        // Implementación básica de scroll horizontal con mouse
        let isDown = false;
        let startX;
        let scrollLeft;

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // Velocidad de scroll
            slider.scrollLeft = scrollLeft - walk;
        });
    });
}

// Función para mostrar/ocultar menú de usuario
function toggleUserMenu() {
    const userMenu = document.querySelector('.user-menu-dropdown');
    if (userMenu) {
        userMenu.classList.toggle('show');
    }
}

// Función para simular búsqueda
function searchMovies() {
    const searchInput = document.querySelector('.search input');
    if (searchInput && searchInput.value.trim() !== '') {
        // Aquí iría la lógica real de búsqueda
        // Por ahora, solo redirecciona a una página de resultados
        window.location.href = `busqueda.html?q=${encodeURIComponent(searchInput.value.trim())}`;
    }
}

// Event listener para el botón de búsqueda
document.addEventListener('DOMContentLoaded', function() {
    const searchBtn = document.querySelector('.search-btn');
    if (searchBtn) {
        searchBtn.addEventListener('click', searchMovies);
    }

    // También permitir búsqueda con Enter
    const searchInput = document.querySelector('.search input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchMovies();
            }
        });
    }
});

// Función para manejar redirección después de la compra
function handlePurchaseRedirect() {
    const params = new URLSearchParams(window.location.search);
    if (params.has('message')) {
        alert(params.get('message'));
    }
}