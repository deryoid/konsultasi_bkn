<?php

session_start();  
   $_SESSION['role'];

   if ($_SESSION['role'] == "Admin") {
       	$_SESSION['id_user'];  
     	unset($_SESSION["id_user"]);

     }elseif ($_SESSION['role'] == "User") {
     	$_SESSION['id_user'];   
     	unset($_SESSION["id_user"]);

     }





session_unset();
session_destroy();

echo "<meta http-equiv='refresh' content='0; url=index.php'>";

?>
  
