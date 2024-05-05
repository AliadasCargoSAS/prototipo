<?php
require_once "../include/permission-admin.php";
?>

<?php
include "../../../extra/conexion.php";
$borrar = implode(",", $_POST['select']);
$numeroambientes = count($_POST['select']);

if ($borrar == '') {
    echo "<script>window.location = 'gestionar';</script>";
};

$sql = "UPDATE ambientes SET Status = 0 WHERE IdPrincipal IN ($borrar)";
$query = $link->query($sql);

$sql2 = "UPDATE eventoinstructor SET  NumeroAmbiente = '' WHERE substring(NumeroAmbiente,1,3 ) IN (Select Numero from ambientes where IdPrincipal IN ($borrar))";
$query2 = $link->query($sql2);

$sql3 = "UPDATE eventoficha SET  NumeroAmbiente = '' WHERE substring(NumeroAmbiente,1,3 ) IN (Select Numero from ambientes where IdPrincipal IN ($borrar))";
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

$sql4 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se eliminaron ' . $numeroambientes . ' ambientes de formación", "Se eliminaron ' . $numeroambientes . ' de formación que se identificaban mediante los Ids ' . $borrar . ' y todos sus elementos ligados el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se pueden restablecer los ambientes", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
if ($link->query($sql4) === FALSE) {
    echo '<script>alert("No se puedo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
};

if ($query != null && $query2 != null && $query3 != null) {
    header("location:gestionar");
} else {
    print "<script>alert(\"No se pudo eliminar.\");window.location='gestionar';</script>";
}


?>



