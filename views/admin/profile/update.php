<?php
include "../../../extra/conexion.php"; 
 
$usuario = $nombre = $correo = $tel = $descripcion= "";
$tel_err =  "";

//$id =  base64_decode(trim($_REQUEST["IdPrincipal"]));
$id = $_GET['IdPrincipal'];
if(empty($id)){
    echo "<script>window.location='../profile'</script>";
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
       
    $usuario = trim($_POST["Usuario"]);            
    
    $nombre = trim($_POST["Nombre"]);       

    $correo = trim($_POST["Correo"]);      
    
   $input_tel = trim($_POST["Telefono"]);
    if(empty($input_tel)){
        $tel_err = "<small>Por favor ingrese el telefono.</small>";
    } elseif(!filter_var($input_tel, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/[0-9]+/")))){
        $tel_err = "<small>Por favor ingrese un telefono válido.</small>";
    } else{
        $tel = $input_tel;
    }
    
    $input_descripcion = trim($_POST["Descripcion"]);
        $descripcion = $input_descripcion;  

    
   if(empty($tel_err)){
        $sql = "UPDATE usuarios SET Telefono=?, Descripcion=? WHERE IdPrincipal=".$_SESSION['idUser'];
         
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "isi", $param_tel, $param_descripcion, $param_id);
                  
            $param_tel = $tel;
            $param_descripcion = $descripcion;      
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: ../profile");
                exit();
            } else{
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }
        }
         
        mysqli_stmt_close($stmt);
    }
    
    //mysqli_close($link);
}else{
    if(isset($id) && !empty(trim($id ))){
        $id =  trim($id);
        
        $sql = "SELECT * FROM usuarios WHERE IdPrincipal = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
                    $usuario = $row["Usuario"];
                    $nombre = $row["Nombre"];                 
                    $correo = $row["Correo"];
                    $tel = $row["Telefono"];
                    $descripcion = $row["Descripcion"];
                } else{
                    header("location: ../principal/error");
                    exit();
                }
                
            } else{
                echo "Algo salió mal. Por favor, inténtelo de nuevo más tarde.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
       // mysqli_close($link);
    }  else{
        header("location: ../principal/error");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<?php     
    require_once "../include/header-admin.php";
    if($_GET['IdPrincipal'] != $_SESSION['idUser']){        
        echo "<script>window.location='../profile'</script>";
    }
?>  

<body>
<div class="container">        
    <div class="card" id="card-profile" style=" border-radius: .25rem .25rem 0 0;">  
        <p>
            <h1>Actualizar datos personales</h1>
            <span>Solo se permite actualizar datos estrictamente necesarios, si requiere hacer algún cambio, contacta con un Administrador </span>
        <p>          
            <hr>                           
      
        <div class="card-body" id="content-profile" style="padding-bottom:0px !important;padding-top:0px!important;">
            <ul class="list-group list-group-flush" style="padding-top:0px!important;">

            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">  
                    <li class="list-group-item" style="padding-bottom:20px!important;">Número de identificación               
                        <div class="form-group" style="margin-bottom:0px!important;padding-top:20px!important;">               
                        <input type="text" readonly="readonly" name="Usuario" class="form-control" value="<?php echo $usuario; ?>">
                        </div>             
                    </li>
                    <li class="list-group-item" style="padding-bottom:20px!important;">Nombre              
                        <div class="form-group" style="margin-bottom:0px!important;padding-top:20px!important;">               
                        <input type="text" readonly="readonly" name="Nombre" class="form-control" value="<?php echo $nombre; ?>">
                        </div>             
                    </li> 
                    <li class="list-group-item" style="padding-bottom:20px!important;">Correo institucional              
                        <div class="form-group" style="margin-bottom:0px!important;padding-top:20px!important;">               
                        <input type="text" readonly="readonly" name="Correo" class="form-control" value="<?php echo $correo; ?>">
                        </div>             
                    </li> 
                    <li class="list-group-item" style="padding-bottom:20px!important;">Telefono            
                    <div class="form-group <?php echo (!empty($tel_err)) ? 'has-error' : ''; ?>" style="margin-bottom:0px!important;padding-top:20px!important;">
                        <input type="text" name="Telefono" autocomplete="off" style="border-bottom:1px solid rgba(0,0,0,0.19);border-radius:0 0 0 0;width:104px;padding-left:4px!important;padding-bottom:0!important" class="form-control validanumericos" value="<?php echo $tel; ?>">                        
                        <span style="color: #a94442" class="help-block"><?php echo $tel_err;?></span>
                        </div>               
                    </li> 
                    <li class="list-group-item" style="padding-bottom:20px!important;">Descripcion                                      
                        <div class="form-group" style="margin-bottom:0px!important;padding-top:20px!important;">               
                        <textarea name="Descripcion" class="form-control" style="border-bottom:1px solid rgba(0,0,0,.19);border-radius:0 0 0 0;width:119px;padding-left:4px!important"><?php echo $descripcion; ?></textarea>
                        </div>                                        
                    </li>   
                    <li class="list-group-item">    
                        <input type="hidden" name="IdPrincipal" value="<?php echo $id; ?>"/>          
                        <a class="btnr2" href="../profile/">Cancelar</a>
                        <input type="submit" class="btnr" value="Actualizar">               
                    </li>   
                </form>                             
            </ul>
        </div>  
                   
</div>
  <br>
</div>            

<script scr="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
        $(function(){

            $('.validanumericos').keypress(function(e) {
            if(isNaN(this.value + String.fromCharCode(e.charCode))) 
            return false;
            })
            .on("cut copy paste",function(e){
            e.preventDefault();
            });
        });
</script>

<script> 


</script> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</body>
</html>
