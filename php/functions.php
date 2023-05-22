<?php

include "conexion.php";


function l_variables(){
  unset($usuario);
  unset($contrasena);
}

function register(){
  include "conexion.php";
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    $c_contrasena = $_POST['r_contrasena'];
    $correo = $_POST['correo'];
    $correo = filter_var($correo, FILTER_SANITIZE_EMAIL);

    if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
      if($contrasena==$c_contrasena ){
        $p_encript = md5($contrasena);
        $sql = "INSERT INTO usuarios(usuario,contrasena,correo) VALUES ('$usuario', '$p_encript', '$correo')";
        mysqli_query($conexion, $sql);
        header('Location: login.php');
      } else {
        echo "<p>Les contrasenyes no coincideixen</p>";
      }
    } else {
      echo "<p>Correu invalid</p>";
    }
}

function login(){
  include "conexion.php";
    if (isset($_POST['login'])) {
        $usuario = $_POST['usuario'];
        $contrasena = $_POST['contrasena'];
        $p_encript = md5($contrasena);
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND contrasena = '$p_encript'";
        $resultado = mysqli_query($conexion, $sql);


        if ($resultado -> num_rows == 0) {
          echo '<p>Usuario o Contraseña incorrectos</p>';
        } else {
          while($row = $resultado->fetch_assoc()){
            $_SESSION['user'] = $row['usuarioId'];
          }
          header('Location: user.php');
        } 
      }
}


function crearActivitat(){
  include "conexion.php";
  $nom = $_POST['nom'];
  $localitzacio = $_POST['localitzacio'];
  $data = $_POST['data'];
  $lusuaris = $_POST['lusuaris'];
  $tags = $_POST['tags'];
  $imageData = $_FILES['imatge']['tmp_name'];
  $imageType = $_FILES['imatge']['type'];
  $usuarioId = $_SESSION["user"];

    // Si no es una imagen no la sube
    if($imageType == "image/png" || $imageType == "image/jpg" || $imageType == "image/jpeg"){
        $sql = "INSERT INTO actividades(titulo,localizacion,fecha,limite_usuarios,tags, fk_usuarioId, imageData, imageType) VALUES ('$nom', '$localitzacio', '$data', '$lusuaris', '$tags','$usuarioId','$imageData', '$imageType')";
        mysqli_query($conexion, $sql);
        header('Location: user.php');
    }

}

function activitatsMeves(){
  include "conexion.php";
  $user_id = $_SESSION['user'];
  $sql = "SELECT titulo,localizacion,fecha,limite_usuarios,tags,actividad_id,imageData FROM actividades WHERE fk_usuarioId = '$user_id'";
  $result = mysqli_query($conexion, $sql);
  
  if ($result->num_rows > 0) {
    foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
      echo "<div class='atarjetas'>".
      "<div class='ttarjeta'>".
      "<p class='apttarjetas'>".$row["titulo"]."</p>". 
      "<p class='apttarjetas'>".$row["localizacion"]."</p>".
      "<p class='apttarjetas'>".$row["fecha"]."</p>".
      "<p class='apttarjetas'>Maximo: ".$row["limite_usuarios"]." Persona/s</p>".
      "</div>".
      "<img class='itarjetas' src='data:image/png;base64,".base64_encode( $row["imageData"] )."'/>".
      "<form class='fdelete' method='POST'>
      <button type='submit' value=". $row["actividad_id"] . " name='eliminar' class='opbutton'>X</button><form>"."</div>"."<br>";
      if (isset($_POST["eliminar"])) {
        $actividad_id = $_POST["eliminar"];
        eliminarActivitat($actividad_id);
        header('Location: profile.php');
      }
    }
  } else {
    echo "<p class='apttarjetas'>No tens cap activitat creada</p>";
  }
}

function eliminarActivitat($actividad_id){
  include "conexion.php";
  $user_id = $_SESSION['user'];
  $sql = "DELETE FROM actividades WHERE fk_usuarioId = '$user_id' && actividad_id = '$actividad_id'";
  $result = mysqli_query($conexion, $sql);
}

