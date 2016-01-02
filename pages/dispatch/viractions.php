<?php
session_start();
if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

if (isset($_POST['vir_item']) && isset($_POST['vir_status']))
{
# AJAX call to update vir status
$sql = "UPDATE virs SET updated_status = \"".$_POST['vir_status']."\" WHERE vir_itemnum = ".$_POST['vir_item'];
if (! mysql_query($sql))
{
    echo('Unable to update `updated_status`' . mysql_error());
}
exit;
}

# USER info
$statement = 'SELECT employee_id from users where username = "'.$_SESSION['userid'].'"';
$drivername = mysql_fetch_array(mysql_query($statement),MYSQL_BOTH);
$employee_id = $drivername[0];

# VIR POST variables
$trucktype = $_POST['trucktype'];
$insp_start_time = $_POST['insp_start_time'];
$insp_end_time = localtime();
$insp_end_time = $insp_end_time[2] . ":" . $insp_end_time[1] . ":" . $insp_end_time[0];
$insp_date = $_POST['insp_date'];
$insp_type = $_POST['insp_type'];
$truck_number = $_POST['truck_number'];
$truckodometer = $_POST["truck_odometer"];
$trailer_number = $_POST['trailer_number'];
$preorposttrip = $_POST['preorposttrip'];
$truck_vir_condition = $_POST['vir_truck'][0];
$truck_vir_condition_tire = $_POST['vir_truck_tire'][0];
$trailer_vir_condition = $_POST['vir_trailer'][0];
$trailer_vir_condition_tire = $_POST['vir_trailer_tire'][0];
$vir_notes_quick_report = mysql_escape_string($_POST['vir_notes_quick_report']);
$vir_notes_finish = mysql_escape_string($_POST['vir_notes_finish']);
$vir_notes_detailed_truck = mysql_escape_string($_POST['vir_notes_detailed_truck']);
$vir_notes_detailed_trailer = mysql_escape_string($_POST['vir_notes_detailed_trailer']);
$truck_tires_notes = mysql_escape_string($_POST['truck_tires_notes_'.$trucktype]);
$trailer_tires_notes = mysql_escape_string($_POST['trailer_tires_notes_trailer']);
$username = $_POST['username'];
$vir_detailed_truck = $_POST['vir_detailed_truck'];

$truck_tires_driverside_steer = $_POST['truck_tires_driverside_steer_'.$trucktype];
$truck_tires_driverside_steer_pressure = $_POST['truck_tires_driverside_steer_pressure_'.$trucktype];
$truck_tires_driverside_steer = $truck_tires_driverside_steer . "," . $truck_tires_driverside_steer_pressure;

$truck_tires_passenger_steer = $_POST['truck_tires_passenger_steer_'.$trucktype];
$truck_tires_passenger_steer_pressure = $_POST['truck_tires_passenger_steer_pressure_'.$trucktype];
$truck_tires_passenger_steer = $truck_tires_passenger_steer . "," . $truck_tires_passenger_steer_pressure;

$truck_tires_driverside_ax1front = $_POST['truck_tires_driverside_ax1front_'.$trucktype];
$truck_tires_driverside_ax1front_pressure = $_POST['truck_tires_driverside_ax1front_pressure_'.$trucktype];
$truck_tires_driverside_ax1front_position = $_POST['truck_tires_driverside_ax1front_position_'.$trucktype];
$truck_tires_driverside_ax1front = $truck_tires_driverside_ax1front . "," . $truck_tires_driverside_ax1front_pressure . "," . $truck_tires_driverside_ax1front_position;
$truck_tires_driverside_ax1front = rtrim($truck_tires_driverside_ax1front,',');

$truck_tires_passenger_ax1front = $_POST['truck_tires_passenger_ax1front_'.$trucktype];
$truck_tires_passenger_ax1front_pressure = $_POST['truck_tires_passenger_ax1front_pressure_'.$trucktype];
$truck_tires_passenger_ax1front_position = $_POST['truck_tires_passenger_ax1front_position_'.$trucktype];
$truck_tires_passenger_ax1front = $truck_tires_passenger_ax1front . "," . $truck_tires_passenger_ax1front_pressure . "," . $truck_tires_passenger_ax1front_position;
$truck_tires_passenger_ax1front = rtrim($truck_tires_passenger_ax1front,',');

