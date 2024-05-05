<!DOCTYPE html>
<html lang="es">

<?php     
       require_once "../include/header-admin.php";
       $ficha = trim($_GET["ficha"]);
       $trimestre = trim($_GET["trimestre"]);

       if(($_GET["ficha"] == '') || ($_GET["trimestre"] == '')){
        echo "<script>window.location='../principal/';</script>";
    }
    ?>

<body>
<div class="container-fluid mb-3">
<div class="col" id="event-general">
                <div class="card" style="border-radius:.25rem .25rem 0 0 !important;border: none !important;">
                    <div class="card-header" style="background:#0D1326">
                        <div class="row">
                            <div class="col my-auto">
                                <h2>Evento fichas</h2>
                            </div>
                            <div class="col text-right">

                            <form action="update-fechas" method="POST">  

                                    <?php
                                        include "../../../extra/conexion.php"; 

                                        $sql = "SELECT fechaInicio, fechaFin from eventoficha where CabeceraFicha='$ficha' and trimestreFicha= '$trimestre' limit 1";

                                        if($result = mysqli_query($link, $sql)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                echo'<div class="btn btn-secondary" style="margin-right:15px; background: #fff !important; color: #000;">
                                                        <i class="fas fa-save"></i>
                                                        <input type="hidden" name="ficha" value="'.$ficha.'">
                                                        <input type="hidden" name="trimestre" value="'.$trimestre.'">
                                                        <input type="submit" value="Guardar" style="border:none; outline:none; background:transparent !important;">
                                                    </div>';               
                                            }                             
                                        }else{
                                            echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                        }
                                        mysqli_close($link); 
                                    ?>                                                

                                <a href="#" class="btn btn-danger" data-toggle="modal" data-target='#confirm-eliminar-items-modal'>
                                    <i class="fas fa-minus-circle"></i> <span>Eliminar</span>
                                </a>

                                <a href="crear.php?ficha=<?php echo ($ficha);?>&trimestre=<?php echo ($trimestre);?>" class="btn btn-success" style="margin-left:10px;">
                                    <i class="fas fa-plus-circle"></i>
                                    Crear
                                </a> 
                            </div>
                        </div>
                    </div>
                        <br>
                    <div class="row pl-3 pr-3">
                        <div class="col my-auto">
                            <h6 style="font-size:17px;">Descargar</h6> 
                        </div>
                        <?php
                                include "../../../extra/conexion.php";

                                //Validación sobre los campos que esten vacios en los eventos registrados

                                $sql_valida = "SELECT count(*) AS TotalVacios FROM eventoficha WHERE (CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre') AND (Competencia = '' or Rap = '' or NumeroAmbiente = '' or Instructor = '')"; 

                                $resultado = mysqli_query($link, $sql_valida);

                                while($row = mysqli_fetch_array($resultado)){
                                        
                                        if($row['TotalVacios'] > 0){
                                            
                                            echo '<div class="row">
                                            <div class="msgalerta">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-info-circle"></i> Existen campos vacios o incompletos para algunos eventos de la ficha, por favor verifique.
                                                </div>
                                            </div>
                                        </div>';
                                        } 
                                }
                                
                                //Validación sobre los campos de fecha inicio y fin del trimestre vacios

                                $sql_valida2 = "SELECT count(*) AS TotalFechasVacias FROM eventoficha WHERE (CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre') AND (FechaInicio='' or FechaFin='') order by IdPrincipal ASC limit 1"; 
                                $resultado = mysqli_query($link, $sql_valida2);


                                while($row = @mysqli_fetch_array($resultado)){
                                        
                                        if($row['TotalFechasVacias'] > 0){
                                            
                                            echo '<div class="row">
                                            <div class="msgalerta">
                                                <div class="alert alert-warning">
                                                    <i class="fas fa-info-circle"></i> Existen campos vacios o incompletos para las fechas del trimestre, por favor registrarlas y luego dar click en el botón "Guardar".
                                                </div>
                                            </div>
                                        </div>';
                                        } 
                                } 
                        ?>        
                        <div class="col text-right p-10">
                            <a href="../../../extra/pdf/Evento-Ficha?ficha=<?php echo $ficha; ?>&trimestre=<?php echo $trimestre; ?>" class="btn btn-outline-danger" id="IdPDF" target="_blank" title="Descagar como PDF" style="border-radius: 5px 0px 0px 0px; border-right-color:transparent;"><i class="far fa-file-pdf"></i></a><a href="../../../extra/excel/excel-eventofichas?ficha=<?php echo $ficha; ?>&trimestre=<?php echo $trimestre; ?>" class="btn btn-outline-success" taget="_blank" title="Descagar como archivo Excel" style="border-radius: 0px 5px 0px 0px"><i class="far fa-file-excel"></i></a> 
                        </div>
                    </div>
                                                              
        <div class="container-fluid" style="background:#fff;">
            <div class="table-responsive">
                <table class="table table-striped" style='color:#737373;'>                              
                    <thead>                                                     
                        <tr>                            
                            <th rowspan="4" scope="col"><div class="container"><img src="../../../images/sena.png" style="max-width:65px;"></div></th>
                            <th colspan="13" scope="col">Programación eventos formativos</th>
                        </tr>
                        <tr>
                            <th colspan="13" scope="col">FOO1 - POO2 - 08-11-9216</th>
                        </tr>

                        <tr>
                            <th colspan="13" scope="col">Proceso: Ejecución de la formación profesional</th>
                        </tr>

                        <tr>
                            <th colspan="13" scope="col">Procedimiento: Desarrollo curricular</th>
                        </tr>

                        <tr>
                            <th colspan="1" style="width:220px";>Número de la ficha</th>
                            <th colspan="1" style="font-weight:400;width:100px;">
                                <?php echo ($ficha); ?>
                            </th>
                            <th colspan="1" style="width:200px">Trimestre del año</th>
                            <th colspan="1" class="pr-0" style="font-weight:400;">
                                <?php
                                    include "../../../extra/conexion.php"; 
                                                                      
                                    $sql2 = "SELECT 
                                    case
                                    when month(curdate()) = 1 then 'I'
                                    when month(curdate()) between 11 and 12 then 'I'
                                    when month(curdate()) between 2 and 4 then 'II'
                                    when month(curdate()) between 5 and 7 then 'III'
                                    when month(curdate()) between 8 and 10 then 'IV'
                                    END 'Trimestre año'";

                                     if($result = mysqli_query($link, $sql2)){
                                        if(mysqli_num_rows($result) > 0){
                                            while($row = mysqli_fetch_array($result)){    
                                                echo $row['Trimestre año'];
                                            } 
                                        }   
                                     }else{
                                        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                    }

                                    mysqli_close($link); 
                                ?>   
                            </th>
                            <th colspan="1" class="pl-0">Año</th>
                            <th colspan="1" style="font-weight:400;">
                                <?php
                                   include "../../../extra/conexion.php"; 
                                                          
                                    $sql = "SELECT year(curdate())";
                                
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){
                                          while($row = mysqli_fetch_array($result)){
                                            echo $row['year(curdate())'];                                                              
                                          }                                
                                        }                                                          
                                    }else{
                                        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                    }
                                    mysqli_close($link);
                                ?> 
                            </th>              
                            <th colspan="1" >Número de aprendices</th>
                            <th colspan="1" style="font-weight:400;width:60px;"> 
                            <?php
                                    include "../../../extra/conexion.php"; 

                                    $sql = "SELECT Aprendices from fichas where Numero='$ficha' and status = 1";

                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){ 
                                            while($row = mysqli_fetch_array($result)){                                              
                                                echo $row['Aprendices'];
                                            }                
                                        }                                
                                    }else{
                                        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                    }
                                    mysqli_close($link); 
                                ?>
                            </th> 
                            
                                <th colspan="1" style="width:80px">Fecha Inicio</th>
                                <th colspan="1"> 
                                    <?php
                                        include "../../../extra/conexion.php"; 

                                        $sql = "SELECT fechaInicio from eventoficha where CabeceraFicha='$ficha' and trimestreFicha= '$trimestre' limit 1";

                                        if($result = mysqli_query($link, $sql)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                while($row = mysqli_fetch_array($result)){
                                                    echo "<input class='input-evento' style='width:150px;' type='date' name='fechaInicio' value='".$row['fechaInicio']."'>";
                                                }                
                                            }                             
                                        }else{
                                            echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                        }
                                        mysqli_close($link); 
                                    ?>                                              
                                </th>
                                <th colspan="1" style="width:80px">Fecha Fin</th>
                                <th colspan="1">  
                                    <?php
                                        include "../../../extra/conexion.php"; 

                                        $sql = "SELECT fechaFin from eventoficha where CabeceraFicha='$ficha' and trimestreFicha= '$trimestre' limit 1";

                                        if($result = mysqli_query($link, $sql)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                while($row = mysqli_fetch_array($result)){
                                                    echo "<input class='input-evento' style='width:150px;' type='date' name='fechaFin' value='".$row['fechaFin']."'>";
                                                }                
                                            }                                
                                        }else{
                                            echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                        }
                                        mysqli_close($link); 
                                    ?>                                          
                                </th>  
                            </form>  
                        </tr> 
                        <tr>
                            <th colspan="1" >Programa de formación</th>
                            <th colspan="2" style="font-weight:400;">
                            
                                <?php
                                    include "../../../extra/conexion.php"; 

                                    $sql = "SELECT Programa from fichas where Numero='$ficha' and status = 1";

                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){ 
                                            while($row = mysqli_fetch_array($result)){
                                                $programa = $row['Programa'];                                                      
                                                echo $programa;
                                            }                
                                        }                                
                                    }else{
                                        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                    }
                                    mysqli_close($link); 
                                ?>                            
                            </th>
                            <th colspan="1" style="width: 230px;">Trimestre de la ficha</th>
                            <th colspan="1" style="font-weight:400;width:30px;">
                                <?php echo ($trimestre); ?>
                            </th>
                            <th colspan="1">Jornada</th>
                            <th colspan="1" style="font-weight:400;width:50px;">
                                <?php
                                    include "../../../extra/conexion.php"; 

                                    $sql = "SELECT Jornada from fichas where Numero='$ficha' and status = 1";

                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){ 
                                            while($row = mysqli_fetch_array($result)){     
                                                echo $row['Jornada'];
                                            }                
                                        }                                
                                    }else{
                                        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                    }
                                    mysqli_close($link); 
                                ?>          
                            </th>
                            <th colspan="1">Proyecto</th>
                            <th colspan="4" style="font-weight:400;">
                                <?php
                                    include "../../../extra/conexion.php"; 

                                    $sql = "SELECT Proyecto from programas where Nombre = (Select Programa from fichas where Numero = '$ficha')";

                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){
                                          while($row = mysqli_fetch_array($result)){
                                            echo $row['Proyecto'];
                                          }                                    
                                        }

                                    }else{
                                        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                    }
                                    mysqli_close($link); 
                                ?>    
                            </th>                            
                        </tr>              
                    </thead> 
                </div>           
                       
            <tbody>
            <tr>
            <?php
                        include "../../../extra/conexion.php"; 
                                
                                $sql = "SELECT IdPrincipal, Fase, Competencia, Rap, NumeroAmbiente, Dia, substring(HoraInicio, 1, 5), substring(HoraFin, 1, 5), hour(HoraInicio), hour(HoraFin), Instructor FROM eventoficha WHERE CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre'";
                                                                 
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){  
                                            echo "
                                            <form method='POST' action='delete-all.php'>
                                            <div class='table-responsive'>
                                                <table class='table'> 
                                                    <thead>
                                                        <tr>
                                                            <th><span class='custom-checkbox'><input type='checkbox' title='Seleccionar todos los eventos' id='selectAll'><label for='selectAll'></label></span></th>                                                        
                                                            <th>Fase del proyecto</th>
                                                            <th style='padding-left: 43px;'>Competencia</th>
                                                            <th>Resultado de aprendizaje</th>
                                                            <th>Ambiente</th>
                                                            <th>Día</th> 
                                                            <th>Horario</th> 
                                                            <th>Instructor</th>                                                      
                                                            <th colspan='2'>Acciones</th>
                                                        </tr>
                                                    </thead>";

                                            echo "<tbody>";                                                       
                                            while($row = mysqli_fetch_array($result)){
                                                                                                   
                                                    echo "<tr>";                                                                                            
                                                    echo "<td> <div id='listado' class='custom-checkbox'><input type='checkbox' name='select[]' id='select[]' value='".$row['IdPrincipal']."'><label for='select'></label></div> </td>";                                    
                                                    echo "<td>" . $row['Fase'] . "</td>";
                                                    echo "<td style='padding-left: 43px;'>" . $row['Competencia'] . "</td>";
                                                    echo "<td>" . $row['Rap'] . "</td>";
                                                    echo "<td>" . $row['NumeroAmbiente'] . "</td>";
                                                    echo "<td>" . $row['Dia'] . "</td>";

                                                    $hInicio = $row['hour(HoraInicio)'];                                    
                                                    $hFin = $row['hour(HoraFin)'];
                                                
                                                    echo "<td>" . $row['substring(HoraInicio, 1, 5)'] . ' - ' .$row['substring(HoraFin, 1, 5)']; echo ($hInicio >= 0 && $hFin <= 12) ? " AM" : " PM";"</td>";                                                                                               
                                                    echo "<td>" . $row['Instructor'] . "</td>";
                                                    echo "<td>";                                                    
                                                    echo "<a href='update.php?IdPrincipal=". $row['IdPrincipal'] ."&ficha=".$ficha."&trimestre=".$trimestre."' data-toggle='tooltip'><i class='far fa-edit' title='Editar'></i></a>";                                                    
                                                    echo "<a href='#' data-href='delete.php?IdPrincipal=". $row['IdPrincipal'] ."&ficha=".$ficha."&trimestre=".$trimestre."' data-toggle='modal' data-target='#confirm-eliminar-modal'><i class='far fa-trash-alt' title='Eliminar'></i></a> </td>";
                                                echo "</tr>";                                
                                            }                                                                      
                                            echo "</tbody>";   
                                            echo '<div id="confirm-eliminar-items-modal" class="modal fade">
                                            <div class="modal-dialog">
                                                <div class="modal-content">			
                                                        <div class="modal-header">	
                                                                                
                                                            <h4 class="modal-title">Eliminar Eventos</h4>
                                                            <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                                                                        
                                                        </div>                   
                                                            <div class="modal-body">
                                                            <form method="POST" action="delete-all.php">					
                                                                <p>¿Estas seguro de eliminar los eventos seleccionados?</p>						
                                                            </div>
                                    
                                                        <div class="modal-footer">
                                                            <input type="hidden" name="ficha" value="'.$ficha.'">
                                                            <input type="hidden" name="trimestre" value="'.$trimestre.'">
                                                            <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                                                            <input type="submit" class="btn btn-danger btn-ok" value="Eliminar">                    
                                    
                                                        </div>';
                                                                                                                                                                                    
                                                } else{
                                                    echo "<div class='alert-vacio' style='border-top:1px solid #e9e9e9;'><div class='alert alert-success' style='text-align:center;width:55%;'><em><i class='fas fa-info-circle  mr-1'></i> No hay registro de eventos para la ficha y trimestre seleccionado, <a href='crear.php?ficha=$ficha&trimestre=$trimestre' style='text-decoracion:none;'>agrega un nuevo evento</a>. </em></div></div>";
                                                }
                                            } else{
                                                echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                            }                                                                 
                                            mysqli_close($link); 
                     ?>                                                     
                        </form>                   
                    </table>
        </div>
    </div>
</div>

    <div id="confirm-eliminar-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">			
					<div class="modal-header">	
                        					
						<h4 class="modal-title">Eliminar Evento-Ficha</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>          
                        
					</div>                   
                        <div class="modal-body">
                            <form role="form">					
                            <p>¿Estas seguro de eliminar el evento?</p>						
                        </div>

					<div class="modal-footer">
						<input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                        <a href="delete.php?IdPrincipal=<?php echo $row['IdPrincipal'] ?>&ficha=<?php echo $ficha; ?>&trimestre=<?php echo $trimestre; ?>" class="btn btn-danger btn-ok" >Eliminar</a>
					</div>

				</form>
			</div>
		</div>
    </div>

    <script> 
            $('#confirm-eliminar-modal').on ('show.bs.modal', function (e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data 
                ('href'));

                $('.debug-url').html('Delete URL: <strong>' + $(this).find(
                    '.btn-ok').attr('href') + '</strong>');
            });     
    </script>

    <script>
      $(function(){
        $('#selectAll').change(function() {
          $('#listado > input[type=checkbox]').prop('checked', $(this).is(':checked'));
        });
      });
    </script>

    <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>

</html>