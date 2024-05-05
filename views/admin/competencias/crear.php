<?php
include "../../../extra/conexion.php"; 

$id = $descripcion = $duracion = $informacion = "";
$id_err = $descripcion_err =  $duracion_err = $informacion_err =  "";

if(isset($_GET["IdPrograma"]) && !empty(trim($_GET["IdPrograma"]))){
    $idProgramas =  trim($_GET["IdPrograma"]);
}

if($_GET["IdPrograma"] == ''){
    echo "<script>window.location = 'gestionar';</script>";
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    $input_id = trim($_POST["Id"]);
    if(empty($input_id)){
        $id_err = "<small>Por favor ingrese el ID de la competencia.</small>";     
    } elseif(!ctype_digit($input_id)){
        $id_err = "<small>Por favor ingrese un valor correcto y positivo.</small>";
    } else{
        $id = $input_id;
    }

    $input_descripcion = trim($_POST["descripcion"]);
    if(empty($input_descripcion)){
        $descripcion_err = "<small>Por favor ingrese el descripcion del competencia.</small>";
    } elseif(!filter_var($input_descripcion, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[a-zA-Z]+/")))){
        $descripcion_err = "<small>Por favor ingrese un descripcion válido.</small>";
    } else{
        $descripcion = $input_descripcion;
    }

    $input_duracion = trim($_POST["Duracion"]);
    if(empty($input_duracion)){
        $duracion_err = "<small>Por favor ingrese la duración del competencia.</small>";
    } elseif(!filter_var($input_duracion, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[A-Za-z0-9]+/")))){
        $duracion_err = "<small>Por favor ingrese una duración válida.</small>";
    } else{
        $duracion = $input_duracion;
    }

    $input_informacion = trim($_POST["Informacion"]);
    if(empty($input_informacion)){
        $informacion_err = "<small>Por favor ingrese la información adicional de la competencia.</small>";     
    }else{
        $informacion = $input_informacion;
    }

    $IdPrograma = trim($_POST["IdPrograma"]);

    if(empty($id_err) && empty($descripcion_err)  && empty($duracion_err)){


        $sql = "INSERT INTO competencias (Id, Descripcion, Duracion, Informacion, IdPrincipalProgramaCompetencia, Status) VALUES (?, upper(?), upper(?), upper(?), '$IdPrograma','1')";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isss", $param_id, $param_descripcion, $param_duracion, $param_informacion);

            $param_id = $id;
            $param_descripcion = $descripcion;
            $param_duracion = $duracion;         
            $param_informacion = $informacion;

            if(mysqli_stmt_execute($stmt)){
                header("location: gestionar.php?IdPrograma=$IdPrograma");
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
                <a>Crear competencia</a>
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
            <input type="text" name="descripcion" class="form-control" style="text-transform: uppercase;" value="<?php echo $descripcion; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $descripcion_err;?></span>
        </div> 

        <div class="form-group <?php echo (!empty($duracion_err)) ? 'has-error' : ''; ?>">
            <label>Duración</label>

            <input type="text"name="Duracion" class="form-control" style="text-transform: uppercase;" value="<?php echo $duracion; ?>"> 

            <span style="color: #a94442"  class="help-block"><?php echo $duracion_err;?></span>
        </div>        	        

        <div class="form-group">
            <label>Información adicional</label>
            <textarea name="Informacion" class="form-control" style="text-transform: uppercase;" value="<?php echo $informacion; ?>"></textarea>
        </div>			
                        
                        <input type="hidden" name="IdPrograma" value="<?php echo $idProgramas;?>"/>
                        <a class="btnr2" href="gestionar.php?IdPrograma=<?php echo ($idProgramas);?>" style="">Cancelar</a>
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
