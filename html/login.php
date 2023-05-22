<?php
session_start();
session_destroy();
session_start();
include "../php/functions.php";
l_variables();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta lang="es">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Apuntat</title>
        <link rel="stylesheet" type="text/css" href="../css/estilos_sesion.css">
        <link rel="shortcut icon" type="text/css" href="../img/icon.ico">
    </head>
    <body>
        <div class="container">
            <div class="form_estilos_l">
                <form method="POST">
                    <h1>Inicia Sessio</h1>
                    <input type="text" class="itext" placeholder="Usuari" id="usuario" name="usuario">
                    <input type="password" class="itext" placeholder="Contrasenya" id="contrasena" name="contrasena">
                    <input type="submit" class="rbutton" name="login" id="login" placeholder="login"/>
                    <?php  if (!empty($_POST['login'])){login();} ?>
                    <p>No tens conta? Registrat <a href="register.php"><span>aqui</span></a></p>
                </form>
            </div>
        </div>
        <footer>
            <p>Copyright <span>&copy</span> 2023 · Apuntat</p>
        </footer>
    </body>
</html>