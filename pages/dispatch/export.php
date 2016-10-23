<?php
session_start();
if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

# Export the POST values into a csv file.  The output of the CSV needs to be:
# HWB,Status,PODName,PODDate,PodTime,Pcs,ReWeigh,Load Position,PickupConfirmed,PickupTime,Remarks,Driver,Remark Type,Remark Date,Remark Time,Wait Time Pick Up,Wait Time Delivery,Residential Pick Up,Residential Delivery,Lift Gate Pick Up,Lift Gate Delivery,Lumper Fee Pick Up,Lumper Fee Pickup Amount,Lumper Fee Delivery,Lumper Fee Delivery Amount

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

# Get some db values
$recordid = $_POST[recordid];
$sql = "select 
        hawbNumber, PUAgentDriverName, delAgentDriverName, COALESCE(puAgentCode,'CATTUS1') puAgentCode,
        date_format(str_to_date(dueDate, '%c/%e/%Y'),'%c/%e/%Y') dueDate,
        control
        from dispatch where recordId = $recordid";

$statement = 'SELECT drivername,employee_id,fname,lname,email from users where username = "'.$_SESSION['userid'].'"';
$output = mysql_fetch_array(mysql_query($statement),MYSQL_BOTH);
$drivername = $output['drivername'];
$drivername_export = $drivername;
$employee_id = $output['employee_id'];
$driver_full_name = $output['fname'] . " " . $output['lname'];
$driver_email = $output['email'];

$row = mysql_fetch_array(mysql_query($sql),MYSQL_BOTH);
$hawb       = $row['hawbNumber'];
$puDriver   = $row['PUAgentDriverName'];
$delDriver  = $row['delAgentDriverName'];
$exportdest = $row['control'];
$dueDate    = $row['dueDate'];
$username	= $_SESSION['userid'];

# Common POST
$localtime 	= $_POST['bx_localtime'];
$localdate 	= $_POST['bx_localdate'];
$loadposition	= $_POST['LoadPosition'];
$remarks	= $_POST['remarks'];
$statustype	= $_POST['btn_sourceform'];

// If we're doing a simple status just set statusType to that value
if (! empty($_POST['sel_quickStatus']))
{
    $statustype = $_POST['sel_quickStatus'];
}

$pieces		= $_POST['txt_pieces'];
$pallets	= $_POST['txt_pallets'];
$podname        = $_POST['podName'];
$poddate        = $_POST['podDate'];
$podtime        = $_POST['bx_localtime'];
$sqlSearchIn = "";
$accessorials = "";
$accessorialsEmail = "";

# set some defaults
$reweigh = "";
$puconf  = "";
$putime  = "";
$remtype = "DC";
$remdate = "";
$remtime = "";
$deldriv = "";

# Remove CRLF from Remarks
$remarks = str_replace (array("\r\n", "\n", "\r", ","), ' ', $remarks);

# If the statustype is NOT delivered then we'll just reset podDate and podTime to empty
if ($statustype != 'Delivered')
{
    $poddate = '';
    $podtime = '';
}

