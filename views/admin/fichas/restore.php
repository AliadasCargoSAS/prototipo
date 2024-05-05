<?php
require_once "../include/permission-admin.php";
?>
<?php
if (!empty($_GET)) {
    include "../../../extra/conexion.php";

    $sql = "UPDATE fichas SET Status = 1 WHERE IdPrincipal = " . $_GET["IdPrincipal"];
    $query = $link->query($sql);
    if ($query != null) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudo restablecer\");window.location='gestionar';</script>";
    }
}
?>