$truck_tires_driverside_ax2rear = $_POST['truck_tires_driverside_ax2rear_'.$trucktype];
$truck_tires_driverside_ax2rear_pressure = $_POST['truck_tires_driverside_ax2rear_pressure_'.$trucktype];
$truck_tires_driverside_ax2rear_position = $_POST['truck_tires_driverside_ax2rear_position_'.$trucktype];
$truck_tires_driverside_ax2rear = $truck_tires_driverside_ax2rear . "," . $truck_tires_driverside_ax2rear_pressure . "," . $truck_tires_driverside_ax2rear_position;
$truck_tires_driverside_ax2rear = rtrim($truck_tires_driverside_ax2rear,',');

$truck_tires_passenger_ax2rear = $_POST['truck_tires_passenger_ax2rear_'.$trucktype];
$truck_tires_passenger_ax2rear_pressure = $_POST['truck_tires_passenger_ax2rear_pressure_'.$trucktype];
$truck_tires_passenger_ax2rear_position = $_POST['truck_tires_passenger_ax2rear_position_'.$trucktype];
$truck_tires_passenger_ax2rear = $truck_tires_passenger_ax2rear . "," . $truck_tires_passenger_ax2rear_pressure . "," . $truck_tires_passenger_ax2rear_position;
$truck_tires_passenger_ax2rear = rtrim($truck_tires_passenger_ax2rear,',');

$trailer_tires_driverside_ax1front = $_POST['trailer_tires_driverside_ax1front_trailer'];
$trailer_tires_driverside_ax1front_pressure = $_POST['trailer_tires_driverside_ax1front_pressure_trailer'];
$trailer_tires_driverside_ax1front_position = $_POST['trailer_tires_driverside_ax1front_position_trailer'];
$trailer_tires_driverside_ax1front = $trailer_tires_driverside_ax1front . "," . $trailer_tires_driverside_ax1front_pressure . "," . $trailer_tires_driverside_ax1front_position;
$trailer_tires_driverside_ax1front = rtrim($trailer_tires_driverside_ax1front,',');

$trailer_tires_passenger_ax1front = $_POST['trailer_tires_passenger_ax1front_trailer'];
$trailer_tires_passenger_ax1front_pressure = $_POST['trailer_tires_passenger_ax1front_pressure_trailer'];
$trailer_tires_passenger_ax1front_position = $_POST['trailer_tires_passenger_ax1front_position_trailer'];
$trailer_tires_passenger_ax1front = $trailer_tires_passenger_ax1front . "," . $trailer_tires_passenger_ax1front_pressure . "," . $trailer_tires_passenger_ax1front_position;
$trailer_tires_passenger_ax1front = rtrim($trailer_tires_passenger_ax1front,',');

$trailer_tires_driverside_ax2rear = $_POST['trailer_tires_driverside_ax2rear_trailer'];
$trailer_tires_driverside_ax2rear_pressure = $_POST['trailer_tires_driverside_ax2rear_pressure_trailer'];
$trailer_tires_driverside_ax2rear_position = $_POST['trailer_tires_driverside_ax2rear_position_trailer'];
$trailer_tires_driverside_ax2rear = $trailer_tires_driverside_ax2rear . "," . $trailer_tires_driverside_ax2rear_pressure . "," . $trailer_tires_driverside_ax2rear_position;
$trailer_tires_driverside_ax2rear = rtrim($trailer_tires_driverside_ax2rear,',');

$trailer_tires_passenger_ax2rear = $_POST['trailer_tires_passenger_ax2rear_trailer'];
$trailer_tires_passenger_ax2rear_pressure = $_POST['trailer_tires_passenger_ax2rear_pressure_trailer'];
$trailer_tires_passenger_ax2rear_position = $_POST['trailer_tires_passenger_ax2rear_position_trailer'];
$trailer_tires_passenger_ax2rear = $trailer_tires_passenger_ax2rear . "," . $trailer_tires_passenger_ax2rear_pressure . "," . $trailer_tires_passenger_ax2rear_position;
$trailer_tires_passenger_ax2rear = rtrim($trailer_tires_passenger_ax2rear,',');

