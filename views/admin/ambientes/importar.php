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
                            $capacidad = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();
                            $tipo = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getValue();
                            $sede = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getValue();
                            $torre = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getValue();  
                            $informacion = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getValue();

                          if (preg_replace('([^0-9])', '', $numero)!=$numero || preg_replace('([^0-9])', '', $capacidad)!=$capacidad || preg_replace('([^A-Za-z ])', '', $tipo)!=$tipo ||
                            preg_replace('([^A-Za-z ])', '', $sede)!=$sede || preg_replace('([^A-Za-z ])', '', $torre)!=$torre) {

                              echo "<script>alert('No se ha podido importar todos los datos porque hay campos con caracteres incorrectos.');window.location='gestionar';</script>";

                             }else {
                                if(!empty($numero) && !empty($capacidad) && !empty($tipo) && !empty($sede) && !empty($torre)){
                                  
                                  $sqlAmbientes = "INSERT INTO ambientes (Numero, Capacidad, Tipo, Sede, Torre, Informacion, Status) VALUES ('$numero','$capacidad',upper('$tipo'),upper('$sede'),upper('$torre'),upper('$informacion'), '1')";
                                  
                                    if($stmt = mysqli_prepare($link, $sqlAmbientes)){  
                                      
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
	    }else{
          echo "<script>window.location = '../principal/error';</script>";
      }
?>
