
    <?php        
    include "../../../extra/conexion.php"; 

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $IdPrograma = $_POST["IdPrograma"];
        $borrar = implode(",", $_POST['select']);

    if(($borrar == '') || ($_GET["IdPrograma"] == '')){
        echo "<script>window.location='historial?IdPrograma=$IdPrograma';</script>";
    };
   
        $sql = "DELETE FROM competencias WHERE IdCompetencia IN ($borrar)";
        $query = $link->query($sql);

        $sql2 = "DELETE FROM raps WHERE IdPrincipalCompetencias IN ($borrar)";
        $query2 = $link->query($sql2);

        if($query!=null || $query2!=null){
            header("location:historial?IdPrograma=$IdPrograma");  
        }else{
            print "<script>alert(\"No se pudo eliminar\");window.location='historial?IdPrograma=$IdPrograma';</script>";

        }
    }	
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>


