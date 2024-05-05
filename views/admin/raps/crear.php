<?php
include "../../../extra/conexion.php"; 
 
$id = $descripcion = $tipoResultado = $CantidadHoras = $informacion = "";
$id_err = $descripcion_err = $tipoResultado_err =  $CantidadHoras_err = $informacion_err =  "";

if($_GET["IdCompetencia"] == ''){
    echo "<script>window.location='../programas/gestionar';</script>";
}

if(isset($_GET["IdCompetencia"]) && !empty(trim($_GET["IdCompetencia"]))){
    $IdCompetencia =  trim($_GET["IdCompetencia"]);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    $input_id = trim($_POST["Id"]);
    if(empty($input_id)){
        $id_err = "<small>Por favor ingrese el ID del resultado de aprendizaje.</small>";     
    } elseif(!ctype_digit($input_id)){
        $id_err = "<small>Por favor ingrese un valor correcto y positivo.</small>";
    } else{
        $id = $input_id;
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
    
    $IdCompetencias = $_POST["IdCompetencia"];


    if(empty($id_err) && empty($descripcion_err)  && empty($tipoResultado_err) && empty($CantidadHoras_err)){
        $sql = "INSERT INTO raps (Id, Descripcion, CantidadHoras, TipoResultado, Informacion, Status, IdPrincipalCompetencias) VALUES (?, upper(?), upper(?), upper(?), upper(?), '1', $IdCompetencias)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "issss", $param_id, $param_descripcion, $param_CantidadHoras, $param_tipoResultado, $param_informacion);
            
            $param_id = $id;
            $param_descripcion = $descripcion;
            $param_CantidadHoras = $CantidadHoras;
            $param_tipoResultado = $tipoResultado;                
            $param_informacion = $informacion;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: gestionar?IdCompetencia=$IdCompetencias");
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
                <a>Crear resultado de aprendizaje</a>
            </div>

            <div class="contenido">

            <form action="" method="post">    
		
        <div class="form-group <?php echo (!empty($id_err)) ? 'has-error' : ''; ?>">
                <label>ID</label>
                <input type="text" name="Id" class="form-control" style="text-transform: uppercase;" value="<?php echo $id; ?>">
                <span style="color: #a94442" class="help-block"><?php echo $id_err;?></span>
            </div>
        
        <div class="form-group <?php echo (!empty($descripcion_err)) ? 'has-error' : ''; ?>">
            <label>Descripción</label>
            <input type="text" name="Descripcion" class="form-control" style="text-transform: uppercase;" value="<?php echo $descripcion; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $descripcion_err;?></span>
        </div> 
       
        <div class="form-group <?php echo (!empty($tipoResultado_err)) ? 'has-error' : ''; ?>">
            <label>Tipo de resultado</label>
            <select name="TipoResultado" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control" value="<?php echo $tipoResultado; ?>">                                                
                    <option> </option>
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
            <textarea name="Informacion" class="form-control" style="text-transform: uppercase;" value="<?php echo $informacion; ?>"></textarea>
        </div>			
                        <input type="hidden" name="IdCompetencia" value="<?php echo $IdCompetencia; ?>">
                        <a class="btnr2" href="gestionar.php?IdCompetencia=<?php echo ($IdCompetencia); ?>">Cancelar</a>
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
