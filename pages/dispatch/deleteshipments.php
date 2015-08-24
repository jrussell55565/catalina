<?php
$orders 	= $_POST['order'];
$result 	= count($orders);

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");

$aDoor = $_POST['chk_hawb'];
  if(empty($aDoor))
  {
    header("Location: /pages/dispatch/orders.php");
  }
  else
  {
    $N = count($aDoor);
 
    for($i=0; $i < $N; $i++)
    {
        $recordid = $aDoor[$i];
	mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
	mysql_select_db($db_name) or DIE('Database name is not available!');

	// Retrieve username and password from database according to user's input
	#mysql_query("UPDATE dispatch SET deleted='T' WHERE recordID=$recordid");
	mysql_query("DELETE FROM dispatch WHERE recordID=$recordid");
	mysql_query("insert into deleted (hawbNumber) select hawbNumber from dispatch WHERE recordID=$recordid");
    }
  }

exit;
?>
