<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

// If we just want to download the file then run this

if (isset($_GET['download_file'])) {
   $statement = "SELECT file_name,file_name_uploaded FROM ifta_uploads
                 WHERE id = ".$_GET['id'];

   if ($result = $mysqli->query($statement)) {
     while($obj = $result->fetch_object()){
            $file_name = $obj->file_name;
            $file_name_uploaded = $obj->file_name_uploaded;
     }
   }
   /* free result set */
   $result->close();
   downloadFile($file_name, $file_name_uploaded);
}

####

# Start TX
$mysqli->autocommit(FALSE);
$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

// Run this part if we're ADDING an IFTA
if (isset($_POST['add_ifta'])) {
  try {

    # IFTA table
    $sql_ifta = "INSERT INTO ifta
    (
    trip_no,
    date_started,
    date_ended,
    driver1,
    driver2,
    truck_no,
    odo_start,
    odo_end,
    compliance_trip,
    compliance_logs,
    compliance_vir,
    compliance_fuel,
    compliance_bol,
    compliance_permits,
    compliance_gps,
    compliance_dot,
    notes_trip_driver,
    notes_trip_internal,
    location_start,
    location_stops,
    location_end,
    points_trip,
    points_fuel,
    points_images
    )
    VALUES
    (
    '".$_POST['txt_tripnum']."',
    str_to_date('".$_POST['txt_date_start']."','%m/%d/%Y'),
    str_to_date('".$_POST['txt_date_end']."','%m/%d/%Y'),
    '".$_POST['sel_add_driver_1']."',
    '".$_POST['sel_add_driver_2']."',
    ".$_POST['txt_truckno'].",
    ".$_POST['txt_od_start'].",
    ".$_POST['txt_od_end'].",
    '".$_POST['compliance_trip']."',
    '".$_POST['compliance_logs']."',
    '".$_POST['compliance_vir']."',
    '".$_POST['compliance_fuel']."',
    '".$_POST['compliance_bol']."',
    '".$_POST['compliance_permits']."',
    '".$_POST['compliance_gps']."',
    '".$_POST['compliance_dot']."',
    '".$_POST['notes_trip_driver']."',
    '".$_POST['notes_trip_internal']."',
    '".$_POST['location_start']."',
    '".$_POST['location_stops']."',
    '".$_POST['location_end']."',
    ".(!$_POST['points_trip'] ? 0 : $_POST['points_trip']) .",
    ".(!$_POST['points_fuel'] ? 0 : $_POST['points_fuel']) .",
    ".(!$_POST['points_images'] ? 0 : $_POST['points_images']) ."
    )";

    if ($mysqli->query($sql_ifta) === false)
    {
        throw new Exception("Error INSERTING into table IFTA: ".$mysqli->error);
    }

    // Insert into the ifta_updated_by
    $ifta_updated_by_sql = "INSERT INTO ifta_updated_by
    (trip_no, updated_by)
    VALUES
    (
    '".$_POST['txt_tripnum']."',
    '".$_SESSION['employee_id']."'
    )";

    if ($mysqli->query($ifta_updated_by_sql) === false)
    {
        throw new Exception("Error INSERTING into table IFTA_UPDATED_BY: ".$mysqli->error);
    }

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
        if (isset($_POST['cb_trip_issue_details'][$i])) { $issue = 'Y'; }else{ $issue = 'N'; }
        if ($_POST['sl_trip_issue_details'][$i] == '') { $_POST['sl_trip_issue_details'][$i] = 'NULL'; }
        if ($_POST['issue_comment_details'][$i] == '') { $_POST['issue_comment_details'][$i] = 'NULL'; }
        if ($_POST['date_resolved_details'][$i] == '') { $_POST['date_resolved_details'][$i] = 'NULL'; }
      
        $sql_details = 
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
        permit_required,
        cb_trip_issue,
        sl_trip_issue,
        issue_comment,
        date_resolved
        )
        VALUES
        (
        '".$_POST['txt_tripnum_details'][$i]."',
        str_to_date('".$_POST['txt_date_details'][$i]."','%m/%d/%Y'),
        '".$_POST['txt_driver_details'][$i]."',
        '".$_POST['txt_hwb_details'][$i]."',
        '".$_POST['txt_routes_details'][$i]."',
        '".$_POST['txt_state_exit_details'][$i]."',
        '".$_POST['txt_state_enter_details'][$i]."',
        ".$_POST['txt_state_odo_details'][$i].",
        ".$_POST['txt_state_miles_details'][$i].",
        '$permit',
        '$issue',
        '".$_POST['sl_trip_issue_details'][$i]."',
        '".$_POST['issue_comment_details'][$i]."',
        str_to_date('".$_POST['date_resolved_details'][$i]."','%m/%d/%Y')
        )";

        if ($mysqli->query($sql_details) === false)
        {
            throw new Exception("Error INSERTING into table IFTA_DETAILS: ".$mysqli->error);
        }
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
        
        $sql_fuel = "INSERT INTO ifta_fuel
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
        '".$_POST['txt_fuel_tripnum'][$i]."',
        str_to_date('".$_POST['txt_fuel_date'][$i]."','%m/%d/%Y'),
        ".$_POST['txt_fuel_gallons'][$i].",
        ".$_POST['txt_fuel_reefer'][$i].",
        ".$_POST['txt_fuel_other'][$i].",
        '".$_POST['txt_fuel_vendor'][$i]."',
        '".$_POST['txt_fuel_city'][$i]."',
        '".$_POST['txt_fuel_state'][$i]."',
        ".$_POST['txt_fuel_odo'][$i]."
        )";

        if ($mysqli->query($sql_fuel) === false)
        {
            throw new Exception("Error INSERTING into table IFTA_FUEL: ".$mysqli->error);
        }
    }

    $id = $_POST['hdn_upload'];
    for ($i=0; $i<sizeof($id); $i++)
    {
        $file_name = $_POST['hdn_upload'][$i];
        $file_ary = reArrayFiles($_FILES["$file_name"]);
        foreach ($file_ary as $file)
        {
            if ($file["name"])
            {
                switch ($file['error'])
                {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new Exception('No file found.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new Exception('Exceeded filesize limit.');
                    default:
                        throw new Exception('Unknown error uploading '.$file['name']);
                }
                $dst = md5($_POST['txt_tripnum'].".".$file['tmp_name']);
                $sql_uploads = "INSERT INTO ifta_uploads
                               (
                               trip_no,
                               type,
                               file_name,
                               file_name_uploaded
                               )
                               VALUES
                               (
                               '".$_POST['txt_tripnum']."',
                               '".$_POST['hdn_upload'][$i]."',
                               '".$dst."',
                               '".$file["name"]."'
                               )";

                if ($mysqli->query($sql_uploads) === false)
                {
                    throw new Exception("Error INSERTING file into IFTA_UPLOADS: ". $mysqli->error);
                }

                $src = $file['tmp_name'];
                $dst = IFTA_UPLOAD."/".$dst;
                if (!move_uploaded_file($src, $dst))
                {
                    throw new Exception("Unable to move $src into $dst");
                }

            }
        }
    }

    $mysqli->commit();

  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    $mysqli->rollback();
    header("location: /pages/dispatch/ifta.php?error=$url_error");
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    exit;
  }

  // This part will email the driver1 (and driver2 if applicable)
  // to inform them of any missing compliance info
  sendIftaEmail($mysqli);
}

