<?php
include "../../../extra/conexion.php"; 
 
$numero = $programa = $aprendices = $jornada = $inicio = $fin= $informacion = "";
$numero_err = $programa_err = $aprendices_err = $jornada_err = $inicio_err = $fin_err = $informacion_err =  "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $input_numero = trim($_POST["Numero"]);
    if(empty($input_numero)){
        $numero_err = "<small>Por favor ingrese el número de la ficha.</small>";     
    }else{
        $numero = $input_numero;
    }

    $input_programa = trim($_POST["Programa"]);
    if(empty($input_programa)){
        $programa_err = "<small>Por favor ingrese el programa de formación de la ficha.</small>";     
    }else{
        $programa = $input_programa;
    }

    $input_aprendices = trim($_POST["Aprendices"]);
    if(empty($input_aprendices)){
        $aprendices_err = "<small>Por favor ingrese la cantidad de aprendices de la ficha.</small>";     
    }else{
        $aprendices = $input_aprendices;
    }
    
    $input_jornada = trim($_POST["Jornada"]);
    if(empty($input_jornada)){
        $jornada_err = "<small>Por favor ingrese la jornada de la ficha.</small>";     
    }else{
        $jornada = $input_jornada;
    }

    $input_inicio = trim($_POST["Inicio"]);
    if(empty($input_inicio)){
        $inicio_err = "<small>Por favor ingrese la fecha de inicio de la ficha.</small>";     
    }else{
        $inicio = $input_inicio;
    }

    $input_fin = trim($_POST["Fin"]);
    if(empty($input_fin)){
        $fin_err = "<small>Por favor ingrese la fecha de finalización de la ficha.</small>";     
    }else{
        $fin = $input_fin;
    }

    $input_informacion = trim($_POST["Informacion"]);
    if(empty($input_informacion)){
        $informacion_err = "Por favor ingrese la información adicional de la ficha.</small>";     
    }else{
        $informacion = $input_informacion;
    }
    
    if(empty($numero_err) && empty($programa_err) && empty($aprendices_err) && empty($jornada_err) && empty($inicio_err) && empty($fin_err)){
        $sql = "INSERT INTO fichas (Numero, Programa, Aprendices, Jornada, Inicio, Fin, Informacion, Status) VALUES (upper(?), upper(?), ?, upper(?), ?, ?, upper(?), '1')";
         
        if($stmt = mysqli_prepare($link, $sql)){

            mysqli_stmt_bind_param($stmt, "ssissss", $param_numero, $param_programa, $param_aprendices, $param_jornada, $param_inicio, $param_fin, $param_informacion);
            
            $param_numero = $numero;
            $param_programa = $programa;
            $param_aprendices = $aprendices;
            $param_jornada = $jornada;
            $param_inicio = $inicio;
            $param_fin = $fin;
            $param_informacion = $informacion;
            
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
                <a>Crear ficha</a>
            </div>

            <div class="contenido">        

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  

                    <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                        <label>Número de la ficha</label>
                        <input type="text" name="Numero" class="form-control" style="text-transform: uppercase;" value="<?php echo $numero; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $numero_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($programa_err)) ? 'has-error' : ''; ?>">
                        <label>Programa de formación</label>
                        <select name="Programa" style="font-size:16px;color:#495057;" class="form-control" style="text-transform: uppercase;">
                            <option></option>
                            <option disabled class="font-weight-bold">TECNOLOGOS</option>
                            <?php
                                include "../../../extra/conexion.php"; 

                                $sql = "SELECT Nombre from programas where Tipo='TECNOLOGO' and status = 1";

                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){ 
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option>".$row['Nombre']."</option>";       
                                        }                
                                    }                                
                                }else{
                                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                }
                                mysqli_close($link); 
                            ?>  
                            <option disabled class="font-weight-bold">TECNICOS</option>
                            <?php
                                include "../../../extra/conexion.php"; 

                                $sql = "SELECT Nombre from programas where Tipo='TECNICO' and status = 1";

                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){ 
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option>".$row['Nombre']."</option>";       
                                        }                
                                    }                                
                                }else{
                                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                }
                                mysqli_close($link); 
                            ?>  
                        </select>
                        <span style="color: #a94442" class="help-block"><?php echo $programa_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($aprendices_err)) ? 'has-error' : ''; ?>">
                        <label>Cantidad de aprendices</label>
                        <input type="text" name="Aprendices" class="form-control validanumericos" style="text-transform: uppercase;" value="<?php echo $aprendices; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $aprendices_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($jornada_err)) ? 'has-error' : ''; ?>">
                        <label>Jornada</label>
                        <select name="Jornada" style="font-size:16px;color:#495057;" class="form-control" style="text-transform: uppercase;"
                            value="<?php echo $jornada; ?>">
                            <option> </option>
                            <option>MAÑANA</option>
                            <option>TARDE</option>
                            <option>NOCTURNA</option>
                            <option>MIXTA</option>
                        </select>
                        <span style="color: #a94442" class="help-block"><?php echo $jornada_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($inicio_err)) ? 'has-error' : ''; ?>">
                        <label>Fecha de inicio</label>
                        <input type="date" name="Inicio" class="form-control" style="text-transform: uppercase;" value="<?php echo $inicio; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $inicio_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($fin_err)) ? 'has-error' : ''; ?>">
                    <label>Fecha fin</label>
                    <input type="date" name="Fin" class="form-control" style="text-transform: uppercase;" value="<?php echo $fin; ?>">
                    <span style="color: #a94442" class="help-block"><?php echo $fin_err;?></span>
                    </div>	

                    <div class="form-group">
                        <label>Información adicional</label>
                        <textarea name="Informacion" class="form-control" style="text-transform: uppercase;"
                            value="<?php echo $informacion; ?>"></textarea>
                    </div>
                    <a class="btnr2" href="gestionar.php">Cancelar</a>
                    <input type="submit" value="Crear" class="btnr">
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
    <script src="main.js"></script>

</body>

</html>