<?php
require_once "../include/permission-admin.php";
?>
<?php
if (!empty($_GET)) {
    if ($_GET["IdPrincipal"] == '') {
        echo "<script>window.location='gestionar'</script>";
    }
    include "../../../extra/conexion.php";
    $sql = "DELETE FROM usuarios WHERE IdPrincipal = " . $_GET["IdPrincipal"];
    $query = $link->query($sql);
    if ($query != null) {
        header("location:historial.php");
    } else {
        print "<script>alert(\"No se pudo eliminar.\");window.location='historial.php';</script>";
    }
}
?>

    