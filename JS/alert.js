// Sweet Alert para confirmación de asignación o desasignación de mesa
document.addEventListener('DOMContentLoaded', () => {
    // Detectar clic en el botón "Asignar"
    const btnAsignar = document.getElementById('btn-asignar');
    const formAsignar = document.getElementById('form-asignar');

    if (btnAsignar) {
        btnAsignar.addEventListener('click', (event) => {
            event.preventDefault(); // Evitar el envío del formulario inmediato
            Swal.fire({
                title: '¿Seguro que quieres asignar esta mesa?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, asignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    formAsignar.submit(); // Enviar el formulario si se confirma
                }
            });
        });
    }

    // Detectar clic en el botón "Desasignar"
    const btnDesasignar = document.getElementById('btn-desasignar');
    const formDesasignar = document.getElementById('form-desasignar');

    if (btnDesasignar) {
        btnDesasignar.addEventListener('click', (event) => {
            event.preventDefault(); // Evitar el envío del formulario inmediato

            Swal.fire({
                title: '¿Seguro que quieres desasignar esta mesa?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, desasignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    formDesasignar.submit(); // Enviar el formulario si se confirma
                }
            });
        });
    }
});