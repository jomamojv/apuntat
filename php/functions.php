<?php

include "conexion.php";


function l_variables(){
  unset($usuario);
  unset($contrasena);
}

/**
 * Realiza el proceso de registro de un nuevo usuario.
 *
 * @function register
 * @memberof module:Auth
 * @api {post} /registro Registro de Usuario
 * @apiDescription Esta función registra un nuevo usuario en el sistema.
 *
 * @apiParam {String} usuario Nombre de usuario.
 * @apiParam {String} contrasena Contraseña del usuario.
 * @apiParam {String} r_contrasena Confirmación de la contraseña.
 * @apiParam {String} correo Correo electrónico del usuario.
 *
 * @apiSuccessExample {html} Registro Exitoso
 *     HTTP/1.1 302 Found
 *     Location: login.php
 *
 * @apiErrorExample {html} Contraseñas no coinciden
 *     HTTP/1.1 200 OK
 *     <p>Les contrasenyes no coincideixen</p>
 *
 * @apiErrorExample {html} Correo o nombre de usuario existentes
 *     HTTP/1.1 200 OK
 *     <p>Correu electronic invalid</p>
 */
function register(){
  include "conexion.php";

  $usuario = $_POST['usuario'];
  $contrasena = $_POST['contrasena'];
  $c_contrasena = $_POST['r_contrasena'];
  $correo = $_POST['correo'];
  $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);

  if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    if($contrasena == $c_contrasena ){
      $p_encript = md5($contrasena);
      $queryUsuaris = "SELECT COUNT(*) FROM usuarios WHERE usuario = '$usuario'";
      $totalUsuaris = mysqli_query($conexion, $queryUsuaris);
      $contaUusaris = mysqli_fetch_array($totalUsuaris)[0];

      $queryCorreu = "SELECT COUNT(*) FROM usuarios WHERE correo = '$correo'";
      $resultatCorreu = mysqli_query($conexion, $queryCorreu);
      $contaCorreu = mysqli_fetch_array($resultatCorreu)[0];

      if ($contaUusaris > 0 || $contaCorreu > 0) {
        echo "<p>El correu o el nom d'usuari esta en us</p>";
      } else {
        $sql = "INSERT INTO usuarios(usuario, contrasena, correo) VALUES ('$usuario', '$p_encript', '$correo')";
        mysqli_query($conexion, $sql);

        header('Location: login.php');
        exit();
      }
    } else {
      echo "<p>Les contrasenyes no coincideixen</p>";
    }
  } else {
    echo "<p>Correu electronic invalid</p>";
  }
}




/**
 * Realiza el proceso de inicio de sesión.
 *
 * @function login
 * @memberof module:Auth
 *
 * @example
 * // Ejemplo de uso:
 * login();
 */
