<?php
include('DBconfig.php');
//Sessie wordt gestopt en wordt terug geleid naar de homepage
session_destroy();
echo "<script> location.href='/project-sites/FFF/index.php'; </script>";
?>