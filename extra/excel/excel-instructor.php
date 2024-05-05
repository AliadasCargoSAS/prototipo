<?php

include("../../extra/conexion.php");
$nombreinst = $_GET['Nombre']; 

if($nombreinst == ''){
    echo "<script>window.location='../../../../views/admin/instructores/gestionar'</script>";
}

$sql = "SELECT * FROM instructores WHERE Status = 1 and Nombre = '$nombreinst'";

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
                        <th></th>
                        <th><h2 style='text-align:center; font-size:27px;'><em>Instructores</em></h2></th>
                    </tr>
                    <tr>
                    </tr> 
            </table>      
            <table border='1' style='color:#737373;'> 
                <thead>                                                  
                    <tr>
                        <th style='background: #000; color: #fff;'>Número de identifación</>
                        <th style='background: #000; color: #fff;'>Nombre</th>
                        <th style='background: #000; color: #fff;'>Fecha de nacimiento</th>
                        <th style='background: #000; color: #fff;'>Número telefonico</th>
                        <th style='background: #000; color: #fff;'>Especialidad</th>                                                      
                        <th style='background: #000; color: #fff;'>Correo institucional</th>
                        <th style='background: #000; color: #fff;'>Tipo de contrato</th>
                        <th style='background: #000; color: #fff;'>Contrato Inicio</th>
                        <th style='background: #000; color: #fff;'>Contrato Fin</th>
                        <th style='background: #000; color: #fff;'>Información adicional</th>
                    </tr>
                </thead>";
        echo "<tbody>";                                                       
        while($row = mysqli_fetch_array($result)){          
            echo "<tr style='text-align: center;'>";                                                          
                echo "<td>" . $row['Identificacion'] . "</td>";
                echo "<td>" . $row['Nombre'] . "</td>";
                echo "<td>" . $row['Nacimiento'] . "</td>";
                echo "<td>" . $row['Telefono'] . "</td>";
                echo "<td>" . $row['Especialidad'] . "</td>";                                                 
                echo "<td>" . $row['Correo'] . "</td>";
                echo "<td>" . $row['TipoContrato'] . "</td>";
                echo "<td>" . $row['ContratoInicio'] . "</td>";
                echo "<td>" . $row['ContratoFin'] . "</td>";
                echo "<td>" . $row['Informacion'] . "</td>";
                $nombre = $row['Nombre'];
            echo "</tr>";                                
        }                                                                      
        echo "</tbody>";                                                                                                                                                  
    }else{
        echo "<script>window.location='../../../../views/admin/instructores/gestionar'</script>";
    } 
}  
    echo "</table>"; 
    
    header("Content-Type: application/xls;");
    header("Content-Disposition: attachment; filename=Instructor(a)-".strtolower($nombre).".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>