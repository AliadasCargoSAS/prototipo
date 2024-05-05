<!DOCTYPE html>
<html lang="es">

<?php     
    require_once "../include/header-admin.php";
?>  

<body>
    <div class="container mb-3">
        <div class="row">
            <div class="col" id="cont-bloque-corto">
                <div class="card">
                    <div class="card-header" style="background:#0D1326">
                        <div class="row">
                            <div class="col my-auto">
                                <h2>Historial de fichas</h2>
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
                        <?php
                    include "../../../extra/conexion.php"; 

                    $sql2 = mysqli_query ($link, "SELECT COUNT(*) AS TotalRegistros FROM fichas WHERE Status = 0");
                    $result2 = mysqli_fetch_array($sql2);
                    $totalRegistros = $result2['TotalRegistros']; 

                    $porPagina = 6;
                    if(empty($_GET['pagina'])){
                        $pagina = 1;
                    }else{
                        $pagina = $_GET['pagina'];
                    }

                    $desde = ($pagina-1) * $porPagina;
                    $totalPaginas = ceil($totalRegistros / $porPagina);

                    $sql = "SELECT * FROM fichas WHERE Status = 0 LIMIT $desde,$porPagina";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){  
                            echo '<div class="info-busqueda">                                            
                            <div class="panel-group">
                                <div class="panel-heading">                                                                                                                                              
                                    <div class="busca">
                                        <i style="font-size:16px;" class="fas fa-search"></i>
                                        <input type="text" name="busqueda" id="busqueda" placeholder=" Realizar una búsqueda" title="Buscar" autocomplete="off"> 
                                    </div>                                                                          
                                </div>                                    
                                </div>                           
                            </div>                      
                            <br>';
                            echo '
                                <form method="POST" action="suppress-all.php">
                                    <div id="confirm-suppress-items-modal" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">			
                                                <div class="modal-header">	
                                                                        
                                                    <h4 class="modal-title">Eliminar definitivamente fichas</h4>
                                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            
                                                   
                                                    
                                                </div>                   
                                                    <div class="modal-body">
                                                    <!-- <form method="POST" action="delete-all.php">-->					
                                                        <p>¿Estas seguro de eliminar definitivamente las fichas seleccionadas?</p>						
                                                    </div>
                            
                                                <div class="modal-footer">
                                                    <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                                                    <input type="submit" class="btn btn-danger btn-ok" value="Eliminar definitivamente">
                                                </div>       
                                                <!--</form>-->
                                        </div>
                                    </div>
                                </div>';   
    
                                echo '
                                <form method="POST" action="">
                                <div id="confirm-restore-items-modal" class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">			
                                                <div class="modal-header">	
                                                                        
                                                    <h4 class="modal-title">Restablecer fichas</h4>
                                                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>                                                                            
                                                    
                                                </div>                   
                                                    <div class="modal-body">
                                                    					
                                                        <p>¿Estas seguro de restablecer las fichas seleccionadas?</p>						
                                                    </div>
                            
                                                <div class="modal-footer">
                                                    <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">  
                                                    <!-- <input type="submit" class="btn btn-historial btn-ok" value="Restablecer"> -->                  
                                                    <input type="submit" class="btn btn-historial btn-ok" name="boton_2" id="boton_2" value="Restablecer" dir="restore-all.php" />
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>';
                                
                                echo "<div class='table-responsive'>";
                                echo "<table class='table table-striped' style='color:#737373b6;'> ";
                                    echo "<thead>";
                                        echo "<tr>";
                                        echo "<th><span class='custom-checkbox'><input type='checkbox' title='Seleccionar todos los instructores eliminados' id='selectAll'><label for='selectAll'></label></span></th>";
                                        echo "<th>Número de la ficha </th>";
                                            echo "<th>Programa de formación</th>";
                                            echo "<th>Cantidad de aprendices</th>";
                                            echo "<th>Jornada</th>";
                                            echo "<th>Fecha de inicio</th>";
                                            echo "<th>Fecha fin</th>";
                                            echo "<th>Información adicional</th>";
                                            echo "<th colspan='2'>Acciones</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody id='fichas'>";
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr style='style='background-color: #e9ecef23;opacity: 1;'>";
    
                                        echo "<td> <div id='listado' class='custom-checkbox'><input type='checkbox' name='select[]' id='select[]' value='".$row['IdPrincipal']."'><label for='select'></label></div> </td>";                      
                                        echo "<td>" . $row['Numero'] . "</td>";
                                            echo "<td>" . $row['Programa'] . "</td>";
                                            echo "<td>" . $row['Aprendices'] . "</td>";
                                            echo "<td>" . $row['Jornada'] . "</td>";
                                            echo "<td>" . $row['Inicio'] . "</td>";
                                            echo "<td>" . $row['Fin'] . "</td>";
                                            echo "<td>" . $row['Informacion'] . "</td>";
                                            echo "<td>
                                            <a href='restore.php?IdPrincipal=". $row['IdPrincipal'] ."' data-toggle='tooltip'><i class='fas fa-undo' title='Restablecer'></i></a> 
                                            <a href='#' data-href='suppress.php?IdPrincipal=". $row['IdPrincipal'] ."' data-toggle='modal' data-target='#confirm-eliminar-modal'><i class='fas fa-minus-circle' title='Eliminar definitivamente'></i></a> </td>";
                                                echo "</tr>";
                                    }
                                    echo "</tbody>";                                                   
                                echo "</table>";                     
                                mysqli_free_result($result);
                            } else{
                                echo "<p style='text-align:center;' ><em>No hay registros de fichas eliminadas.  </em></p>";
                            }
                        } else{
                            echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>". mysqli_error($link);
                        }     
                        mysqli_close($link);
                        ?>

                        </div>
                        <?php                                                
                        if( $totalRegistros >= 7){
                                            ?>  
                        <hr style="margin-top:0px">
                        <div class="text-center">
                            <div class="clearfix" style="padding:5px;margin-left:30px;margin-right:30px;">
                                <div class="hint-text">Página <b><?php echo $pagina ;?></b> de <b><?php echo $totalRegistros;?></b> fichas eliminadas</div>
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
						<h4 class="modal-title">Eliminar definitivamente programa de formación</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>

                        <div class="modal-body">					
                            <p>¿Estas seguro de eliminar definitivamente el programa de formación?</p>
                            <h6><strong>Esta acción es irrevocable</strong></h6>                         				
                        </div>

					<div class="modal-footer">
						<input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                        <a href="suppress.php?IdPrincipal=<?php echo $row['IdPrincipal'] ?>" class="btn btn-danger btn-ok" >Eliminar definitivamente</a>
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
            
            $("#fichas tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });

        });
        });
    </script>  

    <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>

</html>