<?php
session_start();

if (!isset($_SESSION["adminID"])) {
    header('Location: ../../index.php?error=nosesion');
    exit();
}

require_once "./conection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];

    // Verificar si existe
    $sql_check_username = "SELECT * FROM tbl_user WHERE username = :username";
    $stmt_check = $conn->prepare($sql_check_username);
    $stmt_check->bindParam(':username', $username);
    $stmt_check->execute();
    $username_exists = $stmt_check->fetchColumn();

    if ($username_exists > 0) {
        // Si el usuario ya existe
        header("Location: ../Paginas/Admin/new_user.php?error=usuarioexistente");
        exit();
    }

    // Insertar nuevo usuario 
    $sql = "INSERT INTO tbl_user (name, surname, username, user_pwd, rol_user) 
            VALUES (:name, :surname, :username, SHA2(:user_pwd, 256), :rol_user)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':surname', $surname);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':user_pwd', $password);
    $stmt->bindParam(':rol_user', $rol);

    if ($stmt->execute()) {
        header("Location: ../Paginas/Admin/new_user.php?new_user=success");
    } else {
        header("Location: ../Paginas/Admin/new_user.php?error=error_db");
    }
}
?>
