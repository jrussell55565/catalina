<?php
session_start();
if ($_SESSION['login'] != 1)
{
        header('Location: orders.php');
}
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$driverName = $_POST['driver_name'];
$driverLogin = $_POST['driver_login'];
$driverPassword = $_POST['driver_password'];
$driverEmail	= $_POST['driver_email'];
$driverID = $_POST['driver_ID'];
$vtext = $_POST['driver_vtext'];
#print_r($_POST);exit;
if(isset($_POST['driver_admin']))
{
	$driverAdmin = "1";
}else{
	$driverAdmin = "0";
}
if(isset($_POST['ck_vtextupdate']))
{
	$vtextupdate = "1";
}else{
	$vtextupdate = "0";
}
if(isset($_POST['ck_emailupdate']))
{
	$emailupdate = "1";
}else{
	$emailupdate = "0";
}

switch ($_POST['btn_submit'])
{
    case "Update":
        $sql = "UPDATE users SET drivername=\"$driverName\", username=\"$driverLogin\", password=\"$driverPassword\", email=\"$driverEmail\", driverid=\"$driverID\", admin=\"$driverAdmin\", vtext=\"$vtext\", emailupdate=\"$emailupdate\", vtextupdate=\"$vtextupdate\" WHERE username=\"$driverLogin\"";
        break;
    case "Delete":
	$sql = "DELETE FROM users WHERE username=\"$driverLogin\"";
        break;
    case "Add":
        $sql = "INSERT INTO users (drivername,username,password,email,vtext,driverid,admin,emailupdate,vtextupdate) VALUES (\"$driverName\",\"$driverLogin\",\"$driverPassword\",\"$driverEmail\",\"$vtext\",\"$driverID\",\"$driverAdmin\",\"$emailupdate\",\"$vtextupdate\")";
        break;
}

$output = mysql_query($sql);
if (!$output)
{
	$_SESSION['dberror'] = mysql_error();
}else{
	if (isset($_SESSION['dberror']))
	{
		unset($_SESSION['dberror']);
	}
}
header("Location: admin.php");
?>
