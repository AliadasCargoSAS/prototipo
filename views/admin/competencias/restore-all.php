
    <?php        
    include "../../../extra/conexion.php"; 

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $IdPrograma = $_POST["IdPrograma"];
        $borrar = implode(",", $_POST['select']);

        echo $borrar;

        if($borrar == ''){
           // echo "<script>window.location='gestionar?IdPrograma=$IdPrograma';</script>";
        }
        
        $sql = "UPDATE competencias SET Status = 1 WHERE IdCompetencia IN ($borrar)"; 
        $query = $link->query($sql);

        $sql2 = "UPDATE raps SET Status = 1 WHERE IdPrincipalCompetencias IN ($borrar)";
        $query2 = $link->query($sql2);
        

        if($query!=null || $query2!=null){
           // header("location:gestionar?IdPrograma=$IdPrograma");  
        }else{
            //print "<script>alert(\"No se pudo eliminar\");window.location='gestionar?IdPrograma=$IdPrograma';</script>";

        }
    }	
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>