function login(){
  include "conexion.php";

  /**
   * Comprueba si se ha enviado el formulario de inicio de sesión.
   *
   * @name login
   * @function
   * @inner
   * @memberof module:Auth
   * @inner
   * @param {string} $_POST['login'] - El valor del campo 'login' en el formulario.
   */
  if (isset($_POST['login'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $p_encript = md5($contrasena);

    /**
     * Consulta la base de datos para buscar el usuario y la contraseña proporcionados.
     *
     * @name sql
     * @type {string}
     * @inner
     * @memberof module:Auth.login
     */
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contrasena = '$p_encript'";
    $resultado = mysqli_query($conexion, $sql);

    /**
     * Comprueba si se encontraron resultados en la consulta.
     *
     * @name num_rows
     * @type {number}
     * @inner
     * @memberof module:Auth.login
     */
    if ($resultado -> num_rows == 0) {
      echo '<p>Usuari o contrasenya incorrectes</p>';
    } else {
      while($row = $resultado->fetch_assoc()){
        $_SESSION['user'] = $row['usuarioId'];

        /**
         * Comprueba el tipo de usuario y redirige a la página correspondiente.
         *
         * @name usuarioId
         * @type {number}
         * @inner
         * @memberof module:Auth.login
         */
        if ($row['usuarioId'] == 15) {
          header('Location: admin.php');
          exit();
        } else {
          header('Location: user.php');
          exit();
        }
      }
    } 
  }
}




/**
 * Crea una nueva actividad.
 *
 * @function crearActivitat
 * @memberof module:Activities
 *
 * @example
 * // Ejemplo de uso:
 * crearActivitat();
 */
function crearActivitat(){
  include "conexion.php";

  $nom = $_POST['nom'];
  $localitzacio = $_POST['localitzacio'];
  $data = $_POST['data'];
  $lusuaris = $_POST['lusuaris'];
  $tags = $_POST['tags'];
  $tmpName = $_FILES['imatge']['tmp_name'];
  $imageData=file_get_contents($tmpName);
  $imageData=addslashes($imageData);
  $imageType = $_FILES['imatge']['type'];
  $usuarioId = $_SESSION["user"];

  $fechaActual = date("Y-m-d");

  if ($data < $fechaActual) {
    echo "<p class='errmsg'>La data introduida es incorrecta</p>";
  }else if($imageType == "image/png" || $imageType == "image/jpg" || $imageType == "image/jpeg"){

    /**
     * Inserta una nueva actividad en la base de datos.
     *
     * @name sql
     * @type {string}
     * @inner
     * @memberof module:Activities.crearActivitat
     */
    $sql = "INSERT INTO actividades(titulo,localizacion,fecha,limite_usuarios,tags,fk_usuarioId,imageData,imageType) VALUES ('$nom', '$localitzacio', '$data', '$lusuaris', '$tags','$usuarioId','$imageData', '$imageType')";
    mysqli_query($conexion, $sql);

    /**
     * Redirige al usuario a la página de perfil después de crear la actividad.
     *
     * @name echo
     * @function
     * @inner
     * @memberof module:Activities.crearActivitat
     *
     * @param {string} "<script>window.location.replace('profile.php');</script>" - Script para redirigir al usuario a la página de perfil.
     */
    echo "<script>window.location.replace('profile.php');</script>";
    exit();
  }
}



/**
 * Muestra las actividades creadas por el usuario actual.
 *
 * @function activitatsMeves
 * @memberof module:Activities
 *
 * @example
 * // Ejemplo de uso:
 * activitatsMeves();
 */
function activitatsMeves(){
  
  include "conexion.php";
  $user_id = $_SESSION['user'];
  
  $limit = 3;
  $paginas = isset($_GET['paginas']) ? $_GET['paginas'] : 1;
  $offset = ($paginas - 1) * $limit;
  
  $sql = "SELECT COUNT(*) AS total FROM actividades WHERE fk_usuarioId = '$user_id'";
  $resultadoCount = mysqli_query($conexion, $sql);
  $totalActivities = mysqli_fetch_assoc($resultadoCount)['total'];
  
  $paginasTotales = ceil($totalActivities / $limit);
  
  $sql = "SELECT titulo, localizacion, fecha, limite_usuarios, tags, actividad_id, imageData
          FROM actividades
          WHERE fk_usuarioId = '$user_id'
          LIMIT $limit
          OFFSET $offset";
  $resultado = mysqli_query($conexion, $sql);
  
  if ($resultado->num_rows > 0) {
    foreach ($resultado->fetch_all(MYSQLI_ASSOC) as $row) {
      $imatge = $row["imageData"];
      echo "<div class='atarjetas'>".
      "<div class='ttarjeta'>".
      "<p class='pttarjetas'>".$row["titulo"]."</p>". 
      "<p class='apttarjetas'>".$row["localizacion"]."</p>".
      "<p class='apttarjetas'>".$row["fecha"]."</p>".
      "<p class='apttarjetas'>Maximo: ".$row["limite_usuarios"]." Persona/s</p>".
      "</div>".
      "<img class='itarjetas' alt='Imatge Activitat' src='data:image/png;base64,".base64_encode( $row["imageData"] )."'/>".
      "<form class='fdelete' method='POST'>
      <button type='submit' value=". $row["actividad_id"] . " name='eliminar' class='opbutton'></button><form>"."</div>"."<br>";
      if (isset($_POST["eliminar"])) {
        $actividad_id = $_POST["eliminar"];
        eliminarActivitat($actividad_id);
        echo "<script>window.location.replace('profile.php');</script>";
        exit();
      }
    }
    
    /**
     * Muestra la paginación para navegar entre las páginas de actividades.
     *
     * @name pagination
     * @function
     * @inner
     * @memberof module:Activities.activitatsMeves
     */
    echo "<div>";
    if ($paginas > 1) {
      echo "<a href='profile.php?paginas=".($paginas - 1)."'>Anterior</a>";
    }
    if ($paginas < $paginasTotales) {
      echo "<a href='profile.php?paginas=".($paginas + 1)."'>Seguent</a>";
    }
    echo "</div>";
  } else {
    echo "<p class='apttarjetas'>No has creat cap activitat</p>";
  }  
}

/**
 * Elimina una actividad específica para el usuario actual.
 *
 * @function eliminarActivitat
 * @memberof module:Activities
 *
 * @param {number} actividad_id - El ID de la actividad a eliminar.
 *
 * @example
 * // Ejemplo de uso:
 * eliminarActivitat($actividad_id);
 */
function eliminarActivitat($actividad_id){
  include "conexion.php";
  $user_id = $_SESSION['user'];
  $sql = "DELETE FROM actividades WHERE fk_usuarioId = '$user_id' && actividad_id = '$actividad_id'";
  $resultado = mysqli_query($conexion, $sql);
}

/**
 * Modifica la cuenta del usuario actual.
 *
 * @function modificarConta
 * @memberof module:Account
 *
 * @example
 * // Ejemplo de uso:
 * modificarConta();
 */
function modificarConta(){
  include "conexion.php";
  $user_id = $_SESSION['user'];
  $nom = $_POST['nom'];
  $contrasena = $_POST['contrasena'];
  $c_contrasena = $_POST['r_contrasena'];

  if($contrasena==$c_contrasena ){
    $p_encript = md5($contrasena);
    $sql = "UPDATE usuarios SET usuario='$nom',contrasena='$p_encript' WHERE usuarioId='$user_id '";
    mysqli_query($conexion, $sql);
    echo "<script>window.location.replace('profile.php');</script>";
    exit();
  } else {
    echo "<p>Les contrasenyes no coincideixen</p>";
  }
}

/**
 * Elimina la cuenta del usuario actual.
 *
 * @function eliminarConta
 * @memberof module:Account
 *
 * @example
 * // Ejemplo de uso:
 * eliminarConta();
 */
function eliminarConta(){
  include "conexion.php";
  $user_id = $_SESSION['user'];
  $nom = $_POST['nom'];
  $contrasena = $_POST['contrasena'];
  $c_contrasena = $_POST['r_contrasena'];

  if($contrasena == $c_contrasena){
    $p_encript = md5($contrasena);
    $sql = "DELETE FROM actividades_usuarios WHERE usuarioId='$user_id'";
    mysqli_query($conexion, $sql);
    $sql = "DELETE FROM actividades WHERE fk_usuarioId='$user_id'";
    mysqli_query($conexion, $sql);
    $sql = "DELETE FROM usuarios WHERE usuario='$nom' AND contrasena='$p_encript'";
    mysqli_query($conexion, $sql);
    echo "<script>window.location.replace('index.html');</script>";
    exit();
  } else {
    echo "<p>Les contrasenyes no coincideixen</p>";
  }
}


/**
 * Muestra las actividades en la página actual con paginación.
 *
 * @function mostrarActivitat
 * @memberof module:Actividades
 *
 * @example
 * // Ejemplo de uso:
 * mostrarActivitat();
 */
function mostrarActivitat(){
  include "conexion.php";
  
  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 4;
  $offset = ($paginas - 1) * $resultadosPagina;

  $fechaActual = date("Y-m-d");

  $sql = "SELECT titulo, localizacion, fecha, limite_usuarios, tags, actividad_id, imageData, usuarios_apuntados 
          FROM actividades 
          WHERE fecha >= '$fechaActual' 
          LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);
  
  if (isset($_POST["apuntar"])) {
    $actividad_id = $_POST["apuntar"];
    apuntarActivitat($actividad_id);
    echo "<script>window.location.replace('user.php');</script>";
    exit();
  }
  
  if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
      $actividad_id = $row["actividad_id"];
      echo "<div class='tarjetas'>" .
        "<div class='ttarjeta'>" .
        "<p class='pttarjetas'>" . $row["titulo"] . "</p>" .
        "<p class='ptarjetas'>" . $row["localizacion"] . "</p>" .
        "<p class='ptarjetas'>" . $row["fecha"] . "</p>" .
        "<p class='ptarjetas'>Maximo: " . $row["limite_usuarios"] . " Persona/s</p>" .
        "<p class='ptarjetas'>Usuarios Apuntados:" . $row["usuarios_apuntados"] . "</p>" .
        "</div>" .
        "<img class='itarjetas' alt='Imatge Activitat' src='data:image/png;base64," . base64_encode($row["imageData"]) . "'/>" .
        "<form class='fdelete' method='POST'><button type='submit' value=" . $actividad_id . "  name='apuntar' class='opbutton'></button></form>" .
        "</div>" .
        "<br>";
    }
  } else {
    echo "<p class='pttarjetas'>No hi han activitats per mostrar</p>";
  }

  $sql = "SELECT COUNT(*) as count FROM actividades WHERE fecha >= '$fechaActual'";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
    echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  for ($i=1; $i<=$paginasTotales; $i++) {
    if ($i == $paginas) {
      echo "<span class=''></span>";
    } else {
      echo "<a href='?paginas='></a>";
    }
  }
  if ($paginas < $paginasTotales) {
    echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}

