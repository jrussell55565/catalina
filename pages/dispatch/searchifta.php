<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

// General purpose search.
if ($_GET['search_type'] == 'truck_odo')
{
    // Let's just search for the odometer for the truck
    $query = "select max(odo_end) odo_end from ifta where truck_no=" . $_GET['truck_no'];

}else{
    // Transform the GET variables into sql statements
    if (isset($_GET['trip_no']) && ($_GET['trip_no'] != '')) { 
        $_GET['trip_no'] = ' ifta.trip_no = "' . $_GET['trip_no'] . '"'; 
    } else { 
        $_GET['trip_no'] = ' ifta.trip_no like "%"';
    }

    if (isset($_GET['hwb_no']) && ($_GET['hwb_no'] != '')) { 
        $_GET['hwb_no'] = ' ifta.hwb_no = "' . $_GET['hwb_no'] . '"'; 
    } else { 
        $_GET['hwb_no'] = ' ifta.hwb_no like "%"';
    }
    
    // Don't set a trip_date value if we left off a beginning date
    if (! empty($_GET['trip_start']) && $_GET['trip_start'] != '') {
        $trip_date = ' 
                    ifta.date_started BETWEEN str_to_date(\'' . $_GET['trip_start'] . '\',\'%m/%d/%Y\') AND str_to_date(\'' . $_GET['trip_end'] . '\',\'%m/%d/%Y\')
                    OR
                    ifta.date_ended BETWEEN str_to_date(\'' . $_GET['trip_start'] . '\',\'%m/%d/%Y\') AND str_to_date(\'' . $_GET['trip_end'] . '\',\'%m/%d/%Y\')
                     ';
    }else{
        $trip_date = ' 1=1 ';
    }
    
    if (isset($_GET['trip_truck_no']) && ($_GET['trip_truck_no'] != '')) { 
        $_GET['trip_truck_no'] = ' ifta.truck_no = "' . $_GET['trip_truck_no'] . '"'; 
    } else { 
        $_GET['trip_truck_no'] = ' ifta.truck_no like "%"';
    }
    
    if (isset($_GET['trip_driver']) && $_GET['trip_driver'] != '' && $_GET['trip_driver'] != 'null') {
        $_GET['trip_driver'] = ' (ifta.driver1 = "' . $_GET['trip_driver'] . '" OR ifta.driver2 = "'. $_GET['trip_driver']. '")'; 
    } else { 
        $_GET['trip_driver'] = ' (ifta.driver1 like "%" OR ifta.driver2 like "%")';
    }
    
    $query = "SELECT 
              (SELECT concat(fname,' ',lname) from users where employee_id=driver1) as driver1,
              (SELECT concat(fname,' ',lname) from users where employee_id=driver2) as driver2,
              odo_start,
              odo_end,
              odo_end - odo_start as trip_miles,
              ifta.trip_no,
              truck_no,
              date_format(date_started,'%m/%d/%Y') as trip_start,
              date_format(date_ended,'%m/%d/%Y') as trip_end
              FROM ifta
              WHERE 1=1
              AND ". $_GET['trip_no'] ."
              AND
              (
              ". $trip_date . "
              )
              AND ". $_GET['trip_truck_no']. "
              AND ". $_GET['trip_driver']. "
              ORDER BY date_started ASC";
    
}

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

if ($result = $mysqli->query($query)) {

    /* fetch associative array */
    while ($row = $result->fetch_assoc()) {
        $emparray[] = $row;
    }

    /* free result set */
    $result->free();
}

/* close connection */
$mysqli->close();

if ($_GET['btn_export_results'] == 'export') {
  // We want to export this data to CSV
  $fileName = time() . '.csv';
  $fileDir = '/tmp/';
  $header = "driver1,driver2,odo_start,odo_end,trip_miles,trip_no,truck_no,trip_start,trip_end\n";

  $file = fopen($fileDir . $fileName, "w") or die("Unable to open file!");
  file_put_contents($fileDir . $fileName, $header, FILE_APPEND | LOCK_EX);
  
  foreach ($emparray as $key => $value) {       
    foreach ($value as $k => $v) {
      $line .= $v . ",";
    }
    $line = rtrim($line,',');
    file_put_contents($fileDir . $fileName, $line, FILE_APPEND | LOCK_EX);
  
    $line = "\n";
    file_put_contents($fileDir . $fileName, $line, FILE_APPEND | LOCK_EX);
    unset($line);
  }
  fclose($fileDir . $fileName);
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header('Content-Disposition: attachment; filename='.basename($file));
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header('Content-Length: ' . filesize($fileDir . $fileName));
  header ("Content-Disposition:attachment; filename=\"$fileName\"");
  readfile($fileDir . $fileName);
  unlink($fileDir . $fileName);
}else{
  print json_encode($emparray);  
}

?>
