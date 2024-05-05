<!DOCTYPE html>
<html lang="es">

<?php   
    if($_GET["IdPrograma"] == ''){
        echo "<script>window.location = '../programas/gestionar';</script>";
    }  
    
    require_once "../include/header-admin.php";
    $IdPrograma =  trim($_GET["IdPrograma"]);
?>

<body>    
    <div class="container mb-3">
        <div class="row">
            <div class="col" id="cont-bloque-corto">
                <div class="card">
                    <div class="card-header" style="background:#0D1326">
                        <div class="row">
                            <div class="col my-auto">
                                <h2>Historial de competencias</h2>
                            </div>
                            <div class="col text-right">                              
                                <a href='#' class='btn btn-historial' data-toggle='modal' data-target='#confirm-restore-items-modal' style="margin-right:10px;">
                                <i class="fas fa-undo"></i> Restablecer
                                </a>
                                <a href='#' class='btn btn-danger' data-toggle='modal' data-target='#confirm-suppress-items-modal'>
                                 <i class="fas fa-minus-circle"></i> <span>Eliminar definitivamente</span>
                                </a>
                            </div>                    
                        </div>
                    </div>
                    <div class="card-body">
                    <div class='info-busqueda'>

                    <?php
                        include "../../../extra/conexion.php"; 

                        $vacia = "<p style='color:rgb(192, 48, 48); text-align:center;'>Por favor llene el campo de busqueda.</p>";
                            
                            $sql2 = mysqli_query ($link, "SELECT COUNT(*) AS TotalRegistros FROM competencias WHERE Status = 0 and IdPrincipalProgramaCompetencia = '$IdPrograma'");
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

                                $sql = "SELECT * FROM competencias WHERE Status = 0 and IdPrincipalProgramaCompetencia = '$IdPrograma' LIMIT $desde,$porPagina";

                                if($result = mysqli_query($link, $sql)){
                                    if(mysqli_num_rows($result) > 0){  
                                        echo "
                                        <div class='row'>
                                <div class='msgalerta' >
                                 <div class='alert alert-success'> 
                                    <a type='button' class='close' data-dismiss='alert' aria-hidden='true'> ×</a>                               
                                    <i class='fas fa-info-circle'></i> Puedes realizar una búsqueda filtrando los resultados, ya sean
                                    programas formativos de tipo Técnico o Tecnologo.                         
                                </div>                           
                            </div>
                            
                        </div>  
                                        <div class='panel-group'>
                                            <div class='panel-heading'>
                                                                                          
                                                <div class='busca'>
                                                    <input style='width:2%; height:2%;' type='submit'  name ='buscar' value='' ><i style='font-size:16px;' class='fas fa-search'></i>
                                                        <input type='text' name='busqueda' id='busqueda' placeholder=' Realizar una búsqueda' title='Buscar' autocomplete='off' autofocus=''> 
                                                    <a class='btn btn-success' title='Filtrar' data-toggle='collapse' href='#collapse1'><i class='fas fa-sliders-h'></i></a>                                    
                                                </div>                                          
                                            </div>
                                            <div id='collapse1' class='panel-collapse collapse'>
                                                <div class='panel-body'>
                                                    <label style='color:#737373d1;'><input type='checkbox'> Ténico</label>
                                                    <br><label style='color:#737373d1;'><input type='checkbox'> Tecnologo </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                                <div class='table-responsive'>
                                            <table class='table table-striped' style='color: #737373b6;'> 
                                                <thead>
                                                    <tr>
                                                        <th><span class='custom-checkbox'><input type='checkbox' title='Seleccionar todas las competencias' id='selectAll'><label for='selectAll'></label></span></th>
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
                                            
                                            echo "<tr style='background-color: #e9ecef23; opacity: 1;'>";                                    
                                                echo "<td> <div id='listado' class='custom-checkbox'><input type='checkbox' name='select[]' id='select[]' value='".$row['IdCompetencia']."'><label for='select'></label></div> </td>";                      
                                                echo "<td>" . $row['Id'] . "</td>";
                                                echo "<td>" . $row['Descripcion'] . "</td>";
                                                echo "<td>" . $row['Duracion'] . "</td>";
                                                echo "<td class='text-center'>  <a href='#' title='Resultados de aprendizaje'><i class='fas fa-tasks'></i> </a> </td>";                                                  
                                                echo "<td>" . $row['Informacion'] . "</td>";
                                                echo "<td>
                                                <a href='restore.php?IdCompetencia=". $row['IdCompetencia'] ."&IdPrograma=".$IdPrograma."' data-toggle='tooltip'><i class='fas fa-undo' title='Restablecer'></i></a> 
                                                <a href='#' data-href='suppress.php?IdCompetencia=". $row['IdCompetencia'] ."&IdPrograma=".$IdPrograma."' data-toggle='modal' data-target='#confirm-eliminar-modal'><i class='fas fa-minus-circle' title='Eliminar definitivamente'></i></a> </td>";
                                            echo "</tr>";                                
                                        }                                                                      
                                        echo "</tbody>";   

                                        echo '        
                                        <form method="POST" action="suppress-all.php">                                 
                                        <div id="confirm-suppress-items-modal" class="modal fade">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">			
                                                            <div class="modal-header">	
                                                                                    
                                                                <h4 class="modal-title">Eliminar definitivamente competencias</h4>
                                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>                                                                           
                                                                
                                                            </div>                   
                                                                <div class="modal-body">				
                                                                    <p>¿Estas seguro de eliminar definitivamente las competencias seleccionadas?</p>						
                                                                </div>
                                        
                                                            <div class="modal-footer">
                                                            <!--<form method="POST" action="suppress-all.php">-->
                                                                <input type="hidden" name="IdPrograma" value="'.$IdPrograma.'">
                                                                <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                                                                <input type="submit" class="btn btn-danger btn-ok" value="Eliminar definitivamente">
                                                            <!--</form>-->
                                                            </div>
                                                                    
                                                    </div>
                                                </div>
                                            </div>';   

                                         echo '  <form method="POST" action="">                                    
                                         <div id="confirm-restore-items-modal" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">			
                                                    <div class="modal-header">	
                                                                    
                                                <h4 class="modal-title">Restablecer Competencias</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                                                        
                                            </div>                   
                                                <div class="modal-body">                                              			
                                                    <p>¿Estas seguro de restablecer las competencias seleccionadas?</p>						
                                                </div>
                        
                                            <div class="modal-footer">
                                                <!--<form method="POST" action="">-->
                                                    <input type="hidden" name="IdPrograma" value="'.$IdPrograma.'">
                                                    <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">  
                                                    <!--<input type="submit" class="btn btn-historial btn-ok" value="Restablecer"> -->
                                                    <input type="submit" class="btn btn-historial btn-ok" name="boton_2" id="boton_2" value="Restablecer" dir="restore-all.php" />
                                                <!--</form>-->
                                            </div></form>';
                                                                                                                                                                                
                                            } else{
                                                echo "<div class='alert-vacio'><div class='alert alert-success' style='text-align:center;width:55%;' ><em><i class='fas fa-info-circle  mr-1'></i> No hay registro de competencias eliminadas. </em></div></di>";
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
                                <div class="hint-text">Página <b><?php echo $pagina ;?></b> de <b><?php echo $totalRegistros;?></b> competencias eliminadas</div>
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
                               <?php } 
                               
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

    <div id="confirm-eliminar-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form>

					<div class="modal-header">						
						<h4 class="modal-title">Eliminar definitivamente competencia</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>

                        <div class="modal-body">					
                            <p>¿Estas seguro de eliminar definitivamente la competencia?</p>
                            <h6><strong>Esta acción es irrevocable</strong></h6>                         				
                        </div>

					<div class="modal-footer">
						<input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                        <a href="suppress.php?IdCompetencia=<?php echo $row['IdCompetencia'] ?>&IdPrograma=<?php echo ($IdPrograma); ?>" class="btn btn-danger btn-ok" >Eliminar definitivamente</a>
					</div>

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
    <script>         
    $(document).ready(function(){
    
    $("input[type=submit]").click(function() {
        var accion = $(this).attr('dir');
        $('form').attr('action', accion);
        $('form').submit();
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

    <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>


</body>

</html>