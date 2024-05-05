<?php
include "../../../extra/conexion.php"; 
 
$usuario = $nombre = $correo = $telefono = $clave = $rol = $informacion = "";
$usuario_err = $nombre_err = $correo_err = $telefono_err = $clave_err =  $rol_err = $informacion_err =  "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $input_usuario = trim($_POST["Usuario"]);
    if(empty($input_usuario)){
        $usuario_err = "<small>Por favor ingrese el número de identificación.</small>";
    } elseif(!filter_var($input_usuario, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]+/")))){
        $usuario_err = "<small>Por favor ingrese un número de identificación válido.</small>";
    } else{
        $usuario = $input_usuario;
    }

    $input_nombre = trim($_POST["Nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "<small>Por favor ingrese el nombre del usuario.</small>";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[A-Z]+/")))){
        $nombre_err = "<small>Por favor ingrese un nombre de usuario válido.</small>";
    } else{
        $nombre = $input_nombre;
    }

    $input_correo = trim($_POST["Correo"]);
    if(empty($input_correo)){
        $correo_err = "<small>Por favor ingrese el correo institucional del usuario.</small>";     
    }else{
        $correo = $input_correo;
    }

    $input_telefono = trim($_POST["Telefono"]);
    if(empty($input_telefono)){
        $telefonoerr = "<small>Por favor ingrese el telefono del usuario.</small>";
    } elseif(!filter_var($input_telefono, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]+/")))){
        $telefono_err = "<small>Por favor ingrese un telefono válido.</small>";
    } else{
        $telefono = $input_telefono;
    }

    $input_clave = trim($_POST["Clave"]);
    if(empty($input_clave)){
        $clave_err = "<small>Por favor ingrese una contraseña.</small>";     
    }else{
        $clave = $input_clave;
    }

    $input_rol = trim($_POST["Rol"]);
    if(empty($input_rol)){
        $rol_err = "<small>Por favor seleccione el rol.</small>";     
    }else{
        $rol = $input_rol;
    }
    
    $input_informacion = trim($_POST["Informacion"]);
    if(empty($input_informacion)){
        $informacion_err = "<small>Por favor ingrese la información adicional del programa de formación.</small>";     
    }else{
        $informacion = $input_informacion;
    }
    
    if(empty($usuario_err) && empty($nombre_err) && empty($correo_err) && empty($telefono_err) && empty($clave_err) && empty($rol_err)){
        $sql = "INSERT INTO usuarios (Usuario, Nombre, Correo, Telefono, Clave, Rol, Informacion, Status) VALUES (?, upper(?), ?, ?, md5(?), ?, upper(?), '1')";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ississs", $param_usuario, $param_nombre, $param_correo, $param_telefono, $param_clave, $param_rol, $param_informacion);
            
            $param_usuario = $usuario;
            $param_nombre = $nombre;
            $param_correo = $correo;
            $param_telefono = $telefono;
            $param_clave= $clave;
            $param_rol = $rol;
            $param_informacion = $informacion;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: gestionar.php");
                exit();
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
     
}
?>

<!-- Envio de datos del usuario registrado hacia el correo correspondiente del mismo -->

<?php

if (isset($_POST['Crear'])) {

     if (!empty($_POST['Nombre']) && !empty($_POST['Usuario']) && !empty($_POST['Rol']) && !empty($_POST['Clave']) && !empty($_POST['Correo']) && !empty($_POST['Informacion'])){

         $nombre = $_POST['Nombre'];
         $email = $_POST['Correo'];
         $rol = $_POST['Rol'];
         $clave = $_POST['Clave'];
         $informacion = $_POST['Informacion'];

         $header="From: Programacion Eventos Formativos" . "\r'n";
         $header.= "Content-type: text/html; charset=iso-8859-1\r\n"; 
         $header.="Reply-To: programacioneventosformativos@sena.edu.co" . "\r'n";

         $mensaje="
        <html>           
            <body style= 'margin: 0px; width:370px;'> 
                <div>
                    <h3 style= 'color:#fff; background-color:#333; border-radius:4px; padding:13px; text-align: end;'><img style='text-align: start; position: absolute; left: 10px; top: 23px;' width='48px;' height='38px;' src='../../../images/logo.png' alt='Logo SENA'>Programación de eventos formativos</h3>
            
                    <div style= 'border: 2px solid #333; border-radius:3px;'>
                        <p style= 'color:#000; padding-left:20px; padding-right: 2px;'>Bienvenido {$nombre} a nuestro sistema (Programación de eventos formativos), acontinuación encontrará los datos correspondientes para ingresar <br> e interactuar con el mismo.</p>
                        
                        <p style= 'color:#fff; background-color:#333; padding:6px;'><strong>Datos de autenticación</strong></p>

                        <p style= 'color:#000; padding-left:8px;'><Strong>Rol:</Strong> {$rol}</p>
                        
                        <p style= 'color:#000; padding-left:8px;'><Strong>Usuario - Número Identificación:</Strong> {$usuario}</p>
                    
                        <p style= 'color:#000; padding-left:8px;'><Strong>Contraseña:</Strong> {$clave}</p>
                        
                        <p style= 'color:#fff; background-color:#333; padding:6px;'><strong>Información Adicional</strong></p>
                        
                        <p style= 'color:#000; padding-left:8px;'>{$informacion}</p>
                        
                        <footer style= 'font-size: 12px; color:#fff; background-color:#333; padding:6px; padding-right: 15px; text-align: center;'>
                        <strong>Centro de diseño y metrologia - SENA</strong><br><strong>-•- Bogotá, Colombia -•-</strong><br><strong>© 2020 Todos los derechos reservados</strong></footer>
                    </div>                  
                </div>
            </body>                
        </html>

         <br />";

         $mail= @mail($email,"Acceso al sistema de Programación de eventos formativos",$mensaje,$header);

         if ($mail) {
             echo "<script>alert('Mensaje enviado correctamente');</script>";
         }
                 
     }
}

