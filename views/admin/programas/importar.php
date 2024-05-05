<?php

    //Hacemos el procedimiento de la inserción del archivo excel a la base de datos	

      if (isset($_POST['importar'])) {
        $nombreArchivo=$_FILES['archivo']['name'] ;

		  if (isset($nombreArchivo )&& !empty($nombreArchivo) )  {
			  $Tipo_archivo=$_FILES['archivo']['type'];
			  $temp_archivo=$_FILES['archivo']['tmp_name'];

			  if (!((strpos($Tipo_archivo, "msexcel") || strpos($Tipo_archivo, "vnd.ms-excel")  || strpos($Tipo_archivo, "xls")  || strpos($Tipo_archivo, "vnd.openxmlformats-officedocument.spreadsheetml.sheet")
			  || strpos($Tipo_archivo, "x-msexcel")   || strpos($Tipo_archivo, "x-ms-Excel") || strpos($Tipo_archivo, "x-Excel") || strpos($Tipo_archivo, "x-dos_ms_Excel") || strpos($Tipo_archivo, "x-xls" )))){
				echo '<script>alert("La extensión de los archivos no es correcta.\n\n- Se permiten archivos .xlsx, .xls");window.location="gestionar";</script>';
				  
			  }else {
					 
                    if (move_uploaded_file($temp_archivo, '../../../extra/archivos_excel_importados/'.$nombreArchivo)) {
                    
                        require '../../../extra/Classes/PHPExcel/IOFactory.php';
                        require '../../../extra/conexion.php';
                        
                        $objPHPExcel = PHPEXCEL_IOFactory::load('../../../extra/archivos_excel_importados/'.$nombreArchivo);
                        
                        $objPHPExcel->setActiveSheetIndex(0);
                        
                        $numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();                        
                        
                        for($i = 2; $i <= $numRows; $i++){
                            
                            $id = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
                            $nombre = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
                            $tipo = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
                            $duracion = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
                            $version = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
                            $proyecto = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
                            $informacion = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue(); 

                            if (preg_replace('([^0-9])', '', $id)!=$id || preg_replace('([^A-Za-z ])', '', $nombre)!=$nombre || preg_replace('([^A-Za-z])', '', $tipo)!=$tipo||
                             preg_replace('([^A-Za-z0-9 ])', '', $duracion)!=$duracion || preg_replace('([^0-9])', '', $version)!=$version || preg_replace('([^A-Za-z0-9 ])', '', $proyecto)!=$proyecto) {

                              echo "<script>alert('No se ha podido importar todos los datos porque hay campos con caracteres incorrectos.');window.location='gestionar';</script>";

                            }else {

                              if(!empty($id) && !empty($nombre) && !empty($tipo) && !empty($duracion) && !empty($version) && !empty($proyecto)){
                            
                                $sql = "INSERT INTO programas (Id, Nombre, Tipo, Duracion, Version, Proyecto, Informacion, Status) VALUES ('$id',upper('$nombre'),upper('$tipo'),upper('$duracion'),'$version',upper('),upper('$informacion'),'1')";
                                
                                  if($stmt = mysqli_prepare($link, $sql)){                       
    
                                    if(mysqli_stmt_execute($stmt)){
    
                                        echo "<script>alert('Se importo el archivo, pero posiblemente algunos datos no se guardaron por caracteres incorrectos o tipo de dato no correspondiente.');window.location='gestionar';</script>";
                                        
                                    } else{
                                        echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>";
                                    }
                                }
    
                              }else{
                                echo "<script>alert('Error al importar algunos datos porque hay campos en blanco o vacios.');window.location='gestionar';</script>";
                              } 
                              
                            }

                        }                
                      }
			    }
			}  
	  }
?>