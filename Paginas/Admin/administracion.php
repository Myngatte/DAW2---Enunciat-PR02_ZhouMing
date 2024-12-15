<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
} else {
    $id_user = $_SESSION["adminID"];
}

require_once "../../Procesos/conection.php";

// Cambiar entre usuarios y recursos (salas, mesas)
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'usuarios';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="../../CSS/estilos-crud.css">
</head>
<body>
    <a href='../../Procesos/destruir.php'><button class='logout'>Cerrar Sesión</button></a>
    <a href="../Camarero/historial.php"><button type="button" class="back">Historial</button></a>
    <div class="botones">
        <a href="?vista=usuarios">Usuarios</a>
        <a href="?vista=salas">Salas</a>
        <a href="?vista=mesas">Mesas</a>
    </div>

    <?php if ($vista === 'usuarios'): ?>
        <h2>Gestión de Usuarios</h2>
        <a href='./new_user.php'><button class='new'>Nuevo Usuario</button></a>
        <table>
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Username</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT tbl_user.id_user, tbl_user.name, tbl_user.surname, tbl_user.username, roles.nombre_rol 
                    FROM tbl_user 
                    INNER JOIN roles ON tbl_user.rol_user = roles.id_rol
                    WHERE tbl_user.id_user != :current_user -- Excluir al user actual
                    ORDER BY tbl_user.id_user;";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':current_user', $id_user, PDO::PARAM_INT);
            $stmt->execute();
            $usuarios = $stmt->fetchAll();

            if (count($usuarios) > 0) {
                foreach ($usuarios as $row) {
                    echo "<tr>";
                    echo "<td>{$row['id_user']}</td>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['surname']}</td>";
                    echo "<td>{$row['username']}</td>";
                    echo "<td>{$row['nombre_rol']}</td>";
                    echo "<td>
                            <a href='./edit_user.php?id={$row['id_user']}'><button class='btn'>Editar</button></a>
                            <a href='../../Procesos/delete_user.php?id={$row['id_user']}'><button class='btn-delete'>Eliminar</button></a>
                        </td>"; 
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay usuarios registrados.</td></tr>"; 
            }
            ?>
            </tbody>
        </table>
    <?php elseif ($vista === 'salas'): ?>
        <h2>Gestión de Salas</h2>
        <a href='./new_sala.php'><button class='new'>Nueva Sala</button></a>
        <table>
            <thead>
                <tr>
                    <th>ID Sala</th>
                    <th>Nombre Sala</th>
                    <th>Tipo Sala</th>
                    <th>Número de Mesas</th>
                    <th>Número de Sillas</th>
                    <th>Acciones</th> 
                </tr>
            </thead>
            <tbody>
            <?php
            $sql_salas = "SELECT tbl_salas.id_salas, tbl_salas.name_sala, tipo_salas.nombre_tipo_sala, 
            COUNT(tbl_mesas.id_mesa) AS mesas, SUM(tbl_mesas.n_asientos) AS sillas
            FROM tbl_salas
            LEFT JOIN tbl_mesas ON tbl_salas.id_salas = tbl_mesas.id_sala
            INNER JOIN tipo_salas ON tbl_salas.tipo_sala = tipo_salas.id_tipo_sala
            GROUP BY tbl_salas.id_salas, tbl_salas.name_sala, tipo_salas.nombre_tipo_sala
            ORDER BY tbl_salas.id_salas";
            
            $stmt = $conn->query($sql_salas);
            $salas = $stmt->fetchAll();

            if (count($salas) > 0) {
                foreach ($salas as $row) {
                    echo "<tr>";
                    echo "<td>{$row['id_salas']}</td>";
                    echo "<td>{$row['name_sala']}</td>";
                    echo "<td>{$row['nombre_tipo_sala']}</td>";
                    echo "<td>{$row['mesas']}</td>"; 
                    echo "<td>{$row['sillas']}</td>";
                    echo "<td>
                            <a href='./edit_sala.php?id={$row['id_salas']}'><button class='btn'>Editar</button></a>
                            <a href='../../Procesos/delete_sala.php?id={$row['id_salas']}'><button class='btn-delete'>Eliminar</button></a>
                        </td>"; 
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6'>No hay salas registradas.</td></tr>"; 
            }
            ?>
            </tbody>
        </table>
        <?php elseif ($vista === 'mesas'): ?>
        <h2>Gestión de Mesas</h2>
        <a href='./new_mesa.php'><button class='new'>Nueva Mesa</button></a>
        <table>
            <thead>
                <tr>
                    <th>ID Mesa</th>
                    <th>Número de Asientos</th>
                    <th>Sala a la que pertenece</th>
                    <th>Acciones</th> 
                </tr>
            </thead>
            <tbody>
            <?php
            $sql = "SELECT tbl_mesas.id_mesa, tbl_mesas.n_asientos, tbl_salas.name_sala 
                    FROM tbl_mesas
                    LEFT JOIN tbl_salas ON tbl_mesas.id_sala = tbl_salas.id_salas
                    ORDER BY id_mesa";
            $stmt = $conn->query($sql);
            $mesas = $stmt->fetchAll();

            if (count($mesas) > 0) {
                foreach ($mesas as $row) {
                    // Comprobar si la sala es NULL para resaltarlo
                    $rowStyle = is_null($row['name_sala']) ? "style='background-color: #FF0000;'" : "";

                    echo "<tr $rowStyle>";
                    echo "<td>{$row['id_mesa']}</td>";
                    echo "<td>{$row['n_asientos']}</td>";
                    echo "<td>" . ($row['name_sala'] ? $row['name_sala'] : "No asignada") . "</td>";
                    echo "<td>
                            <a href='./edit_mesa.php?id={$row['id_mesa']}'><button class='btn'>Editar</button></a>
                            <a href='../../Procesos/delete_mesa.php?id={$row['id_mesa']}'><button class='btn-delete'>Eliminar</button></a>
                        </td>"; 
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay mesas registradas.</td></tr>"; 
            }
            ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Archivo de JavaScript con las alertas -->
    <script src="../../JS/crud.js"></script>
</body>
</html>
