<?php
session_start();

include_once("./conection.php");

if (!filter_has_var(INPUT_POST, 'enviar')) {
    header("Location: ../index.php?error=inicioMal");
    exit();
}

$usr = htmlspecialchars($_POST["username"]);
$pwd = htmlspecialchars(hash('sha256', $_POST["pwd"]));

try {
    // Preparar la consulta para verificar el usuario
    $sqlInicio = "SELECT id_user, user_pwd, rol_user FROM tbl_user WHERE username = :username";
    $stmt = $conn->prepare($sqlInicio);
    $stmt->bindParam(':username', $usr, PDO::PARAM_STR);
    $stmt->execute();

    // Obtener el resultado
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró el usuario
    if ($row) {
        // Comparar la contraseña ingresada con el hash almacenado
        if (!password_verify($pwd, $row["user_pwd"])) {
            header("Location: ../index.php?error=datosMal");
            exit();
        }

        // Redirigir según el rol del usuario
        if ($row["rol_user"] == 1) {
            // Almacenar el ID del admin en la sesión
            $_SESSION["adminID"] = $row["id_user"];
            header("Location: ../Paginas/Admin/administracion.php?login=success");
        } elseif ($row["rol_user"] == 2) {
            // Almacenar el ID del camarero en la sesión
            $_SESSION["camareroID"] = $row["id_user"];
            header("Location: ../Paginas/Camarero/salas.php?login=success");
        } else {
            header("Location: ../index.php?error=rol");
        }
        exit();
    } else {
        header("Location: ../index.php?error=datosMal");
        exit();
    }
} catch (PDOException $e) {
    // Mostrar un error en caso de fallo de conexión o consulta
    echo "Error al iniciar sesión: " . $e->getMessage();
    die();
}
?>
