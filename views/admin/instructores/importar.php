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
                            
                            $identificacion = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
                            $nombre = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();

                            $nacimiento = $objPHPExcel->getActiveSheet()->getCell('C'.$i);
                            $nacimientoValue = $nacimiento->getValue();
                            if(PHPExcel_Shared_Date::isDateTime($nacimiento)){
                              $format = "Y-m-d";
                              $FechaNacimiento = date($format, PHPExcel_Shared_Date::ExcelToPHP($nacimientoValue));
                            }

                            $telefono = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
                            $especialidad = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();
                            $correo = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();
                            $tipo_contrato = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getValue();

                            $contratoI = $objPHPExcel->getActiveSheet()->getCell('H'.$i);
                            $contratoI_Value = $contratoI->getValue();
                            if(PHPExcel_Shared_Date::isDateTime($contratoI)){
                              $format = "Y-m-d";
                              $contratoInicio = date($format, PHPExcel_Shared_Date::ExcelToPHP($contratoI_Value));
                            }

                            $contratoF = $objPHPExcel->getActiveSheet()->getCell('I'.$i);
                            $contratoF_Value = $contratoF->getValue();
                            if(PHPExcel_Shared_Date::isDateTime($contratoF)){
                              $format = "Y-m-d";
                              $contratoFin = date($format, PHPExcel_Shared_Date::ExcelToPHP($contratoF_Value));
                            }

                            $informacion = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getValue();
                            
                            if (preg_replace('([^0-9])', '', $identificacion)!=$identificacion || preg_replace('([^A-Za-z ])', '', $nombre)!=$nombre || preg_replace('([^0-9 /])', '', $nacimiento)!=$nacimiento
                            || preg_replace('([^0-9])', '', $telefono)!=$telefono || preg_replace('([^A-Za-z ])', '', $especialidad)!=$especialidad ||  preg_replace('([^A-Za-z0-9 @.])', '', $correo)!=$correo || preg_replace('([^A-Za-z ])', '', $tipo_contrato)!=$tipo_contrato
                            || preg_replace('([^0-9 /])', '', $contratoI)!=$contratoI ||  preg_replace('([^0-9 /])', '', $contratoF)!=$contratoF ){

                              echo "<script>alert('No se ha podido importar todos los datos porque hay campos con caracteres incorrectos.');window.location='gestionar++';</script>";

                            }else {

                              if(!empty($identificacion) && !empty($nombre) && !empty($nacimiento) && !empty($telefono) && !empty($especialidad) && !empty($correo) && !empty($tipo_contrato) && !empty($contratoI) && !empty($contratoF)){

                                $sql = "INSERT INTO instructores (Identificacion, Nombre, Nacimiento, Telefono, Especialidad, Correo, TipoContrato, ContratoInicio, ContratoFin, Informacion, Status) VALUES ('$identificacion',upper('$nombre'),'$FechaNacimiento','$telefono', upper('$especialidad'), upper('$correo'), upper('$tipo_contrato'), '$contratoInicio', '$contratoFin',upper('$informacion'),'1')";
                                
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
