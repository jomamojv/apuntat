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
    <link rel="stylesheet" type="text/css" href="../css/estilos_profile.css">
    <link rel="stylesheet" type="text/css" href="../css/estilos_footer.css">
    <link rel="shortcut icon" type="text/css" href="../img/logo-no-background.svg">
    <script type="text/javascript" language="javascript" src="../js/index.js"></script>
</head>

<body>
    <nav class="menu">
        <div class="dlogo">
            <img class="logo" alt="Logo Apuntat" src="../img/logo.png">
        </div>
        <div class="dicons">
            <div class="dmicons">
                <a href="user.php"><img class="nicons" src="../img/home.png"></a>
                <a href="index.html"><img class="nicons" src="../img/power.png"></a>
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
                            <input type="datetime-local" placeholder="data" name="data" class="inputs" required>
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
            <div class="s_container2">
                <div class="d_modificar">
                    <div class="d_header">
                        <h3 class="t_header">Modificar Compte</h3>
                        <form method="POST">
                            <input type="text" placeholder="Usuari" name="nom" class="inputs">
                            <input type="password" placeholder="Contrasenya" name="contrasena" class="inputs">
                            <input type="password" placeholder="Repetir Contrasenya" name="r_contrasena" class="inputs">
                            <input type="submit" placeholder="Modificar" name="Modificar" value="Modificar" class="submit" />
                            <?php if (!empty($_POST['Modificar'])) {
                                modificarConta();
                            } ?>
                        </form>
                    </div>
                </div>
            </div>
            <div class="s_container2">
            <div class="d_modificar">
                    <div class="d_header">
                        <h3 class="t_header">Eliminar Compte</h3>
                        <form method="POST">
                            <input type="text" placeholder="Usuari" name="nom" class="inputs">
                            <input type="password" placeholder="Contrasenya" name="contrasena" class="inputs">
                            <input type="password" placeholder="Repetir Contrasenya" name="r_contrasena" class="inputs">
                            <input type="submit" placeholder="Eliminar" name="Eliminar" value="Eliminar" class="submit" />
                            <?php if (!empty($_POST['Eliminar'])) {
                                eliminarConta();
                            } ?>
                        </form>
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