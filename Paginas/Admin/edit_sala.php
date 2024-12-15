<?php
session_start();
require_once "../../Procesos/conection.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

// Obtener el ID de la sala a editar
$id_sala = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar formulario
    $name_sala = $_POST['name_sala'];
    $tipo_sala = $_POST['tipo_sala'];

    $sql = "UPDATE tbl_salas SET name_sala = :name_sala, tipo_sala = :tipo_sala WHERE id_salas = :id_sala";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name_sala', $name_sala, PDO::PARAM_STR);
    $stmt->bindParam(':tipo_sala', $tipo_sala, PDO::PARAM_INT);
    $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: administracion.php?vista=salas&edit=success");
    exit();
}

// Obtener datos de la sala
$sql = "SELECT * FROM tbl_salas WHERE id_salas = :id_sala";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
$stmt->execute();
$sala = $stmt->fetch();

// Obtener tipos de sala
$sql_tipos = "SELECT * FROM tipo_salas";
$tipos_stmt = $conn->query($sql_tipos);
$tipos = $tipos_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sala</title>
    <link rel="stylesheet" href="../../CSS/estilos-editar.css">
    <script src="../../JS/editar_sala.js"></script>
</head>
<body>
    <a href='./administracion.php'><button class='back'>Volver atrás</button></a>
    <h2>Editar Sala</h2>
    <form method="POST">
        <label for="name_sala">Nombre de la Sala:</label>
        <input type="text" id="name_sala" name="name_sala" value="<?= htmlspecialchars($sala['name_sala']) ?>" onblur="validateNameSala()">
        <span id="errorSala" class="error"></span>

        <label for="tipo_sala">Tipo de Sala:</label>
        <select id="tipo_sala" name="tipo_sala">
            <?php foreach ($tipos as $tipo): ?>
                <option value="<?= $tipo['id_tipo_sala'] ?>" <?= $tipo['id_tipo_sala'] == $sala['tipo_sala'] ? 'selected' : '' ?> onblur="validateTipoSala()">
                    <?= htmlspecialchars($tipo['nombre_tipo_sala']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span id="errorTipoSala" class="error"></span>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
