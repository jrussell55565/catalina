<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$recordid = $_POST['recordid'];
$newstatus = $_POST['StatusChange'];

$recordid = mysql_real_escape_string($recordid);
$newstatus = mysql_real_escape_string($newstatus);

$sql = "UPDATE dispatch SET status=\"$newstatus\", modifiedDate = now() WHERE recordID=$recordid";
$output = mysql_query($sql);
header("Location: /pages/dispatch/orders.php");
?>
