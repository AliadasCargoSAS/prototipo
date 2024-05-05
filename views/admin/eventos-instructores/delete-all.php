<?php
require_once "../include/permission-admin.php";
?>
<?php

if (!empty($_POST["Instructor"])) {
    $instructor = $_POST["Instructor"];
}
if ($_POST["Instructor"] == '') {
    echo "<script>window.location='../principal/';</script>";
};

$borrar = implode(",", $_POST['select']);

include "../../../extra/conexion.php";

$query = mysqli_query($link, "DELETE FROM eventoinstructor WHERE IdPrincipal IN ($borrar)");

    $borrar = implode(",", $_POST['select']);

    $sqlConsulta = "SELECT * FROM eventoinstructor where IdPrincipal IN ($borrar)";
        $result = mysqli_query($link, $sqlConsulta);
        if (mysqli_num_rows($result)>0) {
            
            while($row = mysqli_fetch_array($result)){
                $competencia = $row['Competencia'];
                $rap = $row['Rap'];
                $ambiente = $row['NumeroAmbiente'];
                $dia = $row['Dia'];
                $horaI = $row['HoraInicio'];
                $horaF = $row['HoraFin']; 
                
                $sql2 = "DELETE FROM eventoficha WHERE IdPrincipal = (SELECT IdPrincipal FROM eventoficha where Competencia = '$competencia' and Rap = '$rap' and NumeroAmbiente = '$ambiente' and Dia = '$dia' and HoraInicio = '$horaI' and HoraFin = '$horaF')";
                $query2 = $link->query($sql2); 
            } 

            $sql = "DELETE FROM eventoinstructor WHERE IdPrincipal IN ($borrar)"; 
            $query = $link->query($sql);

            if( $query!=null && $query2!=null){
                header("location:gestionar?Instructor=$instructor"); 
            }else{
                print "<script>alert(\"No se pudo eliminar.\");window.location='gestionar?Instructor=$instructor';</script>";
            }
        }  
?>

