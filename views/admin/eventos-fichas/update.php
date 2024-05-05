<?php
include "../../../extra/conexion.php"; 
 
$fase = $competencia = $rap = $numAmbiente = $dia = $horarioI = $horarioF= $instructor ="";
$fase_err = $competencia_err = $rap_err =  $numAmbiente_err = $dia_err = $horarioI_err = $horarioF_err = $instructor_err = "";

if (($_GET["ficha"] == '') || ($_GET["trimestre"] == '')) {
    echo "<script>window.location='../principal/';</script>";
};

if(isset($_GET['ficha']) && !empty(trim($_GET["ficha"])) && !empty(trim($_GET["trimestre"]))){
    $ficha = trim($_GET["ficha"]);
    $trimestre = trim($_GET["trimestre"]);
}
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    $input_fase = trim($_POST["Fase"]);
    if(empty($input_fase)){
        $fase_err = "<small>Por favor ingrese la fase del evento.</small>";     
    }else{
        $fase = $input_fase;
    }


    $input_competencia = trim($_POST["Competencia"]);
    if(empty($input_competencia)){
        $competencia_err = "<small>Por favor ingrese la competencia del evento.</small>";
    }else{
        $competencia = $input_competencia;
    }

    @$input_rap = trim($_POST["Rap"]);
    if(empty($input_rap)){
        $rap_err = "<small>Por favor ingrese el resultado de aprendizaje del evento.</small>";     
    }else{
        $rap = $input_rap;
    }
    
    $input_numAmbiente = trim($_POST["NumAmbiente"]);
    if(empty($input_numAmbiente)){
        $numAmbiente_err = "<small>Por favor ingrese el número de ambiente del evento.</small>";
    }else{
        $numAmbiente = $input_numAmbiente;
    }

    $input_dia = trim($_POST["Dia"]);
    if(empty($input_dia)){
        $dia_err = "<small>Por favor ingrese el día del evento.</small>";     
    }else{
        $dia = $input_dia;
    }

    $input_horarioI = trim($_POST["HorarioI"]);
    if(empty($input_horarioI)){
        $horarioI_err = "<small>Por favor ingrese la hora de inicio para el evento.</small>";     
    }elseif(!filter_var($input_horarioI, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]+:+[0-9]/")))){
        $horarioI_err = "<small>Por favor ingrese un formato correcto para la hora inicio del evento.</small>"; 
    }else{
        $horarioI = $input_horarioI;
    }
 
    $input_horarioF = trim($_POST["HorarioF"]);
    if(empty($input_horarioF)){
        $horarioF_err = "<small>Por favor ingrese la hora fin para el evento.</small>";     
    }elseif(!filter_var($input_horarioF, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]+:+[0-9]/")))){
        $horarioF_err = "<small>Por favor ingrese un formato correcto para la hora fin del evento.</small>"; 
    }else{
        $horarioF = $input_horarioF;
    }

    $input_instructor = trim($_POST["Instructor"]);
    if(empty($input_instructor )){
        $instructor_err = "<small>Por favor ingrese el instructor del evento.</small>";     
    }else{
        $instructor = $input_instructor;
    }

    $id=$_POST['IdPrincipal'];
    $ficha2 = $_POST['ficha2'];
    $trimestre2 = $_POST['trimestre2'];
    $idPrincipalInstructor = $_POST['IdPrincipalInstructor'];

    if(empty($fase_err) && empty($competencia_err)  && empty($rap_err) && empty($numAmbiente_err) && empty($dia_err) && empty($horarioI_err) && empty($horarioF_err) && empty($instructor_err)){

        $sqlEventos1 = "SELECT * FROM eventoficha where (CabeceraFicha <> '$ficha' and trimestreFicha = '$trimestre' and Dia = '$dia' and NumeroAmbiente = '$numAmbiente' and Instructor = '$instructor') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";  
            $resultado_eventos1 = mysqli_query($link, $sqlEventos1);

            if(mysqli_num_rows($resultado_eventos1) > 0){
                echo "<script>alert('Ya existe un evento de este tipo en otra ficha, se recomienda cambiar el ambiente e instructor.'); window.location='update?IdPrincipal=$id&ficha=$ficha2&trimestre=$trimestre2';</script>";
            }else{
                $sqlEventos2 = "SELECT * FROM eventoficha where (CabeceraFicha <> '$ficha' and trimestreFicha = '$trimestre' and Dia = '$dia' and NumeroAmbiente = '$numAmbiente') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";
                $resultado_eventos2 = mysqli_query($link, $sqlEventos2);

                if(mysqli_num_rows($resultado_eventos2) > 0){
                    echo "<script>alert('Ya existe un evento de este tipo en otra ficha, se recomienda cambiar el ambiente.'); window.location='update?IdPrincipal=$id&ficha=$ficha2&trimestre=$trimestre2';</script>";
                }else{
                    $sqlEventos3 = "SELECT * FROM eventoficha where (CabeceraFicha <> '$ficha' and trimestreFicha = '$trimestre' and Dia = '$dia' and Instructor = '$instructor') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";
                    $resultado_eventos3 = mysqli_query($link, $sqlEventos3);

                    if(mysqli_num_rows($resultado_eventos3) > 0){
                        echo "<script>alert('Ya existe un evento de este tipo en otra ficha, se recomienda cambiar el instructor.'); window.location='update?IdPrincipal=$id&ficha=$ficha2&trimestre=$trimestre2';</script>";
                    }else{
                        $sqlEventos4 = "SELECT * FROM eventoficha where (IdPrincipal <> '$id' and CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre' and Dia = '$dia') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";
                        $resultado_eventos4 = mysqli_query($link, $sqlEventos4);

                        if(mysqli_num_rows($resultado_eventos4) > 0){
                            echo "<script>alert('Ya existe un evento de este tipo para la ficha y trimestre seleccionado, se recomienda cambiar el dia y/o horario.'); window.location='update?IdPrincipal=$id&ficha=$ficha2&trimestre=$trimestre2';</script>";
                            
                        }else{
                            $sql = "UPDATE eventoficha SET Fase=upper(?), Competencia=upper(?), Rap=upper(?), NumeroAmbiente=upper(?), Dia=upper(?), HoraInicio=?, HoraFin=?, Instructor=upper(?) WHERE IdPrincipal=?";
         
                            if($stmt = mysqli_prepare($link, $sql)){
                                mysqli_stmt_bind_param($stmt, "ssssssssi", $param_fase, $param_competencia, $param_rap, $param_numAmbiente, $param_dia, $param_horarioI, $param_horarioF, $param_instructor, $param_id);
                                
                                $param_fase = $fase;
                                $param_competencia = $competencia;
                                $param_rap = $rap;
                                $param_numAmbiente = $numAmbiente;         
                                $param_dia = $dia;
                                $param_horarioI = $horarioI;
                                $param_horarioF = $horarioF;
                                $param_instructor = $instructor;
                                $param_id = $id;

                                $sqlInstructor = "UPDATE eventoinstructor SET Competencia=upper('$competencia'), Rap=upper('$rap'), NumeroAmbiente=upper('$numAmbiente'), Dia=upper('$dia'), HoraInicio='$horarioI', HoraFin='$horarioF', CabeceraInstructor=upper('$instructor') WHERE IdPrincipal = '$idPrincipalInstructor'";
                                $queryInstructor = $link->query($sqlInstructor);
                                
                                if(mysqli_stmt_execute($stmt) && $queryInstructor != null){
                                    header("location: gestionar?ficha=$ficha2&trimestre=$trimestre2");
                                    exit();
                                } else{
                                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
                                }
                            }
                            
                            mysqli_stmt_close($stmt);
                        }
                    }
                }
            }
    }
    
    //mysqli_close($link);
}else{
    if(isset($_GET["IdPrincipal"]) && !empty(trim($_GET["IdPrincipal"]))){
        $id =  trim($_GET["IdPrincipal"]);
        
        $sql = "SELECT Fase, Competencia, Rap, NumeroAmbiente, Dia, substring(HoraInicio, 1, 5), substring(HoraFin, 1, 5), Instructor FROM eventoficha WHERE IdPrincipal = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $fase = $row["Fase"];
                    $competencia = $row["Competencia"];                   
                    $rap = $row["Rap"];
                    $numAmbiente= $row["NumeroAmbiente"];
                    $dia= $row["Dia"];
                    $horarioI = $row["substring(HoraInicio, 1, 5)"];
                    $horarioF= $row["substring(HoraFin, 1, 5)"];
                    $instructor= $row["Instructor"];
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
    }else{
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
    <div class='info-busqueda'>
        <div class='row mb-2' id="cont-bloque-corto" style='margin-left:20%;margin-right:0; max-width:800px'>
            <div class='msgalerta'>
                <div class='alert alert-success'> 
                <a type='button' class='close' data-dismiss='alert' aria-hidden='true'>    ×</a>                               
                    <i class='fas fa-info-circle'></i>  Recuerde que el horario se debe manejar en sistema de 24 horas. Ejemplo: 12:00 A 18:00                         
                </div>                           
            </div>                                  
        </div>    
    </div>          
    <div class="panel" id="cont-bloque-corto2">
        <div class="titulo">
                <a>Actualizar evento-ficha</a>
            </div>

            <div class="contenido">

            <form action="" method="post">    
		
        <div class="form-group">
                <label>Fase</label>
                <select name="Fase" id="crearEvento" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                    <option><?php echo $fase; ?></option>
                    <option>ANALISIS</option>
                    <option>PLANEACION</option>
                    <option>EJECUCION</option>
                    <option>EVALUACION</option>
                </select>
                <span style="color: #a94442" class="help-block"><?php echo $fase_err;?></span>
            </div>
        
        <div class="form-group">
            <label>Competencia</label>
            <select name="Competencia" id="crearEvento1" onchange="ShowSelected();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
            <?php
                include "../../../extra/conexion.php"; 

                $sqlPrograma = "SELECT Programa from fichas where Numero = '$ficha'";
                if($result = mysqli_query($link, $sqlPrograma)){

                    while($row = mysqli_fetch_array($result)){
                        $programa = $row['Programa'];             
                    }      
                }

                $sql = "SELECT Descripcion from competencias where status = 1 and IdPrincipalProgramaCompetencia = (SELECT IdPrograma from programas where Nombre = '$programa')";

                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        echo "<option>$competencia</option>";
                        while($row = mysqli_fetch_array($result)){
                        echo "<option>".$row['Descripcion']."</option>";          
                        }
                                        
                    }

                }else{
                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                }
                mysqli_close($link); 
            ?>
            </select>
            <span style="color: #a94442"  class="help-block"><?php echo $competencia_err;?></span>
        </div> 
        
        <div class="form-group">
            <label>Resultado de aprendizaje</label>
            <select name="Rap" id="crearEvento2" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">  
            <?php
                include "../../../extra/conexion.php"; 

                $sql = "SELECT Descripcion from raps where status = 1 and IdPrincipalCompetencias = (SELECT IdCompetencia from competencias where Descripcion = '$competencia')";

                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        echo "<option>$rap</option>";
                        while($row = mysqli_fetch_array($result)){
                        echo "<option>".$row['Descripcion']."</option>";          
                        }
                                        
                    }

                }else{
                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                }
                mysqli_close($link); 
            ?>                                              
            </select>
            <span style="color: #a94442" class="help-block"><?php echo $rap_err;?></span>
        </div>

        <div class="form-group">
            <label>Número de ambiente</label>
            <select name="NumAmbiente" id="crearEvento3" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
            <?php
                include "../../../extra/conexion.php"; 

                $sql = "SELECT Numero, Tipo, Torre from ambientes where status = 1 and Capacidad <= (Select Aprendices from fichas where Numero = '$ficha')";

                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        echo "<option>$numAmbiente</option>";
                        while($row = mysqli_fetch_array($result)){
                        echo "<option>".$row['Numero'].' - Sala de '.$row['Tipo'].' Torre '.$row['Torre']."</option>";          
                        }                    
                    }
                }else{
                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                }
                mysqli_close($link); 
            ?>
            </select>

            <span style="color: #a94442"  class="help-block"><?php echo $numAmbiente_err;?></span>
        </div>   

        <div class="form-group">
            <label>Dia</label>

            <select name="Dia" id="crearEvento4" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                <option><?php echo $dia; ?></option>
                <option>LUNES</option>
                <option>MARTES</option>
                <option>MIERCOLES</option>
                <option>JUEVES</option>
                <option>VIERNES</option>
                <option>SABADO</option>
            </select>

            <span style="color: #a94442"  class="help-block"><?php echo $dia_err;?></span>
        </div> 

        <div class="form-group">
            <label>Instructor</label>

            <select name="Instructor" id="crearEvento5" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
            <?php
                include "../../../extra/conexion.php"; 

                $sql = "SELECT Nombre, Especialidad from instructores where status = 1";

                        if($resultado = mysqli_query($link, $sql)){
                            if(mysqli_num_rows($result) > 0){
                                echo "<option>".$instructor."</option>";
                                while($row = mysqli_fetch_array($resultado)){

                                    $instructor = $row['Nombre']." (".$row['Especialidad'].")"; 
                                    $nombreInstructor = $row['Nombre'];
                                    $contador = 0;

                                    $sqlhorasInstructor = "SELECT abs(hour(HoraInicio) - hour(HoraFin)), minute(HoraInicio) + minute(HoraFin), hour(HoraInicio), hour(HoraFin) FROM eventoinstructor where CabeceraInstructor = '$nombreInstructor'";
                    
                                    $result = mysqli_query($link, $sqlhorasInstructor);
                                    if(mysqli_num_rows($result) > 0){
                                        while($row = mysqli_fetch_array($result)){ 
                        
                                            $sumaHoras = $row['abs(hour(HoraInicio) - hour(HoraFin))'];
                                            $sumaMinutos = $row['minute(HoraInicio) + minute(HoraFin)'];
                                                                                                                                
                                            $contador += $sumaHoras+($sumaMinutos/60);
                                        }
                                        if($contador < 40){
                                            echo "<option value='".$nombreInstructor."'>".$instructor."</option>";
                                        }
                                    }else{
                                        echo "<option value='".$nombreInstructor."'>".$instructor."</option>";
                                    }         
                                }                             
                            }
                        }else{
                            echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                        }
                        mysqli_close($link); 
            ?>
            </select>

            <span style="color: #a94442"  class="help-block"><?php echo $instructor_err;?></span>
        </div> 

        <div class="form-group">
            <label>Horario</label>

            <div class="horas" style="display: flex;">
                <input type="time" name="HorarioI" class="form-control" style="text-transform: uppercase; width: 50%;" placeholder="HH : MM  (Inicio)" value="<?php echo $horarioI; ?>">&nbsp&nbsp A &nbsp&nbsp<input type="time" name="HorarioF" class="form-control" style="text-transform: uppercase; width: 50%;" placeholder="HH : MM  (Fin)" value="<?php echo $horarioF; ?>">  
            </div> 

            <span style="color: #a94442"  class="help-block"><?php echo $horarioI_err;?></span>
            <span style="color: #a94442"  class="help-block"><?php echo $horarioF_err;?></span>
        </div>         

        
                        <?php
                            include "../../../extra/conexion.php";

                            $sqlBuscaEventoInstructor = "SELECT IdPrincipal FROM eventoinstructor WHERE NumeroFicha='$ficha' and Dia='$dia' and HoraInicio='$horarioI' and HoraFin='$horarioF'";
                            $resultEventoInstructor = mysqli_query($link, $sqlBuscaEventoInstructor);
                                if(mysqli_num_rows($resultEventoInstructor) > 0){ 
                                    while($row = mysqli_fetch_array($resultEventoInstructor)){
                                        echo "<input type='hidden' name='IdPrincipalInstructor' value='".$row['IdPrincipal']."'>";
                                    }                
                                }                             
                        ?>

                        <input type="hidden" name="ficha2" value="<?php echo($ficha);?>">
                        <input type="hidden" name="trimestre2" value="<?php echo($trimestre);?>">
                        <input type="hidden" name="IdPrincipal" value="<?php echo($id);?>">
                        <a class="btnr2" href="gestionar.php?ficha=<?php echo ($ficha);?>&trimestre=<?php echo ($trimestre);?>">Cancelar</a>
                        <input type="submit" value="Actualizar" class="btnr" >
                  
                        </form>
                    </div>
                    </div>
            </div>
    </div>
</div>

<script src="select-dinamico.js" type='text/javascript'></script>

<!-- Liberia para modificar las etiquetas select que se encuentren en el documento actual--> 
<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
$("#crearEvento").select2({
  width: '100%'
});
</script>
 <script>
$("#crearEvento1").select2({
  width: '100%'
});
</script>
<script>
$("#crearEvento2").select2({
  width: '100%'
});
</script>
<script>
$("#crearEvento3").select2({
  width: '100%'
});
</script>
<script>
$("#crearEvento4").select2({
  width: '100%'
});
</script>
<script>
$("#crearEvento5").select2({
  width: '100%'
});
</script>

</body>
</html>