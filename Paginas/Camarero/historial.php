<?php
    require_once "../../Procesos/conection.php";
    session_start();

    // Verificar si el usuario está logueado
    if (!isset($_SESSION["camareroID"]) && !isset($_SESSION["adminID"])) {
        header('Location: ../index.php?error=nosesion');
        exit();
    }

    // Inicializar variables
    $SalaSeleccionada = isset($_POST['room']) ? $_POST['room'] : null;
    $selectedTable = isset($_POST['table']) ? $_POST['table'] : null;
    $filterDate = isset($_POST['filter_date']) ? $_POST['filter_date'] : null;
    $filterUser = isset($_POST['filter_user']) ? $_POST['filter_user'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Historial de Mesas</title>
    <link rel="stylesheet" href="../../CSS/estilos-historial.css">
</head>
<body>
    <a href="salas.php"><button class="back">Volver a salas</button></a>

    <h2>Selecciona una Sala y Mesa para ver el Historial</h2>

    <!-- Formulario de selección de sala -->
    <form method="post" action="historial.php">
        <label for="room">Seleccione una Sala:</label>
        <select name="room" id="room" onchange="this.form.submit()">
            <option value="">Todo</option>
            <?php
            // Consulta para obtener las salas
            $stmt_rooms = $conn->prepare("SELECT id_salas, name_sala FROM tbl_salas");
            $stmt_rooms->execute();
            $rooms = $stmt_rooms->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rooms as $row) {
                $selected = ($SalaSeleccionada == $row['id_salas']) ? 'selected' : '';
                echo "<option value='" . $row['id_salas'] . "' $selected>" . $row['name_sala'] . "</option>";
            }
            ?>
        </select>
    </form>

    <?php if ($SalaSeleccionada): ?>
        <!-- Formulario de selección de mesa (solo si se selecciona una sala) -->
        <form method="post" action="historial.php">
            <label for="table">Seleccione una Mesa:</label>
            <select name="table" id="table" onchange="this.form.submit()">
                <option value="">Todo</option>
                <?php
                // Consulta para obtener las mesas de la sala seleccionada
                $stmt_tables = $conn->prepare("
                    SELECT m.id_mesa, m.n_asientos,
                        CASE
                            WHEN o.fecha_F IS NULL AND o.id_mesa IS NOT NULL THEN 'Asignada'
                            ELSE 'No Asignada'
                        END AS estado_mesa
                    FROM tbl_mesas m
                    LEFT JOIN ocupacion o ON m.id_mesa = o.id_mesa AND o.fecha_F IS NULL
                    WHERE m.id_sala = ?
                ");
                $stmt_tables->execute([$SalaSeleccionada]);
                $tables = $stmt_tables->fetchAll(PDO::FETCH_ASSOC);

                foreach ($tables as $row) {
                    $selected = ($selectedTable == $row['id_mesa']) ? 'selected' : '';
                    echo "<option value='" . $row['id_mesa'] . "' $selected>Mesa " . $row['id_mesa'] . " (" . $row['n_asientos'] . " asientos, " . $row['estado_mesa'] . ")</option>";
                }
                ?>
            </select>
            <input type="hidden" name="room" value="<?php echo $SalaSeleccionada; ?>">
        </form>
    <?php endif; ?>

    <?php if ($selectedTable): ?>
        <!-- Filtros para el historial de la mesa -->
        <form method="post" action="historial.php">
            <input type="hidden" name="room" value="<?php echo $SalaSeleccionada; ?>">
            <input type="hidden" name="table" value="<?php echo $selectedTable; ?>">

            <label for="filter_date">Filtrar por Fecha:</label>
            <input type="date" name="filter_date" id="filter_date" value="<?php echo $filterDate; ?>" onchange="this.form.submit()">

            <label for="filter_user">Filtrar por Usuario:</label>
            <select name="filter_user" id="filter_user" onchange="this.form.submit()">
                <option value="">Todo</option>
                <?php
                // Consulta para obtener todos los usuarios (camareros y administradores)
                $stmt_users = $conn->prepare("SELECT id_user, name, surname FROM tbl_user WHERE rol_user IN (2, 5)");
                $stmt_users->execute();
                $users = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

                foreach ($users as $row) {
                    $selectedUser = ($filterUser == $row['id_user']) ? 'selected' : '';
                    echo "<option value='" . $row['id_user'] . "' $selectedUser>" . $row['name'] . " " . $row['surname'] . "</option>";
                }
                ?>
            </select>
        </form>
        <h3>Historial de Mesa <?php echo $selectedTable; ?></h3>
    <?php else: ?>
        <h3>Historial Completo de Mesas</h3>
    <?php endif; ?>

    <?php
        // Consulta SQL base para mostrar el historial completo si no se selecciona mesa ni sala
        $sql = "
            SELECT o.fecha_C, o.fecha_F, u.name AS name_user, u.surname AS surname_user, o.assigned_to, m.id_mesa, m.n_asientos, s.name_sala
            FROM ocupacion o
            INNER JOIN tbl_user u ON o.assigned_by = u.id_user
            INNER JOIN tbl_mesas m ON o.id_mesa = m.id_mesa
            INNER JOIN tbl_salas s ON m.id_sala = s.id_salas
        ";

        // Variables para los parámetros de la consulta
        $parametros = [];
        $tipos = "";

        // Si hay filtro de fecha
        if ($filterDate) {
            $sql .= " AND DATE(o.fecha_C) = ?";
            $parametros[] = $filterDate;
            $tipos .= "s";
        }

        // Si hay filtro de usuario
        if ($filterUser) {
            $sql .= " AND o.assigned_by = ?";
            $parametros[] = $filterUser;
            $tipos .= "i";
        }

        // Si se seleccionó una sala
        if ($SalaSeleccionada) {
            $sql .= " AND m.id_sala = ?";
            $parametros[] = $SalaSeleccionada;
            $tipos .= "i";
        }

        // Si se seleccionó una mesa
        if ($selectedTable) {
            $sql .= " AND o.id_mesa = ?";
            $parametros[] = $selectedTable;
            $tipos .= "i";
        }

        // Preparar la consulta
        $stmt_history = $conn->prepare($sql);

        // Ejecutar la consulta con los parámetros vinculados
        if (!empty($parametros)) {
            $stmt_history->execute($parametros);
        } else {
            $stmt_history->execute();
        }

        // Obtener los resultados
        $result_history = $stmt_history->fetchAll(PDO::FETCH_ASSOC);

        if (count($result_history) > 0) {
            echo "<div class='historial'>";
                echo "<table border='1'>
                        <tr class'attr_tr'>
                            <th>Sala</th>
                            <th>Mesa</th>
                            <th>Usuario</th>
                            <th>Fecha ocupación</th>
                            <th>Cliente asignado</th>
                            <th>Fecha desocupación</th>
                        </tr>";
                foreach ($result_history as $row) {
                    echo "<tr>
                            <td>" . $row['name_sala'] . "</td>
                            <td>Mesa " . $row['id_mesa'] . " (" . $row['n_asientos'] . " asientos)</td>
                            <td>" . $row['name_user'] . " " . $row['surname_user'] . "</td>
                            <td>" . $row['fecha_C'] . "</td>
                            <td>" . $row['assigned_to'] . "</td>
                            <td>" . ($row['fecha_F'] ? $row['fecha_F'] : "N/A") . "</td>
                        </tr>";
                }
                echo "</table>";
            echo "</div>";
        } else {
            echo "<p>No hay historial disponible.</p>";
        }

        $stmt_history = null;
    ?>
</body>
</html>

<?php
    $conn = null;
?>
