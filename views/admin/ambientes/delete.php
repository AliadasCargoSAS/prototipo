<?php
require_once "../include/permission-admin.php";
?>

<?php
if (!empty($_GET)) {
    include "../../../extra/conexion.php";

    if ($_GET["IdPrincipal"] == '') {
        echo "<script>window.location = 'gestionar';</script>";
    }

    $sql = "UPDATE ambientes SET Status = 0 WHERE IdPrincipal = " . $_GET["IdPrincipal"];
    $query = $link->query($sql);

    $sql2 = "UPDATE eventoinstructor SET  NumeroAmbiente = '' WHERE substring(NumeroAmbiente,1,3 ) =(Select Numero from ambientes where IdPrincipal = " . $_GET["IdPrincipal"] . " )";
    $query2 = $link->query($sql2);

    $sql3 = "UPDATE eventoficha SET  NumeroAmbiente = '' WHERE substring(NumeroAmbiente,1,3 ) =(Select Numero from ambientes where IdPrincipal = " . $_GET["IdPrincipal"] . " )";
    $query3 = $link->query($sql3);

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

    $sql4 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se elimino el ambiente de formación que se identificaba con el Id ' . $_GET["IdPrincipal"] . '", "El ambiente de formación que se identificaba con el Id ' . $_GET["IdPrincipal"] . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' y todos sus elementos ligados el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer el ambiente", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
    if ($link->query($sql4) === FALSE) {
        echo '<script>alert("No se puedo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
    };

    if ($query != null && $query2 != null && $query3 != null) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar';</script>";
    }
}
?>


    