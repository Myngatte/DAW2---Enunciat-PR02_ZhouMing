document.addEventListener("DOMContentLoaded", function () {
    const salaInput = document.getElementById('sala');
    const asientosInput = document.getElementById('asientos');
    const submitBtn = document.querySelector('button[type="submit"]');

    // Elementos de error
    const errorSala = document.getElementById('errorSala');
    const errorAsientos = document.getElementById('errorAsientos');

    // Función para habilitar o deshabilitar el botón de envío
    function updateSubmitButton() {
        if (validateSala() && validateAsientos()) {
            submitBtn.disabled = false; 
        } else {
            submitBtn.disabled = true; 
        }
    }

    // Validar Sala
    window.validateSala = function () {
        if (salaInput.value === '') {
            errorSala.textContent = 'Debe seleccionar una sala.';
            errorSala.style.color = 'red';
            return false;
        }
        errorSala.textContent = '';
        return true;
    };

    // Validar Número de Asientos
    window.validateAsientos = function () {
        const asientosValue = asientosInput.value;
        if (asientosValue === '') {
            errorAsientos.textContent = 'El número de asientos no puede estar vacío.';
            errorAsientos.style.color = 'red';
            return false;
        }
        if (asientosValue > 12) {
            errorAsientos.textContent = 'El número de asientos no puede ser mayor a 12.';
            errorAsientos.style.color = 'red';
            return false;
        }
        if (isNaN(asientosValue)) {
            errorAsientos.textContent = 'Deben ser numérico';
            errorAsientos.style.color = 'red';
            return false;
        }
        errorAsientos.textContent = '';
        return true;
    };

    // Validar los campos cuando pierden el foco (onblur)
    salaInput.addEventListener('blur', function () {
        validateSala();
        updateSubmitButton(); 
    });

    asientosInput.addEventListener('blur', function () {
        validateAsientos();
        updateSubmitButton();
    });

    // Deshabilitar el botón de submit al inicio
    submitBtn.disabled = true;

});

// Sweetalert
const urlParams = new URLSearchParams(window.location.search);
const NMesa = urlParams.get('new_mesa');

if (NMesa === 'success') {
    Swal.fire({
        icon: 'success',
        title: 'Bien',
        text: 'Mesa añadida con éxito',
        background: '#1b1b1b',
        color: '#fff',
        iconColor: '#ff0080',
        confirmButtonColor: '#ff00e6', 
        confirmButtonText: 'Aceptar',
        customClass: {
            popup: 'popup-neon',
            title: 'title-neon',
            content: 'content-neon',
            footer: 'footer-neon'
        }
    });
}
