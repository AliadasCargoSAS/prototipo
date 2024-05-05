<?php
require_once "../include/permission-admin.php";
?>
<?php
if (!empty($_GET)) {
    include "../../../extra/conexion.php";

    $sql = "UPDATE programas SET Status = 1 WHERE IdPrograma = " . $_GET["IdPrograma"];
    $query = $link->query($sql);

    $sql2 = "UPDATE competencias SET Status = 1 WHERE IdPrincipalProgramaCompetencia  = " . $_GET["IdPrograma"];
    $query2 = $link->query($sql2);

    $sql3 = "SELECT IdCompetencia from competencias where IdPrincipalProgramaCompetencia = " . $_GET["IdPrograma"];
    $query3 = mysqli_query($link, $sql3);

    while ($row = mysqli_fetch_array($query3)) {
        $sql4 = "UPDATE raps SET Status = 1 WHERE IdPrincipalCompetencias = " . $row['IdCompetencia'];
        $query4 = $link->query($sql4);
    }

    if ($query != null || $query2 != null || $query4 != null) {
        header("location:gestionar");
    } else {
        print "<script>alert(\"No se pudo restablecer\");window.location='gestionar';</script>";
    }
}

?>

