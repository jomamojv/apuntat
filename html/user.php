<?php
session_start();
include "../php/functions.php";
if (!isset($_SESSION['user'])) {
    header('location: login.php');
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta lang="es">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apuntat</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos_main.css">
    <link rel="stylesheet" type="text/css" href="../css/estilos_footer.css">
    <link rel="shortcut icon" type="text/css" href="../img/icon.ico">
    <script type="text/javascript" language="javascript" src="../js/index.js"></script>
</head>

<body>
    <nav class="menu">
        <div class="dlogo">
            <img class="logo" src="../img/icon.png">
        </div>
        <div class="dicons">
            <div class="dmicons">
                <a href="profile.php"><img class="nicons" src="../img/user.png"></a>
                <a href="index.php"><img class="nicons" src="../img/power.png"></a>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="o_container">
            <div class="s_container">
                <div class="d_actividadesa">
                    <div class="d_header">
                        <h3 class="t_header">Activitats Aceptades</h3>
                        <?php activitatsApuntades(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="l_container">
            <div class="d_actividades">
                <div class="d_header">
                    <h3 class="t_header">Activitats</h3>
                </div>
                <?php mostrarActivitat(); ?>
            </div>
        </div>
    </div>
    <footer>
        <div class="footer_cover_2">
            <div class="contactus">
                <h2>Contacta amb nosaltres</h2>
                <p class="pcontact">93371823</p>
                <p class="pcontact">contact@apuntat.com</p>
                <p class="pcontact">C/Sant Oleguer 203, Sabadell</p>
            </div>
            <div class="socialmedia">
                <h2>Xarxes Socials</h2>
                <div class="imgs">
                    <img class="smlogo" src="../img/instagram.png">
                    <img class="smlogo" src="../img/facebook.png">
                    <img class="smlogo" src="../img/linkedin.png">
                    <img class="smlogo" src="../img/twitter.png">
                </div>
            </div>
        </div>

    </footer>
</body>

</html>