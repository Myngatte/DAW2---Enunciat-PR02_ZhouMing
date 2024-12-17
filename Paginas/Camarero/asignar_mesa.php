<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mesas</title>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="../../CSS/estilos-asignar.css">
</head>
<body>
    <div class="container text-center">
        <?php
            require_once "../../Procesos/conection.php"; 
            session_start();

            // Comprobación de sesión activa
            if (!isset($_SESSION["camareroID"]) && !isset($_SESSION['sala'])) {
                header('Location: ../../index.php');
                exit();
            }

            $id_user = $_SESSION["camareroID"]; // ID del usuario actual

            if (isset($_POST['mesa'])) {
                $id_mesa = $_POST['mesa'];

                // Verificar si se ha solicitado desasignar la mesa
                if (isset($_POST['desasignar'])) {
                    $stmt_update = $conn->prepare("UPDATE ocupacion SET fecha_F = NOW() WHERE id_mesa = :id_mesa AND fecha_F IS NULL");
                    $stmt_update->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                    $stmt_update->execute();

                    if ($stmt_update->rowCount() > 0) {
                        echo "<p class='text-success'>Mesa $id_mesa desasignada exitosamente.</p>";
                    } else {
                        echo "<p class='text-danger'>Error al desasignar la mesa. Intenta de nuevo.</p>";
                    }
                }

                // Verificar la acción seleccionada
                if (isset($_POST['accion']) && $_POST['accion'] === 'asignar') {
                    // Validación y asignación directa
                    if (isset($_POST['assigned_to']) && preg_match("/^[\p{L} ]{3,}$/u", $_POST['assigned_to'])) {
                        $assigned_to = trim($_POST['assigned_to']);
                        $stmt_insert = $conn->prepare("
                            INSERT INTO ocupacion (fecha_C, assigned_by, assigned_to, es_reserva, id_mesa)
                            VALUES (NOW(), :assigned_by, :assigned_to, 0, :id_mesa)
                        ");
                        $stmt_insert->bindParam(':assigned_by', $id_user, PDO::PARAM_INT);
                        $stmt_insert->bindParam(':assigned_to', $assigned_to, PDO::PARAM_STR);
                        $stmt_insert->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                        $stmt_insert->execute();
                    
                        if ($stmt_insert->rowCount() > 0) {
                            echo "<p class='text-success'>Mesa $id_mesa asignada exitosamente a $assigned_to.</p>";
                        } else {
                            echo "<p class='text-danger'>Error al asignar la mesa. Intenta de nuevo.</p>";
                        }
                    } else {
                        echo "<p class='text-danger'>El nombre asignado no es válido o está vacío.</p>";
                    }
                    
                } elseif (isset($_POST['accion']) && $_POST['accion'] === 'reservar') {
                    // Validación y creación de reserva
                    if (isset($_POST['fecha_reserva']) && isset($_POST['duracion_reserva']) && isset($_POST['assigned_to2']) && preg_match("/^[\p{L} ]{3,}$/u", $_POST['assigned_to2'])) {
                        $fecha_reserva = DateTime::createFromFormat('Y-m-d\TH:i', $_POST['fecha_reserva']);
                        if ($fecha_reserva) {
                            $fecha_reserva = $fecha_reserva->format('Y-m-d H:i:s');
                            $duracion_reserva = intval($_POST['duracion_reserva']);
                            $assigned_to = trim($_POST['assigned_to2']);

                            // Calcular fecha final y guardar reserva
                            $stmt_insert = $conn->prepare("
                                INSERT INTO ocupacion (fecha_C, fecha_F, assigned_by, assigned_to, es_reserva, id_mesa)
                                VALUES (:fecha_C, DATE_ADD(:fecha_C, INTERVAL :duracion MINUTE), :assigned_by, :assigned_to, 1, :id_mesa)
                            ");
                            $stmt_insert->bindParam(':fecha_C', $fecha_reserva, PDO::PARAM_STR);
                            $stmt_insert->bindParam(':duracion', $duracion_reserva, PDO::PARAM_INT);
                            $stmt_insert->bindParam(':assigned_by', $id_user, PDO::PARAM_INT);
                            $stmt_insert->bindParam(':assigned_to', $assigned_to, PDO::PARAM_STR);
                            $stmt_insert->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                            $stmt_insert->execute();

                            if ($stmt_insert->rowCount() > 0) {
                                echo "<p class='text-success'>Reserva creada exitosamente para la mesa $id_mesa.</p>";
                            } else {
                                echo "<p class='text-danger'>Error al crear la reserva. Intenta de nuevo.</p>";
                            }
                        } else {
                            echo "<p class='text-danger'>El formato de fecha no es válido.</p>";
                        }
                    } else {
                        echo "<p class='text-danger'>Por favor, completa todos los campos de la reserva.</p>";
                    }
                }

                // Consulta para verificar si la mesa está asignada actualmente
                $stmt = $conn->prepare("
                    SELECT o.id_ocupacion, o.fecha_C, o.fecha_F, o.assigned_by, o.assigned_to, o.es_reserva, u.name AS name_user, u.surname AS surname_user
                    FROM ocupacion o
                    JOIN tbl_user u ON o.assigned_by = u.id_user
                    WHERE o.id_mesa = :id_mesa AND o.fecha_F IS NULL
                    ORDER BY o.fecha_C DESC
                    LIMIT 1
                ");
                $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt->execute();
                $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

                // Mostrar información de la asignación si existe
                if ($asignacion) {
                    echo "<a href='mesas.php'><button class='btn btn-secondary back'>Volver a mesas</button></a>";
                    echo "<h2>Detalles de Asignación de la Mesa $id_mesa</h2>";
                    echo "<p><strong>Fecha de Asignación:</strong> " . htmlspecialchars($asignacion['fecha_C']) . "</p>";
                    echo "<p><strong>Asignada por:</strong> " . htmlspecialchars($asignacion['name_user']) . " " . htmlspecialchars($asignacion['surname_user']) . "</p>";
                    echo "<p><strong>Asignada a:</strong> " . htmlspecialchars($asignacion['assigned_to']) . "</p>";

                    // Botón de desasignar
                    echo "<form method='POST' action='' id='form-desasignar'>";
                    echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                    echo "<input type='hidden' name='desasignar' value='true'>";
                    echo "<button type='submit' id='btn-desasignar' class='btn btn-rojo'>Desasignar</button>";
                    echo "</form>";
                } else {
                    echo "<a href='mesas.php'><button class='btn btn-secondary back'>Volver a mesas</button></a>";
                    echo "<p>Esta mesa no está asignada actualmente.</p>";

                    // Formulario
                    echo "<form method='POST' action='' id='form-asignar'>";
                    echo "<input type='hidden' name='mesa' value='$id_mesa'>";
                    echo "<label for='accion'>Acción: </label>";
                    echo "<select id='accion' name='accion' class='form-control mb-2' required>";
                    echo "<option value='' disabled selected>Selecciona una opción</option>";
                    echo "<option value='asignar'>Asignar Ahora</option>";
                    echo "<option value='reservar'>Reservar</option>";
                    echo "</select>";

                    // Campos adicionales
                    echo "<div id='campo-nombre' class='campo-accion' style='display:none;'>";
                    echo "<label for='assigned_to'>Asignar a: </label>";
                    echo "<input type='text' id='assigned_to' name='assigned_to' class='form-control mb-2'>";
                    echo "</div>";

                    echo "<div id='campo-reserva' class='campo-accion' style='display:none;'>";
                    echo "<label for='fecha_reserva'>Fecha de Reserva: </label>";
                    echo "<input type='datetime-local' id='fecha_reserva' name='fecha_reserva' class='form-control mb-2'>";
                    echo "<label for='duracion_reserva'>Duración: </label>";
                    echo "<select id='duracion_reserva' name='duracion_reserva' class='form-control mb-2' required>";
                    echo "<option value='' disabled selected>Selecciona la duración</option>";
                    echo "<option value='30'>30 minutos</option>";
                    echo "<option value='60'>1 hora</option>";
                    echo "<option value='90'>1 hora y 30 minutos</option>";
                    echo "<option value='120'>2 horas</option>";
                    echo "</select>";
                    echo "<label for='assigned_to2'>Reservar para: </label>";
                    echo "<input type='text' id='assigned_to2' name='assigned_to2' class='form-control mb-2'>";
                    echo "</div>";

                    // Botón enviar
                    echo "<button type='submit' class='btn-asignar'>Enviar</button>";
                    echo "</form>";
                }
            }
        ?>
    </div>
    <!-- Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- js -->
     <script src="../../JS/alert.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
