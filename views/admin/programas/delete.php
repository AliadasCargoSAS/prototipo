<?php
require_once "../include/permission-admin.php";
?>
<?php
if (!empty($_GET)) {
    if ($_GET["IdPrograma"] == '') {
        echo "<script>window.location='gestionar';</script>";
    }

    include "../../../extra/conexion.php";

    $sql9 = "SELECT Nombre FROM programas WHERE IdPrograma = " . $_GET["IdPrograma"];
    $query9 = mysqli_query($link, $sql9);
    while ($row = mysqli_fetch_array($query9)) {
        $nombrep = $row['Nombre'];
    }

    $sql = "UPDATE programas SET Status = 0 WHERE IdPrograma = " . $_GET["IdPrograma"];
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
        $descripcionaccion = 'El programa de formación ' . $nombrep . ' que se identificaba mediante el ID ' .  $_GET["IdPrograma"] . ', fue eliminado por el ' . $rol . ' ' . $_SESSION['nombre'] . ' y todos los elementos ligados el ' . $hoy . ' a las ' . $hoytiempo . ' mediante el link ' . $linkelimado . ' • Se puede restablecer el programa';
        $sql10 = 'INSERT INTO notificaciones (Accion, Descripcion, Autor, Link) VALUES 
        ("Se elimino el programa de formación ' . $nombrep . '", "' . $descripcionaccion . '", "' . $_SESSION['nombre'] . '", "' . $linkelimado . '")';
        $query10 = $link->query($sql10);
    }

    $sql2 = "UPDATE competencias SET Status = 0 WHERE IdPrincipalProgramaCompetencia  = " . $_GET["IdPrograma"];
    $query2 = $link->query($sql2);

    $sql3 = "SELECT IdCompetencia from competencias where IdPrincipalProgramaCompetencia = " . $_GET["IdPrograma"];
    $query3 = mysqli_query($link, $sql3);

    while ($row = mysqli_fetch_array($query3)) {
        $sql4 = "UPDATE raps SET Status = 0 WHERE IdPrincipalCompetencias = " . $row['IdCompetencia'];
        $query4 = $link->query($sql4);
    }

    $sql5 = "UPDATE proyectos SET Status = 0 WHERE IdPrincipalProgramaProyecto  = " . $_GET["IdPrograma"];
    $query5 = $link->query($sql5);

    $sql6 = "UPDATE fichas SET Programa = '' where Programa = (Select Nombre from programas where Idprograma =" . $_GET["IdPrograma"] . ")";
    $query6 = $link->query($sql6);

    $sql7 = "DELETE FROM eventoinstructor where Programa = (Select Nombre from programas where IdPrograma=" . $_GET["IdPrograma"] . ")";
    $query7 = $link->query($sql7);

    $sql8 = "DELETE FROM eventoficha where CabeceraFicha in (Select Numero from fichas where Programa = (select Nombre from programas where IdPrograma=" . $_GET["IdPrograma"] . "))";
    $query8 = $link->query($sql8);




    if ($query != null || $query2 != null || $query4 != null || $query5 != null && $query6 != null && $query7 != null && $query8 != null && $query9 != null && $query10 != null) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudo eliminar\");window.location='gestionar';</script>";
    }
}
?>