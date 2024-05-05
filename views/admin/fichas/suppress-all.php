
<?php
require_once "../include/permission-admin.php";
?>
    <?php
    include "../../../extra/conexion.php";
    $borrar = implode(",", $_POST['select']);
    if ($borrar == '') {
        echo "<script>window.location = 'historial'</script>";
    }

    $sql = "DELETE FROM fichas WHERE IdPrincipal IN ($borrar)";
    $query = $link->query($sql);
    if ($query != null) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudieron eliminar\");window.location='gestionar';</script>";
    }

    ?>




