
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const metodoTarjeta = document.querySelector('input[value="tarjeta"]');
    const metodoYape = document.querySelector('input[value="yape"]');
    const infoTarjeta = document.getElementById('tarjeta-info');
    const infoYape = document.getElementById('yape-info');
    const cantidadSelect = document.getElementById('cantidad');
    const totalPago = document.getElementById('total-pago');
    const formPago = document.querySelector('form');

    // 1. Cambiar entre métodos de pago
    if (metodoTarjeta && metodoYape) {
        // Mostrar método inicial
        toggleMetodoPago();

        // Escuchar cambios en los radio buttons
        metodoTarjeta.addEventListener('change', toggleMetodoPago);
        metodoYape.addEventListener('change', toggleMetodoPago);
    }

    function toggleMetodoPago() {
        if (metodoTarjeta.checked) {
            infoTarjeta.style.display = 'block';
            infoYape.style.display = 'none';
        } else {
            infoTarjeta.style.display = 'none';
            infoYape.style.display = 'block';
        }
    }

    // 2. Actualizar total al cambiar cantidad
    if (cantidadSelect && totalPago) {
        const precioUnitario = parseFloat(totalPago.textContent.replace('S/. ', ''));
        
        cantidadSelect.addEventListener('change', function() {
            const cantidad = parseInt(this.value);
            const total = cantidad * precioUnitario;
            totalPago.textContent = `S/. ${total.toFixed(2)}`;
        });
    }

    // 3. Formatear número de tarjeta (XXXX XXXX XXXX XXXX)
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

    // 4. Formatear fecha de expiración (MM/AA)
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

    // 5. Validar CVV (3 o 4 dígitos)
    const tarjetaCVV = document.getElementById('tarjeta-cvv');
    if (tarjetaCVV) {
        tarjetaCVV.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (this.value.length > 4) {
                this.value = this.value.slice(0, 4);
            }
        });
    }

    // 6. Validación del formulario antes de enviar
    if (formPago) {
        formPago.addEventListener('submit', function(e) {
            let isValid = true;

            // Validar campos generales
            if (!cantidadSelect.value || cantidadSelect.value < 1 || cantidadSelect.value > 10) {
                markAsInvalid(cantidadSelect);
                isValid = false;
            }

            if (!document.getElementById('nombre').value.trim()) {
                markAsInvalid(document.getElementById('nombre'));
                isValid = false;
            }

            const email = document.getElementById('email').value;
            if (!email.trim() || !validateEmail(email)) {
                markAsInvalid(document.getElementById('email'));
                isValid = false;
            }

            // Validar según método de pago
            if (metodoTarjeta.checked) {
                if (!validateCardNumber(tarjetaNumero.value)) {
                    markAsInvalid(tarjetaNumero);
                    isValid = false;
                }

                if (!validateExpirationDate(tarjetaExpiracion.value)) {
                    markAsInvalid(tarjetaExpiracion);
                    isValid = false;
                }

                if (!tarjetaCVV.value || tarjetaCVV.value.length < 3) {
                    markAsInvalid(tarjetaCVV);
                    isValid = false;
                }

                if (!document.getElementById('tarjeta-nombre').value.trim()) {
                    markAsInvalid(document.getElementById('tarjeta-nombre'));
                    isValid = false;
                }
            }

            if (!isValid) {
                e.preventDefault();
                showErrorToast('Por favor complete todos los campos requeridos correctamente');
            }
        });
    }

    // Funciones de ayuda
    function markAsInvalid(element) {
        element.style.borderColor = '#e50914';
        setTimeout(() => {
            element.style.borderColor = '';
        }, 2000);
    }

    function showErrorToast(message) {
        const toast = document.createElement('div');
        toast.className = 'toast-error';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function validateCardNumber(number) {
        const cleaned = number.replace(/\s+/g, '');
        return /^[0-9]{13,16}$/.test(cleaned);
    }

    function validateExpirationDate(date) {
        if (!/^\d{2}\/\d{2}$/.test(date)) return false;
        
        const [month, year] = date.split('/').map(Number);
        const currentYear = new Date().getFullYear() % 100;
        const currentMonth = new Date().getMonth() + 1;
        
        if (year < currentYear || (year === currentYear && month < currentMonth)) return false;
        if (month < 1 || month > 12) return false;
        
        return true;
    }

    // 7. Animación al enviar el formulario
    if (formPago) {
        formPago.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="loading-spinner"></i> Procesando...';
            }
        });
    }
});