// Run this part if we're UPDATING an IFTA
if (isset($_POST['update_ifta'])) {

  try {
    # IFTA table
    $sql_ifta = "UPDATE ifta SET
    date_started = str_to_date('".$_POST['txt_date_start']."','%m/%d/%Y'),
    date_ended = str_to_date('".$_POST['txt_date_end']."','%m/%d/%Y'),
    driver1 = '".$_POST['sel_add_driver_1']."',
    driver2 = '".$_POST['sel_add_driver_2']."',
    truck_no = ".$_POST['txt_truckno'].",
    odo_start = ".$_POST['txt_od_start'].",
    odo_end = ".$_POST['txt_od_end'].",
    compliance_trip = '".$_POST['compliance_trip']."',
    compliance_logs = '".$_POST['compliance_logs']."',
    compliance_vir = '".$_POST['compliance_vir']."',
    compliance_fuel = '".$_POST['compliance_fuel']."',
    compliance_bol = '".$_POST['compliance_bol']."',
    compliance_permits = '".$_POST['compliance_permits']."',
    compliance_gps = '".$_POST['compliance_gps']."',
    compliance_dot = '".$_POST['compliance_dot']."',
    notes_trip_driver = '".$_POST['notes_trip_driver']."',
    notes_trip_internal = '".$_POST['notes_trip_internal']."',
    location_start = '".$_POST['location_start']."',
    location_stops = '".$_POST['location_stops']."',
    location_end = '".$_POST['location_end']."',
    points_trip = ".(!$_POST['points_trip'] ? 0 : $_POST['points_trip']) .",
    points_fuel = ".(!$_POST['points_fuel'] ? 0 : $_POST['points_fuel']) .",
    points_images = ".(!$_POST['points_images'] ? 0 : $_POST['points_images']) .",
    trip_status = '".$_POST['trip_status']."'
    WHERE trip_no = '".$_POST['txt_tripnum']."'";

    if ($mysqli->query($sql_ifta) === false)
    {
        throw new Exception("Error UPDATING IFTA: ".$mysqli->error);
    }

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
        if ($_POST['sl_trip_issue_details'][$i] == '') { $_POST['sl_trip_issue_details'][$i] = 'NULL'; }
        if ($_POST['issue_comment_details'][$i] == '') { $_POST['issue_comment_details'][$i] = 'NULL'; }
        if ($_POST['date_resolved_details'][$i] == '') { $_POST['date_resolved_details'][$i] = 'NULL'; }
        
        $count = 0;
        foreach($_POST['txt_permit_req_details'] as $permit_value) {
          if ($permit_value == $_POST['hdn_details_id'][$i]) {
              $count = 1;
          }
        }
        if ($count == 1) {
            $permit = 'Y';
        }else{
            $permit = 'N';
        }
        $count = 0;
        foreach($_POST['cb_trip_issue_details'] as $issue_value) {
          if ($issue_value == $_POST['hdn_details_id'][$i]) {
              $count = 1;
          }
        }
        if ($count == 1) {
            $issue = 'Y';
        }else{
            $issue = 'N';
        }
      
        $sql_details = "UPDATE ifta_details SET
        trip_date = str_to_date('".$_POST['txt_date_details'][$i]."','%m/%d/%Y'),
        driver = '".$_POST['txt_driver_details'][$i]."',
        hwb = '".$_POST['txt_hwb_details'][$i]."',
        route = '".$_POST['txt_routes_details'][$i]."',
        st_exit = '".$_POST['txt_state_exit_details'][$i]."',
        st_enter = '".$_POST['txt_state_enter_details'][$i]."',
        state_line_odometer = ".$_POST['txt_state_odo_details'][$i].",
        state_miles = ".$_POST['txt_state_miles_details'][$i].",
        permit_required = '$permit',
        cb_trip_issue = '$issue',
        sl_trip_issue = '".$_POST['sl_trip_issue_details'][$i]."',
        issue_comment = '".$_POST['issue_comment_details'][$i]."',
        date_resolved = str_to_date('".$_POST['date_resolved_details'][$i]."','%m/%d/%Y')
        WHERE id = ".$_POST['hdn_details_id'][$i];
        /* Used for debug
        print $sql_details . "\n";
        continue;
        */
        if ($mysqli->query($sql_details) === false)
        {
            throw new Exception("Error UPDATING IFTA_DETAILS: ".$mysqli->error);
        }
    }

    /* Used for debug
    exit;
    */

    $id = $_POST['hdn_details_id_add'];
    for ($i=0; $i<sizeof($id); $i++)
    {
        if ($_POST['txt_tripnum_details_add'][$i] == '') { $_POST['txt_tripnum_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_date_details_add'][$i] == '') { $_POST['txt_date_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_driver_details_add'][$i] == '') { $_POST['txt_driver_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_hwb_details_add'][$i] == '') { $_POST['txt_hwb_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_routes_details_add'][$i] == '') { $_POST['txt_routes_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_state_exit_details_add'][$i] == '') { $_POST['txt_state_exit_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_state_enter_details_add'][$i] == '') { $_POST['txt_state_enter_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_state_odo_details_add'][$i] == '') { $_POST['txt_state_odo_details_add'][$i] = 'NULL'; }
        if ($_POST['txt_state_miles_details_add'][$i] == '') { $_POST['txt_state_miles_details_add'][$i] = 'NULL'; }
        if (isset($_POST['txt_permit_req_details_add'][$i])) { $permit = 'Y'; }else{ $permit = 'N'; }
        if (isset($_POST['cb_trip_issue_details_add'][$i])) { $issue = 'Y'; }else{ $issue = 'N'; }
        if ($_POST['sl_trip_issue_details_add'][$i] == '') { $_POST['sl_trip_issue_details_add'][$i] = 'NULL'; }
        if ($_POST['issue_comment_details_add'][$i] == '') { $_POST['issue_comment_details_add'][$i] = 'NULL'; }
        if ($_POST['date_resolved_details_add'][$i] == '') { $_POST['date_resolved_details_add'][$i] = 'NULL'; }
      
        $sql_details_add = 
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
        permit_required,
        cb_trip_issue,
        sl_trip_issue,
        issue_comment,
        date_resolved
        )
        VALUES
        (
        '".$_POST['txt_tripnum_details_add'][$i]."',
        str_to_date('".$_POST['txt_date_details_add'][$i]."','%m/%d/%Y'),
        '".$_POST['txt_driver_details_add'][$i]."',
        '".$_POST['txt_hwb_details_add'][$i]."',
        '".$_POST['txt_routes_details_add'][$i]."',
        '".$_POST['txt_state_exit_details_add'][$i]."',
        '".$_POST['txt_state_enter_details_add'][$i]."',
        ".$_POST['txt_state_odo_details_add'][$i].",
        ".$_POST['txt_state_miles_details_add'][$i].",
        '$permit',
        '$issue',
        '".$_POST['sl_trip_issue_details_add'][$i]."',
        '".$_POST['issue_comment_details_add'][$i]."',
        str_to_date('".$_POST['date_resolved_details_add'][$i]."','%m/%d/%Y')
        )";
        /*
        Used for debug
        print $sql_details_add; exit;
        */
        if ($mysqli->query($sql_details_add) === false)
        {
            throw new Exception("Error INSERTING into table IFTA_DETAILS: ".$mysqli->error);
        }
    }

  #IFTA_FUEL
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
        if ($_POST['sl_trip_issue_fuel'][$i] == '') { $_POST['sl_trip_issue_fuel'][$i] = 'NULL'; }
        if ($_POST['issue_comment_fuel'][$i] == '') { $_POST['issue_comment_fuel'][$i] = 'NULL'; }
        if ($_POST['date_resolved_fuel'][$i] == '') { $_POST['date_resolved_fuel'][$i] = 'NULL'; }

        $count = 0;

        foreach($_POST['cb_trip_issue_fuel'] as $issue_value) {
          if ($issue_value == $_POST['hdn_fuel_id'][$i]) {
              $count = 1;
          }
        }
        if ($count == 1) {
            $issue = 'Y';
        }else{
            $issue = 'N';
        }
        
        $sql_fuel = "UPDATE ifta_fuel SET
        trip_date = str_to_date('".$_POST['txt_fuel_date'][$i]."','%m/%d/%Y'),
        fuel_gallons = ".$_POST['txt_fuel_gallons'][$i].",
        fuel_reefer = ".$_POST['txt_fuel_reefer'][$i].",
        fuel_other = ".$_POST['txt_fuel_other'][$i].",
        vendor = '".$_POST['txt_fuel_vendor'][$i]."',
        city = '".$_POST['txt_fuel_city'][$i]."',
        state = '".$_POST['txt_fuel_state'][$i]."',
        odometer = ".$_POST['txt_fuel_odo'][$i].",
        cb_trip_issue = '$issue',
        sl_trip_issue = '".$_POST['sl_trip_issue_fuel'][$i]."',
        issue_comment = '".$_POST['issue_comment_fuel'][$i]."',
        date_resolved = str_to_date('".$_POST['date_resolved_fuel'][$i]."','%m/%d/%Y')
        WHERE id = ".$_POST['hdn_fuel_id'][$i];

        if ($mysqli->query($sql_fuel) === false)
        {
            throw new Exception("Error UPDATING table IFTA_FUEL: ".$mysqli->error);
        }
    }

    $id = $_POST['hdn_fuel_id_add'];
    for ($i=0; $i<sizeof($id); $i++)
    {
        if ($_POST['txt_fuel_tripnum_add'][$i] == '') { $_POST['txt_fuel_tripnum_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_date_add'][$i] == '') { $_POST['txt_fuel_date_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_gallons_add'][$i] == '') { $_POST['txt_fuel_gallons_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_reefer_add'][$i] == '') { $_POST['txt_fuel_reefer_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_other_add'][$i] == '') { $_POST['txt_fuel_other_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_vendor_add'][$i] == '') { $_POST['txt_fuel_vendor_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_city_add'][$i] == '') { $_POST['txt_fuel_city_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_state_add'][$i] == '') { $_POST['txt_fuel_state_add'][$i] = 'NULL'; }
        if ($_POST['txt_fuel_odo_add'][$i] == '') { $_POST['txt_fuel_odo_add'][$i] = 'NULL'; }
        if (isset($_POST['cb_trip_issue_fuel_add'][$i])) { $issue = 'Y'; }else{ $issue = 'N'; }
        if ($_POST['sl_trip_issue_fuel_add'][$i] == '') { $_POST['sl_trip_issue_fuel_add'][$i] = 'NULL'; }
        if ($_POST['issue_comment_fuel_add'][$i] == '') { $_POST['issue_comment_fuel_add'][$i] = 'NULL'; }
        if ($_POST['date_resolved_fuel_add'][$i] == '') { $_POST['date_resolved_fuel_add'][$i] = 'NULL'; }
        
        $sql_fuel = "INSERT INTO ifta_fuel
        (
        trip_no,
        trip_date,
        fuel_gallons,
        fuel_reefer,
        fuel_other,
        vendor,
        city,
        state,
        odometer,
        cb_trip_issue,
        sl_trip_issue,
        issue_comment,
        date_resolved
        )
        VALUES
        (
        '".$_POST['txt_fuel_tripnum_add'][$i]."',
        str_to_date('".$_POST['txt_fuel_date_add'][$i]."','%m/%d/%Y'),
        ".$_POST['txt_fuel_gallons_add'][$i].",
        ".$_POST['txt_fuel_reefer_add'][$i].",
        ".$_POST['txt_fuel_other_add'][$i].",
        '".$_POST['txt_fuel_vendor_add'][$i]."',
        '".$_POST['txt_fuel_city_add'][$i]."',
        '".$_POST['txt_fuel_state_add'][$i]."',
        ".$_POST['txt_fuel_odo_add'][$i].",
        '$issue',
        '".$_POST['sl_trip_issue_fuel_add'][$i]."',
        '".$_POST['issue_comment_fuel_add'][$i]."',
        str_to_date('".$_POST['date_resolved_fuel_add'][$i]."','%m/%d/%Y')
        )";

        if ($mysqli->query($sql_fuel) === false)
        {
            throw new Exception("Error INSERTING into table IFTA_FUEL: ".$mysqli->error);
        }
    }

    $id = $_POST['hdn_upload'];
    for ($i=0; $i<sizeof($id); $i++)
    {
        $file_name = $_POST['hdn_upload'][$i];
        $file_ary = reArrayFiles($_FILES["$file_name"]);
        foreach ($file_ary as $file)
        {
            if ($file["name"])
            {
                switch ($file['error'])
                {
                    case UPLOAD_ERR_OK:
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        throw new Exception('No file found.');
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new Exception('Exceeded filesize limit.');
                    default:
                        throw new Exception('Unknown error uploading '.$file['name']);
                }
                $dst = md5($_POST['txt_tripnum'].".".$file['tmp_name']);
                $sql_uploads = "INSERT INTO ifta_uploads
                               (
                               trip_no,
                               type,
                               file_name,
                               file_name_uploaded
                               )
                               VALUES
                               (
                               '".$_POST['txt_tripnum']."',
                               '".$_POST['hdn_upload'][$i]."',
                               '".$dst."',
                               '".$file["name"]."'
                               )";

                if ($mysqli->query($sql_uploads) === false)
                {
                    throw new Exception("Error INSERTING file into IFTA_UPLOADS: ". $mysqli->error);
                }

                $src = $file['tmp_name'];
                $dst = IFTA_UPLOAD."/".$dst;
                if (!move_uploaded_file($src, $dst))
                {
                    throw new Exception("Unable to move $src into $dst");
                }

            }
        }
    }

  // This part will email the driver1 (and driver2 if applicable)
  // to inform them of any missing compliance info
  if ($_POST['send_email'] == 'yes')
  {
    sendIftaEmail($mysqli);
  }

  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    $mysqli->rollback();
    header("location: /pages/dispatch/updateifta.php?trip_no=".$_POST['txt_tripnum']."&error=$url_error");
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    exit;
  }
}

