<?php

    // Iniciamos la sessión
    session_start();

    // Eliminamos todas las variables de sesión
    session_unset();

    // Destruimos la sessión
    session_destroy();

    // Redirigimos al inicio de sesión
    header("Location: ../index.php");