<?php
require_once "../include/permission-admin.php";
?>

<?php
if (!empty($_GET)) {
    if ($_GET["IdPrincipal"] == '') {
        echo "<script>window.location='gestionar'</script>";
    }
    include "../../../extra/conexion.php";

    $sql = "UPDATE usuarios SET Status = 1 WHERE IdPrincipal = " . $_GET["IdPrincipal"];
    $query = $link->query($sql);
    if ($query != null) {
        header("location:gestionar.php");
    } else {
        print "<script>alert(\"No se pudo restablecer.\");window.location='gestionar.php';</script>";
    }
}
?>