/**
 * Muestra las actividades a las que el usuario se ha apuntado.
 *
 * @function activitatsApuntades
 * @memberof module:Actividades
 *
 * @example
 * // Ejemplo de uso:
 * activitatsApuntades();
 */
function activitatsApuntades(){
  include "conexion.php";
  $user_id = $_SESSION['user'];
   
  $paginas = isset($_GET['apuntades_paginas']) ? intval($_GET['apuntades_paginas']) : 1;
  $resultadosPagina = 4;
  $off = ($paginas - 1) * $resultadosPagina;
  $fechaActual = date("Y-m-d");
    
  $sql = "SELECT a.usuarios_apuntados, a.titulo, a.localizacion, a.fecha, a.limite_usuarios, a.tags, au.actividad_id, a.imageData
    FROM actividades AS a, actividades_usuarios AS au
    WHERE a.actividad_id = au.actividad_id AND au.usuarioId = '$user_id' AND a.fecha >= '$fechaActual'
    LIMIT $off, $resultadosPagina";
  
  $resultado = mysqli_query($conexion, $sql);
    
  if (isset($_POST["eliminar"])) {
    $actividad_id = $_POST["eliminar"];
    eliminarActivitatApuntada($actividad_id);
    echo "<script>window.location.replace('user.php?apuntades_paginas=$paginas');</script>";
    exit();
  }
  
  if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
      echo "<div class='ltarjetas'>" .
        "<div class='ttarjeta'>" .
        "<p class='apttarjetas'>" . $row["titulo"] . "</p>" .
        "<p class='apttarjetas'>" . $row["localizacion"] . "</p>" .
        "<p class='apttarjetas'>" . $row["fecha"] . "</p>" .
        "<p class='apttarjetas'>Maximo: " . $row["limite_usuarios"] . " Persona/s</p>" .
        "<p class='apttarjetas'>Usuarios Apuntados: " . $row["usuarios_apuntados"] . "</p>" .
        "</div>" .
        "<img class='itarjetas' alt='Imatge Activitat' src='data:image/png;base64," . base64_encode($row["imageData"]) . "'/>" .
        "<form class='fdelete' method='POST'><button type='submit' value=" . $row["actividad_id"] . " name='eliminar' class='opcbutton'></button><form>" .
        "</div>" .
        "<br>";
    }
  
    $sql = "SELECT COUNT(*) as count FROM actividades AS a, actividades_usuarios AS au WHERE a.actividad_id = au.actividad_id AND au.usuarioId = '$user_id' AND a.fecha >= '$fechaActual'";
    $resultadoCountado = mysqli_query($conexion, $sql);
    $countRow = mysqli_fetch_assoc($resultadoCountado);
    $resultadoTotal = $countRow["count"];
  
    $paginasTotales = ceil($resultadoTotal / $resultadosPagina);
  
    echo "<div>";
    if ($paginas > 1) {
      echo "<a href='?apuntades_paginas=" . ($paginas - 1) . "'>Anterior</a>";
    }
    if ($paginas < $paginasTotales) {
      echo "<a href='?apuntades_paginas=" . ($paginas + 1) . "'>Seguent</a>";
    }
    echo "</div>";
  } else {
    echo "<p class='apttarjetas'>No t'has apuntat a cap activitat</p>";
  }
}

  
  

