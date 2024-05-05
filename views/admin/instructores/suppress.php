<?php 
    if(!empty($_GET)){
        include "../../../extra/conexion.php"; 

        if($_GET["IdPrincipal"] == ''){
            echo '<script>window.location="historial";</script>';
        }
        
        $sql = "DELETE FROM instructores WHERE IdPrincipal = ".$_GET["IdPrincipal"];
        $query = $link->query($sql);
        if($query!=null){
            header("location:gestionar");  
            // print "<script>alert(\"Eliminado exitosamente.\");window.location='gestionar.php';</script>";
        }else{
            print "<script>alert(\"No se pudo eliminar\");window.location='historial';</script>";

        }
    }else{                    
        echo '<script>window.location="historial";</script>';
    }
?>


<?php     
        require_once "../include/permission-admin.php";
    ?>