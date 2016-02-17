<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

print_r($_POST);

# Start TX
$mysqli->autocommit(FALSE);
$mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);
try {

    # IFTA table
    $sql_ifta = "INSERT INTO ifta
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

        if ($mysqli->query($sql_fuel) === false)
        {
            throw new Exception("Error INSERTING into table IFTA_FUEL: ".$mysqli->error);
        }
    }

    $id = $_POST['hdn_upload'];
    for ($i=0; $i<sizeof($id); $i++)
    {
    $file_name = $_POST['hdn_upload'][$i];
    $continue = 0;
    switch ($_FILES["$file_name"]['error']) {
        case UPLOAD_ERR_OK:
            break;
        case UPLOAD_ERR_NO_FILE:
            $continue = 1;
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            throw new Exception('Exceeded filesize limit.');
        default:
            throw new Exception('Unknown error uploading '.$_FILES["$file_name"]['name']);
    }
    if ($continue == 1)
    {
        # if no file was uploaded then skip to the next file
        continue;
    }
   
      print $_FILES["$file_name"]['tmp_name']."<br>";
      $file = md5($_POST['txt_tripnum'].".".$_FILES["$file_name"]['name'].$_POST['hdn_upload'][$i]);
      $sql_uploads = "INSERT INTO ifta_uploads
      (
      trip_no,
      type,
      file_name
      )
      VALUES
      (
      ".$_POST['txt_tripnum'].",
      '".$_POST['hdn_upload'][$i]."',
      '$file'
      )";
    
    if ($mysqli->query($sql_uploads) === false)
    {
        throw new Exception("Error uploading file to IFTA_UPLOADS: ". $mysqli->error);
    }

    $src = $_FILES["$file_name"]['tmp_name'];
    $dst = IFTA_UPLOAD."/".$file;
    if (!move_uploaded_file($src, $dst))
    {
        throw new Exception("Unable to move $src into $dst");
    }
    }

    $mysqli->commit();

} catch (Exception $e) {
    // An exception has been thrown
    // We must rollback the transaction
    $url_error = urlencode($e->getMessage());
    $mysqli->rollback();
    #header("location: /pages/dispatch/ifta.php?error=$url_error");
    $mysqli->autocommit(TRUE);
    $mysqli->close();
    echo $url_error."<BR>";
    exit;
}

$mysqli->autocommit(TRUE);
$mysqli->close();
exit;
header("location: /pages/dispatch/ifta.php");
exit;
?>