/**
 * Realiza la acción de apuntarse a una actividad.
 *
 * @function apuntarActivitat
 * @memberof module:Actividades
 * @param {number} $actividad_id - El ID de la actividad a la que se desea apuntar.
 *
 * @example
 * // Ejemplo de uso:
 * $actividad_id = 1;
 * apuntarActivitat($actividad_id);
 */
function apuntarActivitat($actividad_id){
  include "conexion.php";
  $user_id = $_SESSION['user'];

  $sql = "SELECT limite_usuarios, usuarios_apuntados FROM actividades WHERE actividad_id = '$actividad_id'";
  $res_limite = mysqli_query($conexion, $sql);
  $row_limite = mysqli_fetch_assoc($res_limite);
  $limite_usuarios = $row_limite['limite_usuarios'];
  $usuarios_apuntados = $row_limite['usuarios_apuntados'];

  if ($usuarios_apuntados >= $limite_usuarios) {
    echo "<script>alert('S\'ha arribat al limit d\'usuaris')</script>";
    echo "<script>window.location.replace('user.php');</script>";
    exit();
  }

  $sql = "SELECT COUNT(*) AS total FROM actividades_usuarios WHERE usuarioId = '$user_id' AND actividad_id = '$actividad_id'";
  $resultado_check = mysqli_query($conexion, $sql);
  $row_check = mysqli_fetch_assoc($resultado_check);
  $apuntado = $row_check['total'];

  if ($apuntado > 0) {
    echo "<script>alert('Ja estas apuntat a aquesta activitat')</script>";
    echo "<script>window.location.replace('user.php');</script>";
    exit();
  }

  $sql = "INSERT INTO actividades_usuarios(usuarioId, actividad_id) VALUES ('$user_id','$actividad_id')";
  $resultado = mysqli_query($conexion, $sql);

  $sql_a = "UPDATE actividades SET usuarios_apuntados = usuarios_apuntados + 1 WHERE actividad_id = '$actividad_id'";
  $result_a = mysqli_query($conexion, $sql_a);
}



