<?php
require_once "../include/permission-admin.php";
?>
    <?php
    $borrar = implode("' ,'", $_POST["select"]);
    $nombres = implode(", ", $_POST['select']);
    $numerousuarios = count($_POST['select']);

    if ($borrar == '') {
        echo "<script>window.location='gestionar'</script>";
    }
    include "../../../extra/conexion.php";

    $sql = "UPDATE usuarios SET Status = '0' WHERE Nombre IN ('$borrar')";
    $query = $link->query($sql);
    if ($query == FALSE) {
        echo '<script>alert("No se pudieron eliminar los usuarios, más detalles del error > ' . $link->error . '")</script>';
    };

    $host = $_SERVER["HTTP_HOST"];
    $url = $_SERVER["REQUEST_URI"];
    $linkelimado = "https://" . $host . $url;
    date_default_timezone_set('America/Bogota');
    $hoy = date('d-m-Y');
    $hoytiempo = date('H:i:s');

    if ($_SESSION['rol'] == 1) {
        $rol = 'administrador';
    }else if ($_SESSION['rol'] == 2) {
        $rol = 'coordinador';
    }else if ($_SESSION['rol'] == 2) {
        $rol = 'instructor';
    };


    $sql2 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se eliminaron ' . $numerousuarios . ' usuarios", "Los instructores ' . $nombres . ' fueron eliminados por el ' . $rol . ' ' . $_SESSION['nombre'] . ' el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se pueden restablecer los usuarios", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
    $query = $link->query($sql2);
    if ($query == FALSE) {
        echo '<script>alert("No se pudo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
    };

    header('location:gestionar');
    ?>




