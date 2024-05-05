<?php 
    if(!empty($_GET)){
        include "../../../extra/conexion.php"; 
        
        $sql = "UPDATE instructores SET Status = 1 WHERE IdPrincipal = ".$_GET["IdPrincipal"];
        $query = $link->query($sql);
        if($query!=null){
            header("location:gestionar.php");  
            // print "<script>alert(\"Eliminado exitosamente.\");window.location='admin-gestionar-instructores.php';</script>";
        }else{
            print "<script>alert(\"No se pudo restablecer.\");window.location='gestionar.php';</script>";

        }
    }
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>