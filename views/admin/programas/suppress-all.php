<?php
require_once "../include/permission-admin.php";
?>

    <?php
    $borrar = implode(",", $_POST['select']);
    if ($borrar == '') {
        echo "<script>window.location='historial';</script>";
    }

    include "../../../extra/conexion.php";

    $sql = "DELETE FROM programas WHERE IdPrograma IN ($borrar)";
    $query = $link->query($sql);

    $sql2 = "DELETE FROM competencias WHERE IdPrincipalProgramaCompetencia IN ($borrar)";
    $query2 = $link->query($sql2);

    $sql3 = "SELECT IdCompetencia from competencias where IdPrincipalProgramaCompetencia IN ($borrar)";
    $query3 = $link->query($sql3);

    while ($row = mysqli_fetch_array($query3)) {
        $sql4 = "DELETE FROM raps WHERE IdPrincipalCompetencias = " . $row['IdCompetencia'];
        $query4 = $link->query($sql4);
    }
    
    if($query!=null || $query2!=null || $query4!=null){
        header("location:historial");  
    }else{
        print "<script>alert(\"No se pudo eliminar\");window.location='historial';</script>";
    }
    ?>



