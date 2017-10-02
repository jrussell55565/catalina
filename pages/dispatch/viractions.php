<?php
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

if (isset($_POST['vir_item']) && isset($_POST['vir_status']))
{
# AJAX call to update vir status
## Let's see if we are trying to close the ticket
if (! isset($_POST['repair_notes'])) { $repair_notes = "NULL"; }else{ $repair_notes = '"'.$_POST['repair_notes'].'"'; }
if (! isset($_POST['repair_cost'])) { $repair_cost = "NULL"; }else{ $repair_cost = $_POST['repair_cost']; }
if (! isset($_POST['repair_by'])) { $repair_by = "NULL"; }else{ $repair_by = '"'.$_POST['repair_by'].'"'; }
if (! isset($_POST['work_order_no'])) { $work_order = "NULL"; }else{ $work_order = $_POST['work_order_no']; }

$sql = "UPDATE virs SET updated_status = \"".$_POST['vir_status']."\",
        repair_notes = $repair_notes,
        repair_cost = $repair_cost,
        repair_by = $repair_by,
        work_order = $work_order
        WHERE vir_itemnum = ".$_POST['vir_item'];

if (! mysql_query($sql))
{
    echo('Unable to update `updated_status`' . mysql_error());
}
exit;
}

# USER info
$statement = 'SELECT employee_id,fname,lname from users where username = "'.$_SESSION['userid'].'"';
$drivername = mysql_fetch_array(mysql_query($statement),MYSQL_BOTH);
$employee_id = $drivername[0];
$driver_name = $drivername[1]. " ". $drivername[2];
$latitude = $_SESSION['latitude'];
$longitude = $_SESSION['longitude'];

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

if (isset($_POST['truck_ck_accessorials'])){
    foreach ($_POST['truck_ck_accessorials'] as $key => $val)
    {
        $truck_vir_items .= $val.',';
    }
    $truck_vir_items = rtrim($truck_vir_items,",");
}

if (isset($_POST['trailer_ck_accessorials'])){
    foreach ($_POST['trailer_ck_accessorials'] as $key => $val)
    {
        $trailer_vir_items .= $val.',';
    }
    $trailer_vir_items = rtrim($trailer_vir_items,",");
}

# Insert TRUCK vir:
if ($trucktype != 'trailer')
{
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
    $truck_po = mysql_insert_id();
}

if ($trucktype == 'trailer')
{
    // If we're a trailer only then insert this record
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
    '$vir_notes_finish',
    '$trucktype',
    NULL,
    '$trailer_vir_condition_tire',
    NULL
    )";

    if (! mysql_query($sql))
    {
        die('Unable to INSERT trailer VIR into table: ' . mysql_error());
    }
    $truck_po = "N/A";
    $trailer_po = mysql_insert_id();
}

if (($trucktype == 'combo') && ($trailer_number != ''))
{
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
    '$trucktype',
    NULL,
    '$trailer_vir_condition_tire',
    $truck_po
    )";

    if (! mysql_query($sql))
    {
        die('Unable to INSERT trailer VIR into table: ' . mysql_error());
    }
    $trailer_po = mysql_insert_id();
}

# Process file upload (if exists)
// Set the default value of the $images var
$images = "Driver uploaded no images.";
if ($_FILES['fileToUpload']['size'][0] > 0){
    // Get the po (truck or trailer)
    if (is_int($truck_po)) {
        // This is a truck, use that
        $local_vir_itemnum = $truck_po;
    }else{
        $local_vir_itemnum = $trailer_po;
    }

    $file_array = reArrayFiles($_FILES['fileToUpload']);
    $dom_name = 'fileToUpload';
    $target_dir = '/uploads/vir/';

    // Create a string to append to the email.
    $images = null;

    foreach($file_array as $file) {        
        // Grab the extension of the file
        $path_parts = pathinfo($file["name"]);
        $file_extension = $path_parts['extension'];

        $target_name = md5($file['name']) . '_' . time() . '.' . $file_extension;
        $return_page = '/pages/dispatch/vir.php';
        $sql = "INSERT INTO vir_images (vir_itemnum, image_path) VALUES (".$local_vir_itemnum.", '".HTTP . $target_dir . $target_name."')";        
        $file_size = 5000000; # 5megs
        upload_image($file, $target_dir, $target_name, $return_page, $sql, $file_size, $mysqli);
        $images .= HTTP . $target_dir . $target_name . "\n";
    }
}

# Send the email out
# Reset the trucktype if it's 'combo'
($trucktype == 'combo' ? $trucktype = 'semi' : $trucktype = $trucktype);
$to = "trucks@catalinacartage.com";
$subject = "VIR $truck_number | $trailer_number | $trucktype $preorposttrip";
$body = <<<EOT
Work Orders:			Truck WO: $truck_po , Trailer WO: $trailer_po
Driver:				$driver_name
Truck Type:			$trucktype
Truck:				$truck_number, $truck_vir_condition
Truck Tires:			$truck_vir_condition_tire
Trailer:				$trailer_number, $trailer_vir_condition
Trailer Tires:			$trailer_vir_condition_tire
Date:				$insp_date
Start Time:			$insp_start_time
End Time:			$insp_end_time
Coordinates:        		$latitude, $longitude
Truck Odometer:		$truckodometer

General VIR Notes:		$vir_notes_finish;

:::::::::::::Truck VIR:::::::::::::::::::::::::::::::::::::::::::::::::::::::
Truck VIR:			$truck_vir_condition;
Comments:			$vir_notes_detailed_truck $vir_notes_quick_report;
Item(s):				$truck_vir_items;

Truck Tires VIR:	$truck_vir_condition_tire
Tire Notes:			$truck_tires_notes
Steer (Driver):			$truck_tires_driverside_steer		Steer (Passenger): $truck_tires_passenger_steer
Axel 1(Driver):			$truck_tires_driverside_ax1front		Axel 1(Passenger): $truck_tires_passenger_ax1front
Axel 2(Driver):			$truck_tires_driverside_ax2rear		Axel 2(Passenger): $truck_tires_passenger_ax2rear


:::::::::::::Trailer VIR:::::::::::::::::::::::::::::::::::::::::::::::::::::::
Trailer VIR:			$trailer_vir_condition:
Comments:			$vir_notes_detailed_trailer;
Item(s):				$trailer_vir_items;

Trailer Tires VIR:		$trailer_vir_condition;
Tire Notes:			$trailer_tires_notes;
Axel 1 (Driver): 			$trailer_tires_driverside_ax1front		Axel 1 (Passenger): $trailer_tires_passenger_ax1front
Axel 2 (Driver): 			$trailer_tires_driverside_ax2rear		Axel 2 (Passenger): $trailer_tires_passenger_ax2rear

:::::::::::::Uploaded Images:::::::::::::::::::::::::::::::::::::::::::::::::::
$images

::::::::::::::::::::::::End of Message:::::::::::::::::::::::::::::::::::::::::

EOT;

sendEmail($to,$subject,$body,$_SESSION['email'],null,null);
header("Location: /pages/dispatch/vir.php");
?>
