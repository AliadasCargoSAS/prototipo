<?php
    include "../../../extra/conexion.php";

        $ficha = $_POST['ficha'];
        $trimestre = $_POST['trimestre'];

        $fechaInicio = $_POST['fechaInicio'];
        $fechaFin = $_POST['fechaFin'];

        if($fechaInicio != "" && $fechaFin != ""){

            $sqlFechas = "UPDATE eventoficha SET fechaInicio = '$fechaInicio', fechaFin = '$fechaFin' WHERE CabeceraFicha = '$ficha' and trimestreFicha = '$trimestre'";
            $query = $link->query($sqlFechas);

            if ($query != null) {
                header("location:gestionar?ficha=$ficha&trimestre=$trimestre");  
            }else{
                echo "<script>alert('Ups, ocurrio algo, intentelo de nuevo mas tarde');window.location = 'gestionar?ficha=$ficha&trimestre=$trimestre';</script>";
            }
        }else{
            echo "<script>alert('Ups, ocurrio algo, al parecer no se definieron las fechas y estan vacias');window.location = 'gestionar?ficha=$ficha&trimestre=$trimestre';</script>";
        }

?>