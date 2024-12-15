// Función para validar el número de asientos
function validateAsientos() {
    const numAsientos = document.getElementById('n_asientos').value;
    const errorAsientos = document.getElementById('errorAsientos');

    if (numAsientos === '') {
        errorAsientos.textContent = "El número de asientos no puede estar vacío.";
    } else if (numAsientos > 12) {
        errorAsientos.textContent = "El número de asientos no puede ser mayor a 12.";
    } else {
        errorAsientos.textContent = ''; 
    }
}

// Función para validar la selección de la sala
function validateSala() {
    const sala = document.getElementById('id_sala').value;
    const errorSalaMesa = document.getElementById('errorSalaMesa');

    if (sala === '') {
        errorSalaMesa.textContent = "Debe seleccionar una sala.";
    } else {
        errorSalaMesa.textContent = ''; 
    }
}

// Función para validar todos los campos antes de enviar el formulario
function validateForm() {
    validateAsientos();
    validateSala();

    const errorAsientos = document.getElementById('errorAsientos').textContent;
    const errorSalaMesa = document.getElementById('errorSalaMesa').textContent;

    // Si hay algún error, no permitir el envío del formulario
    if (errorAsientos || errorSalaMesa) {
        return false; // Evitar submit
    }
    return true; // Permitir submit
}

// Asociar la validación al evento submit del formulario
document.querySelector("form").addEventListener("submit", function(event) {
    if (!validateForm()) {
        event.preventDefault(); // Evitar que el formulario se envíe si hay errores
    }
});
