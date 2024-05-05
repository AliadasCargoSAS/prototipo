<?php
require_once "../include/permission-admin.php";
?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $IdCompetencia = $_POST["IdCompetencia"];
        $borrar = implode(",", $_POST['select']);

        if (($borrar == '') || ($IdCompetencia == '')) {
            echo "<script>window.location='../programas/gestionar';</script>";
        }

        include "../../../extra/conexion.php";

        mysqli_query($link, "DELETE FROM raps WHERE IdRae IN ($borrar)");
        header("location:gestionar?IdCompetencia=$IdCompetencia");
        exit();
    }
    ?>




