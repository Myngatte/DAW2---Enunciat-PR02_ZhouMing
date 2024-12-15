<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["camareroID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
} else {
    $id_user = $_SESSION["camareroID"];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../CSS/estilos-salas.css">
    <title>TPV Salas</title>
</head>
<body>
    <a href="../../Procesos/destruir.php"><button type="button" class="logout">Cerrar Sesión</button></a>
    <a href="./historial"><button type="button" class="back">Historial</button></a>
    
    <form action="./mesas.php" method="POST" id="fomruarioDiv">
        <div class="container">
            <?php
                require_once "../../Procesos/conection.php";

                try {
                    // Consulta SQL para obtener las salas y contar las mesas libres por tipo de sala
                    $consulta = "
                        SELECT s.name_sala, 
                               COUNT(m.id_mesa) AS total_mesas, 
                               SUM(CASE WHEN h.fecha_C IS NULL THEN 1 ELSE 0 END) AS mesas_libres,
                               ts.nombre_tipo_sala
                        FROM tbl_salas s
                        LEFT JOIN tbl_mesas m ON s.id_salas = m.id_sala
                        LEFT JOIN ocupacion h ON m.id_mesa = h.id_mesa AND h.fecha_F IS NULL
                        LEFT JOIN tipo_salas ts ON s.tipo_sala = ts.id_tipo_sala
                        GROUP BY ts.id_tipo_sala, s.name_sala
                    ";
                    $stmt = $conn->prepare($consulta);
                    $stmt->execute();
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    $salasPorTipo = [];

                    // Agrupar las salas por tipo
                    foreach ($resultado as $fila) {
                        $salasPorTipo[$fila['nombre_tipo_sala']][] = [
                            'name_sala' => htmlspecialchars($fila['name_sala']),
                            'total_mesas' => $fila['total_mesas'],
                            'mesas_libres' => $fila['mesas_libres']
                        ];
                    }

                    // Generación de divs por tipo de sala
                    foreach ($salasPorTipo as $tipoSala => $salas) {
                        echo "<div class='sala-tipo' onclick='toggleSalas(\"$tipoSala\")'>$tipoSala</div>";
                        echo "<div class='salas-container' id='$tipoSala' style='display:none;'>"; // Inicialmente oculto
                        
                        foreach ($salas as $sala) {
                            $nombre_sala = $sala['name_sala'];
                            $total_mesas = $sala['total_mesas'];
                            $mesas_libres = $sala['mesas_libres'];
                            echo "<div class='sala'>
                                    <input type='submit' name='sala' value='$nombre_sala' class='input_sala'>
                                    <p>Mesas libres: $mesas_libres / $total_mesas</p>
                                  </div>";
                        }
                        
                        echo "</div>";
                    }
                } catch (PDOException $e) {
                    echo "<p>Error en la base de datos: " . $e->getMessage() . "</p>";
                }
            ?>
        </div>
    </form>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../JS/alertIndex.js"></script>

</body>
</html>
