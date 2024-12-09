<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/estilos.css">
    <script src="./JS/validaciones.js"></script>
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="imgIndex">
            <img src="./CSS/img/logo/logo.png" alt="Imagen descriptiva">
        </div>

        <form id="login" class="login" method="POST" action="./Procesos/procesoLogin.php">
            <label>Nombre de usuario:</label>
            <input type="text" id="username" name="username" placeholder="Nombre de usuario" <?php if(isset($_GET["error"]) && $_GET["error"] === "datosMal"){echo "style='border-color: red;'";} ?>>
            <span class="error" id="errorUsername"></span>
            <br>
            <label>Contraseña:</label>
            <input type="password" id="pwd" name="pwd" placeholder="Contraseña" <?php if(isset($_GET["error"]) && $_GET["error"] === "datosMal"){echo "style='border-color: red;'";} ?>>
            <?php
                if(isset($_GET["error"]) && $_GET["error"] === "datosMal"){echo "<span style='color: red;'>Usuario o contraseña incorrectos</span>";}
            ?>
            <span class="error" id="errorContraseña"></span>
            <br>
            <input type="submit" value="Enviar" id="enviar" name="enviar">
        </form>
    </div>
</body>
</html>