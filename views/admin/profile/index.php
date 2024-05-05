<!DOCTYPE html>
<html lang="es">

<?php     
    require_once "../include/header-admin.php";
?>  

<body>
    <div class="container mb-3">  
        <div id="cont-bloque-corto">  
        <div class="card" id="card-profile" id="cont-bloque-corto" style="border-radius: .25rem .25rem 0 0;">  
        <p>
            <h1>Información personal</h1>
            <span>Información básica, como tu nombre y foto, que usas en el sistema de programación de eventos formativos</span>
        <p>          
            <hr>                          
        <div class="card-body">
                <h2 class="card-title" style="text-align:center;margin-bottom:0!important">Perfil</h2>
        </div>
        <?php
                if (isset($_POST['enviar'])) {

                        $nombre = $_FILES['avatar']['name'];

                        if (isset($nombre) && $nombre != "") {

                            $tipo = $_FILES['avatar']['type'];
                            $temp = $_FILES['avatar']['tmp_name'];

                            if (!((strpos($tipo, "gif") || strpos($tipo, "jpeg") || strpos($tipo, "jpg") || strpos($tipo, "png")))){
                                echo '<script>alert("La extensión de los archivos no es correcta.\n\n- Se permiten archivos .gif, .jpg, .png");</script>';             
                            }else{

                                if (move_uploaded_file($temp, '../../../upload-images/'.$nombre)) {
                                    //Cambiamos los permisos del archivo a 777 para poder modificarlo posteriormente
                                    chmod('../../../upload-images/'.$nombre, 0777);
                                    
                                    //Se guarda en la base de datos la imagen cargada por el usuario
                                    include "../../../extra/conexion.php"; 

                                    $sqlImg = "UPDATE usuarios SET Avatar=? WHERE IdPrincipal=? AND Status=1";

                                    if($stmt = mysqli_prepare($link, $sqlImg)){
                                        mysqli_stmt_bind_param($stmt, "si", $param_nombre, $param_id);

                                        $param_nombre= $nombre;
                                        $param_id = $id_user;

                                        if(mysqli_stmt_execute($stmt)){
                                            echo "<script>window.location='../profile/';</script>";                                          
                                        } else{
                                            //Si no se ha podido subir la imagen, mostramos un mensaje de error
                                            echo '<script>alert("Ocurrió algún error al subir la imagen. No pudo guardarse.");</script>';
                                        }
                                    }
                                   
                                }
                            }
                        }                   
                    } 
                //Se realiza una consulta a la base de datos para imprimir la imagen correspondiente del usuario
                include "../../../extra/conexion.php";

                $sqlConsultaImg = "SELECT Avatar FROM usuarios WHERE IdPrincipal = $id_user and Status = 1";

                $result = mysqli_query($link, $sqlConsultaImg);
                if (mysqli_num_rows($result) > 0) {
                    while($row = mysqli_fetch_array($result)){
                        $avatarImg = $row['Avatar'];
                        if($avatarImg != ""){
                            echo '<div class="img-profile">
                                    <img src="../../../upload-images/'.$avatarImg.'" style="cursor:pointer; color:#3C8DBC; border:1px solid #000;" alt="Imagen de usuario">
                                </div>';
                        }else{
                            echo '<div class="img-profile">
                                    <img src="../../../upload-images/user-start.jpg" style="cursor:pointer;color:#3C8DBC; border:1px solid #000;" alt="Imagen de usuario">
                                  </div>';
                        }
                    }
                }
                //onclick="location.href='../../../images/lobo.gif'"                
        ?>      
            <div class="cambiar-img-profile"  data-toggle="modal" data-target="#modal" href="#">                                                             
                <i class='fas fa-camera' data-toggle="modal" data-backdrop="static" data-target="#modal-cambiar-profile-photo" title="Cambiar foto de perfil" style="font-size:18px;cursor:pointer!important;margin-top:10px;color:#737373;"></i>                
            </div> 
      
        <div class="card-body" id="content-profile" style="padding-bottom:0px !important;">
            <ul class="list-group list-group-flush">            
                    <li class="list-group-item">Número de identificación <small style="float:right;color:#212529;"><?php echo $_SESSION['user'];?></small></li>
                    <li class="list-group-item">Nombre <small style="float:right;color:#212529;"><?php echo $_SESSION['nombre'];;?> </small></li>
                    <li class="list-group-item">Contraseña <small title="Actualizar contraseña" style="float:right;color:#212529;"><input type="password" readonly="readonly" value="<?php echo utf8_decode($CLAVE); ?>"> <i style="margin-left:10px" class="fas fa-angle-right"></i></small> </li> 
                    <li class="list-group-item">Correo <small style="float:right;color:#212529;"><?php echo utf8_decode($_SESSION['email']);?> </small></li>
                    <li class="list-group-item">Telefono <small style="float:right;color:#212529;"><?php echo $TELEFONO;?> </small></li>    
            </ul>
        </div>            
        </div> 
        <?php 
         //echo"<div class='actu-datos'><a href='update?IdPrincipal=". base64_encode($_SESSION['idUser']) ."'> Actualizar datos <i style='float:right;margin-right:4%;' class='fas fa-angle-right'></i></a></div>";
         echo"<div class='actu-datos'><a href='update?IdPrincipal=". $_SESSION['idUser'] ."'> Actualizar datos <i style='float:right;margin-right:4%;' class='fas fa-angle-right'></i></a></div>";

        ?>
       

    </div>

<br>

<div id="modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
                   <div class="modal-header">                         
                        <h5>Cambiar foto de perfil</h5>
                         <div class="close"><a style="margin-right:15px" href="#" class="close" data-dismiss="modal">&times;</a></div>  
                    </div>

                        <div class="modal-body">
                            <small>Actualiza tu foto de perfil de usuario</small><br>
                            <span style="font-size:66%">Se recomienda usar una imagen, al menos, de 400x400 pixeles.</span>

                            <div class="card" style="margin-top:10px;margin-bottom:10px">
                                <div class="card-body">
                                    <form action="" method="post" enctype="multipart/form-data">                           
                                        <input type="file" name="avatar" style="max-width: 100%;" required> 
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">                       
                        <input type="button" class="btnr2" style="max-width:100px;" class="close" data-dismiss="modal" value="Cancelar">                
                        <input type="submit" class="btnr" name="enviar" value="Aceptar"></div>         
                    </form>
			</div>
		</div>
    </div> 
    </div>                                            
</body>

<script> 
        $('#modal').on ('show.bs.modal', function (e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data 
            ('href'));

            $('.debug-url').html('Delete URL: <strong>' + $(this).find(
                '.btn-ok').attr('href') + '</strong>');
        });  
                   
    </script>       

    <script src="https://code.jquery.com/jquery-3.2.1.js"></script>    
    <script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</html>    