?>


<!DOCTYPE html>
<html lang="es">

<?php     
    require_once "../include/header-admin.php";
?> 

<body>
<div class="container mb-3">        
    <div class="panel" id="cont-bloque-corto2">
        <div class="titulo">
                <a>Crear usuario</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">    	
        
                <div class="form-group <?php echo (!empty($usuario_err)) ? 'has-error' : ''; ?>">
                    <label>Número de identificación</label>
                    <input type="text" name="Usuario" class="form-control validanumericos" style="text-transform: uppercase;" value="<?php echo $usuario; ?>">
                    <span style="color: #a94442"  class="help-block"><?php echo $usuario_err;?></span>
                </div>

                <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                    <label>Nombre</label>
                    <input type="text" name="Nombre" class="form-control" style="text-transform: uppercase;" value="<?php echo $nombre; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $nombre_err;?></span>
                </div>

                <div class="form-group <?php echo (!empty($correo_err)) ? 'has-error' : ''; ?>">
                    <label>Correo</label>
                    <input type="email" name="Correo" class="form-control" style="text-transform: uppercase;" value="<?php echo $correo; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $correo_err;?></span>
                </div>

                <div class="form-group <?php echo (!empty($telefono_err)) ? 'has-error' : ''; ?>">
                    <label>Telefono</label>
                    <input type="text" name="Telefono" class="form-control validanumericos" style="text-transform: uppercase;" max-length="10" value="<?php echo $telefono; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $telefono_err;?></span>
                </div>
                <?php 
                    function generaPass(){
                        $cadena = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
                        $longitudCadena=strlen($cadena);
                    
                        $pass = "";
                        $longitudPass=8;
                    
                        for($i=1 ; $i<=$longitudPass ; $i++){
                            $pos=rand(0,$longitudCadena-1);
                    
                            $pass .= substr($cadena,$pos,1);
                        }
                        return $pass;
                    }
                    ?>    
                <div class="form-group" style="display:none">
                    <label>Contraseña</label>
                    <input type="text" name="Clave" class="form-control" value="<?php echo generaPass(); ?>">
                </div>

                <div class="form-group <?php echo (!empty($rol_err)) ? 'has-error' : ''; ?>">
                    <label>Rol</label>
                    <select name="Rol" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                            <option><?php echo $rol; ?></option>
                            <option>1 ADMINISTRADOR</option>
                            <option>2 COORDINADOR</option>
                            <option>3 INSTRUCTOR</option>
                        </select>
                    <span style="color: #a94442" class="help-block"><?php echo $rol_err;?></span>
                </div>

                <div class="form-group">
                    <label>Información adicional</label>
                    <textarea name="Informacion" class="form-control" style="text-transform: uppercase;" value="<?php echo $informacion; ?>"></textarea>
                </div>			
                        <a class="btnr2" href="gestionar">Cancelar</a>
                        <input type="submit" name="Crear" value="Crear" class="btnr" >
                  
            </form>
            
                </div>
            </div>
        </div>
    </div>
</div>



<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
        $(function(){

            $('.validanumericos').keypress(function(e) {
            if(isNaN(this.value + String.fromCharCode(e.charCode))) 
            return false;
            })
            .on("cut copy paste",function(e){
            e.preventDefault();
            });
        });
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

</body>
</html>
