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
                header('Location: ../../index.php');
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
                $stmt = $conn->prepare("SELECT id_salas, foto_sala FROM tbl_salas WHERE name_sala = :nombre_sala");
                $stmt->bindParam(":nombre_sala", $nombre_sala, PDO::PARAM_STR);
                $stmt->execute();
                
                // Obtener el ID de la sala y la foto
                $id_sala = null;
                $foto_sala = null;
                if ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id_sala = $fila['id_salas'];
                    $foto_sala = $fila['foto_sala'];
                
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
                    echo '<h3 class="titulo-formulario">Subir una nueva foto para la sala</h3>';
                    echo '<form action="../../Procesos/upload_photo.php" method="POST" enctype="multipart/form-data" class="formulario-subir-imagen">';
                    echo '    <label for="sala_image" class="label-imagen">Seleccionar imagen para la sala:</label>';
                    echo '    <input type="file" name="sala_image" id="sala_image" accept="image/*" class="input-imagen">';
                    echo '    <input type="hidden" name="sala" value="' . $nombre_sala . '">';
                    echo '    <button type="submit" class="btn-subir-imagen">Subir Foto</button>';
                    echo '</form>';
                    // Mostrar la imagen de la sala
                    if ($foto_sala) {
                        echo "<div class='foto-sala'>";
                        echo "<img src='../../CSS/img/salas/$foto_sala' alt='Foto de la sala $nombre_sala' id='foto_sala'>";
                        echo "</div>";
                    } else {
                        // Imagen predeterminada si no se ha subido una
                        echo "<div class='foto-sala'>";
                        echo "<img src='../../CSS/img/salas/default.png' alt='Foto de la sala $nombre_sala' id='foto_sala'>";
                        echo "</div>";
                    }


                    // Mostrar las mesas
                    echo "<form action='./asignar_mesa.php' method='POST'>";
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
