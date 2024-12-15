<?php
session_start();

// Verificar si la sesión está iniciada
if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

require_once "./conection.php";

// Verificar si el ID del usuario es proporcionado
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_user = $_GET['id'];

    try {
        // Iniciar transacción
        $conn->beginTransaction();

        // Actualizar reservas y ocupaciones
        $stmt = $conn->prepare("UPDATE ocupacion SET assigned_by = NULL WHERE assigned_by = :id_user");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        // Eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM tbl_user WHERE id_user = :id_user");
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar transacción -> equivalente a autocommit false ya que este no existe en pdo
        $conn->commit();

        // Redirigir después de la eliminación
        header("Location: ../Paginas/Admin/administracion.php?vista=usuarios");
        exit();

    } catch (Exception $e) {
        // Si algo falla, rollback
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "ID de usuario no válido.";
}
?>
