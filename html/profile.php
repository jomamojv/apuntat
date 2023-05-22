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
    <link rel="stylesheet" type="text/css" href="../css/estilos_profile.css">
    <link rel="stylesheet" type="text/css" href="../css/estilos_footer.css">
    <link rel="shortcut icon" type="text/css" href="../img/icon.ico">
    <script type="text/javascript" language="javascript" src="../js/index.js"></script>
</head>

<body>
    <nav class="menu">
        <div class="dlogo">

        </div>
        <div class="dicons">
            <div class="dmicons">
                <a href="user.php"><img class="nicons" src="../img/home.png"></a>
                <a href="index.php"><img class="nicons" src="../img/power.png"></a>
            </div>
        </div>
    </nav>
    
    <h2>Perfil Personal</h2>
    <div class="container">
        <div class="c_divs">
            <div class="s_container">
                <div class="d_creacion">
                    <div class="d_header">
                        <h3 class="t_header">Crear Activitats</h3>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="text" placeholder="Nom de l'activitat" name="nom" class="inputs" required>
                            <input type="text" placeholder="Localitzacio" name="localitzacio" class="inputs" required>
                            <input type="date" placeholder="data" name="data" class="inputs" required>
                            <input type="number" placeholder="Cant. Persones" name="lusuaris" class="inputs" required>
                            <input type="text" placeholder="Tags" name="tags" class="inputs" required>
                            <input type="file" placeholder="imatge" name="imatge" class="inputs" required>
                            <input type="submit" placeholder="Crear" name="Crear" value="Crear" class="submit"  required/>
                            <?php if (!empty($_POST['Crear'])) {
                                crearActivitat();
                            } ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="sr_container">
                <div class="d_actividadesa">
                    <div class="d_header">
                        <h3 class="t_header">Les Meves Activitats</h3>
                    </div>
                    <?php activitatsMeves(); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="container_b">
        <div class="c_divs">
            <div class="s_container">
                <div class="d_modificar">
                    <div class="d_header">
                        <h3 class="t_header">Modificar Conta</h3>
                        <form method="POST">
                            <input type="text" placeholder="Usuari" name="nom" class="inputs">
                            <input type="text" placeholder="Contrasenya" name="contrasenya" class="inputs">
                            <input type="text" placeholder="Repetir Contrasenya" name="r_contrasenya" class="inputs">
                            <input type="submit" placeholder="Modificar" name="Modificar" value="Modificar" class="submit" />
                            <?php if (!empty($_POST['Modificar'])) {
                                crearActivitat();
                            } ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="sr_container">
                <div class="d_actividadesa">
                    <div class="d_header">
                        <h3 class="t_header">Eliminar Conta</h3>
                    </div>
                </div>
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