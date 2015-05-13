<?php
// To add printers to your account follow the following link 
// https://support.google.com/cloudprint/answer/1686197

error_reporting(E_ALL);
ini_set('display_errors', 1);

// To add printers to your account follow the following link 
// https://support.google.com/cloudprint/answer/1686197

session_start();
//echo 'start';
if (!isset($_SESSION['accessToken'])) {
    
    header("Location: oAuthRedirect.php?op=getauth");
}
?>