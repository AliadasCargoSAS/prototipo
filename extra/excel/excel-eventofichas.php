<?php
    include("../../extra/conexion.php"); 

    $ficha = $_GET['ficha'];
    $trimestre = $_GET['trimestre'];

    header("Content-Type: application/xls;");
    header("Content-Disposition: attachment; filename=Evento_Ficha_".$ficha.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");  
?>

<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
        <table class="table table-striped" style='color:#737373;'>                              
                    <thead>                   
                        <tr style="border: 1px solid #000;">                                                      
                            <th rowspan="4" colspan="1"><img src="https://imgur.com/6R5EFuH.png"></th>
                            <th colspan="9" style="color: #000; text-align: left;"> Programación eventos formativos</th>
                        </tr>

                        <tr style="border: 1px solid #000;">
                            <th colspan="9" style="color: #000;  text-align: left;"> FOO1 - POO2 - 08-11-9216</th>
                        </tr>
                        
                        <tr style="border: 1px solid #000;">
                            <th colspan="9" style="color: #000;  text-align: left;"> Proceso: Ejecución de la formación profesional</th>
                        </tr>

                        <tr style="border: 1px solid #000;">
                            <th colspan="9" style="color: #000;  text-align: left;"> Procedimiento: Desarrollo curricular</th>
                        </tr>

                        <tr style="border: 1px solid #000;">
                            <th colspan="1" style="width: 220px; color: #000; text-align: left";>Número de la ficha: <?php echo ($ficha); ?></th>

                            <th colspan="1" style="width: 220px; color: #000;">Trimestre del año: 
                            <?php
                                                                      
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
                                     }
                                ?> 
                            </th>

                            <th colspan="2" style="padding-left: 100px; color: #000;">Año: 
                            <?php                             
                                    echo date('Y');
                                ?> 
                            </th>

                            <th colspan="2" style="color: #000;" >Número de aprendices: 
                            <?php
                                    $sql = "SELECT Aprendices from fichas where Numero='$ficha' and status = 1";

                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){ 
                                            while($row = mysqli_fetch_array($result)){                                         
                                                echo $row['Aprendices'];         
                                            }                
                                        }                                
                                    }
                                ?>
                            </th>
                            <th colspan="1" style="width:80px; color: #000;">Fecha Inicio</th>
                                <th colspan="1" style="color: #000;"> 
                                    <?php

                                        $sql = "SELECT fechaInicio from eventoficha where CabeceraFicha='$ficha' and trimestreFicha= '$trimestre' limit 1";

                                        if($result = mysqli_query($link, $sql)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                while($row = mysqli_fetch_array($result)){
                                                    echo $row['fechaInicio'];
                                                }                
                                            }                         
                                        }
                                    ?>                                              
                                </th>
                                <th colspan="1" style="width:80px; color: #000;">Fecha Fin</th>
                                <th colspan="1" style="color: #000;">  
                                    <?php

                                        $sql = "SELECT fechaFin from eventoficha where CabeceraFicha='$ficha' and trimestreFicha= '$trimestre' limit 1";

                                        if($result = mysqli_query($link, $sql)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                while($row = mysqli_fetch_array($result)){
                                                    echo $row['fechaFin'];
                                                }                
                                            }                               
                                        }
                                    ?>                                          
                                </th>  
                        </tr> 
                        <tr style="border: 1px solid #000;">
                            <th colspan="2" style="color: #000; text-align: left">Programa de formación:
                                <?php

                                $sql = "SELECT Programa from fichas where Numero='$ficha' and status = 1";

                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){ 
                                        while($row = mysqli_fetch_array($result)){
                                            $programa = $row['Programa']; 
                                            echo $programa;                                                       
                                        }                
                                    }                                
                                } 
                                ?>    
                            </th>
                            <th colspan="2" style="width: 230px; color: #000;">Trimestre de la ficha:  
                                <?php echo ($trimestre); ?> 
                            </th>
                            <th colspan="1" style="color: #000;">Jornada: 
                                <?php

                                $sql = "SELECT Jornada from fichas where Numero='$ficha' and status = 1";

                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){ 
                                        while($row = mysqli_fetch_array($result)){
                                            echo $row['Jornada'];         
                                        }                
                                    }                                
                                }
                                ?>
                            </th>
                            <th colspan="5" style="padding-left: 60px; color: #000;">Proyecto:
                                <?php
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
                    <tbody>

                        <?php   

                            include ("../../extra/conexion.php");      
                                                                          
                                $sql = "SELECT Fase, Competencia, Rap, NumeroAmbiente, Dia, substring(HoraInicio, 1, 5), substring(HoraFin, 1, 5), hour(HoraInicio), hour(HoraFin), Instructor FROM eventoficha WHERE CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre'";
                                                                 
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){  
                                            echo "                                          
                                            <table border='1' class='table'> 
                                                    <thead>
                                                        <tr>                                      
                                                            <th>Fase del proyecto</th>
                                                            <th colspan='2'>Competencia</th>
                                                            <th colspan='2'>Resultado de aprendizaje</th>
                                                            <th>Número Ambiente</th>
                                                            <th>Día</th> 
                                                            <th>Horario</th> 
                                                            <th colspan='2'>Instructor</th>                                                      
                                                        </tr>
                                                    </thead>";
                                            echo "<tbody>";                                                       
                                            while($row = mysqli_fetch_array($result)){
                                                
                                                echo "<tr style='text-align: center;'>";
                                                    echo "<td>" . $row['Fase'] . "</td>";
                                                    echo "<td colspan='2'>" . $row['Competencia'] . "</td>";
                                                    echo "<td colspan='2'>" . $row['Rap'] . "</td>";
                                                    echo "<td>" . $row['NumeroAmbiente'] . "</td>";
                                                    echo "<td>" . $row['Dia'] . "</td>";

                                                    $hInicio = $row['hour(HoraInicio)'];                                    
                                                    $hFin = $row['hour(HoraFin)'];

                                                    echo "<td>" . $row['substring(HoraInicio, 1, 5)'] . ' - ' .$row['substring(HoraFin, 1, 5)']; 
                                                    echo ($hInicio >= 0 && $hFin <= 12) ? " AM" : " PM";"</td>";
                                                    echo "<td colspan='2'>" . $row['Instructor'] . "</td>";                                           
                                                echo "</tr>";                                
                                            }                                                                      
                                            echo "</tbody>";                                                                                                                                                                                                                           
                                                } 
                                            }                                                                
                                        echo "</table>";
                                    echo "</table>";
                            ?> 
                             
                        