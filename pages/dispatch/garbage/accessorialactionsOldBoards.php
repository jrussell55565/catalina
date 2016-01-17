<?php
session_start();
if ($_SESSION['login'] != 1)
{
        header('Location: accessorials.php');
}
include('global.php');
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
$revenue_charge = $_POST['revenue_charge'];
$acc_type = $_POST['acc_type'];
$revenue_amount = $_POST['revenue_amount'];
$acc_itemnum = $_POST['acc_itemnum'];
$src_page = $_POST['src_page'];

if ($revenue_amount == '')
{
	$revenue_amount = 0;
}
$input_type = $_POST['checkortext'];

if ($input_type == "Check Box")
{
	$input_type = "ck_".str_replace(' ','_',$revenue_charge);
}elseif ($input_type == "Text Field"){
	$input_type = "bx_".str_replace(' ','_',$revenue_charge);;
}else{
	$input_type = "hdn_".str_replace(' ','_',$revenue_charge);;
}

switch ($_POST['btn_submit'])
{
    case "Delete":
	$sql = "DELETE FROM accessorials WHERE acc_itemnum=$acc_itemnum";
        break;
    case "Update":
	$sql = "UPDATE accessorials set acc_type=\"$acc_type\", revenue_charge=\"$revenue_charge\", revenue_amount=$revenue_amount, input_type=\"$input_type\", src_page=\"$src_page\"  WHERE acc_itemnum=$acc_itemnum";
        break;
    case "Add":
        $sql = "INSERT INTO accessorials (acc_type,revenue_charge,revenue_amount,input_type) VALUES (\"$acc_type\",\"$revenue_charge\",$revenue_amount,\"$input_type\",\"$src_page\")";
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
header("Location: accessorials.php");
?>
