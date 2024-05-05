<?php
include "../../../extra/conexion.php"; 
 
$programa = $NumFicha = $competencia = $rap = $numAmbiente = $dia = $horarioI = $horarioF= "";
$programa_err = $NumFicha_err = $competencia_err = $rap_err =  $numAmbiente_err = $dia_err = $horarioI_err = $horarioF_err= "";

if(isset($_GET["Instructor"]) && !empty(trim($_GET["Instructor"]))){
    $instructor = trim($_GET["Instructor"]);
}
 
if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    $input_programa = trim($_POST["programa"]);
    if(empty($input_programa)){
        $programa_err = "<small>Por favor ingrese la programa de formación para el evento.</small>";     
    }else{
        $programa = $input_programa;
    }

    @$input_NumFicha = trim($_POST["NumFicha"]);
    if(empty($input_NumFicha)){
        $NumFicha_err = "<small>Por favor ingrese el número de la ficha para el evento.</small>";
    }else{
        $NumFicha = $input_NumFicha;
    }

    @$input_competencia = trim($_POST["Competencia"]);
    if(empty($input_competencia)){
        $competencia_err = "<small>Por favor ingrese la competencia del evento.</small>";
    }else{
        $competencia = $input_competencia;
    }

    @$input_rap = trim($_POST["Rap"]);
    if(empty($input_rap)){
        $rap_err = "<small>Por favor ingrese el resultado de aprendizaje para el evento.</small>";     
    }else{
        $rap = $input_rap;
    }
    
    @$input_numAmbiente = trim($_POST["NumAmbiente"]);
    if(empty($input_numAmbiente)){
        $numAmbiente_err = "<small>Por favor ingrese el número de ambiente para el evento.</small>";
    }else{
        $numAmbiente = $input_numAmbiente;
    }

    $input_dia = trim($_POST["Dia"]);
    if(empty($input_dia)){
        $dia_err = "<small>Por favor ingrese el día para el evento.</small>";     
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
    
    $instructor2 = $_POST['instructor2'];
    $id = $_POST['IdPrincipal'];
    $idEventoFicha = $_POST['IdPrincipalFicha'];

    if(empty($programa_err) && empty($NumFicha_err) && empty($competencia_err) && empty($rap_err) && empty($numAmbiente_err) && empty($dia_err) && empty($horarioI_err) && empty($horarioF_err) && empty($fase_err) && empty($trimestre_err)){
        
        $sqlEventos = "SELECT * FROM eventoinstructor where (CabeceraInstructor <> '$instructor' and Dia = '$dia' and NumeroAmbiente = '$numAmbiente') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";  
        $resultado_eventos = mysqli_query($link, $sqlEventos);

            if(mysqli_num_rows($resultado_eventos) > 0){
                echo "<script>alert('Ya existe un evento de este tipo con otro instructor, se recomienda cambiar el ambiente de formación y/o el dia asignado.'); window.location='update?IdPrincipal=$id&Instructor=$instructor';</script>";
                
            }else{ 
                
                $sqlEventos2 = "SELECT * FROM eventoinstructor where (CabeceraInstructor <> '$instructor' and Dia = '$dia' and NumeroFicha = '$NumFicha') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";  
                $resultado_eventos2 = mysqli_query($link, $sqlEventos2);

                if(mysqli_num_rows($resultado_eventos2) > 0){
                    echo "<script>alert('Ya existe un evento de este tipo con otro instructor, se recomienda cambiar la ficha y/o el dia asignado.'); window.location='update?IdPrincipal=$id&Instructor=$instructor';</script>";
                    
                }else{   
                
                    $sqlEventos4 = "SELECT * FROM eventoinstructor where (IdPrincipal <> '$id' and CabeceraInstructor = '$instructor' and Dia = '$dia') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";
                    $resultado_eventos4 = mysqli_query($link, $sqlEventos4);

                    if(mysqli_num_rows($resultado_eventos4) > 0){
                        echo "<script>alert('Ya existe un evento de este tipo para el instructor, se recomienda cambiar el dia y/o horario.'); window.location='update?IdPrincipal=$id&Instructor=$instructor';</script>";

                    }else{

                        $sql = "UPDATE eventoinstructor SET Programa=upper(?), NumeroFicha=upper(?), Competencia=upper(?), Rap=upper(?), NumeroAmbiente=upper(?), Dia=upper(?), HoraInicio=?, HoraFin=? WHERE IdPrincipal=?";
                
                        if($stmt = mysqli_prepare($link, $sql)){
                            mysqli_stmt_bind_param($stmt, "ssssssssi", $param_programa, $param_NumFicha, $param_competencia, $param_rap, $param_numAmbiente, $param_dia,  $param_horarioI, $param_horarioF, $param_id);
                            
                            $param_programa = $programa;
                            $param_NumFicha = $NumFicha;
                            $param_competencia = $competencia;
                            $param_rap = $rap;
                            $param_numAmbiente = $numAmbiente;         
                            $param_dia = $dia;
                            $param_horarioI = $horarioI;
                            $param_horarioF = $horarioF;
                            $param_id = $id;

                            $sqlEventoFicha = "UPDATE eventoficha SET Competencia=upper('$competencia'), Rap=upper('$rap'), NumeroAmbiente=upper('$numAmbiente'), Dia=upper('$dia'), HoraInicio='$horarioI', HoraFin='$horarioF' WHERE IdPrincipal='$idEventoFicha'";
                            $queryFicha = $link->query($sqlEventoFicha);

                            if(mysqli_stmt_execute($stmt) && $queryFicha != null){
                                header("location: gestionar?Instructor=$instructor2");
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
    
    //mysqli_close($link);
}else{
    if(isset($_GET["IdPrincipal"]) && !empty(trim($_GET["IdPrincipal"]))){
        $id =  trim($_GET["IdPrincipal"]);
        
        $sql = "SELECT NumeroFicha, Programa, Competencia, Rap, NumeroAmbiente, Dia, substring(HoraInicio, 1, 5), substring(HoraFin, 1, 5) FROM eventoinstructor WHERE IdPrincipal = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $programa = $row["Programa"];
                    $NumeroFicha = $row["NumeroFicha"]; 
                    $competencia2 = $row["Competencia"];                  
                    $rap = $row["Rap"];
                    $numAmbiente= $row["NumeroAmbiente"];
                    $dia= $row["Dia"];
                    $horarioI= $row["substring(HoraInicio, 1, 5)"];
                    $horarioF= $row["substring(HoraFin, 1, 5)"];
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
                <a>Actualizar evento-instructor</a>
            </div>

            <div class="contenido">

            <form action="" method="post">    
		
        <div class="form-group">
                <label>Programa de formación</label>
                <select name="programa" id="crearEvento" onchange="ShowPrograma();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                <?php
                    include "../../../extra/conexion.php";
                    
                    $sql = "SELECT Nombre from programas where status = 1";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<option>$programa</option>";
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
        
        <div class="form-group">
            <label>Número de la ficha</label>
            <select name="NumFicha" id="crearEvento1" onchange="ShowFichas();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
            <?php
                include "../../../extra/conexion.php"; 

                $sql = "SELECT Numero from fichas where status = 1 and Programa = '$programa'";

                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        echo "<option>$NumeroFicha</option>";
                        while($row = mysqli_fetch_array($result)){
                        echo "<option>".$row['Numero']."</option>";          
                        }                                        
                    }

                }else{
                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                }
                mysqli_close($link); 
            ?>
            </select>
            <span style="color: #a94442" class="help-block"><?php echo $NumFicha_err;?></span>
        </div> 

        <div class="form-group">
            <label>Competencia</label>
            <select name="Competencia" class="competencia" id="competencia" onchange="ShowCompetencia();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
            <?php
                include "../../../extra/conexion.php"; 

                $sql = "SELECT Descripcion from competencias where status = 1 and IdPrincipalProgramaCompetencia = (SELECT IdPrograma from programas where Nombre = '$programa')";

                if($result = mysqli_query($link, $sql)){
                    if(mysqli_num_rows($result) > 0){
                        echo "<option>$competencia2</option>";
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

                $sql = "SELECT Descripcion from raps where status = 1 and IdPrincipalCompetencias = (SELECT IdCompetencia from competencias where Descripcion = '$competencia2')";

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
            <select name="NumAmbiente" id="crearEvento3" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control" >                                                
            <?php
                include "../../../extra/conexion.php"; 

                $sql = "SELECT Numero, Tipo, Torre from ambientes where status = 1 and Capacidad <= (Select Aprendices from fichas where Numero = '$NumeroFicha')";

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
                <option><?php echo($dia); ?></option>
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
            <label>Horario</label>
            <div class="horas" style="display: flex;">
                <input type="time" name="HorarioI" class="form-control" style="text-transform: uppercase; width: 50%;" placeholder="HH : MM  (Inicio)" value="<?php echo $horarioI; ?>">&nbsp&nbsp A &nbsp&nbsp<input type="time" name="HorarioF" class="form-control" style="text-transform: uppercase; width: 50%;" placeholder="HH : MM  (Fin)" value="<?php echo $horarioF; ?>">  
            </div>
            <span style="color: #a94442"  class="help-block"><?php echo $horarioI_err;?></span>
            <span style="color: #a94442"  class="help-block"><?php echo $horarioF_err;?></span>
        </div>
                        <?php
                            include "../../../extra/conexion.php";

                            $sqlBuscaEventoFicha = "SELECT IdPrincipal FROM eventoficha WHERE CabeceraFicha='$NumeroFicha' and Dia='$dia' and HoraInicio='$horarioI' and HoraFin='$horarioF'";
                            $resultEventoFicha = mysqli_query($link, $sqlBuscaEventoFicha);
                                if(mysqli_num_rows($resultEventoFicha) > 0){ 
                                    while($row = mysqli_fetch_array($resultEventoFicha)){
                                        echo "<input type='hidden' name='IdPrincipalFicha' value='".$row['IdPrincipal']."'>";
                                    }                
                                }                             
                        ?>

                        <input type="hidden" name="IdPrincipal" value="<?php echo($id);?>">
                        <input type="hidden" name="instructor2" value="<?php echo($instructor);?>">
                        <a class="btnr2" href="gestionar.php?Instructor=<?php echo ($instructor);?>">Cancelar</a>
                        <input type="submit" value="Actualizar" class="btnr" >
                  
                        </form>
                    </div>
                    </div>
            </div>
    </div>
</div>

<script type="text/javascript" src="select-dinamico.js"></script>
<script type="text/javascript" src="select-ambiente.js"></script>

<!-- Liberia para modificar las etiquetas select que se encuentren en el documento actual--> 
<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
$("#crearEvento").select2({
  width: '100%'
});

$("#crearEvento1").select2({
  width: '100%'
});

$("#crearEvento2").select2({
  width: '100%'
});

$("#crearEvento3").select2({
  width: '100%'
});

$("#crearEvento4").select2({
  width: '100%'
});

$("#competencia").select2({
  width: '100%'
});
</script>

</body>
</html>