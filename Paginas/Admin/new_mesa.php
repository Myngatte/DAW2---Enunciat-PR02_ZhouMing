<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
} else {
    $id_user = $_SESSION["adminID"];
}

// Incluir archivo de conexión
require_once "../../Procesos/conection.php";

// Obtener las salas disponibles para el selector
$sql = "SELECT id_salas, name_sala FROM tbl_salas";
$stmt = $conn->prepare($sql);
$stmt->execute();
$salas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Mesa</title>
    <link rel="stylesheet" href="../../CSS/estilos-nueva_mesa.css">
</head>
<body>
    <a href='./administracion.php'><button class='back'>Volver atrás</button></a>
    <h1>Agregar Nueva Mesa</h1>

    <form action="../../Procesos/crear_mesa.php" method="POST">
        <label for="sala">Sala a la que pertenece:</label>
        <select name="sala" id="sala" onblur="validateSala()">
            <option value="">Seleccione una sala</option>
            <?php
            // Crear opciones para cada sala
            foreach ($salas as $sala) {
                echo "<option value='{$sala['id_salas']}'>{$sala['name_sala']}</option>";
            }
            ?>
        </select>
        <span id="errorSala"></span><br><br>

        <label for="asientos">Número de Asientos:</label>
        <input type="text" id="asientos" name="asientos" onblur="validateAsientos()">
        <span id="errorAsientos"></span><br><br>
        

        <button type="submit">Crear Mesa</button>
    </form>

    <!-- js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../JS/validacion_nueva_mesa.js"></script>
</body>
</html>

<?php
$conn = null;
?>
