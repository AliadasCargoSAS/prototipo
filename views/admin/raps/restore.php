<?php
require_once "../include/permission-admin.php";
?>

<?php
if (!empty($_GET)) {

    if (($_GET["IdCompetencia"] == '') || ($_GET["IdRae"] == '')) {
        echo "<script>window.location='../programas/gestionar';</script>";
    }

    if (isset($_GET["IdCompetencia"]) && !empty(trim($_GET["IdCompetencia"]))) {
        $IdCompetencia =  trim($_GET["IdCompetencia"]);
    }

    include "../../../extra/conexion.php";

    $sql = "UPDATE raps SET Status = 1 WHERE IdRae = " . $_GET["IdRae"];
    $query = $link->query($sql);
    if ($query != null) {
        header("location:gestionar.php?IdCompetencia=$IdCompetencia");
    } else {
        print "<script>alert(\"No se pudo restablecer\");window.location='gestionar.php?IdCompetencia=$IdCompetencia';</script>";
    }
}
?>