function modificarConta(){
  include "conexion.php";
  $user_id = $_SESSION['user'];
  $nom = $_POST['nom'];
  $contrasena = $_POST['contrasena'];
  $c_contrasena = $_POST['r_contrasena'];

    if($contrasena==$c_contrasena ){
      $p_encript = md5($contrasena);
      $sql = "UPDATE usuarios SET usuario='$usuario',contrasena='$p_encript'";
      mysqli_query($conexion, $sql);
      header('Location: profile.php');
    } else {
      echo "<p>Les contrasenyes no coincideixen</p>";
    }
  }

  function mostrarActivitat(){
    include "conexion.php";
    
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rowsPerPage = 4;
    $offset = ($page - 1) * $rowsPerPage;
  
    $sql = "SELECT titulo,localizacion,fecha,limite_usuarios,tags, actividad_id FROM actividades LIMIT $offset, $rowsPerPage";
    $result = mysqli_query($conexion, $sql);
    
    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        $actividad_id = $row["actividad_id"];
        echo "<div class='tarjetas'>"."<div class='ttarjeta'>"."<p class='pttarjetas'>".$row["titulo"]."</p>". "<p class='ptarjetas'>".$row["localizacion"]."</p>". "<p class='ptarjetas'>".$row["fecha"]."</p>". "<p class='ptarjetas'>Maximo: ".$row["limite_usuarios"]." Persona/s</p>"."</div>"."<img class='itarjetas' src='../img/facebook.png'/>"."<form class='fdelete' method='POST'><button type='submit' value=". $actividad_id . "  name='apuntar' class='opbutton'>X</button><form>"."</div>"."<br>";
      }
      if (isset($_POST["apuntar"])) {
        $actividad_id = $_POST["apuntar"];
        apuntarActivitat($actividad_id);
        header('Location: user.php');
      }
    } else {
      echo "<p class='pttarjetas'>No hi han activitats per mostrar</p>";
    }
  
    $countSql = "SELECT COUNT(*) as count FROM actividades";
    $countResult = mysqli_query($conexion, $countSql);
    $countRow = mysqli_fetch_assoc($countResult);
    $totalRows = $countRow["count"];
  
    $totalPages = ceil($totalRows / $rowsPerPage);
  
    echo "<div class='t_margin'>";
    if ($page > 1) {
      echo "<a href='?page=".($page-1)."'>Anterior</a>";
    }
    for ($i=1; $i<=$totalPages; $i++) {
      if ($i == $page) {
        echo "<span class=''></span>";
      } else {
        echo "<a href='?page='></a>";
      }
    }
    if ($page < $totalPages) {
      echo "<a href='?page=".($page+1)."'>Seguent</a>";
    }
    echo "</div>";
  }

  function activitatsApuntades(){
    include "conexion.php";
    $user_id = $_SESSION['user'];
    $sql = "SELECT a.titulo, a.localizacion, a.fecha, a.limite_usuarios, a.tags, au.actividad_id
    FROM actividades AS a, actividades_usuarios AS au
    WHERE a.actividad_id = au.actividad_id AND au.usuarioId = '$user_id'";

    $result = mysqli_query($conexion, $sql);
    
    if ($result->num_rows > 0) {
      // output data of each row
      foreach ($result->fetch_all(MYSQLI_ASSOC) as $row) {
        echo "<div class='ltarjetas'>"."<div class='ttarjeta'>"."<p class='apttarjetas'>".$row["titulo"]."</p>". "<p class='apttarjetas'>".$row["localizacion"]."</p>". "<p class='apttarjetas'>".$row["fecha"]."</p>". "<p class='apttarjetas'>Maximo: ".$row["limite_usuarios"]." Persona/s</p>"."</div>"."<img class='itarjetas' src='../img/facebook.png'/>"."<form class='fdelete' method='POST'><button type='submit' value=". $row["actividad_id"] . " name='eliminar' class='opcbutton'>X</button><form>"."</div>"."<br>";
        if (isset($_POST["eliminar"])) {
          $actividad_id = $_POST["eliminar"];
          eliminarActivitatApuntada($actividad_id);
          echo $actividad_id;
        }
      }
    } else {
      echo "<p class='apttarjetas'>No tens cap activitat creada</p>";
    }
  }

  function apuntarActivitat($actividad_id){
    include "conexion.php";
    $user_id = $_SESSION['user'];
    $sql = "INSERT INTO actividades_usuarios(usuarioId, actividad_id) VALUES ('$user_id','$actividad_id')";
    $result = mysqli_query($conexion, $sql);
  }  

  function eliminarActivitatApuntada($actividad_id){
    include "conexion.php";
    $user_id = $_SESSION['user'];
    $sql = "DELETE FROM actividades_usuarios WHERE usuarioId = '$user_id' && actividad_id = '$actividad_id'";
    $result = mysqli_query($conexion, $sql);
    header('Location: user.php');
  }
?>