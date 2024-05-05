<?php

$alert = ''; 
session_start();

if(!empty($_SESSION['active'])){
  if(isset($_SESSION['rol'])){
    switch($_SESSION['rol']){
      case 1:
        header('location:views/admin/principal/');        
      break;

      case 2:
        header('location:views/coor/');
      break;

      case 3:
        header('location:views/instr/principal/');
      break;

      default:
    }
  }
}else{
 
  if(!empty($_POST))
  {
    if(empty($_POST['usuario']) && empty($_POST['clave'])){

      $alert = 'Ingrese el usuario y la contraseña por favor';
              
    }   
     elseif(empty($_POST['usuario'])){

      $alert = 'Ingrese el usuario por favor';
              
    }  elseif (empty($_POST['clave'])){

      $alert = 'Ingrese la contraseña por favor';
              
    }          
   
    else{
      include_once "extra/conexion.php";

      $user = mysqli_real_escape_string($link, $_POST['usuario']);
      $pass = md5 (mysqli_real_escape_string($link, $_POST['clave']));

      $query = mysqli_query($link,"SELECT * FROM usuarios WHERE Usuario= '$user' ");     
      $result = mysqli_num_rows($query);        

      if($result > 0){
          $data = mysqli_fetch_array($query);
          $_SESSION['active'] = true;             
          $_SESSION['idUser'] = $data['IdPrincipal'];
          $_SESSION['user'] = $data['Usuario'];
          $_SESSION['nombre'] = $data['Nombre'];
          $_SESSION['email'] = $data['Correo'];
          $_SESSION['tel'] = $data['Telefono'];
          $_SESSION['rol'] = $data['Rol'];

      //header('location:views/views-admin/principal/');
      if(isset($_SESSION['rol'])){
        switch($_SESSION['rol']){
          case 1: 
            header('location:views/admin/principal/');
          break;
    
          case 2:  
            header('location:views/coor/');
          break;
    
          case 3:
            header('location:views/instr/principal/');
          break;
    
          default:
        }
      }
         
      }else{
        $alert = 'Usuario y/o contraseña incorrectas';
        session_destroy();
      }
    }
  }
}

?>

<!DOCTYPE html>
<html lang="es">

<?php
  include_once "header-login.php";
?>

<body>
  <div class="contenedor-menu">
    <div class=menu>
      <div class="derecha" style="float: left;">
        <li class="nav-item">
          <a class="nav-link" style="color:#737373;font-size:16px;padding:10px 10px;"
            href="http://disenometrologia.blogspot.com">
            <i class="fas fa-home"></i> Inicio
          </a>
        </li>
      </div>
      <div class="izquierda" style="float:right;">
        <li class="nav-item">
          <a class="nav-link" style="color:#737373;font-size:16px;padding:10px 10px;"
            href="http://oferta.senasofiaplus.edu.co/sofia-oferta">
            <i class="fas fa-chalkboard-teacher"></i> Sofia Plus
          </a>
        </li>


        <li class="nav-item">
          <a class="nav-link" style="color:#737373;font-size:16px;padding:10px 10px;" target="_blank"
            href="https://accounts.google.com/signin/v2/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2F&service=mail&hd=misena.edu.co&sacu=1&flowName=GlifWebSignIn&flowEntry=AddSession#identifier">
            <i class="fas fa-envelope-square"></i> Correo Misena
          </a>
        </li>

      </div>

    </div>
  </div>

  <div class="form-content">
    <div class="form-right">
      <div class="overlay">

        <div class="grid-info-form">
        </div>

      </div>
    </div>

    <div class="form-left">

      <form class="login-box" style="margin-top: 4rem;" action="" method="post">
        <h1>Login</h1>

        <div class="textbox">
          <i class="fas fa-user"></i>
          <input type="text" name="usuario" placeholder="Documento de identidad" autocomplete="off" >
        </div>

        <div class="textbox" style="margin-bottom:25px;">
          <i class="fas fa-lock"></i>
          <input type="password" name="clave" placeholder="Contraseña" autocomplete="off" >
        </div> 

        <div class="alert" style="position:inherit !important;color: #000;padding: .25em .4em;font-size: 75%;font-weight: 700;line-height: 1;text-align: center;border-radius: .25rem;transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
          <?php echo isset($alert) ? $alert : ''; ?>         
        </div>

        <a class="OlvidoClave" style="color:#737373; cursor: pointer;"  href="restablecer"> Olvidé mi contraseña</a>

        <input type="submit" class="btnr" value="Iniciar sesion">
      </form>

      <div class="botones">
        <a class="btnr2" style="color:#737373;text-decoration:none;" href="ficha"> <i
            style="padding-right:6px;" class="fas fa-user-graduate"></i> Iniciar sesion como ficha</a>
        <br><br><a>© 2020 Todos los derechos reservados | Design by SENA Centro de Diseño y Metrología</a>
      </div>
    </div>
  </div>

</body>

</html>