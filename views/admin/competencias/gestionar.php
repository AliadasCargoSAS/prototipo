<!DOCTYPE html>
<html lang="es">

<?php     
    require_once "../include/header-admin.php";
    $IdPrograma =  trim($_GET["IdPrograma"]);   
    
    if($_GET["IdPrograma"] == ''){
        echo "<script>window.location = '../programas/gestionar';</script>";
    }
?>

<body>
    <div class="container mb-3">    
        <div class="row">
            <div class="col" id="cont-bloque-corto">
	     <div class="container-box">
                <div class="card">
                    <div class="card-header" style="background:#0D1326">
                        <div class="row">
                            <div class="col my-auto">
                                <h2>Competencias</h2>
                            </div>
                            <div class="col text-right">
                            <a href='historial.php?IdPrograma=<?php echo ($IdPrograma);?>' class='btn btn-historial' style='margin-right:10px;'>
                                <i class='fas fa-history'></i>
                                    Historial
                                </a>
            
                                <a href='#' class='btn btn-danger' data-toggle='modal' data-target='#confirm-eliminar-items-modal'>
                                 <i class='far fa-trash-alt'></i> <span>Eliminar</span>
                                </a>

                                <a href='crear.php?IdPrograma=<?php echo ($IdPrograma);?>' class='btn btn-success' style='margin-left:10px;'>
                                <i class='fas fa-plus-circle'></i>
                                    Crear
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class='card-body'>
                        <div class='info-busqueda'>
                                             
                          
                        <?php
                        include "../../../extra/conexion.php"; 

                        $vacia = "<p style='color:rgb(192, 48, 48); text-align:center;'>Por favor llene el campo de busqueda.</p>";

                      

                        if (isset($_POST['buscar'])) {

                            $busqueda = $_POST['busqueda']; 

                            $error = "<p style='color:rgb(192, 48, 48); text-align:center;'>No se han encontrado resultados con la palabra '$busqueda'.</p>";

                            if (!empty($busqueda)) {                             
                            
                                $sql2 = mysqli_query ($link, "SELECT COUNT(*) AS TotalRegistros FROM competencias WHERE Status = 1  and IdPrincipalProgramaCompetencia = '$IdPrograma'");
                                $result2 = mysqli_fetch_array($sql2);
                                        $totalRegistros = $result2['TotalRegistros'];
                                $porPagina = 7;
                                if(empty($_GET['pagina'])){
                                    $pagina = 1;
                                }else{
                                    $pagina = $_GET['pagina'];
                                }

                                $desde = ($pagina-1) * $porPagina;
                                $totalPaginas = ceil($totalRegistros / $porPagina);

                                
                                $sqlbusqueda = "SELECT * FROM competencias where Descripcion regexp '$busqueda' and Status = 1 and IdPrincipalProgramaCompetencia = '$IdPrograma' or Duracion regexp '$busqueda' and Status = 1 and IdPrincipalProgramaCompetencia = '$IdPrograma' or Id regexp '$busqueda' and Status = 1 and IdPrincipalProgramaCompetencia = '$IdPrograma' LIMIT $desde,$porPagina";
                                
                                $resultado = mysqli_query($link, $sqlbusqueda);
                        
                                if (mysqli_num_rows($resultado) > 0){
                                    echo "
                                    <div class='row'>
                                <div class='msgalerta' >
                                 <div class='alert alert-success'> 
                                    <a type='button' class='close' data-dismiss='alert' aria-hidden='true'> ×</a>                               
                                    <i class='fas fa-info-circle'></i> Puedes realizar una búsqueda filtrando las competencias, ya sean
                                    por la duración o número de identificación.                         
                                </div>                           
                            </div>                            
                        </div>  
                                    <div class='panel-group'>
                                        <div class='panel-heading'>
                                            
                                        <form action='' method='POST'>
                                            <div class='busca'>
                                                <input style='width:2%; height:2%;' type='submit'  name ='buscar' value='' ><i style='font-size:16px;' class='fas fa-search'></i>
                                                    <input type='search' name='busqueda' id='busqueda' placeholder=' Realizar una búsqueda' title='Buscar' autocomplete='off' autofocus=''> 
                                                <a class='btn btn-success' title='Filtrar' data-toggle='collapse' href='#collapse1'><i class='fas fa-sliders-h'></i></a>                                    
                                            </div>

                                        </form>

                                    </div>
                                </div>
                                
                                <form method='POST' action='delete-all.php'>
                                <div class='table-responsive'>
                            <table class='table table-striped' style='color:#737373;'> 
                                <thead>
                                    <tr>
                                        <th><span class='custom-checkbox'><input type='checkbox' name='SeleccionarAll' title='Seleccionar todas las competencias' id='selectAll'><label for='selectAll'></label></span></th>
                                        <th>ID</th>
                                        <th>Descripción</th>      
                                        <th>Duración</th>
                                        <th class='text-center'>Resultados de aprendizaje</th>                                        
                                        <th>Información adicional</th>
                                        <th colspan='2'>Acciones</th>
                                    </tr>
                                </thead>";
                                echo "<tbody id='programas'>";
                            while ($row = mysqli_fetch_array($resultado)){
                                                                                             
                                    echo "<tr>";                                    
                                    echo "<td> <div id='listado' class='custom-checkbox'><input type='checkbox' name='select[]' id='select[]' value='".$row['IdCompetencia']."'><label for='select'></label></div> </td>";                      
                                    echo "<td>" . $row['Id'] . "</td>";
                                    echo "<td>" . $row['Descripcion'] . "</td>";
                                    echo "<td>" . $row['Duracion'] . "</td>";
                                    echo "<td class='text-center'>  <a href='../raps/gestionar.php?IdCompetencia=". $row['IdCompetencia'] ."' title='Resultados de aprendizaje'><i class='fas fa-tasks'></i> </a>  </td>";                               
                                    echo "<td>" . $row['Informacion'] . "</td>";
                                    echo "<td>
                                    <a href='update.php?IdCompetencia=". $row['IdCompetencia'] ."&IdPrograma=".$IdPrograma."' data-toggle='tooltip'><i class='far fa-edit' title='Editar'></i></a>
                                    <a href='#' data-href='delete.php?IdCompetencia=". $row['IdCompetencia'] ."&IdPrograma=".$IdPrograma."' data-toggle='modal' data-target='#confirm-eliminar-modal'><i class='far fa-trash-alt' title='Eliminar'></i></a> </td>";
                                echo "</tr>";                                                                     
                            }                             
                            echo "</tbody>"; 
                            
                            }else{
                                echo "
                                <div class='panel-group'>
                                    <div class='panel-heading'>
                                        
                                    <form action='' method='POST'>
                                        <div class='busca'>
                                            <input style='width:2%; height:2%;' type='submit'  name ='buscar' value='' ><i style='font-size:16px;' class='fas fa-search'></i>
                                                <input type='search' name='busqueda' id='busqueda' placeholder=' Realizar una búsqueda' title='Buscar' autocomplete='off' autofocus=''> 
                                            <a class='btn btn-success' title='Filtrar' data-toggle='collapse' href='#collapse1'><i class='fas fa-sliders-h'></i></a>                                    
                                        </div>
                                    </form>

                                    <div class='col text-right p-0'>
                                        <a data-toggle='modal' data-target='#modal-import' class='btn btn-outline-info' id='import' title='Importar Excel' style='border-radius: 5px 0px 0px 0px; border-right-color:transparent; color: #17A2B8;'><i class='fas fa-upload' style=''></i></a><a href='#' class='btn btn-outline-danger' id='IdPDF' taget='_blank' title='Descagar como PDF' style='border-radius: 0px 0px 0px 0px;'><i class='far fa-file-pdf'></i></a><a href='#' class='btn btn-outline-success' id='IdExcel' title='Descagar como archivo Excel' style='border-radius: 0px 5px 0px 0px; border-left-color:transparent;'><i class='far fa-file-excel'></i></a>
                                    </div>

                                    </div>
                                </div>";
                                echo $error;
                            }

                            }else{
                                echo "
                                <div class='panel-group'>
                                    <div class='panel-heading'>
                                        
                                    <form action='' method='POST'>
                                        <div class='busca'>
                                            <input style='width:2%; height:2%;' type='submit'  name ='buscar' value='' ><i style='font-size:16px;' class='fas fa-search'></i>
                                                <input type='search' name='busqueda' id='busqueda' placeholder=' Realizar una búsqueda' title='Buscar' autocomplete='off' autofocus=''>                                     
                                        </div>
                                    </form>

                                    </div>
                                </div>";
                                echo $vacia;
                            }


                        }else{

                            $sql2 = mysqli_query ($link, "SELECT COUNT(*) AS TotalRegistros FROM competencias WHERE Status = 1  and IdPrincipalProgramaCompetencia = '$IdPrograma'");
                            $result2 = mysqli_fetch_array($sql2);
                            $totalRegistros = $result2['TotalRegistros'];
                            $porPagina = 7;
                            if(empty($_GET['pagina'])){
                                $pagina = 1;
                            }else{
                                $pagina = $_GET['pagina'];
                            }

                            $desde = ($pagina-1) * $porPagina;
                            $totalPaginas = ceil($totalRegistros / $porPagina);

                                

                                $sql = "SELECT * FROM competencias WHERE Status = 1 and IdPrincipalProgramaCompetencia = '$IdPrograma' LIMIT $desde,$porPagina";

                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){  
                                        echo "
                                        <div class='row'>
                                <div class='msgalerta' >
                                 <div class='alert alert-success'> 
                                    <a type='button' class='close' data-dismiss='alert' aria-hidden='true'> ×</a>                               
                                    <i class='fas fa-info-circle'></i> Puedes realizar una búsqueda filtrando las competencias, ya sean
                                    por la duración o número de identificación.                        
                                </div>                           
                            </div>
                            
                        </div>  
                                        <div class='panel-group'>
                                            <div class='panel-heading'>
                                                
                                            <form action='' method='POST'>
                                                <div class='busca'>
                                                    <input style='width:2%; height:2%;' type='submit'  name ='buscar' value='' ><i style='font-size:16px;' class='fas fa-search'></i>
                                                        <input type='search' name='busqueda' id='busqueda' placeholder=' Realizar una búsqueda' title='Buscar' autocomplete='off' autofocus=''>                                                               
                                                </div>
                                            </form>

                                            <div class='col text-right p-0'>
                                                <a data-toggle='modal' data-target='#modal-import' class='btn btn-outline-info' id='import' title='Importar Excel' style='border-radius: 5px 0px 0px 0px; border-right-color:transparent; color: #17A2B8;'><i class='fas fa-upload' style=''></i></a><a href='#' class='btn btn-outline-danger' id='IdPDF' title='Descagar como PDF' style='border-radius: 0px 0px 0px 0px;'><i class='far fa-file-pdf'></i></a><a href='#' class='btn btn-outline-success' id='IdExcel' title='Descagar como archivo Excel' style='border-radius: 0px 5px 0px 0px; border-left-color:transparent;'><i class='far fa-file-excel'></i></a>
                                            </div>

                                            </div>
                                        
                                        </div>

                                        <form method='POST' action='delete-all.php'>
                                                <div class='table-responsive'>
                                            <table class='table table-striped' style='color:#737373;'> 
                                                <thead>                                                   
                                                    <tr>
                                                        <th><span class='custom-checkbox'><input type='checkbox' name='SeleccionarAll' title='Seleccionar todas las competencias' id='selectAll'><label for='selectAll'></label></span></th>
                                                        <th>ID</th>
                                                        <th>Descripción</th>                                                       
                                                        <th>Duración</th>
                                                        <th class='text-center'>Resultados de aprendizaje</th>                                                        
                                                        <th>Información adicional</th>
                                                        <th colspan='2'>Acciones</th>
                                                    </tr>
                                                </thead>";

                                        echo "<tbody id='competencias'>";                                                       
                                        while($row = mysqli_fetch_array($result)){
                                            
                                            echo "<tr>";                                    
                                                echo "<td> <div id='listado' class='custom-checkbox'><input type='checkbox' name='select[]' id='select[]' value='".$row['IdCompetencia']."'><label for='select'></label></div> </td>";                      
                                                echo "<td id=".$row['Idcompetencia']." >" . $row['Id'] . "</td>";
                                                echo "<td>" . $row['Descripcion'] . "</td>";
                                                echo "<td>" . $row['Duracion'] . "</td>";
                                                echo "<td class='text-center'>  <a href='../raps/gestionar.php?IdCompetencia=". $row['IdCompetencia'] ."' title='Resultados de aprendizaje'><i class='fas fa-tasks'></i> </a> </td>";                                                  
                                                echo "<td>" . $row['Informacion'] . "</td>";
                                                echo "<td>
                                                <a href='update.php?IdCompetencia=". $row['IdCompetencia']."&IdPrograma=".$IdPrograma."' data-toggle='tooltip'><i class='far fa-edit' title='Editar'></i></a>
                                                <a href='#' data-href='delete.php?IdCompetencia=". $row['IdCompetencia'] ."&IdPrograma=".$IdPrograma."' data-toggle='modal' data-target='#confirm-eliminar-modal'><i class='far fa-trash-alt' title='Eliminar'></i></a> </td>";
                                            echo "</tr>";                                
                                        }                                                                      
                                        echo "</tbody>";   
                                        echo '<div id="confirm-eliminar-items-modal" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">			
                                                    <div class="modal-header">	
                                                                            
                                                        <h4 class="modal-title">Eliminar competencia</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>                               
                                                        
                                                    </div>                   
                                                        <div class="modal-body">					
                                                            <p>¿Estas seguro de eliminar las competencias seleccionadas?</p>						
                                                        </div>
                                
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="IdPrograma" value="'.$IdPrograma.'">
                                                        <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                                                        <input type="submit" class="btn btn-danger btn-ok" value="Eliminar">                    
                                                        </form>
                                                    </div>';
                                                                                                                                                                                
                                            } else{
                                                echo "<div class='alert-vacio'><div class='alert alert-success' style='text-align:center;width:55%;' ><em><i class='fas fa-info-circle  mr-1'></i> No hay registros de competencias, <a href='crear.php?IdPrograma=".$IdPrograma."' style='text-decoracion:none;'>agrega una nueva competencia</a>. </em></div></di>";
                                            }
                                        } else{
                                            echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                                        }                                                                 
                               
                    ?>                                                     
                        
                        </div>
                    </div>
                </div>                      
            </table>
                        <?php                                                
                        if( $totalRegistros >= 8){
                                            ?>  
                            <hr style="margin-top:0px">
                            <div class="text-center">
                                <div class="clearfix" style="padding:5px;margin-left:30px;margin-right:30px;">
                                    <div class="hint-text">Página <b><?php echo $pagina ;?></b> de <b><?php echo $totalRegistros;?></b> competencias</div>
                                    <ul class="pagination">
                                   
                                    <?php                                               
                                        if($pagina !=1){
                                            ?>      
                                     <li class="page-item" title="Inicio"><a href="?pagina=<?php echo 1; ?>" class="page-link"><i style="font-size:12px"  class="fas fa-angle-double-left"></i></a></li>
                                    <li class="page-item" title="Anterior"><a href="?pagina=<?php echo $pagina-1; ?>" class="page-link"> <i style="font-size:12px" class="fas fa-angle-left"></i></a></li>


                                    <?php 

                                        }
                                    
                                     for ($i=1; $i <= $totalPaginas; $i++) {                                                                                              
                                        if($i == $pagina){
                                            echo '<li class="page-item active"><a class="page-link">'.$i.'</a></li>';
                                        }else{
                                            echo '<li class="page-item"><a class="page-link" href="?pagina='.$i.'">'.$i.'</a></li>';
                                        }                                     
                                    }

                                    if($pagina != $totalPaginas){                                   
                                    ?>                                   
                                    <li class="page-item" title="Siguiente"><a href="?pagina=<?php echo $pagina + 1; ?>" class="page-link"> <i style="font-size:12px"  class="fas fa-angle-right"></i></a></li>
                                    <li class="page-item" title="Fin"><a href="?pagina=<?php echo $totalPaginas; ?>" class="page-link"><i style="font-size:12px"  class="fas fa-angle-double-right"></i></a></li>
                               <?php 
                                        } 
                                    }
                                }  
                                mysqli_close($link);
                                
                               ?>
                                </ul>
                            </div>   
                          </div>                                        
                        </div>
                </div>
            </div>            
        </div>
        
    </div>   
