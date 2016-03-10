<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include($_SERVER['DOCUMENT_ROOT']."/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

// Transform the GET variables into sql statements
if (isset($_GET['trip_no']) && ($_GET['trip_no'] != '')) { 
    $_GET['trip_no'] = 'AND ifta.trip_no = "' . $_GET['trip_no'] . '"'; 
} else { 
    $_GET['trip_no'] = 'AND ifta.trip_no like "%"';
}

$trip_date = 'AND (ifta.date_started BETWEEN str_to_date(\'' . $_GET['trip_start'] . '\',\'%m/%d/%Y\') AND str_to_date(\'' . $_GET['trip_end'] . '\',\'%m/%d/%Y\'))
               OR (ifta.date_ended BETWEEN str_to_date(\'' . $_GET['trip_start'] . '\',\'%m/%d/%Y\') AND str_to_date(\'' . $_GET['trip_end'] . '\',\'%m/%d/%Y\'))';

if (isset($_GET['trip_truck_no']) && ($_GET['trip_truck_no'] != '')) { 
    $_GET['trip_truck_no'] = 'AND ifta.truck_no = "' . $_GET['trip_truck_no'] . '"'; 
} else { 
    $_GET['trip_truck_no'] = 'AND ifta.truck_no like "%"';
}

if (isset($_GET['trip_driver']) && $_GET['trip_driver'] != '' && $_GET['trip_driver'] != 'null') {
    $_GET['trip_driver'] = 'AND (ifta.driver1 = "' . $_GET['trip_driver'] . '" OR ifta.driver2 = "'. $_GET['trip_driver']. '")'; 
} else { 
    $_GET['trip_driver'] = 'AND (ifta.driver1 like "%" OR ifta.driver2 like "%")';
}

$query = "SELECT (SELECT concat(fname,' ',lname) from users where employee_id=driver1) as driver1,
       (SELECT concat(fname,' ',lname) from users where employee_id=driver2) as driver2,
          odo_end - odo_start as trip_miles,
          ifta.trip_no,
          truck_no,
          date_format(date_started,'%m/%d/%Y') as date_started,
          date_format(date_ended,'%m/%d/%Y') as date_ended
          FROM ifta
          WHERE 1=1 "
. $_GET['trip_no'] . " "
. $trip_date . " "
. $_GET['trip_truck_no']. " "
. $_GET['trip_driver']. " ";

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

print json_encode($emparray);

?>
