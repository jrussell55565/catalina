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
      
        $sql_details = "UPDATE ifta_details SET
        trip_date = str_to_date('".$_POST['txt_date_details'][$i]."','%m/%d/%Y'),
        driver = '".$_POST['txt_driver_details'][$i]."',
        hwb = '".$_POST['txt_hwb_details'][$i]."',
        route = '".$_POST['txt_routes_details'][$i]."',
        st_exit = '".$_POST['txt_state_exit_details'][$i]."',
        st_enter = '".$_POST['txt_state_enter_details'][$i]."',
        state_line_odometer = ".$_POST['txt_state_odo_details'][$i].",
        state_miles = ".$_POST['txt_state_miles_details'][$i].",
        permit_required = '$permit'
        WHERE id = ".$_POST['hdn_details_id'][$i];
        #print $sql_details . "\n";
        #continue;
        if ($mysqli->query($sql_details) === false)
        {
            throw new Exception("Error UPDATING IFTA_DETAILS: ".$mysqli->error);
        }
    }

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
        permit_required
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
        '$permit'
        )";

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
        
        $sql_fuel = "UPDATE ifta_fuel SET
        trip_date = str_to_date('".$_POST['txt_fuel_date'][$i]."','%m/%d/%Y'),
        fuel_gallons = ".$_POST['txt_fuel_gallons'][$i].",
        fuel_reefer = ".$_POST['txt_fuel_reefer'][$i].",
        fuel_other = ".$_POST['txt_fuel_other'][$i].",
        vendor = '".$_POST['txt_fuel_vendor'][$i]."',
        city = '".$_POST['txt_fuel_city'][$i]."',
        state = '".$_POST['txt_fuel_state'][$i]."',
        odometer = ".$_POST['txt_fuel_odo'][$i]."
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
        '".$_POST['txt_fuel_tripnum_add'][$i]."',
        str_to_date('".$_POST['txt_fuel_date_add'][$i]."','%m/%d/%Y'),
        ".$_POST['txt_fuel_gallons_add'][$i].",
        ".$_POST['txt_fuel_reefer_add'][$i].",
        ".$_POST['txt_fuel_other_add'][$i].",
        '".$_POST['txt_fuel_vendor_add'][$i]."',
        '".$_POST['txt_fuel_city_add'][$i]."',
        '".$_POST['txt_fuel_state_add'][$i]."',
        ".$_POST['txt_fuel_odo_add'][$i]."
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
?>
