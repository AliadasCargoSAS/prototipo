<?php

include("../../extra/conexion.php");
$idCompetencia = $_GET['IdCompetencia']; 
$idPrograma =$_GET['IdPrograma'];

$sql2 = "SELECT Nombre FROM programas where status=1 and IdPrograma= $idPrograma";

if($result = mysqli_query($link, $sql2)){
    if(mysqli_num_rows($result) > 0){ 
        while($row = mysqli_fetch_array($result)){ 
          $Nombre_programa = $row['Nombre'];
        }
    }
}


$sql = "SELECT * FROM competencias WHERE Status = 1 and IdCompetencia = $idCompetencia";

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
                        <th><h4 style='text-align:center; font-size:19px; '><em>".$Nombre_programa."</em></h4></th>
                    </tr>

                    <tr>
                    </tr>
                    <tr>
                       <th><h2 style='text-align:center; '><em>Competencia</em></h2></th>
                    </tr>

                    <tr>
                    </tr>
                    
            </table>      
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
                $Num_competencia=$row['Id'];
            echo "</tr>";                                
        }                                                                      
        echo "</tbody>";                                                                                                                                                  
    } 
}  
    echo "</table>"; 

    $sql = "SELECT * FROM raps WHERE Status = 1 and IdPrincipalCompetencias  = $idCompetencia";

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){  
            echo "<h2><em>Resultados de aprendizaje</em></h2>    
                <table border='1' style='color:#737373;'> 
                    <thead>                                                  
                        <tr>
                            <th style='background: #000; color: #fff;'>ID</>
                            <th style='background: #000; color: #fff;'>Descripción</th>
                            <th style='background: #000; color: #fff;'>Cantidad horas</th>
                            <th style='background: #000; color: #fff;'>Tipo de resultado</th>                                                      
                            <th style='background: #000; color: #fff;'>Información</th>                                                      
                          
                        </tr>
                    </thead>";
            echo "<tbody>";                                                       
            while($row = mysqli_fetch_array($result)){          
                echo "<tr style='text-align: center;'>";                                                          
                    echo "<td>" . $row['Id'] . "</td>";
                    echo "<td>" . $row['Descripcion'] . "</td>";
                    echo "<td>" . $row['CantidadHoras'] . "</td>";
                    echo "<td>" . $row['TipoResultado'] . "</td>";
                    echo "<td>" . $row['Informacion'] . "</td>";
                echo "</tr>";                                
            }                                                                      
            echo "</tbody>";                                                                                                                                                  
        } 
    }  
        echo "</table>"; 

    header("Content-Type: application/xls;");
    header("Content-Disposition: attachment; filename=Competencia-".$Num_competencia.".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
?>