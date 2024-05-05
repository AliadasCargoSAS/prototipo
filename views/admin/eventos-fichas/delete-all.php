<?php
require_once "../include/permission-admin.php";
?>
<?php

if (!empty($_POST["ficha"]) && !empty($_POST["trimestre"])) {
    $ficha = $_POST["ficha"];
    $trimestre = $_POST["trimestre"];
}

if (($_POST["ficha"] == '') || ($_POST["trimestre"] == '')) {
    echo "<script>window.location='../principal/';</script>";
};

include "../../../extra/conexion.php";

$borrar = implode(",", $_POST['select']);
$query = mysqli_query($link, "DELETE FROM eventoficha WHERE IdPrincipal IN ($borrar)");

<<<<<<< HEAD
if ($query != null) {
    header("location:gestionar?ficha=$ficha&trimestre=$trimestre");
} else {
    print "<script>alert(\"No se pudo eliminar.\");window.location='gestionar?ficha=$ficha&trimestre=$trimestre';</script>";
}
=======
    $borrar = implode(",", $_POST['select']);

    $sqlConsulta = "SELECT * FROM eventoficha where IdPrincipal in ($borrar)";
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

            $sql = "DELETE FROM eventoficha WHERE IdPrincipal in ($borrar)"; 
            $query = $link->query($sql);

            if($query!=null && $query2!=null){
                header("location:gestionar.php?ficha=$ficha&trimestre=$trimestre");  
            }else{
                print "<script>alert(\"No se pudo eliminar.\");window.location='gestionar.php?ficha=$ficha&trimestre=$trimestre';</script>";
            }
         }
>>>>>>> 4b81669a2c8b8687c5839e51d8a23bb3c9c2e149
?>