# If the $exportdest is  FSITUS or FSIBK send email to  operations@freightservices.net 
# otherwise send to hwbcom@catalinacartage.com 
switch ($exportdest) {
    case "FSITUS":
        $export_email = "jaime.russell@freightservices.net";
        $accessorial_email = "jaime.russell@freightservices.net";
        break;
    case "FSIBK":
        $export_email = "jaime.russell@freightservices.net";
        $accessorial_email = "jaime.russell@freightservices.net";
        break;
    default:
        $export_email = "hwbcom@catalinacartage.com";
        $accessorial_email = "accessorials@catalinacartage.com";
}
switch ($statustype)
{
    case "Arrived to Shipper":
	    $status = "Arrived to Shipper";
	    $accessorials = processAccessorials($hawb,"PU",$username);
        $drivername = $puDriver;
	    if ($remarks != '')
	    {
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
    break;

    case "Accepted PU":
	    $status = "Accepted PU";
	    $accessorials = processAccessorials($hawb,"PU",$username);
        $drivername = $puDriver;
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
    break;

    case "Arrived to Consignee":
        $status = "Arrived To Consignee";
	    $accessorials = processAccessorials($hawb,"DEL",$username);
        $drivername = $delDriver;
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	}
    break;

    case "Accepted DEL":
	    $status = "Accepted DEL";
	    $accessorials = processAccessorials($hawb,"PU",$username);
        $drivername = $delDriver;
	    if ($remarks != '')
		    {
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
    break;

    case "Trace Note PU":
	    $status = "Trace Note PU";
	    $accessorials = processAccessorials($hawb,"PU",$username);
        $drivername = $puDriver;
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "In Transit":
        $status = "In Transit";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username,$status);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username,$status);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;
	
	
    case "Dropped":
        $status = "Dropped";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username,$status);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username,$status);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;	

    case "On Dock PHX":
        $status = "On Dock PHX";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username,$status);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username,$status);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "On Dock TUS":
        $status = "On Dock TUS";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username,$status);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username,$status);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "Trailer Dropped":
        $status = "Trailer Dropped";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "Reject PU DEL":
        $status = "Reject PU DEL";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "Refused":
        $status = "Refused";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "Freight At Dock":
        $status = "Freight At Dock";
        // If I'm the PU driver then give me a PU accessorial credit for this status
	    if ($puDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"PU",$username,$status);
	    }
        // If I'm the DEL driver then give me a DEL accessorial credit for this status
	    if ($delDriver == $drivername)
	    {
		    $accessorials = processAccessorials($hawb,"DEL",$username,$status);
	    }
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "Picked Up":
        $status = "Picked Up";
	    $puconf = "X";
	    $putime = $localtime;
	    $accessorials = processAccessorials($hawb,"PU",$username);
        $drivername = $puDriver;

	    if ($remarks != '')
	    {
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
	break;

    case "Attempted Pick Up":
        $status = "Attempted Pick Up";
	    $accessorials = processAccessorials($hawb,"PU",$username);
        $drivername = $puDriver;
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
    break;

    case "Attempted Delivery":
        $status = "Attempted Delivery";
	    $accessorials = processAccessorials($hawb,"DEL",$username);
        $drivername = $delDriver;
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$delDriver has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
    break;

    case "Trace Note DEL":
	    $status = "Trace Note DEL";
	    $accessorials = processAccessorials($hawb,"DEL",$username);
        $drivername = $delDriver;
	    if ($remarks != '')
		{
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	}
    break;

    case "Delivered":
        $status = "Delivered";
        # Form checking.
        if ($podname == '') { returnWithError($recordid,'/pages/dispatch/delconfirmed.php','podName'); }
        if ($pieces < 1) { returnWithError($recordid,'/pages/dispatch/delconfirmed.php','pieces'); }

	    $accessorials = processAccessorials($hawb,"DEL",$username);
        $drivername = $delDriver;
	    if ($remarks != '')
	    {
		    sendEmail($export_email,"Remarks $hawb",("$drivername has submitted trace notes for $hawb\r\n\r\nStatus: $status\r\n\r\nComments Below:\r\n\r\n$remarks"),$driver_email);
	    }
        $remarks = "POD Name: $podname @ $podtime $poddate " . $remarks;
    break;
}

if ($accessorials != '')
{
    createAccessorial($exportdest,$accessorials);
}

# Sleep slightly to ensure different file names.
usleep(100);

# Status.csv
$tmpfnameStatus = "status+"."$exportdest+".microtime(true);
$fpStatus = fopen($_SERVER['DOCUMENT_ROOT']."/exports/$tmpfnameStatus.csv", 'w');
fwrite($fpStatus, "HWB,Status,PODName,PODDate,PodTime,Pcs,ReWeigh,Load Position,PickupConfirmed,PickupTime,Remarks,Driver,Remark Type,Remark Date,Remark Time,Delivery Driver,pallets,Truck Miles\n\"$hawb\",\"$status\",\"$podname\",\"$poddate\",\"$podtime\",\"$pieces\",\"$reweigh\",\"".$_SESSION['truckid']."|".$_SESSION['trailerid']."\",\"$puconf\",\"$putime\",\"$remarks\",\"$drivername\",\"$remtype\",\"$remdate\",\"$remtime\",\"$deldriv\",\"$pallets\",\"".$_SESSION['truckid']."|".$_SESSION['odometer']."\"");
fclose($fpStatus);

mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
mysql_query("UPDATE dispatch SET status=\"$statustype\", modifiedDate = now() WHERE recordID=$recordid");

# Insert into the driverexport TABLE
if ($remarks != '')
{
  $trace_notes = '"'.$remarks.'"';
}else{
  $trace_notes = 'NULL';
}

$accessorial_override = processAccessorials($hawb,'OVER',$username);

if ($accessorial_override != '')
{
  $accessorial_override = '"'.$accessorial_override.'"';
}else{
  $accessorial_override = 'NULL';
}

