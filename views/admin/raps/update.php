<?php
include "../../../extra/conexion.php"; 
 
$numero = $descripcion = $tipoResultado = $CantidadHoras = $informacion = "";
$numero_err = $descripcion_err = $tipoResultado_err =  $CantidadHoras_err = $informacion_err =  "";
 
if(isset($_GET["IdCompetencia"]) && !empty(trim($_GET["IdCompetencia"]))){
    $IdCompetencia =  trim($_GET["IdCompetencia"]);
};

if ($_GET["IdCompetencia"] == '') {
    echo "<script>window.location='../programas/gestionar';</script>";
};

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = $_POST['IdRae'];
       
    $input_numero = trim($_POST["Id"]);
    if(empty($input_numero)){
        $numero_err = "<small>Por favor ingrese el ID del resultado de aprendizaje.</small>";     
    } elseif(!ctype_digit($input_numero)){
        $numero_err = "<small>Por favor ingrese un valor correcto y positivo.</small>";
    } else{
        $numero = $input_numero;
    }

    $input_descripcion = trim($_POST["Descripcion"]);
    if(empty($input_descripcion)){
        $descripcion_err = "<small>Por favor ingrese la descripcion del resultado de aprendizaje.</small>";
    } elseif(!filter_var($input_descripcion, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z]+/")))){
        $descripcion_err = "<small>Por favor ingrese una descripcion válida.</small>";
    } else{
        $descripcion = $input_descripcion;
    }

    $input_tipoResultado = trim($_POST["TipoResultado"]);
    if(empty($input_tipoResultado)){
        $tipoResultado_err = "<small>Por favor ingrese el tipo de resultado.</small>";     
    }else{
        $tipoResultado = $input_tipoResultado;
    }
    
    $input_CantidadHoras = trim($_POST["CantidadHoras"]);
    if(empty($input_CantidadHoras)){
        $CantidadHoras_err = "<small>Por favor ingrese la cantidad de horas del resultado de aprendizaje.</small>";
    } elseif(!filter_var($input_CantidadHoras, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[A-Za-z0-9]+/")))){
        $CantidadHoras_err = "<small>Por favor ingrese una cantidad de horas válida.</small>";
    } else{
        $CantidadHoras = $input_CantidadHoras;
    }

    $input_informacion = trim($_POST["Informacion"]);
    if(empty($input_informacion)){
        $informacion_err = "<small>Por favor ingrese la información adicional del resultado de aprendizaje.</small>";     
    }else{
        $informacion = $input_informacion;
    }
    
    if(empty($numero_err) && empty($descripcion_err)  && empty($tipoResultado_err) && empty($CantidadHoras_err)){
        $sql = "UPDATE raps SET Id=?, Descripcion=upper(?), CantidadHoras=upper(?), TipoResultado=upper(?), Informacion=upper(?)  WHERE IdRae=? AND Status=1";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "issssi", $param_numero, $param_descripcion, $param_CantidadHoras, $param_tipoResultado, $param_informacion, $param_id);
            
            $param_numero = $numero;
            $param_descripcion = $descripcion;
            $param_CantidadHoras = $CantidadHoras;
            $param_tipoResultado = $tipoResultado;                
            $param_informacion = $informacion;
            $param_id = $id;

            if(mysqli_stmt_execute($stmt)){
                 //header("location: gestionar?IdCompetencia=$IdCompetencia");
                 //exit();
                 echo "<script>wind.location='gestionar?IdCompetencia=$IdCompetencia'</script>";
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    //mysqli_close($link);
}else{
    if(isset($_GET["IdRae"]) && !empty(trim($_GET["IdRae"]))){
        $id =  trim($_GET["IdRae"]);
        
        $sql = "SELECT * FROM raps WHERE IdRae = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $numero = $row["Id"];
                    $descripcion = $row["Descripcion"];
                    $CantidadHoras = $row["CantidadHoras"];
                    $tipoResultado = $row["TipoResultado"];
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
                <a>Actualizar resultado de aprendizaje</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">    
		
        <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                <label>ID</label>
                <input type="text" name="Id" class="form-control" style="text-transform: uppercase;" value="<?php echo $numero; ?>">
                <span style="color: #a94442" class="help-block"><?php echo $numero_err;?></span>
            </div>
        
        <div class="form-group <?php echo (!empty($descripcion_err)) ? 'has-error' : ''; ?>">
            <label>Descripción</label>
            <input type="text" name="Descripcion" class="form-control" style="text-transform: uppercase;" value="<?php echo $descripcion; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $descripcion_err;?></span>
        </div> 
       
        <div class="form-group <?php echo (!empty($tipoResultado_err)) ? 'has-error' : ''; ?>">
            <label>Tipo de resultado</label>
            <select name="TipoResultado" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control" >                                               
                    <option><?php echo $tipoResultado; ?></option>
                    <option>PARCIAL</option>
                    <option>FINAL</option>
                </select>
            <span style="color: #a94442" class="help-block"><?php echo $tipoResultado_err;?></span>
        </div>

        <div class="form-group <?php echo (!empty($CantidadHoras_err)) ? 'has-error' : ''; ?>">
            <label>Cantidad de horas</label>

            <input type="text"name="CantidadHoras" class="form-control" style="text-transform: uppercase;" value="<?php echo $CantidadHoras; ?>"> 

            <span style="color: #a94442"  class="help-block"><?php echo $CantidadHoras_err;?></span>
        </div>        	        

        <div class="form-group">
            <label>Información adicional</label>
            <textarea name="Informacion" class="form-control" style="text-transform: uppercase;"><?php echo $informacion; ?></textarea>
        </div>			
                        <input type="hidden" name="IdRae" value="<?php echo $id; ?>"/>
                        <a class="btnr2" href="gestionar?IdCompetencia=<?php echo $IdCompetencia; ?>">Cancelar</a>
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
