<?php
header("Content-Type: application/xls;");
header("Content-Disposition: attachment; filename=Ambientes.xls");
header("Pragma: no-cache");
header("Expires: 0");

include("../../extra/conexion.php");

$sql = "SELECT * FROM  ambientes WHERE Status = 1" ;

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
                <th></th>
                <th><h2 style='text-align:center; font-size:26px;'><em>Ambientes</em></h2></th>
            </tr>
            <tr>
            </tr> 
       </table>  

            <table border='1' style='color:#737373;'> 
                <thead>                                                  
                    <tr>
                        <th style='background: #000; color: #fff;'>Número de ambiente</>
                        <th style='background: #000; color: #fff;'>Capacidad de aprendices</th>
                        <th style='background: #000; color: #fff;'>Tipo</th>
                        <th style='background: #000; color: #fff;'>Sede</th>
                        <th style='background: #000; color: #fff;'>Torre</th> 
                        <th style='background: #000; color: #fff;'>Información adicional</th>                       
                    </tr>
                </thead>";
        echo "<tbody>";                                                       
        while($row = mysqli_fetch_array($result)){          
            echo "<tr  style='text-align:center'; >";                                                          
                echo "<td>" . $row['IdPrincipal'] . "</td>";
                echo "<td>" . $row['Capacidad'] . "</td>";
                echo "<td>" . $row['Tipo'] . "</td>";
                echo "<td>" . $row['Sede'] . "</td>";
                echo "<td>" . $row['Torre'] . "</td>";
                echo "<td>" . $row['Informacion'] . "</td>";
            echo "</tr>";                                
        }                                                                      
        echo "</tbody>";                                                                                                                                                  
    } 
}  
    echo "</table>"; 
?>