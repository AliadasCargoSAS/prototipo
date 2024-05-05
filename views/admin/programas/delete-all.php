<?php
require_once "../include/permission-admin.php";

$borrar = implode(",", $_POST['select']);
$cantidadp = count($_POST['select']);

if ($borrar == '') {
    echo "<script>window.location='gestionar';</script>";
}

include "../../../extra/conexion.php";
$sql = "UPDATE programas SET Status = 0 WHERE IdPrograma in ($borrar)";
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
    $descripcionaccion = 'Se eliminaron '.$cantidadp.' programas de formación que se identificaban mediante los IDS '.$borrar.' y todos sus elementos ligados por el '.$rol.' '.$_SESSION['nombre'].' el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link '.$linkelimado.' • Se pueden restablecer los programas';
    $sql9 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES 
    ("Se eliminaron '.$cantidadp.' programas de formación", "' . $descripcionaccion . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
    $query9 = $link->query($sql9);
}

$sql2 = "UPDATE competencias SET Status = 0 WHERE IdPrincipalProgramaCompetencia in ($borrar)";
$query2 = $link->query($sql2);

$sql3 = "SELECT IdCompetencia from competencias where IdPrincipalProgramaCompetencia in ($borrar)";
$query3 = mysqli_query($link, $sql3);

while ($row = mysqli_fetch_array($query3)) {
    $sql4 = "UPDATE raps SET Status = 0 WHERE IdPrincipalCompetencias = " . $row['IdCompetencia'];
    $query4 = $link->query($sql4);
}

$sql5 = "UPDATE proyectos SET Status = 0 WHERE IdPrincipalProgramaProyecto in ($borrar)";
$query5 = $link->query($sql5);
    $sql6 = "UPDATE fichas SET Programa = '' where Programa in (Select Nombre from programas where Idprograma in ($borrar))";
    $query6 = $link->query($sql6);

$sql6 = "UPDATE fichas SET Programa = '' where Programa in (Select Nombre from programas where Idprograma in ($borrar))";
$query6 = $link->query($sql6);

$sql7 = "DELETE FROM eventoinstructor where Programa in (Select Nombre from programas where Idprograma in ($borrar))";
$query7 = $link->query($sql7);

$sql8 = "DELETE FROM eventoficha where CabeceraFicha in (Select Numero from fichas where Programa in (select Nombre from programas where IdPrograma in ($borrar))";
$query8 = $link->query($sql8);
    if($query!=null || $query2!=null || $query4!=null && $query6!=null && $query7!=null && $query8!=null){
        header("location:gestionar");  
    }else{
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar';</script>";
    }

if ($query != null || $query2 != null || $query4 != null || $query5 != null && $query6 != null && $query7 != null && $query8 != null && $query9 != null) {
    header("location:gestionar");
} else {
    print "<script>alert(\"No se pudo eliminar\");window.location='gestionar';</script>";
}

?>

