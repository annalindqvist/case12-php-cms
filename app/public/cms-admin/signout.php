<?php   
session_start();
session_destroy(); 
header("location: /cms-admin/signin.php"); 
exit();
?>