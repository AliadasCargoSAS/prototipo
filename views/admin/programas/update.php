<?php
include "../../../extra/conexion.php"; 
 
$numero = $nombre = $tipo = $duracion = $version = $proyecto = $informacion = "";
$nombre_err = $numero_err = $tipo_err =  $duracion_err = $version_err = $proyecto_err = $informacion_err =  "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    if ($_POST["IdPrograma"] == '') {
        echo "<script>window.location='gestionar';</script>";
    }

    $id = $_POST["IdPrograma"];
       
    $input_numero = trim($_POST["Id"]);
    if(empty($input_numero)){
        $numero_err = "Por favor ingrese el ID del programa de formación.";     
    } elseif(!ctype_digit($input_numero)){
        $numero_err = "Por favor ingrese un valor correcto y positivo.";
    } else{
        $numero = $input_numero;
    }

    $input_nombre = trim($_POST["Nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "Por favor ingrese el nombre del programa de formación.";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z]+/")))){
        $nombre_err = "Por favor ingrese un nombre válido.";
    } else{
        $nombre = $input_nombre;
    }

    $input_tipo = trim($_POST["Tipo"]);
    if(empty($input_tipo)){
        $tipo_err = "Por favor ingrese el tipo de programa de formación.";     
    }else{
        $tipo = $input_tipo;
    }
    
    $input_duracion = trim($_POST["Duracion"]);
    if(empty($input_duracion)){
        $duracion_err = "Por favor ingrese la duración del programa de formación.";
    } elseif(!filter_var($input_duracion, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[A-Za-z0-9]+/")))){
        $duracion_err = "Por favor ingrese una duración válida.";
    } else{
        $duracion = $input_duracion;
    }

    $input_version= trim($_POST["Version"]);
    if(empty($input_version)){
        $version_err = "<small>Por favor ingrese la versión del programa de formación.</small>";
    } else{
        $version = $input_version;
    }

    $input_proyecto= trim($_POST["Proyecto"]);
    if(empty($input_proyecto)){
        $proyecto_err = "<small>Por favor ingrese el proyecto para el programa de formación.</small>";
    } else{
        $proyecto = $input_proyecto;
    }

    $input_informacion = trim($_POST["Informacion"]);
    if(empty($input_informacion)){
        $informacion_err = "Por favor ingrese una información adicional.";     
    } else{
        $informacion = $input_informacion;
    }

    
    if(empty($nombre_err) && empty($numero_err) && empty($duracion_err) && empty($tipo_err) && empty($version_err) && empty($proyecto_err)){
        $sql = "UPDATE programas SET Id=upper(?), Nombre=upper(?), Tipo=upper(?), Duracion=upper(?) , version=?, Proyecto=upper(?), Informacion=upper(?)  WHERE IdPrograma=? AND Status=1";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "issssssi", $param_numero, $param_nombre, $param_tipo, $param_duracion, $param_version, $param_proyecto, $param_informacion, $param_id);
            
            $param_numero = $numero;
            $param_nombre = $nombre;
            $param_tipo = $tipo;
            $param_duracion = $duracion;
            $param_version = $version;
            $param_proyecto = $proyecto;  
            $param_informacion = $informacion;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
               
                header("location: gestionar");
                exit();
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    //mysqli_close($link);
}else{
    if(isset($_GET["IdPrograma"]) && !empty(trim($_GET["IdPrograma"]))){
        if ($_GET["IdPrograma"] == '') {
            echo "<script>window.location='gestionar';</script>";
        }
        $id =  trim($_GET["IdPrograma"]);
        
        $sql = "SELECT * FROM programas WHERE IdPrograma = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $numero = $row["Id"];
                    $nombre = $row["Nombre"];
                    $tipo = $row["Tipo"];
                    $version = $row["Version"];
                    $proyecto = $row["Proyecto"];
                    $duracion = $row["Duracion"];
                    $informacion = $row["Informacion"];
                } else{
                    header("location: ../principal/error");
                    exit();
                }
                
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
        
        mysqli_stmt_close($stmt);
        
        //mysqli_close($link);
    }  else{
        header("location: ../principal/error");
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
		
        <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                <label>ID</label>
                <input type="text" name="Id" class="form-control" style="text-transform: uppercase;" value="<?php echo $numero; ?>">
                <span style="color: #a94442" class="help-block"><?php echo $numero_err;?></span>
            </div>
        
        <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
            <label>Nombre</label>
            <input type="text" name="Nombre" class="form-control" style="text-transform: uppercase;" value="<?php echo $nombre; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $nombre_err;?></span>
        </div>

       
        <div class="form-group <?php echo (!empty($tipo_err)) ? 'has-error' : ''; ?>">
            <label>Tipo de programa</label>
            <select name="Tipo" id="TipoP" onchange="cambioSelect();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                    <option><?php echo $tipo; ?></option>
                    <option>TECNICO</option>
                    <option>TECNOLOGO</option>
                </select>
            <span style="color: #a94442" class="help-block"><?php echo $tipo_err;?></span>
        </div>

       <script>

            function cambioSelect() {

                var tipoprograma = document.getElementById('TipoP');         
                var tipo2 = tipoprograma.options[tipoprograma.selectedIndex].text;
                        
                if (tipo2=='TECNICO') {
                    document.getElementById('txtDuracion').value= '12 meses';
                } else if(tipo2=='TECNOLOGO'){
                    document.getElementById('txtDuracion').value= '24 meses';
                }else{
                    document.getElementById('txtDuracion').value= '';
                }

            }
        </script>

        <div class="form-group <?php echo (!empty($duracion_err)) ? 'has-error' : ''; ?>">
            <label>Duración</label>

            <input type="text"name="Duracion" id ="txtDuracion" class="form-control" style="text-transform: uppercase;" readonly value="<?php echo $duracion; ?>"> 

            <span style="color: #a94442"  class="help-block"><?php echo $duracion_err;?></span>
        </div>  

        <div class="form-group <?php echo (!empty($version_err)) ? 'has-error' : ''; ?>">
            <label>Versión</label>
            <input type="text" name="Version" class="form-control" style="text-transform: uppercase;" value="<?php echo $version; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $version_err;?></span>
        </div> 

        <div class="form-group <?php echo (!empty($proyecto_err)) ? 'has-error' : ''; ?>">
            <label>Proyecto formativo</label>
            <textarea name="Proyecto" class="form-control" style="text-transform: uppercase;" value="<?php echo $proyecto; ?>"></textarea>
            <span style="color: #a94442"  class="help-block"><?php echo $proyecto_err;?></span>
        </div>	

        <div class="form-group <?php echo (!empty($informacion_err)) ? 'has-error' : ''; ?>">
            <label>Información adicional</label>
            <textarea name="Informacion" class="form-control" style="text-transform: uppercase;"><?php echo $informacion; ?></textarea>
            <span class="help-block"><?php echo $informacion_err;?></span>
        </div>			
   
   
                <input type="hidden" name="IdPrograma" value="<?php echo $id; ?>"/>
                        <a class="btnr2" href="gestionar">Cancelar</a>
                        <input type="submit" value="Actualizar" class="btnr" >

                        </form>
                    </div>
                    </div>
            </div>
    </div>
</div>

<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>
</html>
