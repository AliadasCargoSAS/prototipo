<?php
require_once "../../../extra/conexion.php";
 
$numero = $capacidad = $tipo = $tipo2 = $sede = $torre = $torre2 = $informacion = "";
$numero_err = $capacidad_err = $tipo_err = $tipo_err2 = $sede_err = $torre_err = $torre_err2 = $informacion_err =  "";
 
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = $_POST["IdPrincipal"];

    if($id == ''){
        echo "<script>window.location = 'gestionar';</script>";
    };
       
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
    
    if(empty($numero_err) && empty($capacidad_err)  && empty($tipo_err) && empty($sede_err) && empty($torre_err)){
        $sql = "UPDATE ambientes SET Numero=? , Capacidad=? , Tipo=upper(?), Sede=upper(?), Torre=upper(?) , Informacion=upper(?) WHERE IdPrincipal=?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isssssi", $param_numero, $param_capacidad, $param_tipo, $param_sede, $param_torre, $param_informacion, $param_id);
            
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
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: gestionar.php");
                exit();
            } else{
                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
   // mysqli_close($link);
}else{
    if(isset($_GET["IdPrincipal"]) && !empty(trim($_GET["IdPrincipal"]))){
        $id =  trim($_GET["IdPrincipal"]);
        
        $sql = "SELECT * FROM ambientes WHERE IdPrincipal = ?";
        if($stmt = mysqli_prepare($link, $sql)){
           mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){

                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $numero = $row["Numero"];
                    $capacidad = $row["Capacidad"];
                    $tipo = $row["Tipo"];
                    $sede = $row["Sede"];
                    $torre = $row["Torre"];
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
        
        // mysqli_close($link);
    }else{
        header("location: gestionar");
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
                <a>Actualizar programa de formación</a>
            </div>

            <div class="contenido">

            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">  
		
            <div class="form-group <?php echo (!empty($numero_err)) ? 'has-error' : ''; ?>">
                        <label>Número de ambiente</label>
                        <input type="text" name="Numero" class="form-control validanumericos" value="<?php echo $numero; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $numero_err;?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($capacidad_err)) ? 'has-error' : ''; ?>">
                        <label>Capacidad de aprendices</label>
                        <input type="text" name="Capacidad" class="form-control validanumericos" value="<?php echo $capacidad; ?>">
                        <span style="color: #a94442" class="help-block"><?php echo $capacidad_err;?></span>
                    </div>


                    <div class="form-group <?php echo (!empty($tipo_err)) ? 'has-error' : ''; ?>">
                        <label>Tipo de ambiente</label>
                        <select name="Tipo" id="tipo" onchange="cambioSelect2();" style="font-size:16px;color:#495057;" class="form-control">
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

                    <div class="form-group" <?php echo (!empty($torre_err)) ? 'has-error' : ''; ?>">
                        <label>Torre</label>
                        <select name="Torre" id="torre" onchange="cambioSelect();" style="font-size:16px;color:#495057;" class="form-control">
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

                    <div class="form-group <?php echo (!empty($informacion_err)) ? 'has-error' : ''; ?>">
                        <label>Información adicional</label>
                        <textarea name="Informacion" class="form-control"><?php echo $informacion; ?></textarea>
                        <span class="help-block"><?php echo $informacion_err;?></span>
                    </div>			
   
   
                    <input type="hidden" name="IdPrincipal" value="<?php echo $id; ?>"/>
                        <a class="btnr2" href="gestionar" style="">Cancelar</a>
                        <input type="submit" value="Actualizar" class="btnr" >

                        </form>
                    </div>
                </div>
            </div>
    </div>
</div>

<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- Funcion para hacer cambios entre el select Torre del ambiente y el input de otras torres -->
    <script>

        function cambioSelect() {

            var torre = document.getElementById('torre');         
            var torre2 = torre.options[torre.selectedIndex].text;

            var otraTorre = document.getElementById('otraTorre');
            var otraTorre2 = document.getElementById('otraTorre2');

                                        
            if (torre2=='OTRA') {

                otraTorre.style.display="block";
                otraTorre2.style.display="block";

            }else if(torre2=='OCCIDENTAL'){

                otraTorre.style.display="none";
                otraTorre2.style.display="none";

            }else if(torre2=='ORIENTAL'){
                                    
                otraTorre.style.display="none";
                otraTorre2.style.display="none";
            }
        }

        //Funcion para hacer cambios entre el select Tipo de ambiente y el input de otros tipos

        function cambioSelect2() {

            var tipo = document.getElementById('tipo');         
            var tipo2 = tipo.options[tipo.selectedIndex].text;

            var otraTipo = document.getElementById('otraTipo');
            var otraTipo2 = document.getElementById('otraTipo2');

                                        
            if (tipo2=='OTRO') {

                otraTipo.style.display="block";
                otraTipo2.style.display="block";

            }else if(tipo2=='COMPUTO'){

                otraTipo.style.display="none";
                otraTipo2.style.display="none";

            }else if(tipo2=='LABORATORIO'){
                                    
                otraTipo.style.display="none";
                otraTipo2.style.display="none";
            }
        }
    </script>

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
