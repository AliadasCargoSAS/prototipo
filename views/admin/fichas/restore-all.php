<?php
require_once "../include/permission-admin.php";
?>
 <?php
    include "../../../extra/conexion.php";
    $borrar = implode(",", $_POST['select']);

    if ($borrar == '') {
        echo "<script>window.location = 'historial';</script>";
    }

    $sql = "UPDATE fichas SET Status = 1 WHERE IdPrincipal IN ($borrar)";
    $query = $link->query($sql);
    if ($query != null) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudieron restablecer\");window.location='gestionar';</script>";
    }
    ?>




