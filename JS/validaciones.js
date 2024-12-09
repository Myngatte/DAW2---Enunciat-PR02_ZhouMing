// Esperamos a que el DOM esté cargado
document.addEventListener("DOMContentLoaded", function () {
    
    // Llamamos al formulario mediante su ID
    const form = document.getElementById("login");

    // Agregamos un evento de escucha para que se ejecute cuando se envia el formulario
    form.addEventListener("submit", function (event) {
        
        let hasErrors = false;
        const username = document.getElementById("username").value.trim();
        const password = document.getElementById("pwd").value.trim();
        const errorUsername = document.getElementById("errorUsername");
        const errorContraseña = document.getElementById("errorContraseña");

        // Limpiamos errores previos
        errorUsername.textContent = "";
        errorContraseña.textContent = "";

        let errors = {
            username: [],
            password: []
        };

        // VALIDACIÓN USERNAME
        // Campo vacío
        if (username === "" || username === null) {
            errors.username.push("- El nombre de usuario no puede estar vacío.");
            hasErrors = true;
        }

        if (username.length < 3) {
            errors.username.push("- El nombre de usuario debe contener mínimo 3 caracteres.");
            hasErrors = true;
        }

        // // Sin números
        // const nums = /[0-9]/;
        // if (nums.test(username)) {
        //     errors.username.push("- El nombre de usuario no puede contener números.");
        //     hasErrors = true;
        // }

        // VALIDACIÓN CONTRASEÑA
        // Campo vacío
        if (password === "" || password === null) {
            errors.password.push("- La contraseña no puede estar vacía.");
            hasErrors = true;
        }

        // // Más de 8 caracteres
        // if (password.length < 8) {
        //     errors.password.push("- La contraseña debe tener más de 8 caracteres.");
        //     hasErrors = true;
        // }

        // // // Contener 1 numero
        // const num = /[0-9]/;
        // if (!num.test(password)) {
        //     errors.password.push("- La contraseña debe contener al menos un número.");
        //     hasErrors = true;
        // }

        // // // Contener 1 mayúscula
        // const mayus = /[A-Z]/;
        // if (!mayus.test(password)) {
        //     errors.password.push("- La contraseña debe contener al menos una letra mayúscula.");
        //     hasErrors = true;
        // }

        // // // Contener 1 minúscula
        // const minus = /[a-z]/;
        // if (!minus.test(password)) {
        //     errors.password.push("- La contraseña debe contener al menos una letra minúscula.");
        //     hasErrors = true;
        // }

        if (hasErrors) {
            // Mostramos todos los errores acumulados
            errorUsername.innerHTML = errors.username.join('<br>');
            errorContraseña.innerHTML  = errors.password.join('<br>');

            // Prevenimos el envío del formulario
            event.preventDefault();
        } 
    });
});