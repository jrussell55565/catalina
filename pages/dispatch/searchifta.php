<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

print_r($_GET);
?>
