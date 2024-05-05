
<?php
include "../../../extra/conexion.php"; 
 
$identificacion = $nombre = $nacimiento = $telefono = $especialidad = $correo = $TipoContrato = $contratoI = $contratoF = $informacion = "";
$identificacion_err = $nombre_err = $nacimiento_err = $telefono_err = $especialidad_err = $correo_err = $TipoContrato_err = $contratoI_err = $contratoF_err = $informacion_err =  "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = $_POST["IdPrincipal"];
     
    $input_identificacion = trim($_POST["Identificacion"]);
    if(empty($input_identificacion)){
        $identificacion_err = "Por favor ingrese el número de identificación del instructor.";     
    } elseif(!ctype_digit($input_identificacion)){
        $identificacion_err = "Por favor ingrese un valor correcto y positivo.";
    } else{
        $identificacion = $input_identificacion;
    }


    $input_nombre = trim($_POST["Nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "Por favor ingrese el nombre del instructor.";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z]+/")))){
        $nombre_err = "Por favor ingrese un nombre válido.";
    } else{
        $nombre = $input_nombre;
    }

    $input_nacimiento = trim($_POST["Nacimiento"]);
    if(empty($input_nacimiento)){
        $nacimiento_err = "Por favor ingrese la fecha de nacimiento del instructor.";     
    }else{
        $nacimiento = $input_nacimiento;
    }

    $input_telefono = trim($_POST["Telefono"]);
    if(empty($input_telefono)){
        $telefono_err = "Por favor ingrese el telefono del instructor.";
    } elseif(!filter_var($input_telefono, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]+/")))){
        $telefono_err = "Por favor ingrese un telefono válido.";
    } else{
        $telefono = $input_telefono;
    }

    $input_especialidad = trim($_POST["Especialidad"]);
    if(empty($input_especialidad)){
        $especialidad_err = "Por favor ingrese la especialidad del instructor.";     
    }else{
        $especialidad = $input_especialidad;
    }

   $input_correo = trim($_POST["Correo"]);
   if(empty($input_correo)){
       $correo_err = "Por favor ingrese el correo institucional del instructor.";     
   }else{
       $correo = $input_correo;
   }

   $input_tipocontrato = trim($_POST["TipoContrato"]);
   if(empty($input_tipocontrato)){
       $TipoContrato_err = "Por favor ingrese el tipo de contrato del instructor.";     
   }else{
       $TipoContrato = $input_tipocontrato;
   }

    $input_contratoI = trim($_POST["contratoI"]);
    if(empty($input_contratoI)){
        $contratoI_err = "Por favor ingrese el contrato de inicio para el instructor.";     
    }else{
        $contratoI = $input_contratoI;
    }
    
    $input_contratoF = trim($_POST["contratoF"]);
    if(empty($input_contratoF)){
        $contratoF_err = "Por favor ingrese el contrato fin para el instructor.";     
    }else{
        $contratoF = $input_contratoF;
    }
    
    $input_informacion = trim($_POST["Informacion"]);
    if(empty($input_informacion)){
        $informacion_err = "Por favor ingrese la información adicional del instructor.";     
    }else{
        $informacion = $input_informacion;
    }
    
    
    if(empty($identificacion_err) && empty($nombre_err)  && empty($nacimiento_err) && empty($telefono_err) && empty($especialidad_err) && empty($correo_err) && empty($TipoContrato_err) && empty($contratoI_err) && empty($contratoF_err)){
        $sql = "UPDATE instructores SET Identificacion=?, Nombre=upper(?), Nacimiento=? , Telefono=? , Especialidad=upper(?) , Correo=upper(?) , TipoContrato=upper(?) , ContratoInicio=upper(?), ContratoFin=upper(?), Informacion=upper(?) WHERE IdPrincipal=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isssssssssi", $param_identificacion, $param_nombre, $param_nacimiento, $param_telefono, $param_especialidad, $param_correo, $param_TipoContrato, $param_contratoI, $param_contratoF, $param_informacion, $param_id);
            
            $param_identificacion = $identificacion;
            $param_nombre = $nombre;
            $param_nacimiento = $nacimiento;
            $param_telefono = $telefono;
            $param_especialidad = $especialidad;
            $param_correo = $correo;
            $param_TipoContrato = $TipoContrato;
            $param_contratoI = $contratoI;
            $param_contratoF = $contratoF;
            $param_informacion = $informacion;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: gestionar.php");
                exit();
            } else{
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    //mysqli_close($link);
}else{
    if(isset($_GET["IdPrincipal"]) && !empty(trim($_GET["IdPrincipal"]))){
        $id =  trim($_GET["IdPrincipal"]);
        
        $sql = "SELECT * FROM instructores WHERE IdPrincipal = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){                    
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $identificacion = $row["Identificacion"];
                    $nombre = $row["Nombre"];
                    $nacimiento = $row["Nacimiento"];
                    $telefono = $row["Telefono"];
                    $especialidad = $row["Especialidad"];
                    $correo = $row["Correo"];
                    $TipoContrato = $row["TipoContrato"];
                    $contratoI = $row["ContratoInicio"];
                    $contratoF = $row["ContratoFin"];
                    $informacion = $row["Informacion"];
                } else{
                    header("location: ../principal/error.php");
                    exit();
                }
                
            } else{
                echo "Algo salió mal. Por favor, inténtelo nuevamente.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
    //    mysqli_close($link);
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
                <a>Actualizar programa de formación</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">  
		
                <div class="form-group <?php echo (!empty($identificacion_err)) ? 'has-error' : ''; ?>">
                                <label>Número de identificación</label>
                                <input type="text" name="Identificacion" class="form-control validanumericos" style="text-transform: uppercase;" value="<?php echo $identificacion; ?>">
                                <span style="color: #a94442" class="help-block"><?php echo $identificacion_err;?></span>
                            </div>

                            <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
                    <label>Nombre del instructor</label>
                    <input type="text" name="Nombre" class="form-control" style="text-transform: uppercase;" value="<?php echo $nombre; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $nombre_err;?></span>
                </div>

                <div class="form-group <?php echo (!empty($nacimiento_err)) ? 'has-error' : ''; ?>">
                    <label>Fecha de nacimiento</label>
                    <input type="date" name="Nacimiento" class="form-control" style="text-transform: uppercase;" value="<?php echo $nacimiento; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $nacimiento_err;?></span>
                </div>

                <div class="form-group <?php echo (!empty($telefono_err)) ? 'has-error' : ''; ?>">
                    <label>Número telefonico</label>
                    <input type="text" name="Telefono" class="form-control validanumericos" style="text-transform: uppercase;" value="<?php echo $telefono; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $telefono_err;?></span>
                </div>
                
                <div class="form-group <?php echo (!empty($especialidad_err)) ? 'has-error' : ''; ?>">
                    <label>Especialidad</label>
                    <input type="text" name="Especialidad" class="form-control" style="text-transform: uppercase;" value="<?php echo $especialidad; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $especialidad_err;?></span>
                </div>
                
                <div class="form-group <?php echo (!empty($correo_err)) ? 'has-error' : ''; ?>">
                    <label>Correo institucional</label>
                    <input type="email" name="Correo" class="form-control" style="text-transform: uppercase;" value="<?php echo $correo; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $correo_err;?></span>
                </div>

                <div class="form-group <?php echo (!empty($TipoContrato_err)) ? 'has-error' : ''; ?>">
                <label>Tipo de contrato</label>
                <select name="TipoContrato" style="font-size:16px;color:#495057;" style="text-transform: uppercase;" class="form-control">                                                
                        <option><?php echo $TipoContrato; ?></option>
                        <option>PLANTA</option>
                        <option>CONTRATISTA</option>
                    </select>
                <span style="color: #a94442" class="help-block"><?php echo $TipoContrato_err;?></span>
            </div>
            
            <div class="form-group <?php echo (!empty($contratoI_err)) ? 'has-error' : ''; ?>">
                <label>Contrato Inicio</label>
                <input type="date" name="contratoI" class="form-control" style="text-transform: uppercase;" value="<?php echo $contratoI; ?>">
                <span style="color: #a94442" class="help-block"><?php echo $contratoI_err;?></span>
            </div>

            <div class="form-group <?php echo (!empty($contratoF_err)) ? 'has-error' : ''; ?>">
                <label>Contrato Fin</label>
                <input type="date" name="contratoF" class="form-control" style="text-transform: uppercase;" value="<?php echo $contratoF; ?>">
                <span style="color: #a94442" class="help-block"><?php echo $contratoF_err;?></span>
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

        });</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>


</body>
</html>
