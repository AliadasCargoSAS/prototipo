<?php
require_once "../include/permission-admin.php";

    include "../../../extra/conexion.php"; 

    $id_competencia = $_POST['competencia'];

    $sql = "SELECT Descripcion from raps where status = 1 and IdPrincipalCompetencias = (SELECT IdCompetencia from competencias where Descripcion = '$id_competencia')";

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<option></option>";
            while($row = mysqli_fetch_array($result)){
                echo "<option>".$row['Descripcion']."</option>";          
            }                               
        }
    }else{
        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
    }
    mysqli_close($link); 
?>