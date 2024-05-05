<!DOCTYPE html>
<html lang="es">

<?php
require_once "../include/header-admin.php";
if ($_GET["IdCompetencia"] == '') {
    echo "<script>window.location='../programas/gestionar';</script>";
}
$IdCompetencia = trim($_GET["IdCompetencia"]);
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
                                    <h2>Resultados de aprendizaje</h2>
                                </div>
                                <div class="col text-right">
                                    <a href='historial?IdCompetencia=<?php echo ($IdCompetencia); ?>' class='btn btn-historial' style='margin-right:10px;'>
                                        <i class='fas fa-history'></i>
                                        Historial
                                    </a>

                                    <a href='#' class='btn btn-danger' data-toggle='modal' data-target='#confirm-eliminar-items-modal'>
                                        <i class='far fa-trash-alt'></i> <span>Eliminar</span>
                                    </a>

                                    <a href='crear?IdCompetencia=<?php echo ($IdCompetencia); ?>' class='btn btn-success' style='margin-left:10px;'>
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

                                $sql2 = mysqli_query($link, "SELECT COUNT(*) AS TotalRegistros FROM raps WHERE Status = 1 and IdPrincipalCompetencias = '$IdCompetencia'");
                                $result2 = mysqli_fetch_array($sql2);
                                $totalRegistros = $result2['TotalRegistros'];
                                $porPagina = 7;
                                if (empty($_GET['pagina'])) {
                                    $pagina = 1;
                                } else {
                                    $pagina = $_GET['pagina'];
                                }

                                $desde = ($pagina - 1) * $porPagina;
                                $totalPaginas = ceil($totalRegistros / $porPagina);

                                $sql = "SELECT * FROM raps WHERE Status = 1 and IdPrincipalCompetencias = '$IdCompetencia' LIMIT $desde,$porPagina";

                                if ($result = mysqli_query($link, $sql)) {
                                    if (mysqli_num_rows($result) > 0) {
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
                                                <i style='font-size:16px;' class='fas fa-search'></i>
                                                <input type='text' name='busqueda' id='busqueda' placeholder=' Realizar una búsqueda' title='Buscar' autocomplete='off' autofocus=''> 
                                            </div>                                     
                                        </div>                                  
                                    </div>";

                                        echo "<div class='col text-right p-0'>
                                            <a data-toggle='modal' data-target='#modal-import' class='btn btn-outline-info' id='import' title='Importar Excel' style='border-radius: 5px 0px 0px 0px; border-right-color:transparent; color: #17A2B8;'><i class='fas fa-upload' style=''></i></a>
                                        </div>

                                        <form method='POST' action='delete-all.php'>
                                                <div class='table-responsive'>
                                            <table class='table table-striped' style='color:#737373;'> 
                                                <thead>                                                 
                                                    <tr>
                                                        <th><span class='custom-checkbox'><input type='checkbox' title='Seleccionar todos los resultados de aprendizaje' id='selectAll'><label for='selectAll'></label></span></th>
                                                        <th>ID</th>
                                                        <th>Descripcion</th>                   
                                                        <th>Cantidad de horas</th> 
                                                        <th>Tipo de resultado</th>                                                                                                                                    
                                                        <th>Información adicional</th>
                                                        <th colspan='2'>Acciones</th>
                                                    </tr>
                                                </thead>";

                                        echo "<tbody id='raps'>";
                                        while ($row = mysqli_fetch_array($result)) {

                                            echo "<tr>";
                                            echo "<td> <div id='listado' class='custom-checkbox'><input type='checkbox' name='select[]' id='select[]' value='" . $row['IdRae'] . "'><label for='select'></label></div> </td>";
                                            echo "<td>" . $row['Id'] . "</td>";
                                            echo "<td>" . $row['Descripcion'] . "</td>";
                                            echo "<td>" . $row['CantidadHoras'] . "</td>";
                                            echo "<td>" . $row['TipoResultado'] . "</td>";
                                            echo "<td>" . $row['Informacion'] . "</td>";
                                            echo "<td>
                                                <a href='update.php?IdRae=" . $row['IdRae'] . "&IdCompetencia=$IdCompetencia' data-toggle='tooltip'><i class='far fa-edit' title='Editar'></i></a>
                                                <a href='#' data-href='delete.php?IdRae=" . $row['IdRae'] . "&IdCompetencia=$IdCompetencia' data-toggle='modal' data-target='#confirm-eliminar-modal'><i class='far fa-trash-alt' title='Eliminar'></i></a> </td>";
                                            echo "</tr>";
                                        }
                                        echo "</tbody>";
                                        echo '<div id="confirm-eliminar-items-modal" class="modal fade">
                                        <div class="modal-dialog">
                                            <div class="modal-content">			
                                                    <div class="modal-header">	
                                                                            
                                                        <h4 class="modal-title">Eliminar programas de formación</h4>
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                
                                                    
                                                        
                                                    </div>                   
                                                        <div class="modal-body">
                                                        <form method="POST" action="delete-all.php">					
                                                            <p>¿Estas seguro de eliminar los programas de formación seleccionados?</p>						
                                                        </div>
                                
                                                    <div class="modal-footer">
                                                        <input type="hidden" name="IdCompetencia" value="' . $IdCompetencia . '">
                                                        <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                                                        <input type="submit" class="btn btn-danger btn-ok" value="Eliminar">                    
                                
                                                    </div>';
                                    } else {
                                        echo "<div class='alert-vacio'><div class='alert alert-success' style='text-align:center;width:55%;' ><em><i class='fas fa-info-circle  mr-1'></i> No hay registros de resultados de aprendizaje, <a href='crear?IdCompetencia=$IdCompetencia' style='text-decoracion:none;'>agrega un nuevo resultado</a>. </em></div></di>";
                                    }
                                } else {
                                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>
                                            $sql. " . mysqli_error($link);
                                }

                                ?>
                                </form>
                            </div>
                        </div>
                    </div>
                    </table>
                    <?php
                    if ($totalRegistros >= 8) {
                    ?>
                        <hr style="margin-top:0px">
                        <div class="text-center">
                            <div class="clearfix" style="padding:5px;margin-left:30px;margin-right:30px;">
                                <div class="hint-text">Página <b><?php echo $pagina; ?></b> de <b><?php echo $totalRegistros; ?></b> resultados de aprendizaje</div>
                                <ul class="pagination">

                                    <?php
                                    if ($pagina != 1) {
                                    ?>
                                        <li class="page-item" title="Inicio"><a href="?pagina=<?php echo 1; ?>" class="page-link"><i style="font-size:12px" class="fas fa-angle-double-left"></i></a></li>
                                        <li class="page-item" title="Anterior"><a href="?pagina=<?php echo $pagina - 1; ?>" class="page-link"> <i style="font-size:12px" class="fas fa-angle-left"></i></a></li>


                                    <?php

                                    }

                                    for ($i = 1; $i <= $totalPaginas; $i++) {
                                        if ($i == $pagina) {
                                            echo '<li class="page-item active"><a class="page-link">' . $i . '</a></li>';
                                        } else {
                                            echo '<li class="page-item"><a class="page-link" href="?pagina=' . $i . '">' . $i . '</a></li>';
                                        }
                                    }

                                    if ($pagina != $totalPaginas) {
                                    ?>
                                        <li class="page-item" title="Siguiente"><a href="?pagina=<?php echo $pagina + 1; ?>" class="page-link"> <i style="font-size:12px" class="fas fa-angle-right"></i></a></li>
                                        <li class="page-item" title="Fin"><a href="?pagina=<?php echo $totalPaginas; ?>" class="page-link"><i style="font-size:12px" class="fas fa-angle-double-right"></i></a></li>
                                <?php
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

                    <h4 class="modal-title">Eliminar resultado de aprendizaje</h4>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>



                </div>
                <div class="modal-body">
                    <form role="form">
                        <p>¿Estas seguro de eliminar el resultado de aprendizaje?</p>
                </div>

                <div class="modal-footer">
                    <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">
                    <a href="delete.php?IdRae=<?php echo $row['IdRae'] ?>" class="btn btn-danger btn-ok">Eliminar</a>
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
                    <input type="hidden" name="IdCompetencia" value="<?php echo $IdCompetencia; ?>">
                    <input type="button" class="btn btn-light" class="close" data-dismiss="modal" value="Cancelar">
                    <input type="submit" class="btn btn-primary" name="importar" value="Importar"></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $('#confirm-eliminar-modal').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

            $('.debug-url').html('Delete URL: <strong>' + $(this).find(
                '.btn-ok').attr('href') + '</strong>');
        });
    </script>


    <script>
        $(function() {
            $('#selectAll').change(function() {
                $('#listado > input[type=checkbox]').prop('checked', $(this).is(':checked'));
            });
        });
    </script>
    <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#busqueda").on("keyup", function() {
                var value = $(this).val().toLowerCase();

                $("#raps tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });

            });
        });
    </script>


</body>

</html>