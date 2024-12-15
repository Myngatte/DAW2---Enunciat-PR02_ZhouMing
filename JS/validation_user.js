document.addEventListener("DOMContentLoaded", function () {
    const nameInput = document.getElementById('name');
    const surnameInput = document.getElementById('surname');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const passwordRInput = document.getElementById('passwordR');
    const rolSelect = document.getElementById('rol');
    const submitBtn = document.getElementById('submitBtn');

    const errorNombre = document.getElementById('errorNombre');
    const errorApellido = document.getElementById('errorApellido');
    const errorUsr = document.getElementById('errorUsr');
    const errorPassword = document.getElementById('errorPassword');
    const errorPasswordR = document.getElementById('errorPasswordR');
    const errorRol = document.getElementById('errorRol');

    // Función para habilitar o deshabilitar el botón de envío
    function updateSubmitButton() {
        submitBtn.disabled = !(
            validateName() &&
            validateSurname() &&
            validateUsername() &&
            validatePassword() &&
            validatePasswordRepeat() &&
            validateRole()
        );
    }

    // Validar Nombre
    window.validateName = function () {
        if (nameInput.value.trim() === '') {
            errorNombre.textContent = 'El nombre es obligatorio.';
            return false;
        }
        errorNombre.textContent = '';
        return true;
    };

    // Validar Apellido
    window.validateSurname = function () {
        if (surnameInput.value.trim() === '') {
            errorApellido.textContent = 'El apellido es obligatorio.';
            return false;
        }
        errorApellido.textContent = '';
        return true;
    };

    // Validar Nombre de Usuario
    window.validateUsername = function () {
        if (usernameInput.value.trim() === '') {
            errorUsr.textContent = 'El nombre de usuario es obligatorio.';
            return false;
        }
        errorUsr.textContent = '';
        return true;
    };

    // Validar Contraseña
    window.validatePassword = function () {
        const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[.\!@#\$%\^&\*\(\)_\+\-=\[\]\{\};:\'",<>\./?\\|]).{8,}$/;
        if (passwordInput.value.trim() === '') {
            errorPassword.textContent = 'La contraseña es obligatoria.';
            return false;
        } else if (!passwordRegex.test(passwordInput.value)) {
            errorPassword.textContent = 'Debe tener al menos 8 caracteres, 1 mayúscula, 1 minúscula, 1 número y 1 carácter especial.';
            return false;
        }
        errorPassword.textContent = '';
        return true;
    };

    // Validar Repetir Contraseña
    window.validatePasswordRepeat = function () {
        if (passwordRInput.value.trim() === '') {
            errorPasswordR.textContent = 'Por favor, repita la contraseña.';
            return false;
        } else if (passwordRInput.value !== passwordInput.value) {
            errorPasswordR.textContent = 'Las contraseñas no coinciden.';
            return false;
        }
        errorPasswordR.textContent = '';
        return true;
    };

    // Validar Rol
    window.validateRole = function () {
        if (rolSelect.value === '') {
            errorRol.textContent = 'Debe seleccionar un rol.';
            return false;
        }
        errorRol.textContent = '';
        return true;
    };

    nameInput.addEventListener('blur', function () {
        validateName();
        updateSubmitButton();
    });

    surnameInput.addEventListener('blur', function () {
        validateSurname();
        updateSubmitButton();
    });

    usernameInput.addEventListener('blur', function () {
        validateUsername();
        updateSubmitButton();
    });

    passwordInput.addEventListener('blur', function () {
        validatePassword();
        updateSubmitButton();
    });

    passwordRInput.addEventListener('blur', function () {
        validatePasswordRepeat();
        updateSubmitButton();
    });

    rolSelect.addEventListener('blur', function () {
        validateRole();
        updateSubmitButton();
    });

    // Botón deshabilitado, por si acaso
    submitBtn.disabled = true; 
});


// Sweetalert
const urlParams = new URLSearchParams(window.location.search);
const error = urlParams.get('error');
const NUser = urlParams.get('new_user');

if (NUser === 'success') {
    Swal.fire({
        icon: 'success',
        title: 'Bien',
        text: 'Usuario añadido con éxito',
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


if (error === 'usuarioexistente') {
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'Usuario ya existente',
        footer: '<p>Ese username ya está en uso</p>',
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