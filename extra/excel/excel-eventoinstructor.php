<?php
    include("../../extra/conexion.php"); 

    $instructor = trim($_GET["Instructor"]);

    header("Content-Type: application/xls;");
    header("Content-Disposition: attachment; filename=Evento_Instructor_".$instructor.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");  
?>

<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
        <table class="table table-striped" style='color:#737373;'>                              
                    <thead>                   
                        <tr style="border: 1px solid #000;">                                                      
                            <th rowspan="4"><img src="https://imgur.com/6R5EFuH.png"></th>
                            <th colspan="10" style="color: #000; text-align: left;"> Programación eventos formativos</th>
                        </tr>

                        <tr style="border: 1px solid #000;">
                            <th colspan="10" style="color: #000;  text-align: left;"> FOO1 - POO2 - 08-11-9216</th>
                        </tr>
                        
                        <tr style="border: 1px solid #000;">
                            <th colspan="10" style="color: #000;  text-align: left;"> Proceso: Ejecución de la formación profesional</th>
                        </tr>

                        <tr style="border: 1px solid #000;">
                            <th colspan="10" style="color: #000;  text-align: left;"> Procedimiento: Desarrollo curricular</th>
                        </tr>

                        <tr style="border: 1px solid #000;">
                            <th colspan="2" style="color: #000;">Instructor: 
                            <?php
                                    $sql = "SELECT Nombre from Instructores where Nombre = '$instructor'";

                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){
                                          while($row = mysqli_fetch_array($result)){
                                            echo $row['Nombre'];          
                                          }                                    
                                        }

                                    }
                                ?>   
                            </th>

                            <th colspan="2" style="width: 220px; color: #000;">Trimestre del año: 
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

                            <th colspan="3" style="padding-left: 100px; color: #000;">Año: 
                            <?php                             
                                    $sql = "SELECT year(curdate())";
                                
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){
                                          while($row = mysqli_fetch_array($result)){
                                            echo $row['year(curdate())'];                                                              
                                          }                                
                                        }                                                          
                                    }
                                ?> 
                            </th>   
                            <th colspan="1" style="width:80px; color:#000">Fecha Inicio</th>
                            <th colspan="1" style="color:#000">  
                                    <?php

                                        $sql = "SELECT fechaInicio from eventoinstructor where CabeceraInstructor='$instructor' limit 1";

                                        if($result = mysqli_query($link, $sql)){
                                            if(mysqli_num_rows($result) > 0){ 
                                                while($row = mysqli_fetch_array($result)){
                                                    echo $row['fechaInicio'];
                                                }                
                                            }                             
                                        }
                                    ?>                                              
                            </th>
                            <th colspan="1" style="width:80px; color:#000">Fecha Fin</th>
                            <th colspan="1" style="color:#000">  
                                    <?php

                                        $sql = "SELECT fechaFin from eventoinstructor where CabeceraInstructor='$instructor' limit 1";

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
                    </thead>                                
                    <tbody>

                        <?php                                                      
                                $sql = "SELECT IdPrincipal, NumeroFicha, Programa, Competencia, Rap, NumeroAmbiente, Dia, substring(HoraInicio, 1, 5), substring(HoraFin, 1, 5), hour(HoraInicio), hour(HoraFin) FROM eventoinstructor WHERE CabeceraInstructor = '$instructor'";
                                                                 
                                    if($result = mysqli_query($link, $sql)){
                                        if(mysqli_num_rows($result) > 0){  
                                            echo "                                          
                                            <table border='1' class='table'> 
                                                    <thead>
                                                        <tr>                                                                                                  
                                                            <th>Número de ficha</th>
                                                            <th>Programa de formación</th>
                                                            <th colspan='3'>Competencia</th>
                                                            <th colspan='3'>Resultado de aprendizaje</th>
                                                            <th>Número Ambiente</th>
                                                            <th>Día</th> 
                                                            <th>Horario</th>                                                     
                                                        </tr>
                                                    </thead>";

                                            echo "<tbody>";                                                       
                                            while($row = mysqli_fetch_array($result)){
                                                
                                                echo "<tr style='text-align: center;'>";                                                   
                                                    echo "<td>" . $row['NumeroFicha'] . "</td>";
                                                    echo "<td>" . $row['Programa'] . "</td>";
                                                    echo "<td colspan='3'>" . $row['Competencia'] . "</td>";
                                                    echo "<td colspan='3'>" . $row['Rap'] . "</td>";
                                                    echo "<td>" . $row['NumeroAmbiente'] . "</td>";
                                                    echo "<td>" . $row['Dia'] . "</td>";

                                                    $hInicio = $row['hour(HoraInicio)'];                                    
                                                    $hFin = $row['hour(HoraFin)'];

                                                    echo "<td>" . $row['substring(HoraInicio, 1, 5)'] . ' - ' .$row['substring(HoraFin, 1, 5)']; echo ($hInicio > $hFin) ? " PM" : " AM";"</td>";                                          
                                                echo "</tr>";                                
                                            }                                                                      
                                            echo "</tbody>";                                                                                                                                                                                                                           
                                                } 
                                            }                                                                
                                        echo "</table>";
                                    echo "</table>";
                        ?> 