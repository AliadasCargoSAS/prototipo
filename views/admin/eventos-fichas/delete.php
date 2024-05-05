<?php 
    require_once "../include/permission-admin.php";

    if(!empty($_GET)){
        if (($_GET["ficha"] == '') || ($_GET["trimestre"] == '')) {
            echo "<script>window.location='../principal/';</script>";
        };
        
        if(!empty($_GET["ficha"]) && !empty(trim($_GET["trimestre"]))){
            $ficha =  trim($_GET["ficha"]);
            $trimestre = trim($_GET["trimestre"]);
        }

<<<<<<< HEAD
        include "../../../extra/conexion.php"; 

        $sql = "DELETE FROM eventoficha WHERE IdPrincipal = ".$_GET["IdPrincipal"]; 
=======
        $sqlConsulta = "SELECT * FROM eventoficha where IdPrincipal = ".$_GET["IdPrincipal"];
        $result = mysqli_query($link, $sqlConsulta);
        if (mysqli_num_rows($result)>0) {
            
            while($row = mysqli_fetch_array($result)){
                $competencia = $row['Competencia'];
                $rap = $row['Rap'];
                $ambiente = $row['NumeroAmbiente'];
                $dia = $row['Dia'];
                $horaI = $row['HoraInicio'];
                $horaF = $row['HoraFin'];
                $ficha = $row['CabeceraFicha']; 
                
                $sql2 = "DELETE FROM eventoinstructor WHERE IdPrincipal =(SELECT IdPrincipal FROM eventoinstructor where NumeroFicha = '$ficha' and Competencia = '$competencia' and Rap = '$rap' and NumeroAmbiente = '$ambiente' and Dia = '$dia' and HoraInicio = '$horaI' and HoraFin = '$horaF')";
                $query2 = $link->query($sql2); 
            } 
>>>>>>> 4b81669a2c8b8687c5839e51d8a23bb3c9c2e149

            $sql = "DELETE FROM eventoficha WHERE IdPrincipal = ".$_GET["IdPrincipal"]; 
            $query = $link->query($sql);

            if($query!=null && $query2!=null){
                header("location:gestionar.php?ficha=$ficha&trimestre=$trimestre");  
            }else{
                print "<script>alert(\"No se pudo eliminar.\");window.location='gestionar.php?ficha=$ficha&trimestre=$trimestre';</script>";
            }
         }
    }
<<<<<<< HEAD
?>
=======

    require_once "../include/permission-admin.php";

?>
    
        
>>>>>>> 4b81669a2c8b8687c5839e51d8a23bb3c9c2e149
