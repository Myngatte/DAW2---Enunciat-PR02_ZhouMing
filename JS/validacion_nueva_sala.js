document.addEventListener("DOMContentLoaded", function () {
    const nameSalaInput = document.getElementById('name_sala');
    const tipoSalaSelect = document.getElementById('tipo_sala');
    const submitBtn = document.querySelector('button[type="submit"]');

    // Elementos de error
    const errorNameSala = document.getElementById('errorNSala');
    const errorTipoSala = document.getElementById('errorTSala');

    // Función para habilitar o deshabilitar el botón de envío
    function updateSubmitButton() {
        if (validateNameSala() && validateTipoSala()) {
            submitBtn.disabled = false; 
        } else {
            submitBtn.disabled = true; 
        }
    }

    // Validar Nombre de la Sala
    window.validateNameSala = function () {
        if (nameSalaInput.value.trim() === '') {
            errorNameSala.textContent = 'El nombre de la sala no puede estar vacío.';
            errorNameSala.style.color = 'red';
            return false;
        }
        errorNameSala.textContent = '';
        return true;
    };

    // Validar Tipo de Sala
    window.validateTipoSala = function () {
        if (tipoSalaSelect.value === '') {
            errorTipoSala.textContent = 'Debe seleccionar un tipo de sala.';
            errorTipoSala.style.color = 'red';
            return false;
        }
        errorTipoSala.textContent = '';
        return true;
    };

    // Validar los campos cuando pierden el foco (onblur)
    nameSalaInput.addEventListener('blur', function () {
        validateNameSala();
        updateSubmitButton(); 
    });

    tipoSalaSelect.addEventListener('blur', function () {
        validateTipoSala();
        updateSubmitButton();
    });

    // Deshabilitar el botón de submit al inicio
    submitBtn.disabled = true; 

});


// Sweetalert
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');
const NSala = urlParams.get('new_sala');

if (NSala === 'success') {
    Swal.fire({
        icon: 'success',
        title: 'Bien',
        text: 'Sala añadida',
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


if (error === 'salaexist') {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Sala ya existente',
        footer: '<p>Ese nombre de sala ya está en uso</p>',
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