
<!DOCTYPE html>
<html lang="es">

<?php     
    require_once "../include/header-admin.php";
?>  

<body>

<?php 
    $name = $_GET['l'];
    if(empty($name) || ($name != 'true')) {
      $name = 'true';
    }

    if(isset($_GET['l'])) {
        $sqlnotileida= 'UPDATE notificaciones SET leido = 1';                        
            if($link->query($sqlnotileida) === TRUE){
                echo '<script>window.location="notificaciones";</script>';
            }else{ 
                echo '<div class="container"><div class="alert alert-danger">Ocurrió un error con las notificaciones, porfavor intentalo más tarde<a type="button" class="close" data-dismiss="alert" aria-hidden="true"> ×</a></div></div>';         
            } 
    }       
  
?>

    <div class="container mb-3">
        <div class="card" id="cont-bloque-corto">
            <p>
                <h4 class="text-center pt-3">Notificaciones</h4>
                <span class="text-center text-muted mx-2">Todas las notificaciones de elementos eliminados, elementos restablecidos y demás notificaciones sustanciales</span>
                <small class="text-center text-dark pb-2">Las notificaciones se eliminarán aútomaticamente dentro 30 días</small>
            <hr class="ml-4 mr-4 pl-3 pr-3 mb-0">
            </p>

            <div class="card-body pl-4 pr-4 pb-4 pt-0 ml-4 mr-4 mt-0 mb-0">
                <h6 class="pt-0 pb-0 mb-4">Todas las notificaciones</h6>
                       <?php                                                                                  
                                $sqlnoti2= "SELECT * FROM notificaciones ORDER BY Id DESC";                            
                                if($resultcount2 = mysqli_query($link, $sqlnoti2)){
                                    if(mysqli_num_rows($resultcount2) > 0){                                         
                                        while($row = mysqli_fetch_array($resultcount2)){
                                            $idnoti = $row['Id'];
                                            $accion = $row['Accion'];
                                            $descripcionnoti = $row['Descripcion'];                                        
                                            $tiponoti = $row['Tipo'];
                                            $leido = $row['Leido'];
                                            $fechanoti = $row['Fecha'];
                                            $linknoti = $row['Link']; 
                                            $autor = $row['Autor'];
                                       
                                        echo '<div id="'.$idnoti.'" class="pb-4">                                       
                                        <div class="d-flex flex-row"><div class="pr-3" title="'.$autor.'">';
                                        $sqlautor = "SELECT Avatar FROM usuarios WHERE Nombre = '$autor'"; 
                                        if($resultautor = mysqli_query($link, $sqlautor)){                                           
                                        $resultautor = mysqli_query($link, $sqlautor);                                            
                                            while($row = @mysqli_fetch_array($resultautor)){
                                                $avatarImg2 = $row['Avatar'];                                                                                                                                                                                                                                                                
                                               if($avatarImg2 != ''){
                                                echo '<img class="float:left" style="width:40px;height:40px;border-radius:50px;" src="../../../upload-images/'.$avatarImg2.'">';
                                                }else{
                                                    echo '<img class="float:left" style="width:40px;height:40px;border-radius:50px;" src="../../../upload-images/user-start.jpg">';
                                                }
                                            }                                        
                                        }          
                                             echo  '</div>
                                                <div style="max-width:85%;">
                                                <h6 class="a-notificaciones p-0 text-secondary mt-0">'.$accion.'</h6>
                                                <p class="text-muted notificaciones-descri mb-0" style="max-width:100%;white-space: normal;">'.$descripcionnoti.'</p>                                                
                                                    <small class="text-primary" style="font-size:11px;">'.$fechanoti.'</small>
                                                </div></div>
                                        </div>';

                                } 
                                    }
                                }; 
                                
                            $sqleliminarnoti = "DELETE FROM notificaciones WHERE curdate() >= date_add(Fecha, interval 30 day)";
                            $query = $link->query($sqleliminarnoti);       
          
                ?> 

            </div>
        </div>

    </div>



<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</body>
</html>