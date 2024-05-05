<?php
require_once "../include/permission-admin.php";
?>

<?php
$borrar = implode(",", $_POST['select']);
if ($borrar == '') {
    echo "<script>window.location='gestionar'</script>";
}
include "../../../extra/conexion.php";

mysqli_query($link, "UPDATE usuarios SET Status = 1 WHERE IdPrincipal IN ($borrar)");
header('location:gestionar.php');
?>