# NULL out the trailer tires if the type is boxtruck or sprinter
if ($trucktype == 'boxtruck' || $trucktype == 'sprinter')
{
  $trailer_tires_driverside_ax1front = '';
  $trailer_tires_passenger_ax1front = '';
  $trailer_tires_driverside_ax2rear = '';
  $trailer_tires_passenger_ax2rear = '';
  $truck_tires_driverside_ax2rear = '';
  $truck_tires_passenger_ax2rear = '';
}

$vir_finish_notes = mysql_escape_string($_POST['vir_finish_notes_'.$trucktype]);
$bx_localtime2 = $_POST['bx_localtime2'];
$bx_localdate2 = $_POST['bx_localdate2'];
$submitvir = $_POST['submitvir'];

foreach ($_POST['truck_ck_accessorials'] as $key => $val)
{
    $truck_vir_items .= $val.',';
}
$truck_vir_items = rtrim($truck_vir_items,",");

foreach ($_POST['trailer_ck_accessorials'] as $key => $val)
{
    $trailer_vir_items .= $val.',';
}
$trailer_vir_items = rtrim($trailer_vir_items,",");

# Insert TRUCK vir:
$sql = "INSERT INTO virs (
employee_id, /* employee_id = $employee_id */
insp_date, /* str_to_date('\$insp_date','%m/%d/%y') = str_to_date('$insp_date','%m/%d/%y') */
insp_start_time, /* \$insp_start_time = $insp_start_time */
insp_end_time, /* CURTIME() */
insp_duration, /* subtime(curtime(),'\$insp_start_time') = subtime(curtime(),'$insp_start_time') */
insp_type, /* \$preorposttrip = $preorposttrip */
driver_name, /* \$username = $username */
vir_points, /* 1 */
truck_number, /* \$truck_number = $truck_number */
truck_odometer, /* \$truckodometer = $truckodometer */
truck_vir_condition, /* \$truck_vir_condition = $truck_vir_condition */
truck_vir_items, /* \$truck_vir_items = $truck_vir_items */
truck_vir_notes, /* \$vir_notes_detailed_truck = $vir_notes_detailed_truck */
vir_notes_quick_report, /* \$vir_notes_quick_report = $vir_notes_quick_report */
truck_tires_driverside_steer, /* \$truck_tires_driverside_steer = $truck_tires_driverside_steer */
truck_tires_passenger_steer, /* \$truck_tires_passenger_steer = $truck_tires_passenger_steer */
truck_tires_driverside_ax1front, /* \$truck_tires_driverside_ax1front = $truck_tires_driverside_ax1front */
truck_tires_passenger_ax1front, /* \$truck_tires_passenger_ax1front = $truck_tires_passenger_ax1front */
truck_tires_driverside_ax2rear, /* \$truck_tires_driverside_ax2rear = $truck_tires_driverside_ax2rear */
truck_tires_passenger_ax2rear, /* \$truck_tires_passenger_ax2rear = $truck_tires_passenger_ax2rear */
truck_tires_notes, /* \$truck_tires_notes = $truck_tires_notes */
trailer_number, /* \$trailer_number = $trailer_number */
trailer_vir_condition,  /* \$trailer_vir_condition = $trailer_vir_condition */
trailer_vir_items, /* \$trailer_vir_items = $trailer_vir_items */
trailer_vir_notes, /* \$vir_notes_detailed_trailer = $vir_notes_detailed_trailer */
trailer_tires_driverside_ax1front, /* \$trailer_tires_driverside_ax1front = $trailer_tires_driverside_ax1front */
trailer_tires_passenger_ax1front, /* \$trailer_tires_passenger_ax1front = $trailer_tires_passenger_ax1front */
trailer_tires_driverside_ax2rear, /* \$trailer_tires_driverside_ax2rear = $trailer_tires_driverside_ax2rear */
trailer_tires_passenger_ax2rear, /* \$trailer_tires_passenger_ax2rear = $trailer_tires_passenger_ax2rear */
trailer_tires_notes, /* \$trailer_tires_notes = $trailer_tires_notes */
vir_finish_notes, /* \$vir_notes_finish = $vir_notes_finish */
trucktype, /* \$trucktype = $trucktype */
truck_tires_overall, /* \$truck_vir_condition_tire = $truck_vir_condition_tire */
trailer_tires_overall, /* \$trailer_vir_condition_tire = $trailer_vir_condition_tire */
truck_vir_itemnum
)
VALUES
(
'$employee_id',
str_to_date('$insp_date','%m/%d/%y'),
'$insp_start_time',
CURTIME(),
subtime(curtime(),'$insp_start_time'),
'$preorposttrip',
'$username',
1,
$truck_number,
$truckodometer,
'$truck_vir_condition',
'$truck_vir_items',
'$vir_notes_detailed_truck',
'$vir_notes_quick_report',
'$truck_tires_driverside_steer',
'$truck_tires_passenger_steer',
'$truck_tires_driverside_ax1front',
'$truck_tires_passenger_ax1front',
'$truck_tires_driverside_ax2rear',
'$truck_tires_passenger_ax2rear',
'$truck_tires_notes',
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
NULL,
'$vir_notes_finish',
'$trucktype',
'$truck_vir_condition_tire',
NULL,
NULL
)";

