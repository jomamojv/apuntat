<?php
session_start();
include "../php/functions.php";
if (isset($_SESSION['user']) && $_SESSION['user'] == 15) {
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta lang="es">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apuntat</title>
    <link rel="stylesheet" type="text/css" href="../css/estilos_admin.css">
    <link rel="stylesheet" type="text/css" href="../css/estilos_footer.css">
    <link rel="shortcut icon" type="text/css" href="../img/logo-no-background.svg">
    <script type="text/javascript" language="javascript" src="../js/index.js"></script>
</head>
<body>
    <nav class="menu">
        <div class="dicons">
            <div class="dmicons">
                <a href="index.html"><img class="nicons" src="../img/power.png"></a>
            </div>
        </div>
        <div class="d_column">
            <a href="admin.php">Inici</a>
            <a href="ges_usuaris.php">Gestionar Usuaris</a>
            <a href="ges_activitats.php">Gestionar Activitats</a>
        </div>
    </nav>
    <div>
        <div>
            <div>
                <div>
                    <div class="d_tablas">
                        <h3>Gestionar Activitats</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Titulo</th>
                                    <th>Localizacion</th>
                                    <th>Fecha</th>
                                    <th>Cantidad Usuarios</th>
                                    <th>Usuario ID</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <?php showActivitatsAdmin(); ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <div>
                <div class="d_tablas">
                    <h3>Gestionar Usuaris</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <?php showUsuarisAdmin(); ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
</html>
<?php
} else {
	header("Location: login.php");
}
?>