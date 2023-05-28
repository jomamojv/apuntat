<?php
    include '../php/functions.php';
?>
<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Apuntat</title>
        <link rel="stylesheet" type="text/css" href="../css/estilos_sesion.css">
        <link rel="shortcut icon" type="text/css" href="../img/logo-no-background.svg">
    </head>
    <body>
        <nav>
            <a href="index.html"><button class="login_button">Inici</button></a>
        </nav>
        <div class="container">
            <div class="form_estilos">
                <form method="POST">
                    <h1>Registre</h1>
                    <input type="text" class="itext" placeholder="Usuari" id="usuario" name="usuario">
                    <input type="password" class="itext" placeholder="Contrasenya" id="contrasena" name="contrasena">
                    <input type="password" class="itext" placeholder="Confirmar Contrasenya" id="r_contrasena" name="r_contrasena">
                    <input type="text" class="itext" placeholder="Correu" id="correo" name="correo">
                    <input type="submit" class="rbutton" name="registro" id="registro" value="Registrat" placeholder="Registro" />
                    <?php  if (!empty($_POST['registro'])){register();} ?>
                    <p>Ja tens compte? Inicia sessió <a href="login.php"><span>aqui</span></a></p>
                </form>
            </div>
        </div>
        <footer>
            <p>Copyright <span>&copy</span> 2023 · Apuntat</p>
        </footer>
    </body>
</html>