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