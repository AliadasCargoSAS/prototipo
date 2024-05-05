<?php 
    if(!empty($_GET)){
        include "../../../extra/conexion.php"; 
        
        if($_GET["IdPrincipal"] == ''){
            echo "<script>window.location = 'gestionar';</script>";
        }
        
        $sql = "UPDATE ambientes SET Status = 1 WHERE IdPrincipal = ".$_GET["IdPrincipal"];
        $query = $link->query($sql);
        if($query!=null){
            header("location:gestionar.php");  
            // print "<script>alert(\"Eliminado exitosamente.\");window.location='admin-gestionar-ambientes.php';</script>";
        }else{
            print "<script>alert(\"No se pudo restablecer.\");window.location='gestionar.php';</script>";

        }
    }else{        
        echo "<script>window.location = 'gestionar';</script>";        
    }
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>