
    <?php        
    include "../../../extra/conexion.php"; 
    $borrar = implode(",", $_POST['select']);
    mysqli_query($link,"DELETE FROM instructores WHERE IdPrincipal IN ($borrar)");    
    header('location:gestionar.php');	
?>

<?php     
        require_once "../include/permission-admin.php";
    ?>


