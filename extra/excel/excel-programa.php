<?php
include("../../extra/conexion.php");
$idprograma = $_GET['IdPrograma']; 

$sql = "SELECT * FROM programas WHERE Status = 1 and IdPrograma = $idprograma";

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
                        <th><h2 style='text-align:center; font-size:24px;'><em>Programas de Formación</em></h2></th>
                    </tr>
                    <tr>
                    </tr> 
            </table>      
            <table border='1' style='color:#737373;'> 
                <thead>                                                  
                    <tr>
                        <th style='background: #000; color: #fff;'>ID</>
                        <th style='background: #000; color: #fff;'>Nombre</th>
                        <th style='background: #000; color: #fff;'>Tipo</th>
                        <th style='background: #000; color: #fff;'>Duración</th>
                        <th style='background: #000; color: #fff;'>Versión</th>   
                        <th style='background: #000; color: #fff;'>Proyecto</th>                                                     
                        <th style='background: #000; color: #fff;'>Información adicional</th>
                    </tr>
                </thead>";
        echo "<tbody>";                                                       
        while($row = mysqli_fetch_array($result)){          
            echo "<tr style='text-align: center;'>";                                                          
                echo "<td>" . $row['Id'] . "</td>";
                echo "<td>" . $row['Nombre'] . "</td>";
                echo "<td>" . $row['Tipo'] . "</td>";
                echo "<td>" . $row['Duracion'] . "</td>";
                echo "<td>" . $row['Version'] . "</td>";
                echo "<td>" . $row['Proyecto'] . "</td>";                                                   
                echo "<td>" . $row['Informacion'] . "</td>";
                $programa = $row['Nombre'];
            echo "</tr>";                                
        }                                                                      
        echo "</tbody>";                                                                                                                                                  
    } 
}  
    echo "</table>";
    
    $sql = "SELECT * FROM competencias WHERE Status = 1 and IdPrincipalProgramaCompetencia = (Select IdPrograma from programas where IdPrograma=$idprograma)";

if($result = mysqli_query($link, $sql)){
    if(mysqli_num_rows($result) > 0){  
        echo "<h2><em>Competencias</em></h2>       
            <table border='1' style='color:#737373;'> 
                <thead>                                                  
                    <tr>
                        <th style='background: #000; color: #fff;'>ID</>
                        <th style='background: #000; color: #fff;'>Descripción</th>
                        <th style='background: #000; color: #fff;'>Duración</th>
                        <th style='background: #000; color: #fff;'>Información adicional</th>                                                      
                      
                    </tr>
                </thead>";
        echo "<tbody>";                                                       
        while($row = mysqli_fetch_array($result)){          
            echo "<tr>";                                                          
                echo "<td>" . $row['Id'] . "</td>";
                echo "<td>" . $row['Descripcion'] . "</td>";
                echo "<td>" . $row['Duracion'] . "</td>";
                echo "<td>" . $row['Informacion'] . "</td>";
            echo "</tr>";                                
        }                                                                      
        echo "</tbody>";                                                                                                                                                  
    } 
}  
    echo "</table>"; 
    
    header("Content-Type: application/xls;");
    header("Content-Disposition: attachment; filename=ProgramaDeFormación-".$programa.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>                    