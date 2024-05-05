<?php
                include "../../../extra/conexion.php"; 
                $sql = "SELECT COUNT(*), (SELECT COUNT(*) from ambientes where Status = 1), (SELECT COUNT(*) FROM usuarios where Status = 1), (SELECT COUNT(*) FROM fichas where Status = 1), (select count(*) from eventoinstructor), (select count(*) from eventoficha), (select count(*) from programas where Status = 1) from instructores where Status = 1;";
                $result = mysqli_query($link, $sql);


            $array = array();
    
            while($datos = mysqli_fetch_array($result)){
                  
                  array_push($array, $datos[0], $datos[1], $datos[2], $datos[3], $datos[6], $datos[5], $datos[4]);

            }         
            
            echo json_encode($array);
             
?>