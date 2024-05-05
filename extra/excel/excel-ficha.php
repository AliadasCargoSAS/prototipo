<?php
include("../../extra/conexion.php");

$idPrincipal = $_GET['IdPrincipal'];

$sql = "SELECT * FROM fichas WHERE Status = 1 and IdPrincipal = $idPrincipal";

if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){  
        echo "
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
            <table>
                    <tr>
                        <th><img src='https://imgur.com/7se7eyZ.jpg' style='' alt='Logo-SENA'></th>                       
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th><h2 style='text-align:center; font-size:27px;'><em>Fichas</em></h2></th>
                    </tr>
                    <tr>
                    </tr> 
            </table>      
            <table border='1' style='color:#737373;'> 
                <thead>                                                  
                    <tr>
                        <th style='background: #000; color: #fff;'>Número de la ficha</>
                        <th style='background: #000; color: #fff;'>Programa de formación</th>
                        <th style='background: #000; color: #fff;'>Cantidad de aprendices</th>
                        <th style='background: #000; color: #fff;'>Jornada</th>                                                     
                        <th style='background: #000; color: #fff;'>Fecha de inicio</th>
                        <th style='background: #000; color: #fff;'>Fecha fin</th>
                        <th style='background: #000; color: #fff;'>Información adicional</th>
                    </tr>
                </thead>";
        echo "<tbody>";                                                       
        while($row = mysqli_fetch_array($result)){          
            echo "<tr style='text-align: center;'>";                                                          
                echo "<td>" . $row['Numero'] . "</td>";
                echo "<td>" . $row['Programa'] . "</td>";
                echo "<td>" . $row['Aprendices'] . "</td>";
                echo "<td>" . $row['Jornada'] . "</td>";                                              
                echo "<td>" . $row['Inicio'] . "</td>";
                echo "<td>" . $row['Fin'] . "</td>";
                echo "<td>" . $row['Informacion'] . "</td>";
                $ficha = $row['Numero'];
            echo "</tr>";                                
        }                                                                      
        echo "</tbody>";                                                                                                                                                  
    } 
}  
    echo "</table>"; 
    
    header("Content-Type: application/xls;");
    header("Content-Disposition: attachment; filename=Ficha-".$ficha.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>