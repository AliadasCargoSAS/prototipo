    <?php
    require_once "../include/permission-admin.php";
    ?>

    <?php

    if (!empty($_GET)) {
        include "../../../extra/conexion.php";

        $idinstructorx = $_GET["IdPrincipal"];
        $nombreinstructorx = trim($_GET["Nombre"]);

        if (($idinstructorx == '') || ($nombreinstructorx == '')) {
            echo '<script>window.location="gestionar";</script>';
        };

        $sqlforeigkey = "SET FOREIGN_KEY_CHECKS = OFF";
        if ($link->query($sqlforeigkey) === TRUE) {
        } else {
            echo '<script>alert("No se puedo desactivar la verificación de las llaves foraneas, más detalles del error > ' . $link->error . '")</script>';
        }

        $sqlcount1 = "SELECT COUNT(IdPrincipal) FROM eventoinstructor WHERE CabeceraInstructor = '$nombreinstructorx'";
        if ($resultcount1 = mysqli_query($link, $sqlcount1)) {
            if (mysqli_num_rows($resultcount1) > 0) {
                while ($row = mysqli_fetch_array($resultcount1)) {
                    $numEventos1 = $row['COUNT(IdPrincipal)'];
                }
            }
        };

        $sqldeleteevento = "DELETE FROM eventoinstructor WHERE CabeceraInstructor = '$nombreinstructorx'";
        if ($link->query($sqldeleteevento) === FALSE) {
            echo '<script>alert("No se pudo eliminar los eventos de instructor ' . $nombreinstructorx . ', más detalles del error > ' . $link->error . '")</script>';
        };
        $sqlupdateevento = "UPDATE eventoficha SET Instructor = '' WHERE Instructor = '$nombreinstructorx'";
        if ($link->query($sqlupdateevento) === FALSE) {
            echo '<script>alert("No se pudo eliminar los eventos de la ficha ligado al instructor ' . $nombreinstructorx . ', más detalles del error > ' . $link->error . '")</script>';
        };

        $sqldelete = "UPDATE instructores SET Status = 0 WHERE IdPrincipal = $idinstructorx";
        if ($link->query($sqldelete) === TRUE) {
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

            if (($numEventos1 <= 0)) {
                $descripcionaccion = 'El instructor ' . $nombreinstructorx . ' que se identificaba mediante el ID ' . $idinstructorx . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer el instructor';
            } else if (($numEventos1 > 0)) {
                $descripcionaccion = 'El instructor ' . $nombreinstructorx . ' que se identificaba mediante el ID ' . $idinstructorx . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ', se eliminaron ' . $numEventos1 . ' eventos de instructor el ' . $hoy . ' a las' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer el instructor';
            } else {
                $descripcionaccion = 'El instructor ' . $nombreinstructorx . ' que se identificaba mediante el ID ' . $idinstructorx . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' y todos sus elementos ligadoe a el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer el instructor';
            }


            $sqla = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES ("Se elimino el instructor ' . $nombreinstructorx . '", "' . $descripcionaccion . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
            if ($link->query($sqla) === FALSE) {
                echo '<script>alert("No se puedo insertar la acción, más detalles del error > ' . $link->error . '")</script>';
            }

            echo '<script>window.location="gestionar";</script>';
        } else {
            echo '<script>alert("No se puedo eliminar el instructor, más detalles del error > ' . $link->error . '")</script>';
        }
    };

    ?>

