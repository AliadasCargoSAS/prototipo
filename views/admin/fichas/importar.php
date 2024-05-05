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
                            
                            $numero = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
                            $programa = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
                            $aprendices = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
                            $jornada = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();

                            $inicio = $objPHPExcel->getActiveSheet()->getCell('E'.$i);
                            $inicioValue = $inicio->getValue();
                            if(PHPExcel_Shared_Date::isDateTime($inicio)){
                              $format = "Y-m-d";
                              $FechaI = date($format, PHPExcel_Shared_Date::ExcelToPHP($inicioValue));
                            }

                            $fin = $objPHPExcel->getActiveSheet()->getCell('F'.$i);
                            $finValue = $fin->getValue();
                            if(PHPExcel_Shared_Date::isDateTime($fin)){
                              $format = "Y-m-d";
                              $FechaF = date($format, PHPExcel_Shared_Date::ExcelToPHP($finValue));
                            }

                            $informacion = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();  

                          

                            if (preg_replace('([^A-Z0-9-])', '', $numero)!=$numero || preg_replace('([^A-Za-z ])', '', $programa)!=$programa || preg_replace('([^0-9])', '', $aprendices)!=$aprendices ||
                                preg_replace('([^A-Za-z ])', '', $jornada)!=$jornada || preg_replace('([^0-9 /])', '', $inicio)!=$inicio ||  preg_replace('([^0-9 /])', '', $fin)!=$fin) {

                            echo "<script>alert('No se ha podido importar todos los datos porque hay campos con caracteres incorrectos.');window.location='gestionar';</script>";

                            }else {
                                                     
                              if(!empty($numero) && !empty($programa) && !empty($aprendices) && !empty($jornada) && !empty($inicio) && !empty($fin)){
                                                        
                                $sql = "INSERT INTO fichas (Numero, Programa, Aprendices, Jornada, Inicio, Fin, Informacion, Status) VALUES (upper('$numero'),upper('$programa'),'$aprendices',upper('$jornada'), '$FechaI', '$FechaF', upper('$informacion'), '1')";
                                
                                  if($stmt = mysqli_prepare($link, $sql)){                       
  
                                    if(mysqli_stmt_execute($stmt)){
  
                                        echo "<script>alert('Importación exitosa.');window.location='gestionar';</script>";
                                        
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
