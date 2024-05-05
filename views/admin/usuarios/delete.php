<?php
require_once "../include/permission-admin.php";
?>

<?php
if (!empty($_GET)) {
    if (($_GET["IdPrincipal"] == '') || ($_GET["Nombre"] == '')) {
        echo "<script>window.location='gestionar'</script>";
    }

    include "../../../extra/conexion.php";

    $sql = "UPDATE usuarios SET Status = 0 WHERE IdPrincipal = " . $_GET["IdPrincipal"];
    $query = $link->query($sql);

    $host = $_SERVER["HTTP_HOST"];
    $url = $_SERVER["REQUEST_URI"];
    $linkelimado = "https://" . $host . $url;
    date_default_timezone_set('America/Bogota');
    $hoy = date('d-m-Y');
    $hoytiempo = date('H:i:s');

    if ($_SESSION['rol'] == 1) {
        $rol = 'administrador';
    } else if ($_SESSION['rol'] == 2) {
        $rol = 'coordinador';
    } else if ($_SESSION['rol'] == 2) {
        $rol = 'instructor';
    };

    $sql2 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se elimino el instructor ' . $_GET["Nombre"] . '", "El instructor ' . $_GET["Nombre"] . ' que se identificaba con el Id ' . $_GET["IdPrincipal"] . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer el usuario", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
    $query = $link->query($sql2);
    if ($query == FALSE) {
        echo '<script>alert("No se puedo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
    };


    if ($query != null) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar';</script>";
    }
}
?>




