<?php
session_start();
include "../php/functions.php";
if (isset($_SESSION['user']) && $_SESSION['user'] != 15) {
?>
    <!DOCTYPE html>
    <html lang="ca">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Apuntat</title>
        <link rel="stylesheet" type="text/css" href="../css/estilos_main.css">
        <link rel="stylesheet" type="text/css" href="../css/estilos_footer.css">
        <link rel="shortcut icon" type="text/css" href="../img/logo-no-background.svg">
        <script type="text/javascript" language="javascript" src="../js/index.js"></script>
    </head>

    <body>
        <nav class="menu">
            <div class="dlogo">
                <img class="logo" alt="Logo Apuntat" src="../img/icon.png">
            </div>
            <div class="dicons">
                <div class="dmicons">
                    <a href="profile.php"><img class="nicons" src="../img/user.png"></a>
                    <a href="index.html"><img class="nicons" src="../img/power.png"></a>
                </div>
            </div>
        </nav>
        <div class="buscador">
            <form action="user.php" method="POST">
                <input class="form-control me-2" type="text" placeholder="Buscador Activitats" name="busca" aria-label="Buscador Activitats">
                <input type="submit" name="buscar" id="submit" value="Buscar" />
                <?php
					if (!empty($_POST['buscar'])) {
                        buscarActivitat();
					}
					?>
            </form>
        </div>
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
                    <?php activitatsTrobades(); ?>
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
                        <img class="smlogo" alt="Logo Instagram" src="../img/instagram.png">
                        <img class="smlogo" alt="Logo Facebook" src="../img/facebook.png">
                        <img class="smlogo" alt="Logo LinkedIn" src="../img/linkedin.png">
                        <img class="smlogo" alt="Logo Twitter" src="../img/twitter.png">
                    </div>
                </div>
            </div>

        </footer>
    </body>

    </html>
<?php
} else {
    header("Location: login.php");
}
?>