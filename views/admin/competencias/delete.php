<?php
require_once "../include/permission-admin.php";
?>
<?php
if (!empty($_GET)) {
    include "../../../extra/conexion.php";

    if (isset($_GET["IdPrograma"]) && !empty(trim($_GET["IdPrograma"]))) {
        $IdPrograma =  trim($_GET["IdPrograma"]);
    }

    if (($_GET["IdPrograma"] == '') || ($_GET["IdCompetencia"] == '')) {
        echo "<script>window.location = 'gestionar?IdPrograma=$IdPrograma';</script>";
    }

    $sql7 = "SELECT Descripcion FROM competencias WHERE IdCompetencia = " . $_GET["IdCompetencia"];
    $query7 = mysqli_query($link, $sql7);
    while ($row = mysqli_fetch_array($query7)) {
        $nombrec = $row['Descripcion'];
    }

    $sql = "UPDATE competencias SET Status = 0 WHERE IdCompetencia = " . $_GET["IdCompetencia"];
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
        $descripcionaccion = 'La competencia '.$nombrec.' que se identificaba mediante el ID ' .  $_GET["IdCompetencia"] . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' y todos los elementos ligados el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer la competencia';
        $sql8 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES 
        ("Se elimino la competencia ' . $nombrec . '", "' . $descripcionaccion . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
        $query8 = $link->query($sql8);
    }

    $sql2 = "UPDATE raps SET Status = 0 WHERE IdPrincipalCompetencias = " . $_GET["IdCompetencia"];
    $query2 = $link->query($sql2);

    $sql3 = "UPDATE eventoinstructor SET Competencia = '' WHERE Competencia = (select Descripcion from competencias where IdCompetencia = " . $_GET["IdCompetencia"] . ")";
    $query3 = $link->query($sql3);

    $sql4 = "UPDATE eventoficha SET Competencia = '' WHERE Competencia = (select Descripcion from competencias where IdCompetencia = " . $_GET["IdCompetencia"] . ")";
    $query4 = $link->query($sql4);

    $sql5 = "UPDATE eventoinstructor SET Rap = '' WHERE Rap in (SELECT Descripcion from raps where IdPrincipalCompetencias = " . $_GET["IdCompetencia"] . ")";
    $query5 = $link->query($sql5);

    $sql6 = "UPDATE eventoficha SET Rap = '' WHERE Rap in (SELECT Descripcion from raps where IdPrincipalCompetencias = " . $_GET["IdCompetencia"] . ")";
    $query6 = $link->query($sql6);


    if ($query != null || $query2 != null && $query3 != null && $query4 != null && $query5 != null && $query6 != null && $query7 != null && $query8 != null) {
        header("location:gestionar?IdPrograma=$IdPrograma");
    } else {
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar?IdPrograma=$IdPrograma';</script>";
    }
} else {
    echo "<script>window.location = 'gestionar?IdPrograma=$IdPrograma';</script>";
}
?>
<?php
