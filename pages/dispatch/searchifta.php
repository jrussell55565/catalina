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

if (isset($_GET['trip_start']) && ($_GET['trip_start'] != '')) { 
    $_GET['trip_start'] = 'AND ifta.date_started = str_to_date(\'' . $_GET['trip_start'] . '\',\'%m/%d/%Y\')'; 
} else { 
    $_GET['trip_start'] = 'AND ifta.date_started < date(now())';
}

if (isset($_GET['trip_end']) && ($_GET['trip_end'] != '')) { 
    $_GET['trip_end'] = 'AND ifta.date_ended = str_to_date(\'' . $_GET['trip_end'] . '\',\'%m/%d/%Y\')'; 
} else { 
    $_GET['trip_end'] = 'AND ifta.date_ended < date(now())';
}

if (isset($_GET['trip_state']) && ($_GET['trip_state'] != '') && ($_GET['trip_state'] != 'Choose State...')) { 
    $_GET['trip_state'] = 'AND (ifta_details.st_exit = "' . $_GET['trip_state'] . '" OR ifta_details.st_enter = "'. $_GET['trip_state']. '")'; 
} else { 
    $_GET['trip_state'] = 'AND (ifta_details.st_exit like "%" OR ifta_details.st_enter like "%")';
}

if ($_GET['trip_permit'] == 'true') { 
    $_GET['trip_permit'] = 'AND ifta_details.permit_required = "Y"'; 
} else { 
    $_GET['trip_permit'] = 'AND ifta_details.permit_required = "N"';
}

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

$query = "SELECT concat(a.fname,' ',a.lname) AS driver1,
          concat(b.fname,' ',b.lname) AS driver2,
          ifta.trip_no,truck_no,
          date_format(date_started,'%m/%d/%Y') as date_started,
          date_format(date_ended,'%m/%d/%Y') as date_ended,
          st_exit,st_enter 
          FROM ifta, ifta_details, users a, users b
          WHERE ifta.trip_no = ifta_details.trip_no
          AND ifta.driver1 = a.employee_id
          AND ifta.driver2= b.employee_id "
. $_GET['trip_no'] . " "
. $_GET['trip_start']. " "
. $_GET['trip_end']. " "
. $_GET['trip_state']. " "
. $_GET['trip_permit']. " "
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