/**
 * Elimina la participación del usuario en una actividad.
 *
 * @function eliminarActivitatApuntada
 * @memberof module:Actividades
 * @param {number} $actividad_id - El ID de la actividad de la cual se desea eliminar la participación.
 *
 * @example
 * // Ejemplo de uso:
 * $actividad_id = 1;
 * eliminarActivitatApuntada($actividad_id);
 */
function eliminarActivitatApuntada($actividad_id){
  include "conexion.php";
  $user_id = $_SESSION['user'];
  $sql = "DELETE FROM actividades_usuarios WHERE usuarioId = '$user_id' && actividad_id = '$actividad_id'";
  $resultado = mysqli_query($conexion, $sql);

  $sql_a = "UPDATE actividades SET usuarios_apuntados = usuarios_apuntados - 1 WHERE actividad_id = '$actividad_id'";
  $result_a = mysqli_query($conexion, $sql_a);
}


/**
 * Muestra las actividades para el administrador.
 *
 * @function showActivitatsAdmin
 * @memberof module:Actividades
 *
 * @example
 * // Ejemplo de uso:
 * showActivitatsAdmin();
 */
function showActivitatsAdmin(){
  include "conexion.php";
  
  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 20;
  $offset = ($paginas - 1) * $resultadosPagina;

  $sql = "SELECT titulo, localizacion, fecha, limite_usuarios, tags, actividad_id, fk_usuarioId FROM actividades LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);

  if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
      $actividad_id = $row["actividad_id"];
      echo "<tr>".
        "<td>" . $row["titulo"] . "</td>" .
        "<td>" . $row["localizacion"] . "</td>" .
        "<td>" . $row["fecha"] . "</td>" .
        "<td>" . $row["limite_usuarios"] . "</td>" .
        "<td>" . $row["fk_usuarioId"] . "</td>" .
        "</tr>";
    }
  } else {
    echo "<p class='pttarjetas'>No hi han activitats per mostrar</p>";
  }

  $sql = "SELECT COUNT(*) as count FROM actividades";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
    echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  for ($i=1; $i<=$paginasTotales; $i++) {
    if ($i == $paginas) {
      echo "<span class=''></span>";
    } else {
      echo "<a href='?paginas='></a>";
    }
  }
  if ($paginas < $paginasTotales) {
    echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}


/**
 * Muestra las actividades para el administrador con opción de eliminación.
 *
 * @function activitatsAdmin
 * @memberof module:Actividades
 *
 * @example
 * // Ejemplo de uso:
 * activitatsAdmin();
 */
