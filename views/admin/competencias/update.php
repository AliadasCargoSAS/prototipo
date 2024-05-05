<?php
include "../../../extra/conexion.php"; 
 
$numero = $descripcion = $duracion = $informacion = "";
$numero_err = $descripcion_err =  $duracion_err = $informacion_err =  "";

if(isset($_GET["IdPrograma"]) && !empty(trim($_GET["IdPrograma"]))){
    $IdPrograma =  trim($_GET["IdPrograma"]);
};

if(($_GET["IdCompetencia"] == '') || ($_GET["IdPrograma"] == '')){
    echo '<script>window.location="gestionar?IdPrograma='.$_GET['IdPrograma'].'";</script>';
};
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

 $id=$_POST['IdCompetencia'];
       
    $input_numero = trim($_POST["Id"]);
    if(empty($input_numero)){
        $numero_err = "<small>Por favor ingrese el ID de la competencia.</small>";     
    } elseif(!ctype_digit($input_numero)){
        $numero_err = "<small>Por favor ingrese un valor correcto y positivo.</small>";
    } else{
        $numero = $input_numero;
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
        $informacion_err = "<small>Por favor ingrese la información adicional del competencia.</small>";     
    }else{
        $informacion = $input_informacion;
    }
    
    if(empty($numero_err) && empty($descripcion_err)  && empty($duracion_err)){
        $sql = "UPDATE competencias SET Id=?, Descripcion=upper(?), Duracion=upper(?) , Informacion=upper(?)  WHERE IdCompetencia=? AND Status=1";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isssi", $param_numero, $param_descripcion, $param_duracion, $param_informacion, $param_id);
            
            $param_numero = $numero;
            $param_descripcion = $descripcion;
            $param_duracion = $duracion;         
            $param_informacion = $informacion;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: gestionar?IdPrograma=$IdPrograma");
                exit();
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    //mysqli_close($link);
}else{
    if(isset($_GET["IdCompetencia"]) && !empty(trim($_GET["IdCompetencia"]))){
        $id =  trim($_GET["IdCompetencia"]);
        
        $sql = "SELECT * FROM competencias WHERE IdCompetencia = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $numero = $row["Id"];
                    $descripcion = $row["Descripcion"];                   
                    $duracion = $row["Duracion"];
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
        
        //mysqli_close($link);
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
                <a>Actualizar competencia de formación</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">  
		
        <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                <label>ID</label>
                <input type="text" name="Id" class="form-control" style="text-transform: uppercase;" value="<?php echo $numero; ?>">
                <span style="color: #a94442" class="help-block"><?php echo $numero_err;?></span>
            </div>
        
       
        <div class="form-group <?php echo (!empty($descripcion_err)) ? 'has-error' : ''; ?>">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control" style="text-transform: uppercase;" value="<?php echo $descripcion; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $descripcion_err;?></span>
        </div> 
                
        <div class="form-group <?php echo (!empty($duracion_err)) ? 'has-error' : ''; ?>">
            <label>Duración</label>
            <input type="text" name="Duracion" class="form-control" style="text-transform: uppercase;" value="<?php echo $duracion; ?>">
            <span style="color: #a94442"  class="help-block"><?php echo $duracion_err;?></span>
        </div>	

        <div class="form-group <?php echo (!empty($informacion_err)) ? 'has-error' : ''; ?>">
            <label>Información adicional</label>
            <textarea name="Informacion" class="form-control" style="text-transform: uppercase;"><?php echo $informacion; ?></textarea>
            <span class="help-block"><?php echo $informacion_err;?></span>
        </div>			
   
   
                <input type="hidden" name="IdCompetencia" value="<?php echo $id; ?>"/>
                        <a class="btnr2" href="gestionar.php?IdPrograma=<?php echo $IdPrograma;?>">Cancelar</a>
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