if (isset($_POST['delete_upload'])) {
  try {
    // Get the current filename
    $statement = "SELECT file_name FROM ifta_uploads
                  WHERE id = " . $_POST['upload_id'];

    if ($result = $mysqli->query($statement)) {
      while($obj = $result->fetch_object()){
        $file_name = $obj->file_name;
      }
    }

    # IFTA_UPLOADS
    $sql_ifta = "DELETE FROM ifta_uploads
                 WHERE id = ".$_POST['upload_id'];

    if ($mysqli->query($sql_ifta) === false)
    {
        throw new Exception("Error deleting image from IFTA_UPLOADS: ".$mysqli->error);
    }

    // Delete the file
    if (!unlink(IFTA_UPLOAD."/".$file_name))
    {
        throw new Exception("Unable to delete file from disk.");
    }

  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $mysqli->rollback();
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    $data = array('type' => 'error', 'message' => $e->getMessage);
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
  }
}

if (isset($_POST['delete_ifta'])) {
  try {
    $ifta_type = $_POST['ifta_type'];
    $sql_ifta = "DELETE FROM ".$ifta_type."
                 WHERE id = ".$_POST['ifta_id'];

    if ($mysqli->query($sql_ifta) === false)
    {
        throw new Exception("Error deleting record from $ifta_type: ".$mysqli->error);
    }

  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $mysqli->rollback();
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    $data = array('type' => 'error', 'message' => $e->getMessage());
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data);
    exit;
  }
}


