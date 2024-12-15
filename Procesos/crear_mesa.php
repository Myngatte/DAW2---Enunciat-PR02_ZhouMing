<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

require_once "./conection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $sala = $_POST['sala'];
    $asientos = $_POST['asientos'];

    $sql = "INSERT INTO tbl_mesas (n_asientos, id_sala) VALUES (:asientos, :sala)";
    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':asientos', $asientos);
    $stmt->bindParam(':sala', $sala);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        header("Location: ../Paginas/Admin/new_mesa.php?new_mesa=success");
    } else {
        header("Location: ../Paginas/Admin/new_mesa.php?error=error_db");
    }
}

$conn = null;
?>
