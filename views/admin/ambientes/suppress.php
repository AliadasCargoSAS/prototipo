<?php 
    if(!empty($_GET)){
        if($_GET["IdPrincipal"] == ''){
            echo "<script>window.location = 'historial';</script>";
        };
        include "../../../extra/conexion.php";        
        $sql = "DELETE FROM ambientes WHERE IdPrincipal = ".$_GET["IdPrincipal"];
        $query = $link->query($sql);
        if($query!=null){
            header("location:historial.php");  
            // print "<script>alert(\"Eliminado exitosamente.\");window.location='admin-historial-ambientes.php';</script>";
        }else{
            print "<script>alert(\"No se pudo eliminar.\");window.location='historial.php';</script>";

        }
    }
 
?>

    <?php     
        require_once "../include/permission-admin.php";
    ?>