<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

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
    odo_end
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
    ".$_POST['txt_od_end']."
    )";

    if ($mysqli->query($sql_ifta) === false)
    {
        throw new Exception("Error INSERTING into table IFTA: ".$mysqli->error);
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
        permit_required
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
        '$permit'
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
                               file_name
                               )
                               VALUES
                               (
                               '".$_POST['txt_tripnum']."',
                               '".$_POST['hdn_upload'][$i]."',
                               '".$dst."'
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
    odo_end = ".$_POST['txt_od_end']."
    WHERE trip_no = '".$_POST['txt_tripnum']."'";
    
    if ($mysqli->query($sql_ifta) === false)
    {
        throw new Exception("Error UPDATING IFTA: ".$mysqli->error);
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

$mysqli->autocommit(TRUE);
$mysqli->close();
header("location: /pages/dispatch/ifta.php");
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
?>