$mysqli->autocommit(TRUE);
$mysqli->close();
if (isset($_POST['add_ifta'])) {
    header("location: /pages/dispatch/ifta.php");
}
if (isset($_POST['update_ifta'])) {
    header("location: /pages/dispatch/updateifta.php?trip_no=".$_POST['txt_tripnum']);
}
exit;

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

function downloadFile($file_name, $file_name_uploaded) { 
   if(file_exists(IFTA_UPLOAD . "/" . $file_name)) {
       $temp = tempnam(sys_get_temp_dir(), 'TMP_');
       file_put_contents($temp, file_get_contents(IFTA_UPLOAD . "/" . $file_name));
       $file_name_uploaded = str_replace(',',' ',$file_name_uploaded);

       header('Content-Description: File Transfer');
       header('Content-Type: application/octet-stream');
       header('Content-Disposition: attachment; filename='.basename($file_name_uploaded));
       header('Content-Transfer-Encoding: binary');
       header('Expires: 0');
       header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
       header('Pragma: public');
       header('Content-Length: ' . filesize(IFTA_UPLOAD . "/" . $file_name));
       ob_clean();
       flush();
       readfile($temp);
       unlink($temp);
       exit;
  }
}

function sendIftaEmail($mysqli) {
  $compliance = array ('compliance_trip' => 'Did not fill out Trip Pack correctly',
                       'compliance_logs' => 'Logs were missing',
                       'compliance_vir' => 'VIR not included',
                       'compliance_fuel' => 'Fuel receipts not included',
                       'compliance_bol' => 'Bill of Lading is missing',
                       'compliance_permits' => 'Permits are missing',
                       //'compliance_gps',
                       'compliance_dot' => 'DOT violations are missing');
					   //Need to include General Notes for driver (top section),
					   //Need to add Date Specific "issue" to the email and DB, this will come from multiple lines,
					   //Because of multiple lines, need to add specific date/driver to the email,
					   //Need to add Choose Issue (selected) email details also,
					   //Need to add Comments from each specific line.  Note:  If N/A then skip....,
  $subject = "Trip Pack Submitted - ".$_POST['txt_tripnum'];
  $body = "Notice: IFTA trip pack ".$_POST['txt_tripnum']." has been entered.\n";
  $body .= "Start Date: ".$_POST['txt_date_start']." End Date: ".$_POST['txt_date_end']." .\n";
  $body .= "Start city/state: ".$_POST['location_start']."\n";
  $body .= "Stop city/state: ".$_POST['location_stops']."\n";
  $body .= "End city/state: ".$_POST['location_end']."\n";
  $body .= "Notes:\n";
  $body .= $_POST['notes_trip_driver'] . "\n\n";

  //Need to enter 2 new values that will come from the DB.  Not in there yet.  Trip Origin: Trip Destination (this is for the OTR Drivers)
  $body .= "General Trip Compliance:\n\n";
  foreach ($compliance as $key => $value)
  {
    $body .= $value . "\t\t\t" . $_POST[$key] . "\n";
  }

  // Pull the drivers email and names from the DB
  try {
    if ($_POST['sel_add_driver_1'] == 'null') {
      // If the driver1 is 'Choose Driver, Unknown Driver, or Multiple Drivers then we need to get the
      // emails for the drivers in the 'details' section
      $statement = [];
      foreach($_POST['txt_driver_details'] as $driver) {
        if ($driver == 'null') { continue; }
        array_push($statement, $driver);
      }
      $statement = '"' . implode('","',$statement) . '"';
      $statement = "SELECT distinct email, username, employee_id from users where employee_id IN ($statement)";
    }else{
      $statement = "SELECT email, username, employee_id from users where employee_id IN ('".$_POST['sel_add_driver_1']."','".$_POST['sel_add_driver_2']."')";
    }

    if ($result = $mysqli->query($statement))
    {
        $driver_detail = [];
        while($obj = $result->fetch_object()){
          $driver_detail[$obj->employee_id]['username'] = $obj->username;
          $driver_detail[$obj->employee_id]['email'] = $obj->email;
        }
        $result->close();
    }else{
        throw new Exception("Unable to retrieve drivers email for notification: ". $mysqli->error);
    }
  } catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    header("location: /pages/dispatch/ifta.php?error=$url_error");
    $mysqli->close();
    exit;
  }

  $body .= "\n\nDetails:\n\n";
  // Now look at the details to create a list of items that need to be addressed.
  for ($i=0; $i<count($_POST['hdn_details_id']); $i++) {
    // Skip is the driver is null or there are no issues
    if ($_POST['txt_driver_details'][$i] == 'null') { continue; }
    if ($_POST['hdn_details_id'][$i] != $_POST['cb_trip_issue_details'][$i]) { 
      // The issue checkbox was not checked
      if ($_POST['date_resolved_details'][$i] == '') {
        // AND the date is empty (meaning it was not resolved)
        continue; 
      }
    }
    $body .= $driver_detail[$_POST['txt_driver_details'][$i]]['username'] . "\t";
    $body .= $_POST['txt_date_details'][$i] . "\t";
    $body .= $_POST['txt_routes_details'][$i] . "\t";
    $body .= $_POST['txt_state_exit_details'][$i] . "\t";
    $body .= $_POST['txt_state_enter_details'][$i] . "\t";
    $body .= $_POST['txt_state_miles_details'][$i] . "\t";
    $body .= $_POST['sl_trip_issue_details'][$i] . "\t";
    $body .= $_POST['issue_comment_details'][$i] . "\t";
    $body .= $_POST['date_resolved_details'][$i] . "\n";
  }

  // Now that we have the body we'll send an email out.
  foreach($driver_detail as $i) {
    sendEmail($i['email'],$subject,$body,"ifta@catalinacartage.com");
  }
}
?>
