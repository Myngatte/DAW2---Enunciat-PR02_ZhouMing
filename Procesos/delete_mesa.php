<?php
session_start();

// Verificar la sesión 
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

require_once "./conection.php";

// Verificar 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_mesa = $_GET['id'];

    // Eliminar la mesa
    $stmt = $conn->prepare("DELETE FROM tbl_mesas WHERE id_mesa = :id_mesa");
    $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: ../Paginas/Admin/administracion.php?vista=mesas");
    exit();

} else {
    echo "ID de mesa no válido.";
}
?>
