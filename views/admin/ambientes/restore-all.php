
    <?php        
    include "../../../extra/conexion.php"; 
    $borrar = implode(",", $_POST['select']);

    if($borrar == ''){
        echo "<script>window.location = 'historial';</script>";
    }

    mysqli_query($link,"UPDATE ambientes SET Status = 1 WHERE IdPrincipal IN ($borrar)");    
    header('location:gestionar');	
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>


