<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

$username = $_GET['username'];
$frequency = $_GET['frequency'];

if ($username == 'all')
{
    $sql = "select a.*,b.username from productivity_shipments a, users b where a.employee_id = b.employee_id and a.`interval` = '$frequency' order by percentage_earned desc";
}else{
    $sql = "select * from productivity_shipments where `interval` = '$frequency'
            AND employee_id = (select employee_id from users where username = '$username')";
}
    try {
       if ($result = $mysqli->query($sql))
        {
            while ($row = $result->fetch_assoc()) {
                $emparray[] = $row;
            }
            $result->close();
        }else{
            throw new Exception("Query error: ". $mysqli->error);
        }
      } catch (Exception $e) {
        // An exception has been thrown
        $data = array('type' => 'error', 'message' => $e->getMessage());
        header('HTTP/1.1 400 Bad Request');
        header('Content-Type: application/json; charset=UTF-8');
        $mysqli->close();
        echo json_encode($data);
        exit;
      }
#echo $sql; exit;
#var_dump(arsort($emparray));exit;
print json_encode($emparray);
?>
