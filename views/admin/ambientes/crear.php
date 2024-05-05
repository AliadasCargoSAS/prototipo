<?php
include "../../../extra/conexion.php"; 
 

$numero = $capacidad = $tipo = $tipo2 = $sede = $torre = $torre2 = $informacion = "";
$numero_err = $capacidad_err = $tipo_err = $tipo_err2 = $sede_err = $torre_err = $torre_err2 = $informacion_err =  "";

if($_SERVER["REQUEST_METHOD"] == "POST"){ 
    
    $input_numero = trim($_POST["Numero"]);
    if(empty($input_numero)){
        $numero_err = "Por favor ingrese el número del ambiente de formación.";     
    } elseif(!ctype_digit($input_numero)){
        $numero_err = "Por favor ingrese un valor correcto y positivo.";
    } else{
        $numero = $input_numero;
    }

    $input_capacidad = trim($_POST["Capacidad"]);
    if(empty($input_capacidad)){
        $capacidad_err = "Por favor ingrese la capacidad de aprendices del ambiente de formación.";     
    } elseif(!ctype_digit($input_capacidad)){
        $capacidad_err = "Por favor ingrese un valor correcto y positivo.";
    } else{
        $capacidad = $input_capacidad;
    }

    $input_tipo = trim($_POST["Tipo"]);
    if(empty($input_tipo)){
        $tipo_err = "Por favor ingrese el tipo de ambiente de formación.";     
    }else{
        $tipo = $input_tipo;
    }

    $input_tipo2 = trim($_POST["OtroTipo"]);
    if(empty($input_tipo2) && $input_tipo == "OTRO"){
        $tipo_err2 = "Por favor ingrese el tipo de ambiente de formación.";     
    }else{
        $tipo2 = $input_tipo2;
    }

    $input_sede = trim($_POST["Sede"]);
    if(empty($input_sede)){
        $sede_err = "Por favor ingrese la sede para el ambiente de formación.";     
    }else{
        $sede = $input_sede;
    }
    
    $input_torre = trim($_POST["Torre"]);
    if(empty($input_torre)){
        $torre_err = "Por favor ingrese la torre para el ambiente de formación.";     
    }else{
        $torre = $input_torre;
    }

    $input_torre2 = trim($_POST["OtraTorre"]);
    if(empty($input_torre2) && $input_torre == "OTRA"){
        $torre_err2 = "Por favor ingrese la torre para el ambiente de formación.";     
    }else{
        $torre2 = $input_torre2;
    }

    $input_informacion = trim($_POST["Informacion"]);
    if(empty($input_informacion)){
        $informacion_err = "Por favor ingrese la información adicional del ambiente de formación.";     
    }else{
        $informacion = $input_informacion;
    }
    
    if(empty($numero_err) && empty($capacidad_err)  && empty($tipo_err) && empty($tipo_err2) && empty($sede_err) && empty($torre_err) && empty($torre_err2)){
        $sql = "INSERT INTO ambientes (Numero, Capacidad, Tipo, Sede, Torre, Informacion, Status) VALUES (?, ?, upper(?), upper(?), upper(?), upper(?), '1')";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isssss", $param_numero, $param_capacidad, $param_tipo, $param_sede, $param_torre, $param_informacion);
            
            $param_numero = $numero;
            $param_capacidad = $capacidad;

            if($tipo == "OTRO"){
                $param_tipo = $tipo2;
            }else{
                $param_tipo = $tipo;
            }

            $param_sede = $sede;

            if($torre == "OTRA"){
                $param_torre = $torre2;
            }else{
                $param_torre = $torre;
            }

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
                <a>Crear ambiente de formación</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">    

                    <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                        <label>Número de ambiente</label>
                        <input type="text" name="Numero" class="form-control validanumericos" style="text-transform: uppercase;" value="<?php echo $numero; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $numero_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($capacidad_err)) ? 'has-error' : ''; ?>">
                        <label>Capacidad de aprendices</label>
                        <input type="text" name="Capacidad" class="form-control validanumericos" style="text-transform: uppercase;" value="<?php echo $capacidad; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $capacidad_err;?></span>
                    </div>


                    <div class="form-group <?php echo (!empty($tipo_err)) ? 'has-error' : ''; ?>">
                        <label>Tipo de ambiente</label>
                        <select name="Tipo" id="tipo" onchange="cambioSelect2();" style="font-size:16px;color:#495057;" class="form-control" style="text-transform: uppercase;">
                            <option><?php echo $tipo; ?></option>
                            <option>COMPUTO</option>
                            <option>LABORATORIO</option>
                            <option>OTRO</option>
                        </select>
                        <span style="color: #a94442" class="help-block"><?php echo $tipo_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($tipo_err2)) ? 'has-error' : ''; ?>">
                        <label id="otraTipo" style="display:none">¿Cúal?</label>
                        <input type="text" id="otraTipo2" name="OtroTipo" class="form-control" style="text-transform: uppercase; display:none;" value="<?php echo $tipo2; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $tipo_err2;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($sede_err)) ? 'has-error' : ''; ?>">
                        <label>Sede</label>
                        <select name="Sede" style="font-size:16px;color:#495057;" class="form-control" style="text-transform: uppercase;">
                            <option><?php echo $sede; ?></option>
                            <option>CENTRO DE DISEÑO Y METROLOGIA</option>
                            <option>UNIVERSIDAD ECCI</option>                       
                        </select>
                        <span style="color: #a94442" class="help-block"><?php echo $sede_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($torre_err)) ? 'has-error' : ''; ?>">
                        <label>Torre</label>
                        <select name="Torre" id="torre" onchange="cambioSelect();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">
                            <option><?php echo $torre; ?></option>
                            <option>ORIENTAL</option>
                            <option>OCCIDENTAL</option>
                            <option>OTRA</option>
                        </select>
                        <span style="color: #a94442" class="help-block"><?php echo $torre_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($torre_err2)) ? 'has-error' : ''; ?>">
                        <label id="otraTorre" style="display:none">¿Cúal?</label>
                        <input type="text" id="otraTorre2" name="OtraTorre" class="form-control" style="text-transform: uppercase; display:none;" value="<?php echo $torre2; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $torre_err2;?></span>
                    </div>


                    <div class="form-group">
                        <label>Información adicional</label>
                        <textarea name="Informacion" class="form-control" style="text-transform: uppercase;" value="<?php echo $informacion; ?>"></textarea>
                    </div>
                    <a class="btnr2" href="gestionar.php">Cancelar</a>
                    <input type="submit" value="Crear" class="btnr">

                </form>
            </div>
        </div>
    </div>
    </div>
    </div>

    <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script src="select-dinamico2.js" type="text/javascript"></script>

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