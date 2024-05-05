<?php
include "../../../extra/conexion.php"; 
 
$programa = $NumFicha = $competencia = $rap = $numAmbiente = $dia = $horarioI = $horarioF= $fase = $trimestre = "";
$programa_err = $NumFicha_err = $competencia_err = $rap_err =  $numAmbiente_err = $dia_err = $horarioI_err = $horarioF_err= $fase_err = $trimestre_err = "";

if(isset($_GET["Instructor"]) && !empty(trim($_GET["Instructor"]))){
    $instructor = trim($_GET["Instructor"]);
}

if ($_GET["Instructor"] == '')  {
    echo "<script>window.location='../principal/';</script>";
};

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
        $competencia_err = "<small>Por favor ingrese la competencia para el evento.</small>";
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

    $input_fase = trim($_POST["Fase"]);
    if(empty($input_fase)){
        $fase_err = "<small>Por favor ingrese la fase para el evento de la ficha.</small>";     
    }else{
        $fase = $input_fase;
    }

    $input_trimestre = trim($_POST["TrimestreFicha"]);
    if(empty($input_trimestre)){
        $trimestre_err = "<small>Por favor ingrese el trimestre para el evento de la ficha.</small>";     
    }else{
        $trimestre = $input_trimestre;
    }
    
    $instructor2 = $_POST['instructor2'];

    if(empty($programa_err) && empty($NumFicha_err) && empty($competencia_err) && empty($rap_err) && empty($numAmbiente_err) && empty($dia_err) && empty($horarioI_err) && empty($horarioF_err) && empty($fase_err) && empty($trimestre_err)){
        
        $sqlEventos = "SELECT * FROM eventoinstructor where (CabeceraInstructor <> '$instructor' and Dia = '$dia' and NumeroAmbiente = '$numAmbiente') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";  
        $resultado_eventos = mysqli_query($link, $sqlEventos);

            if(mysqli_num_rows($resultado_eventos) > 0){
                echo "<script>alert('Ya existe un evento de este tipo con otro instructor, se recomienda cambiar el ambiente de formación y/o el dia asignado.'); window.location='crear?Instructor=$instructor';</script>";
                
            }else{   

                $sqlEventos2 = "SELECT * FROM eventoinstructor where (CabeceraInstructor <> '$instructor' and Dia = '$dia' and NumeroFicha = '$NumFicha') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";  
                $resultado_eventos2 = mysqli_query($link, $sqlEventos2);

                if(mysqli_num_rows($resultado_eventos2) > 0){
                    echo "<script>alert('Ya existe un evento de este tipo con otro instructor, se recomienda cambiar la ficha y/o el dia asignado.'); window.location='crear?Instructor=$instructor';</script>";
                    
                }else{   

                    $sqlEventos4 = "SELECT * FROM eventoinstructor where (CabeceraInstructor = '$instructor' and Dia = '$dia') and (HoraInicio = '$horarioI' or HoraFin = '$horarioF')";
                    $resultado_eventos4 = mysqli_query($link, $sqlEventos4);

                    if(mysqli_num_rows($resultado_eventos4) > 0){
                        echo "<script>alert('Ya existe un evento de este tipo para el instructor, se recomienda cambiar el dia y/o horario.'); window.location='crear?Instructor=$instructor';</script>";

                    }else{

                        $sql = "INSERT INTO eventoinstructor (Programa, NumeroFicha, Competencia, Rap, NumeroAmbiente, Dia, HoraInicio, HoraFin, CabeceraInstructor) VALUES (upper(?), upper(?), upper(?), upper(?), upper(?), upper(?), ?, ?, '$instructor2')";
            
                        if($stmt = mysqli_prepare($link, $sql)){
                            mysqli_stmt_bind_param($stmt, "ssssssss", $param_programa, $param_NumFicha, $param_competencia, $param_rap, $param_numAmbiente, $param_dia, $param_horarioI, $param_horarioF);
                            
                            $param_programa = $programa;
                            $param_NumFicha = $NumFicha;
                            $param_competencia = $competencia;
                            $param_rap = $rap;
                            $param_numAmbiente = $numAmbiente;         
                            $param_dia = $dia;
                            $param_horarioI = $horarioI;
                            $param_horarioF = $horarioF;

                            $sqlFichas = "INSERT INTO eventoficha (Fase, Competencia, Rap, NumeroAmbiente, Dia, HoraInicio, HoraFin, Instructor, trimestreFicha, CabeceraFicha) VALUES (upper('$fase'), upper('$competencia'), upper('$rap'), upper('$numAmbiente'), upper('$dia'), '$horarioI', '$horarioF', upper('$instructor'), '$trimestre', '$NumFicha')";
                            $queryFichas = $link->query($sqlFichas);

                            if(mysqli_stmt_execute($stmt) && $queryFichas != null){
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
}
    //Validación para las horas sobre el evento del instructor
    $sqlhorasInstructor = "SELECT abs(hour(HoraInicio) - hour(HoraFin)), minute(HoraInicio) + minute(HoraFin), hour(HoraInicio), hour(HoraFin) FROM eventoinstructor where CabeceraInstructor = '$instructor'";
    $contador = 0;

    $result = mysqli_query($link, $sqlhorasInstructor);
        if(mysqli_num_rows($result) > 0){
            while($row = mysqli_fetch_array($result)){ 

                $sumaHoras = $row['abs(hour(HoraInicio) - hour(HoraFin))'];
                $sumaMinutos = $row['minute(HoraInicio) + minute(HoraFin)'];
                                                                                                            
                $contador += $sumaHoras+($sumaMinutos/60);
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

    <div class='info-busqueda' id="error-horas" style="display: none;">
        <div class='row mb-2' id="cont-bloque-corto" style='margin-left:20%;margin-right:0; max-width:800px'>
            <div class='msgalerta'>
                <div class='alert alert-danger'>                       
                    <i class='fas fa-info-circle'></i>  El instructor tiene limite de horas establecidas, por favor agregue la cantidad necesaria para cumplir 40                       
                </div>                           
            </div>                                  
        </div>  
    </div> 

    <div class="panel" id="cont-bloque-corto2">
        <div class="titulo">
                <a>Crear evento-instructor</a>
            </div>

            <div class="contenido">

            <form action="" onsubmit="return ValidarHoras();" method="post">    
		
        <div class="form-group">
                <label>Programa de formación</label>
                <select name="programa" id="crearEvento" onchange="ShowPrograma();" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                <?php
                    include "../../../extra/conexion.php";
                    
                    $sql = "SELECT Nombre from programas where status = 1";

                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo "<option></option>";
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
            <select name="NumFicha" id="crearEvento1" onchange="ShowFichas();" style="font-size:16px;color:#495057;text-transform: uppercase;" disabled class="form-control">                                                
            
            </select>
            <span style="color: #a94442" class="help-block"><?php echo $NumFicha_err;?></span>
        </div> 

        <div class="form-group">
            <label>Competencia</label>
            <select name="Competencia" class="competencia" id="competencia" onchange="ShowCompetencia();" style="font-size:16px;color:#495057;text-transform: uppercase;" disabled class="form-control">                                                
            
            </select>
            <span style="color: #a94442"  class="help-block"><?php echo $competencia_err;?></span>
        </div> 
       
        <div class="form-group">
            <label>Resultado de aprendizaje</label>
            <select name="Rap" id="crearEvento2" style="font-size:16px;color:#495057;text-transform: uppercase;" disabled class="form-control">                                                
            
            </select>
            <span style="color: #a94442" class="help-block"><?php echo $rap_err;?></span>
        </div>

        <div class="form-group">
            <label>Número de ambiente</label>
            <select name="NumAmbiente" id="crearEvento3" style="font-size:16px;color:#495057;text-transform: uppercase;" disabled class="form-control" >                                                
           
            </select>

            <span style="color: #a94442"  class="help-block"><?php echo $numAmbiente_err;?></span>
        </div>   

        <div class="form-group">
            <label>Dia</label>

            <select name="Dia" id="crearEvento4" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                <option></option>
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
                <input type="time" name="HorarioI" class="form-control" style="text-transform: uppercase; width: 50%;" placeholder="HH : MM  (Inicio)" value="<?php echo $horario; ?>">&nbsp&nbsp A &nbsp&nbsp<input type="time" name="HorarioF" class="form-control" style="text-transform: uppercase; width: 50%;" placeholder="HH : MM  (Fin)" value="<?php echo $horario; ?>">  
            </div>
            <span style="color: #a94442"  class="help-block"><?php echo $horarioI_err;?></span>
            <br>
            <span style="color: #a94442"  class="help-block"><?php echo $horarioF_err;?></span>
        </div> 

        <hr class="p-1 border-secondary">
        <h6 class="mb-3">* Datos adicionales y obligatorios para el evento de la ficha</h6>      

        <div class="form-group">
            <label>Fase del evento</label>
            <select name="Fase" id="crearEvento5" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                                
                <option></option>
                <option>ANALISIS</option>
                <option>PLANEACION</option>
                <option>EJECUCION</option>
                <option>EVALUACION</option>
            </select>
            <span style="color: #a94442" class="help-block"><?php echo $fase_err;?></span>
        </div> 

        <div class="form-group">
            <label>Trimestre para el evento de la ficha</label>

            <select name="TrimestreFicha" id="crearEvento6" style="font-size:16px;color:#495057;text-transform: uppercase;" class="form-control">                                               
                <option></option>
                <option>I</option>
                <option>II</option>
                <option>III</option>
                <option>IV</option>
                <option>V</option>
                <option>VI</option>
            </select>

            <span style="color: #a94442"  class="help-block"><?php echo $trimestre_err;?></span>
        </div> 

                        <input type="hidden" name="instructor2" value="<?php echo($instructor);?>">
                        <a class="btnr2" href="gestionar.php?Instructor=<?php echo ($instructor);?>">Cancelar</a>
                        <input type="submit" value="Crear" class="btnr" >
                  
                        </form>
                    </div>
                    </div>
            </div>
    </div>
</div>

<script type="text/javascript" src="select-dinamico.js"></script>
<script type="text/javascript" src="select-ambiente.js"></script>

<script>
    $(function ValidarHoras(){
        $("input[type='submit']").click(function(){
            var Horas = "<?php echo $contador ?>";
            if(Horas>=40){
                document.querySelectorAll("#error-horas").forEach(el=> el.style.display="block");
                return false;
            }
        });
    });
</script>

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

$("#crearEvento5").select2({
  width: '100%'
});

$("#crearEvento6").select2({
  width: '100%'
});

$("#competencia").select2({
  width: '100%'
});
</script>

</body>
</html>