</div>
    	
	<div id="confirm-eliminar-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">			
					<div class="modal-header">	
                        					
						<h4 class="modal-title">Eliminar Competencia</h4>
						<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                       
                        
					</div>                   
                        <div class="modal-body">
                            <form role="form">					
                            <p>¿Estas seguro de eliminar la competencia?</p>						
                        </div>

					<div class="modal-footer">
						<input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                        <a href="delete.php?IdCompetencia=<?php echo $row['IdCompetencia']?>" class="btn btn-danger btn-ok" >Eliminar</a>
					</div>

				</form>
			</div>
		</div>
    </div>

    <!-- Modal Importar/Excel -->
    <div id="modal-import" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
                   <div class="modal-header">
                    <div class="close"><a style="margin-right:15px" href="#" class="close" data-dismiss="modal">&times;</a></div>        
                        <h5>Importación de datos</h5>
                    </div>
                        <div class="modal-body">
                            <i class="fas fa-info-circle"></i>&nbsp&nbsp<small style="font-size: 17px;">Puedes importar archivos de Excel</small><br>

                            <div class="card" style="margin-top:10px;margin-bottom:10px">
                                <div class="card-body">
                                    <form action="importar.php" method="post" enctype="multipart/form-data">                           
                                        <input type="file" name="archivo" required> 
                                </div>
                            </div>
                        </div>
                    <div class="modal-footer"> 
                        <input type="hidden" name="IdPrograma" value="<?php echo $IdPrograma;?>">                      
                        <input type="button" class="btn btn-light" class="close" data-dismiss="modal" value="Cancelar">                
                        <input type="submit" class="btn btn-primary" name="importar" value="Importar"></div>         
                    </form>
			</div>
		</div>
    </div>                              
    
    <script> 
        $('#confirm-eliminar-modal').on ('show.bs.modal', function (e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data 
            ('href'));

            $('.debug-url').html('Delete URL: <strong>' + $(this).find(
                '.btn-ok').attr('href') + '</strong>');
        });     
    </script>


    <script>
      $(function(){
        $('#selectAll').change(function() {
          $('#listado > input[type=checkbox]').prop('checked', $(this).is(':checked'));
        });
      });
    </script>
    <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

