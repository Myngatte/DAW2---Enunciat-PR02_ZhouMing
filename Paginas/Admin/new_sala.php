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

$sql = "SELECT id_tipo_sala, nombre_tipo_sala FROM tipo_salas";
$stmt = $conn->prepare($sql);
$stmt->execute();
$tipos_sala = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nueva Sala</title>
    <link rel="stylesheet" href="../../CSS/estilos-nueva_sala.css">
</head>
<body>
    <a href='./administracion.php'><button class='back'>Volver atrás</button></a>
    <h1>Crear Nueva Sala</h1>
    
    <form action="../../Procesos/crear_sala.php" method="POST">
        <label for="name_sala">Nombre de la Sala:</label>
        <input type="text" id="name_sala" name="name_sala" onblur="validateNameSala()">
        <span id="errorNSala"></span>
        <br><br>
        
        <label for="tipo_sala">Tipo de Sala:</label>
        <select name="tipo_sala" id="tipo_sala" onblur="validateTipoSala()">
            <option value="">Seleccione un tipo</option>
            <?php
            foreach ($tipos_sala as $tipo) {
                echo '<option value="' . $tipo['id_tipo_sala'] . '">' . $tipo['nombre_tipo_sala'] . '</option>';
            }
            ?>
        </select><br><br>
        <span id="errorTSala"></span><br><br>

        <button type="submit" disabled>Crear Sala</button>
    </form>

    <!-- js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../JS/validacion_nueva_sala.js"></script>
</body>
</html>


<?php
$conn = null;
?>
