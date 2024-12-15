<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

require_once "../../Procesos/conection.php";

// Obtener los roles disponibles
$sql_roles = "SELECT id_rol, nombre_rol FROM roles";
$stmt = $conn->query($sql_roles);
$roles = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Nuevo Usuario</title>
    <link rel="stylesheet" href="../../CSS/estilos-nuevo_user.css">
</head>
<body>
    <a href='./administracion.php'><button class='back'>Volver atrás</button></a>
    <h1>Añadir Nuevo Usuario</h1>
    <form action="../../Procesos/crear_usuario.php" method="POST">
    <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" onblur="validateName()">
        <span id="errorNombre"></span>

        <label for="surname">Apellido:</label>
        <input type="text" id="surname" name="surname" onblur="validateSurname()">
        <span id="errorApellido"></span>

        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" onblur="validateUsername()">
        <span id="errorUsr"></span>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" onblur="validatePassword()">
        <span id="errorPassword"></span>

        <label for="passwordR">Repetir Contraseña:</label>
        <input type="password" id="passwordR" name="passwordR" onblur="validatePasswordRepeat()">
        <span id="errorPasswordR"></span>

        <label for="rol">Rol:</label>
        <select id="rol" name="rol" onblur="validateRole()">
            <option value="" disabled selected>Seleccione un rol</option>
            <?php foreach ($roles as $rol): ?>
                <option value="<?= $rol['id_rol'] ?>"><?= htmlspecialchars($rol['nombre_rol']) ?></option>
            <?php endforeach; ?>
        </select>
        <span id="errorRol"></span>

        <button type="submit" id="submitBtn" disabled>Añadir Usuario</button>
        <!-- js -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../../JS/validation_user.js"></script>
    </form>
</body>
</html>