function activitatsAdmin(){
  include "conexion.php";
  
  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 20;
  $offset = ($paginas - 1) * $resultadosPagina;

  $sql = "SELECT titulo, localizacion, fecha, limite_usuarios, tags, actividad_id, fk_usuarioId FROM actividades LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);

  if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
      $actividad_id = $row["actividad_id"];
      echo "<tr>" .
        "<td>" . $row["titulo"] . "</td>" .
        "<td>" . $row["localizacion"] . "</td>" .
        "<td>" . $row["fecha"] . "</td>" .
        "<td>" . $row["limite_usuarios"] . "</td>" .
        "<td>" . $row["fk_usuarioId"] . "</td>" .
        "<td>" .
        "<form method='POST'>" .
        "<button type='submit' value=" . $actividad_id . "  name='eliminar'>Eliminar</button>" .
        "</form>" .
        "</td>" .
        "</tr>";
    }
    if (isset($_POST["eliminar"])) {
      $actividad_id = $_POST["eliminar"];
      eliminarActivitatAdmin($actividad_id);
      echo "<script>window.location.replace('ges_activitats.php');</script>";
      exit();
    }
  } else {
    echo "<p class='pttarjetas'>No hi han activitats per mostrar</p>";
  }

  $sql = "SELECT COUNT(*) as count FROM actividades";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
    echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  if ($paginas < $paginasTotales) {
    echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}


/**
 * Elimina una actividad y sus registros de la base de datos.
 *
 * @function eliminarActivitatAdmin
 * @memberof module:Actividades
 * @param {string} $actividad_id - El ID de la actividad a eliminar.
 *
 * @example
 * // Ejemplo de uso:
 * eliminarActivitatAdmin($actividad_id);
 */
function eliminarActivitatAdmin($actividad_id){
  include "conexion.php";
  $sql = "DELETE FROM actividades_usuarios WHERE actividad_id = '$actividad_id'";
  $resultado = mysqli_query($conexion, $sql);
  $sql = "DELETE FROM actividades WHERE actividad_id = '$actividad_id'";
  $resultado = mysqli_query($conexion, $sql);
}


/**
 * Muestra los usuarios registrados en la base de datos.
 *
 * @function showUsuarisAdmin
 * @memberof module:Usuarios
 *
 * @example
 * // Ejemplo de uso:
 * showUsuarisAdmin();
 */
function showUsuarisAdmin(){
  include "conexion.php";
  
  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 20;
  $offset = ($paginas - 1) * $resultadosPagina;

  $sql = "SELECT usuarioId, usuario, correo FROM usuarios LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);
  
  if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
      $usuarioId = $row["usuarioId"];
      echo "<tr>" .
        "<td>" . $row["usuarioId"] . "</td>" .
        "<td>" . $row["usuario"] . "</td>" .
        "<td>" . $row["correo"] . "</td>" .
        "<td>" .
        "<form method='POST'>" .
        "</td>" .
        "</tr>";
    }
  } else {
    echo "<p class='pttarjetas'>No hi han usuaris per mostrar</p>";
  }

  $sql = "SELECT COUNT(*) as count FROM usuarios";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
    echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  for ($i=1; $i<=$paginasTotales; $i++) {
    if ($i == $paginas) {
      echo "<span class=''></span>";
    } else {
      echo "<a href='?paginas='></a>";
    }
  }
  if ($paginas < $paginasTotales) {
    echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}


/**
 * Muestra los usuarios registrados en la base de datos con opción de eliminar.
 *
 * @function usuarisAdmin
 * @memberof module:Usuarios
 *
 * @example
 * // Ejemplo de uso:
 * usuarisAdmin();
 */
