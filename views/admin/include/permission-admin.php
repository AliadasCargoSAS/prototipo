<?php

session_start();

if ($_SESSION['rol'] == 1) {
  
}else{
  header("location: /");
  exit;
}

if(empty($_SESSION['active'])){
  header('location:../../principal/login.php');
}
?>