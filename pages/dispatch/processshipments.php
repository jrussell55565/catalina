<?php
$orders 	= $_POST['order'];
$result 	= count($orders);

session_start();
if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

# setup the database connection
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

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
      if (isset($_POST["archive"]))
      {         
          $sql = "UPDATE dispatch SET archived='T', deleted='F', modifiedDate = now() WHERE recordID=$recordid";          
          $statement = "INSERT INTO driver_archive_logs (employee_id, dispatch_record_id, notes) VALUES ('".$_SESSION['employee_id']."', $recordid, \"User Archived HWB\")";
      }
      if (isset($_POST["delete"]))
      {
          $sql = "UPDATE dispatch SET archived='F', deleted='T', modifiedDate = now() WHERE recordID=$recordid";
          $statement = "INSERT INTO driver_archive_logs (employee_id, dispatch_record_id, notes) VALUES ('".$_SESSION['employee_id']."', $recordid, \"User Deleted HWB\")";
      }
      if (isset($_POST["unarchive"]))
      {
          $sql = "UPDATE dispatch SET archived='F', deleted='F', modifiedDate = now() WHERE recordID=$recordid";
          $statement = "INSERT INTO driver_archive_logs (employee_id, dispatch_record_id, notes) VALUES ('".$_SESSION['employee_id']."', $recordid, \"User Un-archived HWB\")";
      }
        # Start TX
        $mysqli->autocommit(FALSE);
        $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        try {
          if ($mysqli->query($sql) === false)
          {
            throw new Exception("Unable to set record $record as archived/deleted/unarchived: ".$mysqli->error);
          }
          if ($mysqli->query($statement) === false)
          {
            throw new Exception("Unable to log record $record action: ".$mysqli->error);
          }
          $mysqli->commit();
          } catch (Exception $e) {
            // An exception has been thrown
            // We must rollback the transaction
            $url_error = urlencode($e->getMessage());
            $mysqli->rollback();
            header("location: /pages/dispatch/orders.php?error=$url_error");
            $mysqli->autocommit(TRUE);
            $mysqli->close();
            exit;
          }
        
    }
  }
header("location: /pages/dispatch/orders.php");
exit;
?>
