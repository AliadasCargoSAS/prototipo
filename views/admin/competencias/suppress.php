<?php 
    if(!empty($_GET)){
        include "../../../extra/conexion.php";  
        
        if(isset($_GET["IdPrograma"]) && !empty(trim($_GET["IdPrograma"]))){
            $IdPrograma =  trim($_GET["IdPrograma"]);
        };

        if(($_GET["IdCompetencia"] == '') || ($_GET["IdPrograma"] == '')){
            echo "<script>window.location='historial?IdPrograma=$IdPrograma';</script>";
        };

        $sql = "DELETE FROM competencias WHERE IdCompetencia = ".$_GET["IdCompetencia"];
        $query = $link->query($sql);

        $sql2 = "DELETE FROM raps WHERE IdPrincipalCompetencias = ".$_GET["IdCompetencia"];
        $query2 = $link->query($sql2);

        if($query!=null || $query2!=null){
            header("location:historial.php?IdPrograma=$IdPrograma");  
        }else{
            print "<script>alert(\"No se pudo eliminar.\");window.location='historial?IdPrograma=$IdPrograma';</script>";

        }
    }else{
        echo "<script>window.location='historial?IdPrograma=$IdPrograma';</script>";
    }
?>

    <?php     
        require_once "../include/permission-admin.php";
    ?>