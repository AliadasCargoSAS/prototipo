<?php

    //Hacemos el procedimiento de la inserción del archivo excel a la base de datos	

      if (isset($_POST['importar'])) {
        $nombreArchivo=$_FILES['archivo']['name'] ;
        $idPrograma = $_POST['IdPrograma'];

		  if (isset($nombreArchivo )&& !empty($nombreArchivo) )  {
			  $Tipo_archivo=$_FILES['archivo']['type'];
			  $temp_archivo=$_FILES['archivo']['tmp_name'];

			  if (!((strpos($Tipo_archivo, "msexcel") || strpos($Tipo_archivo, "vnd.ms-excel")  || strpos($Tipo_archivo, "xls")  || strpos($Tipo_archivo, "vnd.openxmlformats-officedocument.spreadsheetml.sheet")
			  || strpos($Tipo_archivo, "x-msexcel")   || strpos($Tipo_archivo, "x-ms-Excel") || strpos($Tipo_archivo, "x-Excel") || strpos($Tipo_archivo, "x-dos_ms_Excel") || strpos($Tipo_archivo, "x-xls" )))){
          echo '<script>alert("La extensión de los archivos no es correcta.\n\n- Se permiten archivos .xlsx, .xls");window.location="gestionar?IdPrograma='.$idPrograma.'";</script>';
				  
			  }else {
					 
                    if (move_uploaded_file($temp_archivo, '../../../extra/archivos_excel_importados/'.$nombreArchivo)) {
                    
                        require '../../../extra/Classes/PHPExcel/IOFactory.php';
                        require '../../../extra/conexion.php';
                        
                        $objPHPExcel = PHPEXCEL_IOFactory::load('../../../extra/archivos_excel_importados/'.$nombreArchivo);
                        
                        $objPHPExcel->setActiveSheetIndex(0);
                        
                        $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();                        
                        
                        for($i = 2; $i <= $numRows; $i++){
                            
                            $id = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
                            $descripcion = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
                            $duracion = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
                            $informacion = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();

                            if (preg_replace('([^0-9])', '', $id)!=$id || preg_replace('([^A-Za-z ])', '', $descripcion)!=$descripcion
                             || preg_replace('([^A-Za-z0-9 ])', '', $duracion)!=$duracion ){

                              echo "<script>alert('No se ha podido importar todos los datos porque hay campos con caracteres incorrectos.');window.location='gestionar?IdPrograma=$idPrograma';</script>";

                             } else {
                                if(!empty($id) && !empty($descripcion) && !empty($duracion)){

                                  $sql = "INSERT INTO competencias (Id, Descripcion, Duracion, Status, IdPrincipalProgramaCompetencia, Informacion) VALUES ('$id',upper('$descripcion'),upper('$duracion'),'1','$idPrograma',upper('$informacion'))";
                                  
                                    if($stmt = mysqli_prepare($link, $sql)){                       
  
                                      if(mysqli_stmt_execute($stmt)){
  
                                          echo "<script>alert('Importación exitosa.');window.location='gestionar?IdPrograma=$idPrograma';</script>";
                                          
                                        } else{
                                          echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
                                       }
                                     }                                                 
                                  } else{
                                    echo "<script>alert('Error al importar algunos datos porque hay campos en blanco o vacios.');window.location='gestionar?IdPrograma=$idPrograma';</script>";
                                  } 
                                  
                        }
              }
            }
        } 
        }
      }else{
        echo "<script>window.location = '../principal/error';</script>";
      }
?>