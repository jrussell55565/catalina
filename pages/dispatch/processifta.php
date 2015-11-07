<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');
#print_r($_POST);
$id = $_POST['id_trip'];
$trip_num = $_POST['trip_num'];
$truck_number = $_POST['truck_number'];
$trailer_number = $_POST['trailer_number'];
$truck_rental = $_SESSION['truck_rental'];
$drivername = $_SESSION['drivername'];
$date_trip = $_POST['date_trip'];
$start_odometer = $_POST['start_odometer'];
$hwb = $_POST['hwb'];
$route = $_POST['route'];
$st_exit = $_POST['st_exit'];
$st_enter = $_POST['st_enter'];
$state_line_odometer = $_POST['state_line_odometer'];
$total_miles = $_POST['total_miles'];
$trip_sts = $_POST['trip_sts'];
$end_odometer = $_POST['end_odometer'];
$date_fuel = $_POST['date_fuel'];
$fuel_invoice_no = $_POST['fuel_invoice_no'];
$fuel_type = $_POST['fuel_type'];
$fuel_gallons = $_POST['fuel_gallons'];
$fuel_st = $_POST['fuel_st'];
$fuel_odometer = $_POST['fuel_odometer'];
$fuel_receipt_total = $_POST['fuel_reciept_total'];
$date_permit = $_POST['date_permit'];
$permit_type = $_POST['permit_type'];
$permit_st = $_POST['permit_st'];
$permit_no = $_POST['permit_no'];
$permit_receipt = $_POST['permit_receipt'];

