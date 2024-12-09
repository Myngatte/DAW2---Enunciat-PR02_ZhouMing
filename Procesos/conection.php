<?php
    $server = "localhost";
    $user = "root";
    $pwd = "";
    $db = "db_restaurante";

    try {
        // Crear conexión con PDO
        $conn = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $user, $pwd);

        // Configurar el modo de error de PDO
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        // Manejo de errores
        die("Error: No se pudo conectar a la base de datos. " . $e->getMessage());
    }
?>
