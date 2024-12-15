// Función para validar el nombre de la sala
function validateNameSala() {
    const nameSala = document.getElementById('name_sala').value.trim();
    const errorSala = document.getElementById('errorSala');

    if (nameSala === '') {
        errorSala.textContent = "El nombre de la sala no puede estar vacío.";
    } else {
        errorSala.textContent = ''; 
    }
}

// Función para validar el tipo de sala
function validateTipoSala() {
    const tipoSala = document.getElementById('tipo_sala').value;
    const errorTipoSala = document.getElementById('errorTipoSala');

    if (tipoSala === '') {
        errorTipoSala.textContent = "Debe seleccionar un tipo de sala.";
    } else {
        errorTipoSala.textContent = ''; 
    }
}
