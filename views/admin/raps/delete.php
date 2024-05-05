<?php
require_once "../include/permission-admin.php";
?>
    
<?php
if (!empty($_GET)) {

    if (($_GET["IdCompetencia"] == '') || ($_GET["IdRae"] == '')) {
        echo "<script>window.location='../programas/gestionar';</script>";
    };

    if (isset($_GET["IdCompetencia"]) && !empty(trim($_GET["IdCompetencia"]))) {
        $IdCompetencia =  trim($_GET["IdCompetencia"]);
    }

    include "../../../extra/conexion.php";

    $sql4 = "SELECT Descripcion FROM raps WHERE IdRae = " . $_GET["IdRae"];
    $query4 = mysqli_query($link, $sql4);
    while ($row = mysqli_fetch_array($query4)) {
        $nombrer = $row['Descripcion'];
    }

    $sql = "UPDATE raps SET Status = 0 WHERE IdRae = " . $_GET["IdRae"];
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
        $descripcionaccion = 'El resultado de aprendizaje '.$nombrer.' que se identificaba mediante el ID ' .  $_GET["Rae"] . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' y todos los elementos ligados el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer el resultado de aprendizaje';
        $sql5 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES 
        ("Se elimino el resultado de aprendizaje ' . $nombrer . '", "' . $descripcionaccion . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
        $query5 = $link->query($sql5);
    }

    $sql2 = "UPDATE eventoinstructor SET Rap = '' WHERE Rap = (select Descripcion from raps where IdRae = " . $_GET["IdRae"] . ")";
    $query2 = $link->query($sql2);

    $sql3 = "UPDATE eventoficha SET Rap = '' WHERE Rap = (select Descripcion from raps where IdRae = " . $_GET["IdRae"] . ")";
    $query3 = $link->query($sql3);

    if ($query != null && $query4 != null && $query5 != null) {
        header("location:gestionar?IdCompetencia=$IdCompetencia");
    } else {
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar.php?IdCompetencia=$IdCompetencia';</script>";
    }
}
?>

    

