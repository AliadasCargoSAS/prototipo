<?php
require_once "../include/permission-admin.php";
?>
    <?php
    include "../../../extra/conexion.php";
    $borrar = implode(",", $_POST['select']);
    $numerofichas = count($_POST['select']);

    if ($borrar == '') {
        echo "<script>window.location = 'gestionar';</script>";
    };

    $sql = "UPDATE fichas SET Status = 0 WHERE IdPrincipal IN ($borrar)";
    $query = $link->query($sql);

    $sql1 = "SELECT Numero FROM fichas WHERE IdPrincipal IN ($borrar)";
    if ($result = mysqli_query($link, $sql1)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {                
                $fichasaeliminar = $row['Numero'];                
            }
        }
    } else {
        echo '<script>alert("No se puedieron consultar los numeros de las fichas, más detalles del error > ' . $link->error . '")</script>';
    }

    $sql2 = "DELETE FROM eventoficha WHERE CabeceraFicha IN (SELECT Numero FROM fichas WHERE IdPrincipal IN ($borrar))";
    if ($link->query($sql2) === FALSE) {
        echo '<script>alert("No se puedieron eliminar los eventos de las fichas ' . $fichasaeliminar . ', más detalles del error > ' . $link->error . '")</script>';
    };

    $sql3 = "UPDATE eventoinstructor SET NumeroFicha = '' WHERE NumeroFicha IN (SELECT Numero FROM fichas WHERE IdPrincipal IN ($borrar))";
    if ($link->query($sql3) === FALSE) {
        echo '<script>alert("No se puedieron eliminar los eventos del instructor de las fichas ' . $fichasaeliminar . ', más detalles del error > ' . $link->error . '")</script>';
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

    $sql4 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se eliminaron ' . $numerofichas . ' fichas", "Se eliminaron ' . $numerofichas . ' que se identificaban mediante los numeros ' . $fichasaeliminar . ' y todos sus elementos ligados por el '.$rol.' '.$_SESSION['nombre'].' el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link '.$linkelimado.' • Se pueden restablecer las fichas", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
    if ($link->query($sql4) === FALSE) {
        echo '<script>alert("No se puedo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
    };

    if ($query === TRUE) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudieron eliminar\");window.location='gestionar';</script>";
    }
    ?>



