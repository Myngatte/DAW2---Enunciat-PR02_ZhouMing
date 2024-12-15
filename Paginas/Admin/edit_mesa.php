<?php
session_start();
require_once "../../Procesos/conection.php";

// Verificar si el usuario está logueado
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

// Obtener el ID de la mesa a editar
$id_mesa = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar formulario
    $n_asientos = $_POST['n_asientos'];
    $id_sala = $_POST['id_sala'];

    $sql = "UPDATE tbl_mesas SET n_asientos = :n_asientos, id_sala = :id_sala WHERE id_mesa = :id_mesa";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':n_asientos', $n_asientos, PDO::PARAM_INT);
    $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
    $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: administracion.php?vista=mesas&edit=success");
    exit();
}

// Obtener datos de la mesa
$sql = "SELECT * FROM tbl_mesas WHERE id_mesa = :id_mesa";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
$stmt->execute();
$mesa = $stmt->fetch();

// Obtener salas
$sql_salas = "SELECT * FROM tbl_salas";
$salas_stmt = $conn->query($sql_salas);
$salas = $salas_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mesa</title>
    <link rel="stylesheet" href="../../CSS/estilos-editar.css">
    <script src="../../JS/editar_mesa.js"></script>
</head>
<body>
    <a href='./administracion.php'><button class='back'>Volver atrás</button></a>
    <h2>Editar Mesa</h2>
    <form method="POST">
        <label for="n_asientos">Número de Asientos:</label>
        <input type="number" id="n_asientos" name="n_asientos" value="<?= htmlspecialchars($mesa['n_asientos']) ?>" onblur="validateAsientos()">
        <span id="errorAsientos" class="error"></span>

        <label for="id_sala">Sala:</label>
        <select id="id_sala" name="id_sala">
            <?php foreach ($salas as $sala): ?>
                <option value="<?= $sala['id_salas'] ?>" <?= $sala['id_salas'] == $mesa['id_sala'] ? 'selected' : '' ?> onblur="validateSala()">
                    <?= htmlspecialchars($sala['name_sala']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span id="errorSalaMesa" class="error"></span>

        <button type="submit">Guardar Cambios</button>
    </form>
</body>
</html>
