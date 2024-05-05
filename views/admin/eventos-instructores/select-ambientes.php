<?php
    
    require_once "../include/permission-admin.php";
   
    $ficha = $_POST['ficha'];

    include "../../../extra/conexion.php";

    $sql = "SELECT Numero, Tipo, Torre from ambientes where status = 1 and Capacidad <= (Select Aprendices from fichas where Numero = '$ficha')";

    if($result = mysqli_query($link, $sql)){
        if(mysqli_num_rows($result) > 0){
            echo "<option></option>";
            while($row = mysqli_fetch_array($result)){
                echo "<option>".$row['Numero'].' - Sala de '.$row['Tipo'].' Torre '.$row['Torre']."</option>";          
            }                    
        }
    }else{
        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
    }
    mysqli_close($link);

?>