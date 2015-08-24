<?php
session_start();
if ($_SESSION['login'] != 1)
{
#	header('Location: orders.php');
}
include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$coordinates    = explode('|',$_POST["hdn_coordinates"]);
$latitude = $coordinates[0];
$longitude = $coordinates[1];

$sql = "insert into coordinates (driver_id, latitude, longitude)
                values ((select driverid from users where username = '$_SESSION[userid]'),
                        $latitude, $longitude)";

        $output = mysql_query($sql);
        if (!$output)
        {
          return mysql_error();
        }else{
          if (isset($_SESSION['dberror']))
          {
                unset($_SESSION['dberror']);
          }
        }
	return "Updated Geolocation";
?>
