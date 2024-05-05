
<?php
    include "../../../extra/conexion.php";

        $instructor = $_POST['instructor'];

        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];

        if($fechaInicio != "" && $fechaFin != ""){

            $sqlFechas = "UPDATE eventoinstructor SET fechaInicio = '$fechaInicio', fechaFin = '$fechaFin' WHERE CabeceraInstructor = '$instructor'";
            $query = $link->query($sqlFechas);

            if ($query != null) {
                header("location:gestionar?Instructor=$instructor");  
            }else{
                echo "<script>alert('Ups, ocurrio algo, intentelo de nuevo mas tarde');window.location = 'gestionar?Instructor=$instructor';</script>";
            }
        }else{
            echo "<script>alert('Ups, ocurrio algo, al parecer no se definieron las fechas y estan vacias');window.location = 'gestionar?Instructor=$instructor';</script>";
        }
?>