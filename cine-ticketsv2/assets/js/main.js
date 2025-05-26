// Funcionalidad para las pestañas de fechas en la página de compra
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
    
    // Cambiar entre métodos de pago
    const metodoTarjeta = document.querySelector('input[value="tarjeta"]');
    const metodoYape = document.querySelector('input[value="yape"]');
    const infoTarjeta = document.getElementById('tarjeta-info');
    const infoYape = document.getElementById('yape-info');
    
    if (metodoTarjeta && metodoYape) {
        metodoTarjeta.addEventListener('change', function() {
            if (this.checked) {
                infoTarjeta.style.display = 'block';
                infoYape.style.display = 'none';
            }
        });
        
        metodoYape.addEventListener('change', function() {
            if (this.checked) {
                infoTarjeta.style.display = 'none';
                infoYape.style.display = 'block';
            }
        });
    }
    
    // Actualizar total al cambiar cantidad
    const cantidadSelect = document.getElementById('cantidad');
    const totalPago = document.getElementById('total-pago');
    if (cantidadSelect && totalPago) {
        const precioUnitario = parseFloat(totalPago.textContent.replace('S/. ', ''));
        
        cantidadSelect.addEventListener('change', function() {
            const cantidad = parseInt(this.value);
            const total = cantidad * precioUnitario;
            totalPago.textContent = `S/. ${total.toFixed(2)}`;
        });
    }
    
    // Formatear número de tarjeta
    const tarjetaNumero = document.getElementById('tarjeta-numero');
    if (tarjetaNumero) {
        tarjetaNumero.addEventListener('input', function() {
            let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += ' ';
                }
                formatted += value[i];
            }
            
            this.value = formatted;
        });
    }
    
    // Formatear fecha de expiración
    const tarjetaExpiracion = document.getElementById('tarjeta-expiracion');
    if (tarjetaExpiracion) {
        tarjetaExpiracion.addEventListener('input', function() {
            let value = this.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formatted = '';
            
            for (let i = 0; i < value.length; i++) {
                if (i === 2) {
                    formatted += '/';
                }
                formatted += value[i];
            }
            
            this.value = formatted;
        });
    }
});