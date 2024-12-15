// Sweetalert edit
const urlParams = new URLSearchParams(window.location.search);
const Edit= urlParams.get('edit'); 

if (Edit=== 'success') {
    Swal.fire({
        icon: 'success',
        title: 'Bien',
        text: 'Edición hecha', 
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

// Sweetalert eliminar
const deleteButtons = document.querySelectorAll('.btn-delete');

deleteButtons.forEach(button => {
  button.addEventListener('click', function (e) {
    e.preventDefault();  // Evita el envio de formulario

    const deleteUrl = this.closest('a').getAttribute('href'); // Obtén la URL del enlace para reenviar luego

    // Muestra el SweetAlert de confirmación
    Swal.fire({
    title: "¿Estás seguro?",
    text: "No podrás revertir esta acción",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#ff00e6",
    cancelButtonColor: "#ff3333",
    confirmButtonText: "¡Seguro!",
    cancelButtonText: "Cancelar",
    customClass: {
        popup: 'popup-neon-delete',
        title: 'title-neon-delete',
        content: 'content-neon-delete',
        footer: 'footer-neon-delete',
        confirmButton: 'swal2-confirm-delete',
        cancelButton: 'swal2-cancel-delete'
    }
    }).then((result) => {
    if (result.isConfirmed) {
        // Acción a ejecutar cuando se confirma
        window.location.href = deleteUrl;
    }
    });
  });
});
