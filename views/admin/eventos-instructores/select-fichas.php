<?php
require_once "../include/permission-admin.php";

    include "../../../extra/conexion.php"; 

    $programa = $_POST['programa'];

    $sql = "SELECT Numero from fichas where status = 1 and Programa = '$programa'";

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<option></option>";
            while($row = mysqli_fetch_array($result)){
                echo "<option>".$row['Numero']."</option>";          
            }                                        
        }
    }else{
        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
    }
    mysqli_close($link); 
?>