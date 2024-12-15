// Función para validar el nombre
function validateName() {
    const name = document.getElementById('name').value.trim();
    const errorName = document.getElementById('errorName');

    if (name === '') {
        errorName.textContent = "El nombre no puede estar vacío.";
    } else {
        errorName.textContent = ''; 
    }
}

// Función para validar el apellido
function validateSurname() {
    const surname = document.getElementById('surname').value.trim();
    const errorSurname = document.getElementById('errorSurname');

    if (surname === '') {
        errorSurname.textContent = "El apellido no puede estar vacío.";
    } else {
        errorSurname.textContent = ''; 
    }
}

// Función para validar el nombre de usuario
function validateUsername() {
    const username = document.getElementById('username').value.trim();
    const errorUsername = document.getElementById('errorUsername');

    if (username === '') {
        errorUsername.textContent = "El nombre de usuario no puede estar vacío.";
    } else {
        errorUsername.textContent = ''; 
    }
}

// Función para validar el rol
function validateRole() {
    const rolUser = document.getElementById('rol_user').value;
    const errorRole = document.getElementById('errorRole');

    if (rolUser === '') {
        errorRole.textContent = "Debe seleccionar un rol.";
    } else {
        errorRole.textContent = ''; 
    }
}
