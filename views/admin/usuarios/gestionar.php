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
                                <h2>Usuarios</h2>
                            </div>
                            <div class="col text-right">

                                <a href="historial" class="btn btn-historial" style="margin-right:10px;">
                                <i class="fas fa-history"></i>
                                    Historial
                                </a>

                                <a href='#' class='btn btn-danger' data-toggle='modal' data-target='#confirm-eliminar-items-modal'>
                                    <i class="far fa-trash-alt"></i> <span>Eliminar</span>
                                </a>

                                <a href="crear" class="btn btn-success" style="margin-left:10px;">
                                <i class="fas fa-plus-circle"></i>
                                    Crear
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="panel-group">
                            <div class="panel-heading">
                                <?php
                                include "../../../extra/conexion.php";

                                $sql2 = mysqli_query($link, "SELECT COUNT(*) AS TotalRegistros FROM usuarios WHERE Status = 1");
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

                                $sql = "SELECT u.IdPrincipal, u.Usuario, u.Nombre, u.Correo, u.Telefono, u.Informacion, 
                    
                    case when u.Rol = 1 then 'ADMINISTRADOR'
                    when u.Rol = 2 then 'COORDINADOR'
                    when u.Rol = 3 then 'INSTRUCTOR'
                    when u.Rol = 0 then 'INDEFINIDO'
                    end as 'Rol' 
                    
                    FROM usuarios u WHERE Status = 1 LIMIT $desde,$porPagina";

                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<div class="info-busqueda">
                            <div class="row">                               
                                <div class="msgalerta">
                                    <div class="alert alert-success"> 
                                        <a type="button" class="close" data-dismiss="alert" aria-hidden="true"> ×</a>                               
                                        <i class="fas fa-info-circle"></i> Puedes realizar una búsqueda filtrando los resultados, ya sean
                                        usuarios de tipo Administrador, Coordinador o Instructor.                                                                               
                                    </div>                                    
                                </div>
                            </div>
                                <div class="busca">
                                <i style="font-size:16px;" class="fas fa-search"></i>   
                                    <input type="text" name="busqueda" id="busqueda" placeholder=" Realizar una búsqueda" title="Buscar" autocomplete="off" spellcheck="false" autofocus=""> 
                                </div>
                                <div class="col text-right p-0">
                                    <a data-toggle="modal" data-target="#modal-import" class="btn btn-outline-info" id="import" title="Importar Excel" style="border-radius: 5px 0px 0px 0px; border-right-color:transparent; color: #17A2B8;"><i class="fas fa-upload"></i></a><a href="#" class="btn btn-outline-danger" id="IdPDF" title="Descagar como PDF" style="border-radius: 0px 0px 0px 0px;"><i class="far fa-file-pdf"></i></a><a href="#" class="btn btn-outline-success" id="IdExcel" title="Descagar como archivo Excel" style="border-radius: 0px 5px 0px 0px; border-left-color:transparent;"><i class="far fa-file-excel"></i></a>
                                </div>

                            </div>';
                                        echo '<form method="POST" action="delete-all.php">';
                                        echo "<div class='table-responsive'>";
                                        echo "<table class='table table-striped' style='color:#737373;'> ";
                                        echo "<thead>";
                                        echo "<tr>";
                                        //Reporte todo echo "<th onclick='ReporteAll()'><span class='custom-checkbox'><input type='checkbox' title='Seleccionar todos los instructores eliminados' id='selectAll'><label for='selectAll'></label></span></th>";
                                        echo "<th><span class='custom-checkbox'><input type='checkbox' name='SeleccionarAll' title='Seleccionar todos los instructores eliminados' id='selectAll'><label for='selectAll'></label></span></th>";
                                        echo "<th>Usuario</th>";
                                        echo "<th>Nombre</th>";
                                        echo "<th>Correo</th>";
                                        echo "<th>Telefono</th>";
                                        echo "<th>Rol</th>";
                                        echo "<th>Información adicional</th>";
                                        echo "<th colspan='2'>Acciones</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody id='usuarios'>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    echo "<td> <div id='listado' class='custom-checkbox'><input type='checkbox' name='select[]' id='select[]' value='".$row['IdPrincipal']."'><label for='select'></label></div> </td>";                      
                                        echo "<td id=".$row['IdPrincipal']." onClick='obtenerID(this.id)'>" . $row['Usuario'] . "</td>";
                                        echo "<td>" . $row['Nombre'] . "</td>";
                                        echo "<td>" . $row['Correo'] . "</td>";
                                        echo "<td>" . $row['Telefono'] . "</td>";                                
                                        echo "<td>" . $row['Rol'] . "</td>";
                                        echo "<td>" . $row['Informacion'] . "</td>";
                                        echo "<td>
                                        <a href='update.php?IdPrincipal=". $row['IdPrincipal'] ."' data-toggle='tooltip'><i class='far fa-edit' title='Editar'></i></a>
                                        <a href='#' data-href='delete.php?IdPrincipal=". $row['IdPrincipal'] ."' data-toggle='modal' data-target='#confirm-eliminar-modal'><i class='far fa-trash-alt' title='Eliminar'></i></a> </td>";

                                    echo "</tr>";
                                }
                                echo "</tbody>";
                                echo '<div id="confirm-eliminar-items-modal" class="modal fade">
                                <div class="modal-dialog">
                                    <div class="modal-content">			
                                            <div class="modal-header">	                                                                    
                                                <h4 class="modal-title">Eliminar usuarios</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>                                                                                                                   
                                            </div>                   
                                                <div class="modal-body">
                                                <form method="POST" action="delete-all.php">					
                                                    <p>¿Estas seguro de eliminar los usuarios seleccionados?</p>						
                                                </div>                        
                                            <div class="modal-footer">
                                                <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">                        
                                                <input type="submit" class="btn btn-danger btn-ok" value="Eliminar">                                        
                                            </div>
                        
                                        </form>
                                    </div>
                                </div>
                            </div>';
                                        echo "</table>";
                                        mysqli_free_result($result);
                                    } else {
                                        echo "<p style='text-align:center;' ><em>No hay registros de usuarios, <a href='crear' style='text-decoracion:none;'>agrega un nuevo usuario</a>. </em></p>";
                                    }
                                } else {
                                    echo "<div class='container'><span class='alert alert-danger'>Algo salió mal. Por favor, inténtelo de nuevo más tarde.</span></div>
                        $sql. " . mysqli_error($link);
                                }

                                mysqli_close($link);
                                ?>

                            </div>
                            <?php
                            if ($totalRegistros >= 8) {
                            ?>
                                <hr style="margin-top:0px">
                                <div class="text-center">
                                    <div class="clearfix" style="padding:5px;margin-left:30px;margin-right:30px;">
                                        <div class="hint-text">Página <b><?php echo $pagina; ?></b> de <b><?php echo $totalRegistros; ?></b> usuarios</div>
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
                                        ?>
                                        </ul>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>

        <div id="confirm-eliminar-modal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">

                        <h4 class="modal-title">Eliminar usuario</h4>
                        <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>



                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <p>¿Estas seguro de eliminar el usuario?</p>
                    </div>

                    <div class="modal-footer">
                        <input type="button" class="btn btn-light" data-dismiss="modal" value="Cancelar">
                        <a href="delete.php?IdPrincipal=<?php echo $row['IdPrincipal'] ?>" class="btn btn-danger btn-ok">Eliminar</a>
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
                        </div>
                    </div>
                    <div class="modal-footer">
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

            //OBTENER EL ID DEL PROGRAMMA POR METODO POST CON UN CLICK
            function obtenerID(clicked_id) {
                //  alert(clicked_id);
                window.open('../../../extra/Reporte-Usuario?IdPrincipal=' + (clicked_id), '_blank');
            };

            function ReporteAll() {
                window.open('../../../extra/Reporte-Usuarios', '_blank');
            }


            //OBTENER ID DEL CEHECK PARA GENERAR EL PDF ESPECIFICO
            $(document).ready(function() {
                var ckbox = $("input[name='select[]']");
                var chkId = '';
                $('input').on('click', function() {

                    if (ckbox.is(':checked')) {
                        $("input[name='select[]']:checked").each(function() {
                            chkId = $(this).val() + ",";
                            chkId = chkId.slice(0, -1);
                        });
                        //     alert ( $(this).val() ); 
                        //    alert(chkId);        
                        document.getElementById("IdPDF").href = "../../../extra/pdf/Reporte-Usuario?IdPrincipal=" + chkId;
                        document.getElementById("IdPDF").target = "blank";
                    }
                });
            });


            //CLICK CHECK PARA GENERAR EL PDF GENERAL
            $(document).ready(function() {
                var ckbox = $("input[name='SeleccionarAll']");
                var chkId = '';
                $('input').on('click', function() {

                    if (ckbox.is(':checked')) {
                        $("input[name='SeleccionarAll']:checked").each(function() {
                            chkId = $(this).val() + ",";
                            chkId = chkId.slice(0, -1);
                        });
                        document.getElementById("IdPDF").href = "../../../extra/pdf/Reporte-Usuarios";
                        document.getElementById("IdPDF").target = "blank";
                    }
                });
            });


            //OBTENER ID DEL CEHECK PARA GENERAR EL EXCEL ESPECIFICO
            $(document).ready(function() {
                var ckbox = $("input[name='select[]']");
                var chkId = '';
                $('input').on('click', function() {

                    if (ckbox.is(':checked')) {
                        $("input[name='select[]']:checked").each(function() {
                            chkId = $(this).val() + ",";
                            chkId = chkId.slice(0, -1);
                        });
                        //     alert ( $(this).val() ); 
                        //    alert(chkId);        
                        document.getElementById("IdExcel").href = "../../../extra/excel/excel-usuario?IdPrincipal=" + chkId
                    }
                });
            });



            //CLICK CHECK PARA GENERAR EL EXCEL GENERAL
            $(document).ready(function() {
                var ckbox = $("input[name='SeleccionarAll']");
                var chkId = '';
                $('input').on('click', function() {

                    if (ckbox.is(':checked')) {
                        $("input[name='SeleccionarAll']:checked").each(function() {
                            chkId = $(this).val() + ",";
                            chkId = chkId.slice(0, -1);
                        });
                        document.getElementById("IdExcel").href = "../../../extra/excel/excel-usuarios"
                    }
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $("#busqueda").on("keyup", function() {
                    var value = $(this).val().toLowerCase();

                    $("#usuarios tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });

                });
            });
        </script>

        <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>

</html>