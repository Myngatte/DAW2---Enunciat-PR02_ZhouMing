<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

require_once "./conection.php";

// Verificar si el ID de la sala es proporcionado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_sala = $_GET['id'];

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        //Actualizar mesas, quedan vagabundas
        $stmt = $conn->prepare("UPDATE tbl_mesas SET id_sala = NULL WHERE id_sala = :id_sala");
        $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $stmt->execute();

        // Eliminar la sala
        $stmt = $conn->prepare("DELETE FROM tbl_salas WHERE id_salas = :id_sala");
        $stmt->bindParam(':id_sala', $id_sala, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar transacción -> equivalente a autocommit false ya que este no existe en pdo
        $conn->commit();

        // Redirigir después de la eliminación
        header("Location: ../Paginas/Admin/administracion.php?vista=salas");
        exit();

    } catch (Exception $e) {
        // Si algo falla, rollback
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de sala no válido.";
}
?>
