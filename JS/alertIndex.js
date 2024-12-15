// Esperar a que el DOM esté cargado
document.addEventListener("DOMContentLoaded", function() {
    // Obtener los parámetros de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const loginSuccess = urlParams.get("login");

    // Verificar si el parámetro login=success está presente
    if (loginSuccess === "success") {
        // Mostrar la alerta de SweetAlert
        Swal.fire({
            position: "center",
            icon: "success",
            title: "Inicio de sesión exitoso.",
            showConfirmButton: false,
            timer: 2000
        });
    }
});


// Función para mostrar u ocultar las salas por tipo de sala
function toggleSalas(tipoSala) {
    // Buscar todos los contenedores de salas
    const salasContainers = document.querySelectorAll('.salas-container');
    
    salasContainers.forEach(container => {
        if (container.id === tipoSala) {
            // Si el contenedor es el que corresponde al tipo de sala seleccionado, lo mostramos
            container.style.display = container.style.display === "none" || container.style.display === "" ? "block" : "none";
        } else {
            // Si no es el contenedor seleccionado, lo ocultamos
            container.style.display = "none";
        }
    });
}