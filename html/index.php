<?php
session_start();
session_destroy();
session_start();
include "../php/functions.php";
l_variables();
?>
<!DOCTYPE html>
<html lang="ca">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Apuntat</title>
        <link rel="stylesheet" type="text/css" href="../css/estilos_index.css">
        <link rel="stylesheet" type="text/css" href="../css/estilos_footer.css">
        <link rel="shortcut icon" type="text/css" href="../img/logo-no-background.svg">
    </head>
    <body>
        <div class="container-img">
            <nav>
                <a href="login.html"><button class="login_button">Iniciar Sessió</button></a>
            </nav>
            <div class="container">
                <img class="logo" alt="Logo Apuntat" src="../img/logo.png">
                <p>La plataforma per trobar i ensenyar activitats que t’agradin en companyia.</p>
                <p class="msg">Descobreix tot aixo en Apuntat</p>
                <a href="register.php"><button class="register_button">Registrat</button></a>
            </div>
        </div>
        <footer>
            <div class="footer_cover">
                <div class="contactus">
                    <h2>Contacta amb nosaltres</h2>
                    <p class="pcontact">93371823</p>
                    <p class="pcontact">contact@apuntat.com</p>
                    <p class="pcontact">C/Sant Oleguer 203, Sabadell</p>
                </div>
                <div class="socialmedia">
                    <h2>Xarxes Socials</h2>
                    <div class="imgs">
                        <img class="smlogo" alt="Logo Instagram" src="../img/instagram.png">
                        <img class="smlogo" alt="Logo Facebook" src="../img/facebook.png">
                        <img class="smlogo" alt="Logo LinkedIn" src="../img/linkedin.png">
                        <img class="smlogo" alt="Logo Twitter" src="../img/twitter.png">
                    </div>
                </div>
            </div>  
            <div class="copy">
                <p>Copyright <span>&copy</span> 2023 · Apuntat</p>
            </div> 
        </footer>
    </body>
</html>