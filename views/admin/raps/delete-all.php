<?php
require_once "../include/permission-admin.php";
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $IdCompetencia = $_POST["IdCompetencia"];
    $borrar = implode(",", $_POST['select']);
    $cantidadr = count($_POST['select']);

    if (($borrar == '') || ($IdCompetencia == '')) {
        echo "<script>window.location='../programas/gestionar';</script>";
    }

    include "../../../extra/conexion.php";

    $sql = "UPDATE raps SET Status = 0 WHERE IdRae IN ($borrar)";
    $query = $link->query($sql);
    if ($link->query($sql) === TRUE) {
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
        $descripcionaccion = 'Se eliminaron ' . $cantidadr . ' resultados de aprendizaje que se identificaban mediante los IDS ' . $borrar . ' y todos sus elementos ligados por el ' . $rol . ' ' . $_SESSION['nombre'] . ' el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se pueden restablecer los resultados de aprendizaje';
        $sql4 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES 
        ("Se eliminaron ' . $cantidadr . ' resultados de aprendizaje", "' . $descripcionaccion . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
        $query4 = $link->query($sql4);
    }

    $sql2 = "UPDATE eventoinstructor SET Rap = '' WHERE Rap IN (select Descripcion from raps where IdRae IN ($borrar))";
    $query2 = $link->query($sql2);

    $sql3 = "UPDATE eventoficha SET Rap = '' WHERE Rap IN (select Descripcion from raps where IdRae IN ($borrar))";
    $query3 = $link->query($sql3);

    if ($query != null && $query4 != null) {
        header("location:gestionar.php?IdCompetencia=$IdCompetencia");
    } else {
        print "<script>alert(\"No se pudieron eliminar\");window.location='gestionar.php?IdCompetencia=$IdCompetencia';</script>";
    }
}
?>




