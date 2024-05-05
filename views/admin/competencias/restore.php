<?php 

    if(isset($_GET["IdPrograma"]) && !empty(trim($_GET["IdPrograma"]))){
        $IdPrograma =  $_GET["IdPrograma"];     
    }

    if(($_GET["IdPrograma"] == '') || ($_GET["IdCompetencia"] == '')){
        echo "<script>window.location = 'historial?IdPrograma=$IdPrograma';</script>"; 
    };

    if(!empty($_GET)){
        include "../../../extra/conexion.php"; 
        
        $sql = "UPDATE competencias SET Status = 1 WHERE IdCompetencia = ".$_GET["IdCompetencia"];
        $query = $link->query($sql);

        $sql2 = "UPDATE raps SET Status = 1 WHERE IdPrincipalCompetencias = ".$_GET["IdCompetencia"];
        $query2 = $link->query($sql2);

        if($query!=null || $query2!=null){
            header("location:gestionar.php?IdPrograma=$IdPrograma");
            exit();	             
        }else{
            print "<script>alert(\"No se pudo restablecer.\");window.location='gestionar?IdPrograma=$IdPrograma';</script>";

        }
    }else{
        echo "<script>window.location = 'historial?IdPrograma=$IdPrograma';</script>";
    }
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>