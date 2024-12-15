<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

// Incluir archivo de conexión
require_once "./conection.php";

// Verificar si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name_sala = $_POST['name_sala'];
    $tipo_sala = $_POST['tipo_sala'];

    // Verificar si el nombre de la sala ya existe
    $sql_check_sala = "SELECT * FROM tbl_salas WHERE name_sala = :name_sala";
    $stmt_check = $conn->prepare($sql_check_sala);
    $stmt_check->bindParam(':name_sala', $name_sala, PDO::PARAM_STR);
    $stmt_check->execute();
    $sala_exists = $stmt_check->fetchColumn();

    if ($sala_exists > 0) {
        // Si el nombre de la sala ya existe redirijir
        header("Location: ../Paginas/Admin/new_sala.php?error=salaexist");
        exit();
    }

    $sql = "INSERT INTO tbl_salas (name_sala, tipo_sala) VALUES (:name_sala, :tipo_sala)";

    try {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':name_sala', $name_sala, PDO::PARAM_STR);
        $stmt->bindParam(':tipo_sala', $tipo_sala, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: ../Paginas/Admin/new_sala.php?new_sala=success");
        } else {
            header("Location: ../Paginas/Admin/new_sala.php?error=error_db");
        }
    } catch (PDOException $e) {
        echo "Error al insertar: " . $e->getMessage();
    }
}

// Cerrar la conexión
$conn = null;
?>
