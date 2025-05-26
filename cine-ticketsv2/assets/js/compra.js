document.addEventListener('DOMContentLoaded', function() {
    // Mostrar la primera fecha por defecto
    const primeraFecha = document.querySelector('.fechas-tabs button');
    if (primeraFecha) {
        primeraFecha.classList.add('active');
        const fecha = primeraFecha.getAttribute('data-fecha');
        document.querySelector(`.funciones-list[data-fecha="${fecha}"]`).classList.add('active');
    }

    // Cambiar entre fechas
    const tabBtns = document.querySelectorAll('.fechas-tabs button');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remover clase active de todos los botones y listas
            document.querySelectorAll('.fechas-tabs button').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.funciones-list').forEach(list => list.classList.remove('active'));
            
            // Agregar clase active al botón clickeado y su lista correspondiente
            this.classList.add('active');
            const fecha = this.getAttribute('data-fecha');
            document.querySelector(`.funciones-list[data-fecha="${fecha}"]`).classList.add('active');
        });
    });

    // Animación al seleccionar una función
    const funcionCards = document.querySelectorAll('.funcion-card');
    funcionCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remover selección de todas las tarjetas
            funcionCards.forEach(c => c.classList.remove('selected'));
            // Seleccionar la tarjeta clickeada
            this.classList.add('selected');
            
            // Animación de confirmación
            this.style.transform = 'scale(1.02)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    });

    // Validar que se seleccione una función antes de continuar
    const selectButtons = document.querySelectorAll('.funcion-card .btn');
    selectButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            const funcionCard = this.closest('.funcion-card');
            if (!funcionCard) {
                e.preventDefault();
                alert('Por favor selecciona una función');
                return;
            }
            
            // Verificar disponibilidad
            const asientosDisponibles = funcionCard.querySelector('p:nth-of-type(2)').textContent;
            const disponibles = parseInt(asientosDisponibles.replace('Asientos disponibles: ', ''));
            
            if (disponibles <= 0) {
                e.preventDefault();
                alert('Lo sentimos, no hay asientos disponibles para esta función');
                return;
            }
            
            // Continuar con el proceso de compra
        });
    });

    // Mostrar información de disponibilidad
    function actualizarDisponibilidad() {
        funcionCards.forEach(card => {
            const asientosDisponibles = card.querySelector('p:nth-of-type(2)').textContent;
            const disponibles = parseInt(asientosDisponibles.replace('Asientos disponibles: ', ''));
            
            if (disponibles <= 0) {
                card.style.opacity = '0.6';
                card.querySelector('.btn').textContent = 'Agotado';
                card.querySelector('.btn').style.backgroundColor = '#999';
                card.querySelector('.btn').style.cursor = 'not-allowed';
            } else if (disponibles < 10) {
                card.querySelector('p:nth-of-type(2)').style.color = '#e50914';
                card.querySelector('p:nth-of-type(2)').style.fontWeight = 'bold';
            }
        });
    }

    // Llamar a la función al cargar la página
    actualizarDisponibilidad();

    // Efecto hover en las tarjetas de película
    const peliculaCards = document.querySelectorAll('.pelicula-card');
    peliculaCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.2)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
        });
    });

    // Mostrar mensaje si no hay funciones disponibles
    const funcionesDisponibles = document.querySelectorAll('.funcion-card').length;
    if (funcionesDisponibles === 0) {
        const funcionesSection = document.querySelector('.funciones');
        const mensaje = document.createElement('p');
        mensaje.textContent = 'Actualmente no hay funciones disponibles para esta película. Por favor revisa más tarde.';
        mensaje.style.textAlign = 'center';
        mensaje.style.padding = '20px';
        mensaje.style.color = '#e50914';
        funcionesSection.appendChild(mensaje);
    }
});