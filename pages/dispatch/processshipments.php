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
    mysql_connect($db_hostname, $db_username, $db_password) 
     or DIE('Connection to host is failed, perhaps the service is down!');
    mysql_select_db($db_name) or DIE('Database name is not available!');
 
    for($i=0; $i < $N; $i++)
    {
        $recordid = $aDoor[$i];

        if (isset($_POST["archive"]))
	{
            $sql = "UPDATE dispatch SET archived='T', deleted='F', modifiedDate = now() WHERE recordID=$recordid";
        }
        if (isset($_POST["delete"]))
	{
            $sql = "UPDATE dispatch SET archived='F', deleted='T', modifiedDate = now() WHERE recordID=$recordid";
        }
        if (isset($_POST["unarchive"]))
	{
            $sql = "UPDATE dispatch SET archived='F', deleted='F', modifiedDate = now() WHERE recordID=$recordid";
        }
        mysql_query($sql);
    }
  }
header("location: /pages/dispatch/orders.php");
exit;
?>
