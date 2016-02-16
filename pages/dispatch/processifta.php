<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
#print_r($_POST) . "<br>";

# Start TX
mysql_query("BEGIN");
try {

# IFTA table
$trip_no = $_POST['txt_tripnum'];
$date_started = $_POST['txt_date_start'];
$date_ended = $_POST['txt_date_end'];
$driver1 = $_POST['sel_add_driver_1'];
$driver2 = $_POST['sel_add_driver_2'];
$truck_no = $_POST['txt_truckno'];

$sql = "INSERT INTO ifta
(
trip_no,
date_started,
date_ended,
driver1,
driver2,
truck_no
)
VALUES
(
".$_POST['txt_tripnum'].",
str_to_date('".$_POST['txt_date_start']."','%m/%d/%Y'),
str_to_date('".$_POST['txt_date_end']."','%m/%d/%Y'),
'".$_POST['sel_add_driver_1']."',
'".$_POST['sel_add_driver_2']."',
".$_POST['txt_truckno']."
)";
mysql_query($sql);

$id = $_POST['hdn_details_id'];
for ($i=0; $i<sizeof($id); $i++)
{
  if ($_POST['txt_tripnum_details'][$i] == '') { $_POST['txt_tripnum_details'][$i] = 'NULL'; }
  if ($_POST['txt_date_details'][$i] == '') { $_POST['txt_date_details'][$i] = 'NULL'; }
  if ($_POST['txt_driver_details'][$i] == '') { $_POST['txt_driver_details'][$i] = 'NULL'; }
  if ($_POST['txt_hwb_details'][$i] == '') { $_POST['txt_hwb_details'][$i] = 'NULL'; }
  if ($_POST['txt_routes_details'][$i] == '') { $_POST['txt_routes_details'][$i] = 'NULL'; }
  if ($_POST['txt_state_exit_details'][$i] == '') { $_POST['txt_state_exit_details'][$i] = 'NULL'; }
  if ($_POST['txt_state_enter_details'][$i] == '') { $_POST['txt_state_enter_details'][$i] = 'NULL'; }
  if ($_POST['txt_state_odo_details'][$i] == '') { $_POST['txt_state_odo_details'][$i] = 'NULL'; }
  if ($_POST['txt_state_miles_details'][$i] == '') { $_POST['txt_state_miles_details'][$i] = 'NULL'; }
  if (isset($_POST['txt_permit_req_details'][$i])) { $permit = 'Y'; }else{ $permit = 'N'; }

    $sql = 
"INSERT INTO ifta_details
(
trip_no,
trip_date,
driver,
hwb,
route,
st_exit,
st_enter,
state_line_odometer,
state_miles,
permit_required
)
VALUES
(
".$_POST['txt_tripnum_details'][$i].",
str_to_date('".$_POST['txt_date_details'][$i]."','%m/%d/%Y'),
'".$_POST['txt_driver_details'][$i]."',
'".$_POST['txt_hwb_details'][$i]."',
'".$_POST['txt_routes_details'][$i]."',
'".$_POST['txt_state_exit_details'][$i]."',
'".$_POST['txt_state_enter_details'][$i]."',
".$_POST['txt_state_odo_details'][$i].",
".$_POST['txt_state_miles_details'][$i].",
'$permit'
)";
mysql_query($sql);
}

$id = $_POST['hdn_fuel_id'];
for ($i=0; $i<sizeof($id); $i++)
{
  if ($_POST['txt_fuel_tripnum'][$i] == '') { $_POST['txt_fuel_tripnum'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_date'][$i] == '') { $_POST['txt_fuel_date'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_gallons'][$i] == '') { $_POST['txt_fuel_gallons'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_reefer'][$i] == '') { $_POST['txt_fuel_reefer'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_other'][$i] == '') { $_POST['txt_fuel_other'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_vendor'][$i] == '') { $_POST['txt_fuel_vendor'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_city'][$i] == '') { $_POST['txt_fuel_city'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_state'][$i] == '') { $_POST['txt_fuel_state'][$i] = 'NULL'; }
  if ($_POST['txt_fuel_odo'][$i] == '') { $_POST['txt_fuel_odo'][$i] = 'NULL'; }
  
  $sql = "INSERT INTO ifta_fuel
(
trip_no,
trip_date,
fuel_gallons,
fuel_reefer,
fuel_other,
vendor,
city,
state,
odometer
)
VALUES
(
".$_POST['txt_fuel_tripnum'][$i].",
str_to_date('".$_POST['txt_fuel_date'][$i]."','%m/%d/%Y'),
".$_POST['txt_fuel_gallons'][$i].",
".$_POST['txt_fuel_reefer'][$i].",
".$_POST['txt_fuel_other'][$i].",
'".$_POST['txt_fuel_vendor'][$i]."',
'".$_POST['txt_fuel_city'][$i]."',
'".$_POST['txt_fuel_state'][$i]."',
".$_POST['txt_fuel_odo'][$i]."
)";
mysql_query($sql);
}

mysql_query('COMMIT');

} catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    mysql_query('ROLLBACK');
}
exit;

header("location: /pages/dispatch/ifta.php");
exit;
?>
