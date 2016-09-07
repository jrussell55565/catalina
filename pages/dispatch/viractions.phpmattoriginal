<?php
session_start();
if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

# VIR POST variables
$insp_start_time = $_POST['insp_start_time'];
$insp_end_time = localtime();
$insp_end_time = $insp_end_time[2] . ":" . $insp_end_time[1] . ":" . $insp_end_time[0];
$insp_date = $_POST['insp_date'];
$truck_number = $_POST['truck_number'];
$trailer_number = $_POST['trailer_number'];
$preorposttrip = $_POST['preorposttrip'];
$vir_truck = $_POST['vir_truck'][0];
$vir_truck_tire = $_POST['vir_truck_tire'][0];
$vir_trailer = $_POST['vir_trailer'][0];
$vir_trailer_tire = $_POST['vir_trailer_tire'][0];
$vir_notes_quick_report = $_POST['vir_notes_quick_report'];
$vir_notes_finish = $_POST['vir_notes_finish'];
$vir_notes_detailed_truck = $_POST['vir_notes_detailed_truck'];
$vir_notes_detailed_trailer = $_POST['vir_notes_detailed_trailer'];
$truck_tires_notes = $_POST['truck_tires_notes_'.$trucktype];
$trailer_tires_notes = $_POST['trailer_tires_notes_trailer'];
$username = $_POST['username'];
$vir_detailed_truck = $_POST['vir_detailed_truck'];
$trucktype = $_POST['trucktype'];

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

$vir_finish_notes = $_POST['vir_finish_notes_'.$trucktype];
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

$sql = "INSERT INTO virs (
insp_date,insp_start_time,insp_end_time,insp_duration,insp_type,driver_name,vir_points,truck_number,truck_vir_condition,truck_vir_items,truck_vir_notes,truck_tires_driverside_steer,truck_tires_passenger_steer,truck_tires_driverside_ax1front,truck_tires_passenger_ax1front,truck_tires_driverside_ax2rear,truck_tires_passenger_ax2rear,truck_tires_notes,trailer_number,trailer_vir_condition,trailer_vir_items,trailer_vir_notes,trailer_tires_driverside_ax1front,trailer_tires_passenger_ax1front,trailer_tires_driverside_ax2rear,trailer_tires_passenger_ax2rear,trailer_tires_notes,vir_finish_notes)
VALUES
(
str_to_date('$insp_date','%m/%d/%y'),
'$insp_start_time',
CURTIME(),
subtime(curtime(),'$insp_start_time'),
'$preorposttrip',
'$username',
1,
$truck_number,
'$vir_truck',
'$truck_vir_items',
'$vir_notes_detailed_truck',
'$truck_tires_driverside_steer',
'$truck_tires_passenger_steer',
'$truck_tires_driverside_ax1front',
'$truck_tires_passenger_ax1front',
'$truck_tires_driverside_ax2rear',
'$truck_tires_passenger_ax2rear',
'$truck_tires_notes',
$trailer_number,
'$vir_trailer',
'$trailer_vir_items',
'$vir_notes_detailed_trailer',
'$trailer_tires_driverside_ax1front',
'$trailer_tires_passenger_ax1front',
'$trailer_tires_driverside_ax2rear',
'$trailer_tires_passenger_ax2rear',
'$trailer_tires_notes',
'$vir_notes_finish'
)";

#print $sql;
mysql_query($sql);

# Send the email out
# Reset the trucktype if it's 'combo'
($trucktype = 'combo' ? $trucktype = 'semi' : $trucktype = $trucktype);
$to = "trucks@catalinacartage.com";
$subject = "VIR Truck $truck_number / $trailer_number";
$body = <<<EOT
Date: $insp_date
Driver: $username
Time Start: $insp_start_time
Time End : $insp_end_time
Truck: $truck_number
Trailer: $trailer_number
Truck Type: $trucktype

Conditions: Truck $truck_number
$vir_truck
Items Marked: Truck $truck_number
$truck_vir_items
Truck Vir Notes:
$vir_notes_detailed_truck

Truck Tire Conditions: Truck $truck_number

Drivers Side Steer,$truck_tires_driverside_steer
Passenger Side Steer: $truck_tires_driverside_steer
Drivers Side Front Drive: $truck_tires_driverside_ax1front
Passenger Side Front Drive: $truck_tires_passenger_ax1front
Drivers Side Rear Drive: $truck_tires_driverside_ax2rear
Passenger Side Rear Drive: $truck_tires_passenger_ax2rear

Conditions: Trailer: $trailer_number
$vir_trailer
Items Marked: Trailer $trailer_number
$trailer_vir_items
Trailer Vir Notes:
$vir_notes_detailed_trailer

Trailer Tire Conditions: Trailer $trailer_number
Drivers Side Front Drive: $trailer_tires_driverside_ax1front
Passenger Side Front Drive: $trailer_tires_passenger_ax1front
Drivers Side Rear Drive: $trailer_tires_driverside_ax2rear
Passenger Side Rear Drive: $trailer_tires_passenger_ax2rear
EOT;

sendEmail($to,$subject,$body,$_SESSION['email']);
header("Location: /pages/dispatch/vir.php");
?>
