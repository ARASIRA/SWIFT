<?php
session_start();
ob_start(); 
unset($_SESSION['uyeid']);
setcookie('uyeid', null);
header("Location: /");
?>