<?php
require_once "../include/permission-admin.php";
?>

<?php
if (!empty($_GET)) {
    include "../../../extra/conexion.php";

    $numero = $_GET["Numero"];

    if (($_GET["IdPrincipal"] == '') || ($_GET["Numero"] == '')) {
        echo "<script>window.location='gestionar'</script>";
    }

    $sql = "UPDATE fichas SET Status = 0 WHERE IdPrincipal = " . $_GET["IdPrincipal"];
    $query = $link->query($sql);

    $sql2 = "DELETE FROM eventoficha WHERE CabeceraFicha = '$numero'";
    if ($link->query($sql2) === FALSE) {
        echo '<script>alert("No se puede eliminar los eventos de ficha ligados > ' . $link->error . '")</script>';
    }

    $sql3 = "UPDATE eventoinstructor SET NumeroFicha = '' WHERE NumeroFicha = '$numero'";
    if ($link->query($sql3) === FALSE) {
        echo '<script>alert("No se puede eliminar los eventos de instructores ligados > ' . $link->error . '")</script>';
    }

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

    $sql4 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se elimino la ficha ' . $_GET["Numero"] . '", "La ficha ' . $_GET["Numero"] . ' que se identificaba con el Id ' . $_GET["IdPrincipal"] . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' y todos sus elementos ligados el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer la ficha", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
    if ($link->query($sql4) === FALSE) {
        echo '<script>alert("No se puedo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
    };

    if (($query != null)) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar';</script>";
    }
}
?>