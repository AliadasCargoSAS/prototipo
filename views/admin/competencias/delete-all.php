<?php
require_once "../include/permission-admin.php";
?>
<?php
include "../../../extra/conexion.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $IdPrograma = $_POST["IdPrograma"];

    $borrar = implode(",", $_POST['select']);
    $cantidadc = count($_POST['select']);

    if ($borrar == '') {
        echo "<script>window.location='gestionar?IdPrograma=$IdPrograma';</script>";
    }

    $sql = "UPDATE competencias SET Status = 0 WHERE IdCompetencia IN ($borrar)";
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
        $descripcionaccion = 'Se eliminaron ' . $cantidadc . ' competencias que se identificaban mediante los IDS ' . $borrar . ' y todos sus elementos ligados por el ' . $rol . ' ' . $_SESSION['nombre'] . ' el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se pueden restablecer las competencias';
        $sql7 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES 
        ("Se eliminaron ' . $cantidadc . ' competencias", "' . $descripcionaccion . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
        $query7 = $link->query($sql7);
    }

    $sql2 = "UPDATE raps SET Status = 0 WHERE IdPrincipalCompetencias IN ($borrar)";
    $query2 = $link->query($sql2);

    $sql3 = "UPDATE eventoinstructor SET Competencia = '' WHERE Competencia IN (select Descripcion from competencias where IdCompetencia IN ($borrar))";
    $query3 = $link->query($sql3);

    $sql4 = "UPDATE eventoficha SET Competencia = '' WHERE Competencia IN (select Descripcion from competencias where IdCompetencia IN ($borrar))";
    $query4 = $link->query($sql4);

    $sql5 = "UPDATE eventoinstructor SET Rap = '' WHERE Rap in (SELECT Descripcion from raps where IdPrincipalCompetencias IN ($borrar))";
    $query5 = $link->query($sql5);

    $sql6 = "UPDATE eventoficha SET Rap = '' WHERE Rap in (SELECT Descripcion from raps where IdPrincipalCompetencias IN ($borrar))";
    $query6 = $link->query($sql6);


    if ($query != null || $query2 != null && $query3 != null && $query4 != null && $query5 != null && $query6 != null && $query7 != null) {
        header("location:gestionar?IdPrograma=$IdPrograma");
    } else {
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar?IdPrograma=$IdPrograma';</script>";
    }
}
?>




