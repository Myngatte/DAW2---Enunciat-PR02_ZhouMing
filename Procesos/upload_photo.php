<?php
require_once "./conection.php";
session_start();

// Comprobación de sesión activa
if (!isset($_SESSION["camareroID"])) {
    header('Location: ../index.php');
    exit();
}

if (isset($_POST['sala'])) {
    $nombre_sala = $_POST['sala']; // El nombre de la sala viene del formulario de selección
} else {
    echo "No se ha seleccionado ninguna sala.";
    exit();
}

// Verificar si se ha enviado un archivo
if (isset($_FILES['sala_image']) && $_FILES['sala_image']['error'] === UPLOAD_ERR_OK) {
    // Obtener detalles del archivo subido
    $file_name = $_FILES['sala_image']['name'];
    $file_tmp = $_FILES['sala_image']['tmp_name'];
    $file_size = $_FILES['sala_image']['size'];
    $file_error = $_FILES['sala_image']['error'];

    // Comprobar si el archivo tiene algún error
    if ($file_error === UPLOAD_ERR_OK) {
        // Validación del tipo de archivo (solo imágenes)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['sala_image']['type'], $allowed_types)) {
            echo "Error: Solo se permiten archivos JPG, PNG y GIF.";
            exit();
        }

        // Validar el tamaño del archivo (5MB recomendado)
        $max_size = 5 * 1024 * 1024;
        if ($file_size > $max_size) {
            echo "Error: El archivo es demasiado grande. El tamaño máximo permitido es 5MB.";
            exit();
        }

        // Renombrar el archivo para evitar conflictos
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = $nombre_sala . '.' . $file_ext;

        // Directorio donde se guardarán las fotos de las salas
        $upload_dir = '../CSS/img/salas/';

        // Mover el archivo a la carpeta de destino
        if (move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
            // Actualizar la base de datos con el nuevo nombre de la foto de la sala
            $stmt = $conn->prepare("UPDATE tbl_salas SET foto_sala = :foto_sala WHERE name_sala = :nombre_sala");
            $stmt->bindParam(':foto_sala', $new_file_name, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_sala', $nombre_sala, PDO::PARAM_STR);
            if ($stmt->execute()) {
                echo "Foto subida y asociada a la sala correctamente.";
                header('Location: ../Paginas/Camarero/Salas.php'); // Redirige de vuelta a la página de las mesas
            } else {
                echo "Error al actualizar la base de datos.";
            }
        } else {
            echo "Error al mover el archivo al directorio.";
        }
    } else {
        echo "Error: Hubo un problema al subir el archivo.";
    }
} else {
    echo "No se ha enviado ninguna imagen o ha ocurrido un error.";
}
?>
