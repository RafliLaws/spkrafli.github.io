<?php 

session_start();
// dibawah jaga2 makanya banyak
$_SESSION = [];
session_unset();
session_destroy();

setcookie('aydi', '', time() - 3600);
setcookie('key', '', time() - 3600);
header("Location: login.php");
?>