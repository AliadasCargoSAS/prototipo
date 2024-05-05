<?php 

require_once "../include/permission-admin.php";

    if(!empty($_GET)){
        if ($_GET["Instructor"] == '') {
            echo "<script>window.location='../principal/';</script>";
        };
              
        if(isset($_GET["Instructor"]) && !empty(trim($_GET["Instructor"]))){
            $instructor = trim($_GET["Instructor"]);
        }

        $sqlConsulta = "SELECT * FROM eventoinstructor where IdPrincipal = ".$_GET["IdPrincipal"];
        $result = mysqli_query($link, $sqlConsulta);
        if (mysqli_num_rows($result)>0) {
            
            while($row = mysqli_fetch_array($result)){
                $competencia = $row['Competencia'];
                $rap = $row['Rap'];
                $ambiente = $row['NumeroAmbiente'];
                $dia = $row['Dia'];
                $horaI = $row['HoraInicio'];
                $horaF = $row['HoraFin']; 
                
                $sql2 = "DELETE FROM eventoficha WHERE IdPrincipal =(SELECT IdPrincipal FROM eventoficha where Competencia = '$competencia' and Rap = '$rap' and NumeroAmbiente = '$ambiente' and Dia = '$dia' and HoraInicio = '$horaI' and HoraFin = '$horaF')";
                $query2 = $link->query($sql2); 
            } 

            $sql = "DELETE FROM eventoinstructor WHERE IdPrincipal = ".$_GET["IdPrincipal"]; 
            $query = $link->query($sql);

            if( $query!=null && $query2!=null){
                header("location:gestionar?Instructor=$instructor"); 
            }else{
                print "<script>alert(\"No se pudo eliminar.\");window.location='gestionar?Instructor=$instructor';</script>";
            }
        }
    }
?>