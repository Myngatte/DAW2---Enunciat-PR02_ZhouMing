<?php
session_start();
require_once "../../Procesos/conection.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

// Obtener el ID del usuario
$id_user = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $rol_user = $_POST['rol_user'];

    $sql = "UPDATE tbl_user SET name = :name, surname = :surname, username = :username, rol_user = :rol_user WHERE id_user = :id_user";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':surname', $surname, PDO::PARAM_STR);
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':rol_user', $rol_user, PDO::PARAM_INT);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: administracion.php?vista=usuarios&edit=success");
    exit();
}

// Obtener datos del usuario
$sql = "SELECT * FROM tbl_user WHERE id_user = :id_user";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch();

// Obtener roles 
$sql_roles = "SELECT * FROM roles";
$roles_stmt = $conn->query($sql_roles);
$roles = $roles_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="../../CSS/estilos-editar.css">
    <script src="../../JS/editar_user.js"></script>
</head>
<body>
    <a href='./administracion.php'><button class='back'>Volver atrás</button></a>
    <h2>Editar Usuario</h2>
    <form method="POST">
        <label for="name">Nombre:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" onblur="validateName()">
        <span id="errorName" class="error"></span>

        <label for="surname">Apellido:</label>
        <input type="text" id="surname" name="surname" value="<?= htmlspecialchars($user['surname']) ?>" onblur="validateSurname()">
        <span id="errorSurname" class="error"></span>

        <label for="username">Usuario:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" onblur="validateUsername()">
        <span id="errorUsername" class="error"></span>

        <label for="rol_user">Rol:</label>
        <select id="rol_user" name="rol_user">
            <?php foreach ($roles as $rol): ?>
                <option value="<?= $rol['id_rol'] ?>" <?= $rol['id_rol'] == $user['rol_user'] ? 'selected' : '' ?> onblur="validateRole()">
                    <?= htmlspecialchars($rol['nombre_rol']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span id="errorRol" class="error"></span>

        <button type="submit">Guardar Cambios</button>
    </form>

</body>
</html>
