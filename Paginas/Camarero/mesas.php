<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mesas de la Sala</title>
    <link rel="stylesheet" href="../../CSS/estilos-mesas.css">
</head>
<body>

    <a href="salas.php"><button class="back">Volver a salas</button></a>
    <div class="contenedor">       
        <?php
            require_once "../../Procesos/conection.php";
            session_start();

            // Comprobación de sesión activa
            if (!isset($_SESSION["camareroID"])) {
                header('Location: ../index.php');
                exit();
            } else {
                $id_user = $_SESSION["camareroID"];
                // sesion de sala
                    if (isset($_POST['sala'])){
                    $_SESSION['sala'] = $_POST['sala'];
                    }
            }

            // Verificar si se ha enviado el nombre de la sala
            if (isset($_SESSION['sala'])) {
                $nombre_sala = $_SESSION['sala']; 
            
                // Sanitizar el nombre de la sala
                $nombre_sala = htmlspecialchars($nombre_sala);
            
                // Consultar ID de la sala basada en el nombre
                $stmt = $conn->prepare("SELECT id_salas FROM tbl_salas WHERE name_sala = :nombre_sala");
                $stmt->bindParam(":nombre_sala", $nombre_sala, PDO::PARAM_STR);
                $stmt->execute();
                
                // Obtener el ID de la sala
                $id_sala = null;
                if ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_sala = $fila['id_salas'];
                
                    // Consultar las mesas en esa sala
                    $stmt_mesas = $conn->prepare("
                    SELECT m.id_mesa, m.n_asientos, 
                    CASE
                        WHEN o.fecha_F IS NULL AND o.id_mesa IS NOT NULL THEN 'Asignada'
                        ELSE 'No Asignada'
                    END AS estado_mesa
                    FROM tbl_mesas m
                    LEFT JOIN ocupacion o ON m.id_mesa = o.id_mesa AND o.fecha_F IS NULL
                    WHERE m.id_sala = :id_sala
                    "); 
                    $stmt_mesas->bindParam(":id_sala", $id_sala, PDO::PARAM_INT);
                    $stmt_mesas->execute();
                
                    // Mostrar mesas como botones
                    echo "<h2>Mesas en: $nombre_sala</h2>";
                    echo "<form action='./asignar_mesa.php' method='POST'>";

                    // Mostrar imagen según el nombre de la sala
                    switch ($nombre_sala) {
                        case 'Terraza_1':
                            echo "<div class='terrazafoto'>";
                            echo '<img src="../CSS/img/salas + mesas/Terraza1.png" alt="" id="terrazafoto">';
                            echo "</div>";
                            break;
                        case 'Terraza_2':
                            echo "<div class='terrazafoto'>";
                            echo '<img src="../CSS/img/salas + mesas/Terraza2.png" alt="" id="terrazafoto">';
                            echo "</div>";
                            break;
                        case 'Jardin':
                            echo "<div class='jardinfoto'>";
                            echo '<img src="../CSS/img/salas + mesas/jardin.png" alt="" id="jardinfoto">';
                            echo "</div>";
                            break;
                        case 'Comedor_1':
                            echo "<div class='comedorfoto'>";
                            echo '<img src="../CSS/img/salas + mesas/comedor1.png" alt="" id="comedorfoto">';
                            echo "</div>";
                            break;
                        case 'Comedor_2':
                            echo "<div class='comedorfoto'>";
                            echo '<img src="../CSS/img/salas + mesas/comedor2.png" alt="" id="comedorfoto">';
                            echo "</div>";
                            break;
                        case 'Salon_VIP':
                            echo "<div class='reservaofoto'>";
                            echo '<img src="../CSS/img/salas + mesas/salon_vip.png" alt="" id="reservaofoto">';
                            echo "</div>";
                            break;
                        case 'Salon_VIP_2':
                            echo "<div class='reservaofoto'>";
                            echo '<img src="../CSS/img/salas + mesas/salon_vip_2.png" alt="" id="reservaofoto">';
                            echo "</div>";
                            break;
                        case 'Salon_romantico':
                            echo "<div class='reservaofoto'>";
                            echo '<img src="../CSS/img/salas + mesas/romantica.png" alt="" id="reservaofoto">';
                            echo "</div>";
                            break;
                        case 'Naturaleza':
                            echo "<div class='reservaofoto'>";
                            echo '<img src="../CSS/img/salas + mesas/naturaleza.png" alt="" id="reservaofoto">';
                            echo "</div>";
                            break;
                        default:
                            echo "<p>Sala no encontrada.</p>";
                            break;
                    }

                    // Mostrar las mesas
                    if ($stmt_mesas->rowCount() > 0) {
                        while ($mesa = $stmt_mesas->fetch(PDO::FETCH_ASSOC)) {
                            $id_mesa = htmlspecialchars($mesa['id_mesa']);
                            $n_asientos = htmlspecialchars($mesa['n_asientos']);
                            $estado_mesa = htmlspecialchars($mesa['estado_mesa']);

                            // Clase del botón según el estado de la mesa
                            $boton_clase = ($estado_mesa === 'Asignada') ? 'btn-rojo' : 'btn-verde';

                            // Botón para cada mesa
                            echo "<button type='submit' id='btn_$id_mesa' name='mesa' value='$id_mesa' class='$boton_clase'>Mesa $id_mesa (Asientos: $n_asientos)</button>";
                        }
                    } else {
                        echo "<p>No hay mesas disponibles en esta sala.</p>";
                    }

                    echo "</form>"; // Cerrar formulario
                
                    // Cerrar declaración de mesas
                    $stmt_mesas->closeCursor();
                } else {
                    echo "<p>No se encontró la sala especificada.</p>";
                }
            
                // Cerrar declaración de sala
                $stmt->closeCursor();
            } else {
                echo "<p>No se ha seleccionado ninguna sala.</p>";
            }

            // Cerrar conexión
            $conn = null;
        ?>
    </div>
</body>
</html>
