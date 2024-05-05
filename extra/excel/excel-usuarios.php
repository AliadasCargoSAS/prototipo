<?php
header("Content-Type: application/xls;");
header("Content-Disposition: attachment; filename=Usuarios.xls");
header("Pragma: no-cache");
header("Expires: 0");

include("../../extra/conexion.php");

$sql = "SELECT u.Usuario, u.Nombre, u.Correo, u.Telefono, u.Informacion, 
                    
        case when u.Rol = 1 then 'ADMINISTRADOR'
        when u.Rol = 2 then 'COORDINADOR'
        when u.Rol = 3 then 'INSTRUCTOR'
        end as 'Rol' 

        FROM usuarios u WHERE Status = 1";

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
                        <th><h2 style='text-align:center; font-size:27px;'><em>Usuarios</em></h2></th>
                    </tr>
                    <tr>
                    </tr> 
            </table>      
            <table border='1' style='color:#737373;'> 
                <thead>                                                  
                    <tr>
                        <th style='background: #000; color: #fff;'>Usuario</>
                        <th style='background: #000; color: #fff;'>Nombre</th>
                        <th style='background: #000; color: #fff;'>Correo</th>
                        <th style='background: #000; color: #fff;'>Telefono</th>
                        <th style='background: #000; color: #fff;'>Rol</th>                                                      
                        <th style='background: #000; color: #fff;'>Información adicional</th>
                    </tr>
                </thead>";
        echo "<tbody>";                                                       
        while($row = mysqli_fetch_array($result)){          
            echo "<tr style='text-align: center;'>";                                                          
                echo "<td>" . $row['Usuario'] . "</td>";
                echo "<td>" . $row['Nombre'] . "</td>";
                echo "<td>" . $row['Correo'] . "</td>";
                echo "<td>" . $row['Telefono'] . "</td>";
                echo "<td>" . $row['Rol'] . "</td>";                                                 
                echo "<td>" . $row['Informacion'] . "</td>";                
            echo "</tr>";                                
        }                                                                      
        echo "</tbody>";                                                                                                                                                  
    } 
}  
    echo "</table>"; 
?>          