<script>

        //OBTENER ID DEL CEHECK PARA GENERAR EL PDF ESPECIFICO
        $(document).ready(function () {
            var ckbox = $("input[name='select[]']");
            var chkId = '';
            $('input').on('click', function () {

                if (ckbox.is(':checked')) {
                    $("input[name='select[]']:checked").each(function () {
                        chkId = $(this).val() + ",";
                        chkId = chkId.slice(0, -1);
                    });     
                    document.getElementById("IdPDF").href = "../../../extra/pdf/Reporte-Competencia?IdCompetencia="+chkId;
                    document.getElementById("IdPDF").target = "blank";
                }
            });
        });


        //CLICK CHECK PARA GENERAR EL PDF GENERAL
        $(document).ready(function () {
            var ckbox = $("input[name='SeleccionarAll']");
            var chkId = '';
            $('input').on('click', function () {

                if (ckbox.is(':checked')) {
                    $("input[name='SeleccionarAll']:checked").each(function () {
                        chkId = $(this).val() + ",";
                        chkId = chkId.slice(0, -1);
                    });
                    document.getElementById("IdPDF").href = "../../../extra/pdf/Reporte-Competencias?IdPrograma="+<?php echo $IdPrograma; ?>;
                    document.getElementById("IdPDF").target = "blank";
                }
            });
        });

 //OBTENER ID DEL CHECK PARA GENERAR EL EXCEL ESPECIFICO
 $(document).ready(function() {
                var ckbox = $("input[name='select[]']");
                var chkId = '';
            $('input').on('click', function() {
                
                if (ckbox.is(':checked')) {
                $("input[name='select[]']:checked").each ( function() {
                        chkId = $(this).val() + ",";
                    chkId = chkId.slice(0, -1);
                });                    
                    document.getElementById("IdExcel").href = "../../../extra/excel/excel-competencia?IdCompetencia="+chkId+"&IdPrograma="+<?php  echo $IdPrograma;?>                  
                }     
            });
            });
        
        //CLICK CHECK PARA GENERAR EL EXCEL GENERAL
            $(document).ready(function() {
                var ckbox = $("input[name='SeleccionarAll']");
                var chkId = '';
            $('input').on('click', function() {
                
                if (ckbox.is(':checked')) {
                $("input[name='SeleccionarAll']:checked").each ( function() {
                        chkId = $(this).val() + ",";
                    chkId = chkId.slice(0, -1);
                });            
                    document.getElementById("IdExcel").href = "../../../extra/excel/excel-competencias?IdPrograma="+<?php  echo $IdPrograma;?> 
                }     
            });
            });


</script>

<script>

    $(document).ready(function(){
    $("#busqueda").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        
        $("#competencias tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });

    });
    });
</script> 

</body>

</html>