for ($i=0; $i<sizeof($id); $i++)
{
  if (!empty($id[$i]))
  {
    # Set to default values if the variable is empty
if ($date_trip[$i] == '') { $date_trip[$i] = 'NULL'; }
if ($hwb[$i] == '') { $hwb[$i] = 'NULL'; }
if ($route[$i] == '') { $route[$i] = 'NULL'; }
if ($st_exit[$i] == '') { $st_exit[$i] = 'NULL'; }
if ($st_enter[$i] == '') { $st_enter[$i] = 'NULL'; }
if ($state_line_odometer[$i] == '') { $state_line_odometer[$i] = 'NULL'; }
if ($trip_sts[$i] == '') { $trip_sts[$i] = 'Open'; }
if ($end_odometer[$i] == '') { $end_odometer[$i] = 'NULL'; }
if ($date_fuel[$i] == '') { $date_fuel[$i] = 'NULL'; }
if ($fuel_invoice_no[$i] == '') { $fuel_invoice_no[$i] = 'NULL'; }
if ($fuel_type[$i] == '') { $fuel_type[$i] = 'NULL'; }
if ($fuel_gallons[$i] == '') { $fuel_gallons[$i] = 'NULL'; }
if ($fuel_st[$i] == '') { $fuel_st[$i] = 'NULL'; }
if ($fuel_odometer[$i] == '') { $fuel_odometer[$i] = 'NULL'; }
if ($fuel_receipt_total[$i] == '') { $fuel_reciept_total[$i] = 'NULL'; }
if ($date_permit[$i] == '') { $date_permit[$i] = 'NULL'; }
if ($permit_type[$i] == '') { $permit_type[$i] = 'NULL'; }
if ($permit_st[$i] == '') { $permit_st[$i] = 'NULL'; }
if ($permit_no[$i] == '') { $permit_no[$i] = 'NULL'; }
if ($permit_receipt[$i] == '') { $permit_receipt[$i] = 'NULL'; }

if ($fuel_st[$i] == 'State') { $fuel_st[$i] = 'NULL'; } else { $fuel_st[$i] = '"'.$fuel_st[$i] .'"'; }
if ($permit_st[$i] == 'State') { $permit_st[$i] = 'NULL'; }else { $permit_receipt[$i] = '"'.$permit_receipt[$i] .'"'; }
 
    $sql = <<<EOT
UPDATE ifta SET
trip_num = $trip_num,
truck_number = $truck_number,
trailer_number = $trailer_number,
truck_rental = '$truck_rental',
date_trip = str_to_date('$date_trip[$i]','%m/%d/%Y'),
hwb = '$hwb[$i]',
route = '$route[$i]',
st_exit = '$st_exit[$i]',
st_enter = '$st_enter[$i]',
state_line_odometer = $state_line_odometer[$i],
trip_sts = '$trip_sts[$i]',
date_fuel = str_to_date('$date_fuel[$i]','%m/%d/%Y'),
fuel_invoice_no = $fuel_invoice_no[$i],
fuel_type = '$fuel_type[$i]',
fuel_gallons = $fuel_gallons[$i],
fuel_st = $fuel_st[$i],
fuel_odometer = $fuel_odometer[$i],
fuel_receipt_total = $fuel_reciept_total[$i],
date_permit = str_to_date('$date_permit[$i]','%m/%d/%Y'),
permit_type = '$permit_type[$i]',
permit_st = $permit_st[$i],
permit_no = $permit_no[$i],
permit_receipt = $permit_receipt[$i]
WHERE id = $id[$i]
EOT;
    print $sql . "\n";
mysql_query($sql);
  }else{
if ($date_trip[$i] == '') { $date_trip[$i] = 'NULL'; }
if ($hwb[$i] == '') { $hwb[$i] = 'NULL'; }
if ($route[$i] == '') { $route[$i] = 'NULL'; }
if ($st_exit[$i] == '') { $st_exit[$i] = 'NULL'; }
if ($st_enter[$i] == '') { $st_enter[$i] = 'NULL'; }
if ($state_line_odometer[$i] == '') { $state_line_odometer[$i] = 'NULL'; }
if ($trip_sts[$i] == '') { $trip_sts[$i] = 'Open'; }
if ($end_odometer[$i] == '') { $end_odometer[$i] = 'NULL'; }
if ($date_fuel[$i] == '') { $date_fuel[$i] = 'NULL'; }
if ($fuel_invoice_no[$i] == '') { $fuel_invoice_no[$i] = 'NULL'; }
if ($fuel_type[$i] == '') { $fuel_type[$i] = 'NULL'; }
if ($fuel_gallons[$i] == '') { $fuel_gallons[$i] = 'NULL'; }
if ($fuel_st[$i] == '') { $fuel_st[$i] = 'NULL'; }
if ($fuel_odometer[$i] == '') { $fuel_odometer[$i] = 'NULL'; }
if ($fuel_receipt_total[$i] == '') { $fuel_receipt_total[$i] = 'NULL'; }
if ($date_permit[$i] == '') { $date_permit[$i] = 'NULL'; }
if ($permit_type[$i] == '') { $permit_type[$i] = 'NULL'; }
if ($permit_st[$i] == '') { $permit_st[$i] = 'NULL'; }
if ($permit_no[$i] == '') { $permit_no[$i] = 'NULL'; }
if ($permit_receipt[$i] == '') { $permit_receipt[$i] = 'NULL'; }
 
if ($fuel_st[$i] == 'State') { $fuel_st[$i] = 'NULL'; } else { $fuel_st[$i] = '"'.$fuel_st[$i] .'"'; }
if ($permit_st[$i] == 'State') { $permit_st[$i] = 'NULL'; }else { $permit_receipt[$i] = '"'.$permit_receipt[$i] .'"'; }

    $sql = <<<EOT
INSERT INTO ifta
(
start_odometer,
trip_num,
truck_number,
trailer_number,
truck_rental,
drivername,
date_trip,
hwb,
route,
st_exit,
st_enter,
state_line_odometer,
trip_sts,
date_fuel,
fuel_invoice_no,
fuel_type,
fuel_gallons,
fuel_st,
fuel_odometer,
fuel_receipt_total,
date_permit,
permit_type,
permit_st,
permit_no,
permit_receipt
)
VALUES
(
$start_odometer,
'$trip_num',
$truck_number,
$trailer_number,
'$truck_rental',
'$drivername',
str_to_date('$date_trip[$i]','%m/%d/%Y'),
'$hwb[$i]',
'$route[$i]',
'$st_exit[$i]',
'$st_enter[$i]',
$state_line_odometer[$i],
'$trip_sts[$i]',
str_to_date('$date_fuel[$i]','%m/%d/%Y'),
$fuel_invoice_no[$i],
'$fuel_type[$i]',
$fuel_gallons[$i],
$fuel_st[$i],
$fuel_odometer[$i],
$fuel_receipt_total[$i],
str_to_date('$date_permit[$i]','%m/%d/%Y'),
'$permit_type[$i]',
$permit_st[$i],
$permit_no[$i],
$permit_receipt[$i]
)
EOT;
#print "$sql\n";
mysql_query($sql);
  }
}

header("location: /pages/dispatch/ifta.php?tripno=$trip_num");
exit;
?>