if (! mysql_query($sql))
{
    die('Unable to INSERT truck VIR into table: ' . mysql_error());
}

if (($trucktype == 'combo') && ($trailer_number != ''))
{
    # Now get the last vir_itemnum so we can use it to populate the child record (trailer)
    $sql = "SELECT vir_itemnum FROM virs WHERE truck_number = $truck_number ORDER BY vir_itemnum DESC LIMIT 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result,MYSQL_BOTH);

    # Insert into virs to populate the trailer info now.
    $sql = "INSERT INTO virs (
    employee_id, /* employee_id = $employee_id */
    insp_date, /* str_to_date('\$insp_date','%m/%d/%y') = str_to_date('$insp_date','%m/%d/%y') */
    insp_start_time, /* \$insp_start_time = $insp_start_time */
    insp_end_time, /* CURTIME() */
    insp_duration, /* subtime(curtime(),'\$insp_start_time') = subtime(curtime(),'$insp_start_time') */
    insp_type, /* \$preorposttrip = $preorposttrip */
    driver_name, /* \$username = $username */
    vir_points, /* 1 */
    truck_number, /* \$truck_number = $truck_number */
    truck_odometer, /* \$truckodometer = $truckodometer */
    truck_vir_condition, /* \$truck_vir_condition = $truck_vir_condition */
    truck_vir_items, /* \$truck_vir_items = $truck_vir_items */
    truck_vir_notes, /* \$vir_notes_detailed_truck = $vir_notes_detailed_truck */
    vir_notes_quick_report, /* \$vir_notes_quick_report = $vir_notes_quick_report */
    truck_tires_driverside_steer, /* \$truck_tires_driverside_steer = $truck_tires_driverside_steer */
    truck_tires_passenger_steer, /* \$truck_tires_passenger_steer = $truck_tires_passenger_steer */
    truck_tires_driverside_ax1front, /* \$truck_tires_driverside_ax1front = $truck_tires_driverside_ax1front */
    truck_tires_passenger_ax1front, /* \$truck_tires_passenger_ax1front = $truck_tires_passenger_ax1front */
    truck_tires_driverside_ax2rear, /* \$truck_tires_driverside_ax2rear = $truck_tires_driverside_ax2rear */
    truck_tires_passenger_ax2rear, /* \$truck_tires_passenger_ax2rear = $truck_tires_passenger_ax2rear */
    truck_tires_notes, /* \$truck_tires_notes = $truck_tires_notes */
    trailer_number, /* \$trailer_number = $trailer_number */
    trailer_vir_condition,  /* \$trailer_vir_condition = $trailer_vir_condition */
    trailer_vir_items, /* \$trailer_vir_items = $trailer_vir_items */
    trailer_vir_notes, /* \$vir_notes_detailed_trailer = $vir_notes_detailed_trailer */
    trailer_tires_driverside_ax1front, /* \$trailer_tires_driverside_ax1front = $trailer_tires_driverside_ax1front */
    trailer_tires_passenger_ax1front, /* \$trailer_tires_passenger_ax1front = $trailer_tires_passenger_ax1front */
    trailer_tires_driverside_ax2rear, /* \$trailer_tires_driverside_ax2rear = $trailer_tires_driverside_ax2rear */
    trailer_tires_passenger_ax2rear, /* \$trailer_tires_passenger_ax2rear = $trailer_tires_passenger_ax2rear */
    trailer_tires_notes, /* \$trailer_tires_notes = $trailer_tires_notes */
    vir_finish_notes, /* \$vir_notes_finish = $vir_notes_finish */
    trucktype, /* \$trucktype = $trucktype */
    truck_tires_overall, /* \$truck_vir_condition_tire = $truck_vir_condition_tire */
    trailer_tires_overall, /* \$trailer_vir_condition_tire = $trailer_vir_condition_tire */
    truck_vir_itemnum
    )
    VALUES
    (
    '$employee_id',
    str_to_date('$insp_date','%m/%d/%y'),
    '$insp_start_time',
    CURTIME(),
    subtime(curtime(),'$insp_start_time'),
    '$preorposttrip',
    '$username',
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    NULL,
    $trailer_number,
    '$trailer_vir_condition',
    '$trailer_vir_items',
    '$vir_notes_detailed_trailer',
    '$trailer_tires_driverside_ax1front',
    '$trailer_tires_passenger_ax1front',
    '$trailer_tires_driverside_ax2rear',
    '$trailer_tires_passenger_ax2rear',
    '$trailer_tires_notes',
    NULL,
    NULL,
    NULL,
    '$trailer_vir_condition_tire',
    ".$row[0]."
    )";

    if (! mysql_query($sql))
    {
        die('Unable to INSERT trailer VIR into table: ' . mysql_error());
    }
}

