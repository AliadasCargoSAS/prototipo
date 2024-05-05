<?php
include "../../../extra/conexion.php"; 
 
$id = $nombre = $tipo = $duracion = $version = $proyecto = $informacion = "";
$id_err = $nombre_err = $tipo_err =  $duracion_err = $version_err = $proyecto_err = $informacion_err =  "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
  
    $input_id = trim($_POST["Id"]);
    if(empty($input_id)){
        $id_err = "<small>Por favor ingrese el ID del programa de formación.</small>";     
    } elseif(!ctype_digit($input_id)){
        $id_err = "<small>Por favor ingrese un valor correcto y positivo.</small>";
    } else{
        $id = $input_id;
    }


    $input_nombre = trim($_POST["Nombre"]);
    if(empty($input_nombre)){
        $nombre_err = "<small>Por favor ingrese el nombre del programa de formación.</small>";
    } elseif(!filter_var($input_nombre, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z]+/")))){
        $nombre_err = "<small>Por favor ingrese un nombre válido.</small>";
    } else{
        $nombre = $input_nombre;
    }

    $input_tipo = trim($_POST["Tipo"]);
    if(empty($input_tipo)){
        $tipo_err = "<small>Por favor ingrese el tipo de programa de formación.</small>";     
    }else{
        $tipo = $input_tipo;
    }
    
    $input_duracion = trim($_POST["Duracion"]);
    if(empty($input_duracion)){
        $duracion_err = "<small>Por favor ingrese la duración del programa de formación.</small>";
    } elseif(!filter_var($input_duracion, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[A-Za-z0-9]+/")))){
        $duracion_err = "<small>Por favor ingrese una duración válida.</small>";
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
        $informacion_err = "<small>Por favor ingrese la información adicional del programa de formación.</small>";     
    }else{
        $informacion = $input_informacion;
    }
    
    if(empty($id_err) && empty($nombre_err)  && empty($tipo_err) && empty($duracion_err) && empty($version_err) && empty($proyecto_err)){
        $sql = "INSERT INTO programas (Id, Nombre, Tipo, Duracion, Version, Proyecto, Informacion, Status) VALUES (upper(?), upper(?), upper(?), upper(?), ?, upper(), upper(?), '1')";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "issssss", $param_id, $param_nombre, $param_tipo, $param_duracion, $param_version, $param_proyecto, $param_informacion);
            
            $param_id = $id;
            $param_nombre = $nombre;
            $param_tipo = $tipo;
            $param_duracion = $duracion;
            $param_version = $version;
            $param_proyecto = $proyecto;         
            $param_informacion = $informacion;
            
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
                <a>Crear programa de formación</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">    
		
        <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                <label>ID</label>
                <input type="text" name="Id" class="form-control" style="text-transform: uppercase;" value="<?php echo $id; ?>">
                <span style="color: #a94442" class="help-block"><?php echo $id_err;?></span>
            </div>
        
        <div class="form-group <?php echo (!empty($nombre_err)) ? 'has-error' : ''; ?>">
            <label>Nombre</label>
            <input type="text" name="Nombre" class="form-control" style="text-transform: uppercase;" value="<?php echo $nombre; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $nombre_err;?></span>
        </div> 
       
        <div class="form-group <?php echo (!empty($tipo_err)) ? 'has-error' : ''; ?>">
            <label>Tipo de programa</label>
            <select name="Tipo" id="TipoP" onchange="cambioSelect();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control" value="<?php echo $tipo; ?>">                                                
                    <option> </option>
                    <option>TECNICO</option>
                    <option>TECNOLOGO</option>
                </select>
            <span style="color: #a94442" class="help-block"><?php echo $tipo_err;?></span>
        </div>

        <!-- Funcion para hacer cambios entre el select Tipo Programa y el input de Duracion -->
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

        <div class="form-group">
            <label>Información adicional</label>
            <textarea name="Informacion" class="form-control" style="text-transform: uppercase;" value="<?php echo $informacion; ?>"></textarea>
        </div>			
                        <a class="btnr2" href="gestionar">Cancelar</a>
                        <input type="submit" value="Crear" class="btnr" >
                  
                        </form>
                    </div>
                    </div>
            </div>
    </div>
</div>



<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>
</html>
