
    <?php        
    include "../../../extra/conexion.php"; 
    $borrar = implode(",", $_POST['select']);
    mysqli_query($link,"UPDATE instructores SET Status = 1 WHERE IdPrincipal IN ($borrar)");    
    header('location:gestionar.php');	
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>