# Send the email out
# Reset the trucktype if it's 'combo'
($trucktype == 'combo' ? $trucktype = 'semi' : $trucktype = $trucktype);
$to = "trucks@catalinacartage.com";
$subject = "VIR $truck_number / $trailer_number / $preorposttrip";
$body = <<<EOT
Driver: $username
Truck Type: $trucktype
Truck: $truck_number, $truck_vir_condition
Trailer: $trailer_number, $trailer_vir_condition
Date: $insp_date
Start Time: $insp_start_time
End Time  : $insp_end_time

General Notes: $vir_notes_finish

$truck_number: $truck_vir_condition
Items Marked:
$truck_vir_items

Additional Driver Notes For $truck_number:
$vir_notes_detailed_truck $vir_notes_quick_report

$truck_number: Tire Conditions (Drivers Side):
Steer,$truck_tires_driverside_steer
Axel 1: $truck_tires_driverside_ax1front
Axel 2: $truck_tires_driverside_ax2rear

$truck_number: Tire Conditions (Passenger Side):
Steer: $truck_tires_driverside_steer
Axel 1: $truck_tires_passenger_ax1front
Axel 2: $truck_tires_passenger_ax2rear

$trailer_number: $trailer_vir_condition:
Items Marked:
$trailer_vir_items

Additional Driver Notes For Trailer:
$vir_notes_detailed_trailer

$trailer_number: Tire Conditions (Drivers Side):
Axel 1: $trailer_tires_driverside_ax1front
AXEL 2: $trailer_tires_driverside_ax2rear

$trailer_number: Tire Conditions (Passenger Side):
Axel 1: $trailer_tires_passenger_ax1front
AXEL 2: $trailer_tires_passenger_ax2rear

EOT;

sendEmail($to,$subject,$body,$_SESSION['email']);
header("Location: /pages/dispatch/vir.php");
?>
