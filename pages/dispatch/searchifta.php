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
    $_GET['trip_no'] = 'AND trip_no = "' . $_GET['trip_no'] . '"'; 
} else { 
    $_GET['trip_no'] = 'AND trip_no like "%"';
}

if (isset($_GET['trip_start']) && ($_GET['trip_start'] != '')) { 
    $_GET['trip_start'] = 'AND date_started = str_to_date(\'' . $_GET['trip_start'] . '\',\'%m/%d/%Y\')'; 
} else { 
    $_GET['trip_start'] = 'AND date_started < date(now())';
}

if (isset($_GET['trip_end']) && ($_GET['trip_end'] != '')) { 
    $_GET['trip_end'] = 'AND date_ended = str_to_date(\'' . $_GET['trip_end'] . '\',\'%m/%d/%Y\')'; 
} else { 
    $_GET['trip_end'] = 'AND date_ended < date(now())';
}

if (isset($_GET['trip_state']) && ($_GET['trip_state'] != '') && ($_GET['trip_state'] != 'Choose State...')) { 
    $_GET['trip_state'] = 'AND (st_exit = "' . $_GET['trip_state'] . '" OR st_enter = "'. $_GET['trip_state']. '")'; 
} else { 
    $_GET['trip_state'] = 'AND (st_exit like "%" OR st_enter like "%")';
}

if ($_GET['trip_permit'] == 'true') { 
    $_GET['trip_permit'] = 'AND trip_permit = "Y"'; 
} else { 
    $_GET['trip_permit'] = 'AND trip_permit = "N"';
}

if (isset($_GET['trip_truck_no']) && ($_GET['trip_truck_no'] != '')) { 
    $_GET['trip_truck_no'] = 'AND truck_no = "' . $_GET['trip_truck_no'] . '"'; 
} else { 
    $_GET['trip_truck_no'] = 'AND truck_no like "%"';
}

if (isset($_GET['trip_driver']) && $_GET['trip_driver'] != '' && $_GET['trip_driver'] != 'null') {
    $_GET['trip_driver'] = 'AND (driver1 = "' . $_GET['trip_driver'] . '" OR driver2 = "'. $_GET['trip_driver']. '")'; 
} else { 
    $_GET['trip_driver'] = 'AND (driver1 like "%" OR driver2 like "%")';
}

$statement = "SELECT * FROM ifta, ifta_details WHERE ifta.trip_no = ifta_details.trip_no "
. $_GET['trip_no'] . " "
. $_GET['trip_start']. " "
. $_GET['trip_end']. " "
. $_GET['trip_state']. " "
. $_GET['trip_permit']. " "
. $_GET['trip_truck_no']. " "
. $_GET['trip_driver']. " ";
print $statement;
?>