function usuarisAdmin(){
  include "conexion.php";
  
  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 20;
  $offset = ($paginas - 1) * $resultadosPagina;

  $sql = "SELECT usuarioId, usuario, correo FROM usuarios LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);
  
  if ($resultado->num_rows > 0) {
    while($row = $resultado->fetch_assoc()) {
      $usuarioId = $row["usuarioId"];
      echo "<tr>" .
        "<td>" . $row["usuarioId"] . "</td>" .
        "<td>" . $row["usuario"] . "</td>" .
        "<td>" . $row["correo"] . "</td>" .
        "<td>" .
        "<form method='POST'>" .
        "<button type='submit' value=". $usuarioId . "  name='eliminaru'>Eliminar</button>" .
        "</form>" .
        "</td>" .
        "</tr>";
    }
    if (isset($_POST["eliminaru"])) {
      $usuarioId = $_POST["eliminaru"];
      eliminarUsuario($usuarioId);
      echo "<script>window.location.replace('ges_usuaris.php');</script>";
      exit();
    }
  } else {
    echo "<p class='pttarjetas'>No hi han usuaris per mostrar</p>";
  }

  $sql = "SELECT COUNT(*) as count FROM usuarios";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
    echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  for ($i=1; $i<=$paginasTotales; $i++) {
    if ($i == $paginas) {
      echo "<span class=''></span>";
    } else {
      echo "<a href='?paginas='></a>";
    }
  }
  if ($paginas < $paginasTotales) {
    echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}


/**
 * Elimina un usuario de la base de datos, incluyendo sus actividades y registros asociados.
 *
 * @function eliminarUsuario
 * @memberof module:Usuarios
 *
 * @param {string} $usuarioId - ID del usuario a eliminar.
 *
 * @example
 * // Ejemplo de uso:
 * $usuarioId = "123"; // ID del usuario a eliminar
 * eliminarUsuario($usuarioId);
 */
function eliminarUsuario($usuarioId){
  include "conexion.php";
  $sql2 = "DELETE FROM actividades_usuarios WHERE usuarioId = '$usuarioId'";
  $result2 = mysqli_query($conexion, $sql2);
  $sql3 = "DELETE FROM actividades WHERE fk_usuarioId = '$usuarioId'";
  $result3 = mysqli_query($conexion, $sql3);
  $sql = "DELETE FROM usuarios WHERE usuarioId = '$usuarioId'";
  $resultado = mysqli_query($conexion, $sql);
}


/**
 * Realiza una búsqueda de actividades en función de un término especificado.
 *
 * @function buscarActivitat
 * @memberof module:Actividades
 *
 * @example
 * // Ejemplo de uso:
 * buscarActivitat();
 */
function buscarActivitat() {
  include "conexion.php";
  $busca = $_POST['busca'];
  $_SESSION['BuscadorS'] = $busca;
  echo $_SESSION['BuscadorS'];
  echo "<script>window.location.replace('user_search.php');</script>";
  exit();
}


/**
 * Muestra las actividades encontradas según el término de búsqueda.
 *
 * @function activitatsTrobades
 * @memberof module:Actividades
 *
 * @example
 * // Ejemplo de uso:
 * activitatsTrobades();
 */
function activitatsTrobades() {
  include "conexion.php";

  $buscaS = $_SESSION['BuscadorS'];

  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 4;
  $offset = ($paginas - 1) * $resultadosPagina;

  $sql = "SELECT titulo,localizacion,fecha,limite_usuarios,tags, actividad_id, imageData FROM actividades WHERE titulo LIKE '%$buscaS%' LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);

  if (isset($_POST["apuntar"])) {
      echo "<script>window.location.replace('user_search.php');</script>";
      exit();
      $actividad_id = $_POST["apuntar"];
      apuntarActivitat($actividad_id);
  }

  if ($resultado->num_rows > 0) {
      while ($row = $resultado->fetch_assoc()) {
          $actividad_id = $row["actividad_id"];
          echo "<div class='tarjetas'>" .
              "<div class='ttarjeta'>" .
              "<p class='pttarjetas'>" . $row["titulo"] . "</p>" .
              "<p class='ptarjetas'>" . $row["localizacion"] . "</p>" .
              "<p class='ptarjetas'>" . $row["fecha"] . "</p>" .
              "<p class='ptarjetas'>Maximo: " . $row["limite_usuarios"] . " Persona/s</p>" .
              "</div>" .
              "<img class='itarjetas' alt='Imatge Activitat' src='data:image/png;base64," . base64_encode($row["imageData"]) . "'/>" .
              "<form class='fdelete' method='POST'><button type='submit' value=" . $actividad_id . "  name='apuntar' class='opbutton'></button></form>" .
              "</div>" .
              "<br>";
      }
  } else {
      echo "<p class='pttarjetas'>No hi han activitats per mostrar</p>";
  }

  $sql = "SELECT COUNT(*) as count FROM actividades";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
      echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  for ($i=1; $i<=$paginasTotales; $i++) {
      if ($i == $paginas) {
          echo "<span class=''></span>";
      } else {
          echo "<a href='?paginas='></a>";
      }
  }
  if ($paginas < $paginasTotales) {
      echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}

/**
* Realiza una búsqueda de actividades en el panel de administrador.
*
* @function buscarAdmin
* @memberof module:Actividades
*
* @example
* // Ejemplo de uso:
* buscarAdmin();
*/
function buscarAdmin() {
  include "conexion.php";
  $busca = $_POST['busca'];
  $_SESSION['BuscadorA'] = $busca;
  echo $_SESSION['BuscadorA'];
  echo "<script>window.location.replace('admin_search.php');</script>";
  exit();
}

  
/**
 * Muestra las actividades encontradas en el panel de administrador según el término de búsqueda.
 *
 * @function activitatsTrobadesAdmin
 * @memberof module:Actividades
 *
 * @example
 * // Ejemplo de uso:
 * activitatsTrobadesAdmin();
 */
function activitatsTrobadesAdmin(){
  include "conexion.php";

  $buscaA = $_SESSION['BuscadorA'];

  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 4;
  $offset = ($paginas - 1) * $resultadosPagina;

  $sql = "SELECT titulo,localizacion,fecha,limite_usuarios,tags, actividad_id, fk_usuarioId  FROM actividades WHERE titulo LIKE '%$buscaA%' LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);

  if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
          $actividad_id = $row["actividad_id"];
          echo "".                           
          "<td>" . $row["titulo"] . "</td>"."<td>" . $row["localizacion"] . "</td>"."<td>" . $row["fecha"] . "</td>"."<td>" . $row["limite_usuarios"] . "</td>"."<td>" . $row["fk_usuarioId"] . "</td>"."<td>"."<form method='POST'>"."<button type='submit' value=". $actividad_id . "  name='eliminar'>Eliminar</button>"."</form>". "</td>"."</tr>";
      }
  } else {
      echo "<p class='pttarjetas'>No hi han activitats per mostrar</p>";
  }

  $sql = "SELECT COUNT(*) as count FROM actividades";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
      echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  for ($i=1; $i<=$paginasTotales; $i++) {
      if ($i == $paginas) {
          echo "<span class=''></span>";
      } else {
          echo "<a href='?paginas='></a>";
      }
  }
  if ($paginas < $paginasTotales) {
      echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}


/**
 * Realiza una búsqueda de usuarios en el panel de administrador.
 *
 * @function searchUsuarisAdmin
 * @memberof module:Usuarios
 *
 * @example
 * // Ejemplo de uso:
 * searchUsuarisAdmin();
 */
function searchUsuarisAdmin(){
  include "conexion.php";

  $paginas = isset($_GET['paginas']) ? intval($_GET['paginas']) : 1;
  $resultadosPagina = 20;
  $offset = ($paginas - 1) * $resultadosPagina;

  $sql = "SELECT usuarioId,usuario,correo FROM usuarios LIMIT $offset, $resultadosPagina";
  $resultado = mysqli_query($conexion, $sql);

  if ($resultado->num_rows > 0) {
      while($row = $resultado->fetch_assoc()) {
          $usuarioId = $row["usuarioId"];
          echo "".                           
          "<td>" . $row["usuarioId"] . "</td>"."<td>" . $row["usuario"] . "</td>"."<td>" . $row["correo"] . "</td>"."<td>"."<form method='POST'>"."</td>"."</tr>";
      }
  } else {
      echo "<p class='pttarjetas'>No hi han activitats per mostrar</p>";
  }
  if (isset($_POST["eliminar"])) {
      $actividad_id = $_POST["eliminar"];
      eliminarActivitatAdmin($actividad_id);
      echo "<script>window.location.replace('admin_search.php');</script>";
      exit();
  }
  $sql = "SELECT COUNT(*) as count FROM usuarios";
  $resultadoCount = mysqli_query($conexion, $sql);
  $countRow = mysqli_fetch_assoc($resultadoCount);
  $totalRows = $countRow["count"];

  $paginasTotales = ceil($totalRows / $resultadosPagina);

  echo "<div class='t_margin'>";
  if ($paginas > 1) {
      echo "<a href='?paginas=".($paginas-1)."'>Anterior</a>";
  }
  for ($i=1; $i<=$paginasTotales; $i++) {
      if ($i == $paginas) {
          echo "<span class=''></span>";
      } else {
          echo "<a href='?paginas='></a>";
      }
  }
  if ($paginas < $paginasTotales) {
      echo "<a href='?paginas=".($paginas+1)."'>Seguent</a>";
  }
  echo "</div>";
}
?>