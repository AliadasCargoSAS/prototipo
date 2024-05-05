<?php
require_once "../include/permission-admin.php";
?>
<?php

$borrar = implode("' ,'", $_POST["select"]);
$instructoresaeliminar = implode(",", $_POST["select"]);
$numeroinstructores = count($_POST['select']);

if (($borrar == '')) {
    echo "<script>window.location='gestionar';</script>";
}

include "../../../extra/conexion.php";

$sqlborrartodos = "UPDATE instructores SET Status = '0' WHERE Nombre IN ('$borrar')";
if ($link->query($sqlborrartodos) === FALSE) {
    echo '<script>alert("No se pudo eliminar los instructores, más detalles del error > ' . $link->error . '")</script>';
};


$sqldeleteevento = "DELETE FROM eventoinstructor WHERE CabeceraInstructor IN ('$borrar')";
if ($link->query($sqldeleteevento) === FALSE) {
    echo '<script>alert("No se pudo eliminar los eventos de los instructores ' . $instructoresaeliminar . ', más detalles del error > ' . $link->error . '")</script>';
};

$sqlupdateevento = "UPDATE eventoficha SET Instructor = '' WHERE Instructor IN ('$borrar')";
if ($link->query($sqlupdateevento) === FALSE) {
    echo '<script>alert("No se pudo eliminar los eventos de la ficha ligado a los instructores ' . $instructoresaeliminar . ', más detalles del error > ' . $link->error . '")</script>';
};

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

$descripcionins = 'Se eliminaron los instructores ' . $instructoresaeliminar . ' y todos sus elementos ligados el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se pueden restablecer los instructores';

$sqla = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se eliminaron ' . $numeroinstructores . ' instructores", "' . $descripcionins . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
if ($link->query($sqla) === FALSE) {
    echo '<script>alert("No se puedo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
}

header('location:gestionar');
?>