if ($pieces == '')
{
  $pieces = 'NULL';
}

if ($pallets == '')
{
  $pallets = 'NULL';
}

$statement = "INSERT INTO driverexport (employee_id,hawbNumber,updated_by,status,hawbDate,dueDate,date,trace_notes,accessorials,pieces,pallets,sts_points)
VALUES (\"$employee_id\",\"$hawb\",\"$drivername_export\",\"$status\",(select str_to_date(hawbDate,'%c/%e/%Y') as hawbDate from dispatch WHERE hawbNumber=\"$hawb\"),(select str_to_date(dueDate,'%c/%e/%Y') as dueDate from dispatch WHERE hawbNumber=\"$hawb\"),now(),$trace_notes,$accessorial_override,$pieces,$pallets,1)";
if (! mysql_query($statement))
{
  echo "Unable to update DRIVEREXPORT table: ".mysql_error();
}

// If the status update was Arrived To Consignee then update the DB with the time
if ($status == "Arrived To Consignee")
{
	$splitdate = explode("/", $localdate);
	mysql_query("UPDATE dispatch SET arrivedConsigneeTime = UNIX_TIMESTAMP(\"20$splitdate[2]-$splitdate[0]-$splitdate[1] $localtime\"), modifiedDate = now() WHERE recordID=$recordid");
}

// If the status update was Arrived To Shipper then update the DB with the time
if ($status == "Arrived to Shipper")
{
	$splitdate = explode("/", $localdate);
	mysql_query("UPDATE dispatch SET arrivedShipperTime = UNIX_TIMESTAMP(\"20$splitdate[2]-$splitdate[0]-$splitdate[1] $localtime\"), modifiedDate = now() WHERE recordID=$recordid");
}

// If the status update was Delivered then populate the POD info
if ($status == "Delivered")
{
	$sql = "UPDATE dispatch SET podName=\"$podname\",podDate=\"$poddate\",podTime=\"$podtime\", modifiedDate = now()  WHERE recordID=$recordid";
	mysql_query("$sql");
}

header("Location: /pages/dispatch/orders.php");

function processAccessorials($hawb,$action,$driver_full_name,$status)
{

    $sqlSearchIn = array();
    // Create an array for the simplestatus
    $simple_status = array('In Transit','Dropped','On Dock PHX','On Dock TUS',
                           'Trailer Dropped','Reject PU DEL','Refused','Freight At Dock');
    foreach ($simple_status as $i)
    {
        if ($status == $i)
        {
            array_push($sqlSearchIn,"\"$i\"");
        }
    }

    foreach ($_POST['ck_accessorials'] as $key => $val)
    {
        array_push($sqlSearchIn,"\"$val\"");
    } 

    $sqlArray = rtrim(implode(',',$sqlSearchIn),",");
    if ($action == "OVER")
    {
        return str_replace('"','',$sqlArray);
    }
    if (count($sqlSearchIn) > 0)
    {
        $statement = "SELECT acc_type,revenue_charge,revenue_amount,input_type FROM accessorials WHERE (acc_type = \"$action\" OR acc_type = \"REVENUE\") AND revenue_charge IN ($sqlArray)";
        $sql = mysql_query($statement);

        while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
        {
            $accessorials .= "$hawb,$row[acc_type],$row[revenue_charge],$row[revenue_amount]\r\n";
			preg_match("/^hdn_.+/", "$row[input_type]",$matches);
			if (! $matches)
			{
				$accessorialsEmail .= "$hawb,$row[acc_type],$row[revenue_charge],$row[revenue_amount]\r\n";
			}
        }
		if (count($accessorialsEmail) > 0 )
		{
			$accessorialsEmail = "Accessorials submitted by $driver_full_name\r\n\r\n$accessorialsEmail";
			sendEmail($accessorial_email,"Accessorials $hawb","$accessorialsEmail");
		}
	        return $accessorials;
    }
}

function returnWithError($recordid,$page,$error)
{
    header("Location: $page?recordid=$recordid&expError=$error");
    exit;
}

function createAccessorial($exportdest,$accessorials)
{
      $tmpfnameAccessorial = "accessorial+"."$exportdest+".microtime(true);
      $fpAccessorial = fopen($_SERVER['DOCUMENT_ROOT']."/exports/$tmpfnameAccessorial.csv", 'w');
      fwrite($fpAccessorial, "HWB,Type,RevenueChargeName,RevenueChargeAmount\r\n$accessorials");
      fclose($fpAccessorial);
}

?>
