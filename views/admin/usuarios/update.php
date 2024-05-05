<?php
include "../../../extra/conexion.php"; 
 
$usuario = $nombre = $correo = $telefono = $rol = $informacion = "";
$usuario_err = $nombre_err = $correo_err = $telefono_err =  $rol_err = $informacion_err =  "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = $_POST["IdPrincipal"];
    
    $input_usuario = trim($_POST["Usuario"]);
    if(empty($input_usuario)){
        $usuario_err = "<small>Por favor ingrese el usuario.</small>";
    } elseif(!filter_var($input_usuario, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]+/")))){
        $usuario_err = "<small>Por favor ingrese un usuario válido, (número del documento de identificación).</small>";
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
        $sql = "UPDATE usuarios SET Usuario=?, Nombre=upper(?), Correo=upper(?), Telefono=?, Rol=?, Informacion=upper(?)  WHERE IdPrincipal=? AND Status=1";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ississi", $param_usuario, $param_nombre, $param_correo, $param_telefono, $param_rol, $param_informacion, $param_id);
            
            $param_usuario = $usuario;
            $param_nombre = $nombre;
            $param_correo = $correo;
            $param_telefono = $telefono;  
            $param_rol = $rol;
            $param_informacion = $informacion;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: gestionar.php");
                exit();
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($link);
}else{
    if(isset($_GET["IdPrincipal"]) && !empty(trim($_GET["IdPrincipal"]))){
        $id =  trim($_GET["IdPrincipal"]);
        
        $sql = "SELECT u.*, 

        case when u.Rol = 1 then 'ADMINISTRADOR'
        when u.Rol = 2 then 'COORDINADOR'
        when u.Rol = 3 then 'INSTRUCTOR'
        end as 'Rol'  
        
        FROM usuarios u WHERE IdPrincipal = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $usuario = $row["Usuario"];    
                    $nombre= $row["Nombre"];
                    $correo = $row["Correo"];
                    $telefono = $row["Telefono"];                          
                    $rol = $row["Rol"];
                    $informacion = $row["Informacion"];
                } else{
                    header("location: ../principal/error.php");
                    exit();
                }
                
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
        
        mysqli_stmt_close($stmt);
                
    }  else{
        header("location: ../principal/error.php");
        exit();
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
                <a>Actualizar usuario</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">  		
        
        <div class="form-group <?php echo (!empty($usuario_err)) ? 'has-error' : ''; ?>">
            <label>Usuario</label>
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

        <div class="form-group <?php echo (!empty($informacion_err)) ? 'has-error' : ''; ?>">
            <label>Información adicional</label>
            <textarea name="Informacion" class="form-control" style="text-transform: uppercase;"><?php echo $informacion; ?></textarea>
            <span class="help-block"><?php echo $informacion_err;?></span>
        </div>			
   
   
                <input type="hidden" name="IdPrincipal" value="<?php echo $id; ?>"/>
                        <a class="btnr2" href="gestionar.php" style="">Cancelar</a>
                        <input type="submit" value="Actualizar" class="btnr" >

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
