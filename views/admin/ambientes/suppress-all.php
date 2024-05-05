
    <?php        
  include "../../../extra/conexion.php"; 
    $borrar = implode(",", $_POST['select']);

    if($borrar == ''){
      echo "<script>window.location = 'historial';</script>";
    }

    mysqli_query($link,"DELETE FROM ambientes WHERE IdPrincipal IN ($borrar)");    
    header('location:gestionar.php');
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>


