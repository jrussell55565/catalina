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
    // Do some _GET manipulation.  This is because I passed in different names when I created
    // The ajax call and now I have to make it work.
    if (isset($_GET['trip_search_tripnum'])) { $_GET['trip_no'] = $_GET['trip_search_tripnum']; }
    if (isset($_GET['trip_search_hwbnum'])) { $_GET['hwb_no'] = $_GET['trip_search_hwbnum']; }
    if (isset($_GET['trip_search_startdate'])) { $_GET['trip_start'] = $_GET['trip_search_startdate']; }
    if (isset($_GET['trip_search_enddate'])) { $_GET['trip_end'] = $_GET['trip_search_enddate']; }
    if (isset($_GET['trip_search_trucknumber'])) { $_GET['trip_truck_no'] = $_GET['trip_search_trucknumber']; }
    if (isset($_GET['trip_search_driver'])) { $_GET['trip_driver'] = $_GET['trip_search_driver']; }


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

    if ($_GET['trip_state'] != 'none') { 
        $_GET['trip_state'] = '( ifta.location_start like "%' . $_GET['trip_state'] . '%" OR ifta.location_end like "%' . $_GET['trip_state'] . '%")'; 
    } else { 
        $_GET['trip_state'] = '( ifta.location_start like "%" OR ifta.location_end like "%")'; 
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

    if ($_GET['btn_display_results'] == 'export') {
        // Dynamically pull out more records if this is an export
        $additional_sql = ",
                  ifta_details.trip_date as 'ifta_details.trip_date',
                  ifta_details.hwb as 'ifta_details.hwb',
                  ifta_details.route as 'ifta_details.route',
                  ifta_details.st_exit as 'ifta_details.st_exit',
                  ifta_details.st_enter as 'ifta_details.st_enter',
                  ifta_details.state_line_odometer as 'ifta_details.state_line_odometer',
                  ifta_details.state_miles as 'ifta_details.state_miles',
                  ifta_fuel.trip_no as 'ifta_fuel.trip_no',
                  ifta_fuel.trip_date as 'ifta_fuel.trip_date',
                  ifta_fuel.fuel_gallons as 'ifta_fuel.fuel_gallons',
                  ifta_fuel.fuel_reefer as 'ifta_fuel.fuel_reefer',
                  ifta_fuel.fuel_other as 'ifta_fuel.fuel_other',
                  ifta_fuel.vendor as 'ifta_fuel.vendor',
                  ifta_fuel.city as 'ifta_fuel.city',
                  ifta_fuel.state as 'ifta_fuel.state'";
        $joins = "JOIN ifta_details on ifta.trip_no = ifta_details.trip_no
                  JOIN ifta_fuel on ifta.trip_no = ifta_fuel.trip_no";
    }else{
        $additional_sql = '';
        $joins = '';
    }
    
    $query = "SELECT 
                 (SELECT 
                          CONCAT(fname, ' ', lname)
                      FROM
                          users
                      WHERE
                          employee_id = driver1) AS 'ifta_driver1',
                  (SELECT 
                          CONCAT(fname, ' ', lname)
                      FROM
                          users
                      WHERE
                          employee_id = driver2) AS 'ifta_driver2',
                  ifta.odo_start as 'ifta_odo_start',
                  ifta.odo_end as 'ifta_odo_end',
                  ifta.odo_end - ifta.odo_start AS 'ifta_trip_miles',
                  ifta.trip_no as 'ifta_trip_no',
                  ifta.truck_no as 'ifta_truck_no',
                  DATE_FORMAT(ifta.date_started, '%m/%d/%Y') AS 'ifta_trip_start',
                  DATE_FORMAT(ifta.date_ended, '%m/%d/%Y') AS 'ifta_trip_end',
                  ifta.location_start,
                  ifta.location_end
                  " . $additional_sql . "
              FROM
                  ifta
              ". $joins ."
              WHERE 1=1
              AND ". $_GET['trip_no'] ."
              AND
              (
              ". $trip_date . "
              )
              AND ". $_GET['trip_truck_no']. "
              AND ". $_GET['trip_driver']. "
              #AND ". $_GET['trip_state']. "
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

  error_log( $emparray );
if ($_GET['btn_display_results'] == 'export') {
  // We want to export this data to CSV
  $fileName = time() . '.csv';
  $fileDir = '/tmp/';
  $header = "ifta_driver1,ifta_driver2,ifta_odo_start,ifta_odo_end,ifta_trip_miles,ifta_trip_no,ifta_truck_no,ifta_trip_start,ifta_trip_end,";
  $header .= "ifta_details.trip_date,ifta_details.hwb,ifta_details.route,ifta_details.st_exit,ifta_details.st_enter,ifta_details.state_line_odometer,ifta_details.state_miles,";
  $header .= "ifta_fuel.trip_no,ifta_fuel.trip_date,ifta_fuel.fuel_gallons,ifta_fuel.fuel_reefer,ifta_fuel.fuel_other,ifta_fuel.vendor,ifta_fuel.city,ifta_fuel.state\n";

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
