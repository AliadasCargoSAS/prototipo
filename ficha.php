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
            href="./">
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

        <li class="nav-item">
          <a class="nav-link" style="color:#737373;font-size:16px;padding:10px 10px;" target="_blank"
            href="https://accounts.google.com/signin/v2/identifier?continue=https%3A%2F%2Fmail.google.com%2Fmail%2F&service=mail&hd=misena.edu.co&sacu=1&flowName=GlifWebSignIn&flowEntry=AddSession#identifier">
            <i class="fas fa-chalkboard"></i> Territorium
          </a>
        </li>

      </div>

    </div>
  </div>

  <div class="form-content">  
    <div class="form-right2">
      <div class="overlay2">
        <div class="grid-info-form">
        </div>
      </div>
    </div>
  

  <div class="form-left">
  <br><br><br><br><br>
  <form action="" method="POST" class="login-box">
        <h1>Fichas</h1>

    <div class="textbox1">
      <i style="font-size: 18px;" class="fas fa-paperclip"></i>
      <select style="font-weight:400;font-size:18px;" name="Trimestre" class="seleccionar ml-1 mr-0 p-0" required>
        <option readonly>Trimestre</option>
        <option> I</option>
        <option> II</option>
        <option> III</option>
        <option> IV</option>
        <option> V</option>
        <option> VI</option>
      </select>
    </div>

    <div class="textbox">
      <i class="fas fa-user-graduate"></i>
      <input type="text" name="Ficha" placeholder="Ficha: 0000000-A" maxlength=9 autocomplete="off" required>
    </div>

    <input type="submit" class="btnr" value="Consultar">
    <?php
      include_once("views/ficha/validacionConsulta.php");
    ?>
</form>
    
    <div class="botones">        
        <a>© 2020 Todos los derechos reservados | Design by SENA Centro de Diseño y Metrología</a>
      </div>
    </div>

 


</body>

</html>