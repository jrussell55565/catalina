<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

$username = $_GET['username'];
$frequency = $_GET['frequency'];

switch($frequency)
{
    case "day":
      $date_dispatch_hwb = "STR_TO_DATE(hawbDate, '%c/%e/%Y') = CURDATE()";
      $date_dispatch_due = "STR_TO_DATE(dueDate, '%c/%e/%Y') = CURDATE()";
      $date_driver_export = "date = CURDATE()";
      break;
    case "week":
      $date_dispatch_hwb = "WEEK(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = WEEK(CURDATE())";
      $date_dispatch_due = "WEEK(STR_TO_DATE(dueDate, '%c/%e/%Y')) = WEEK(CURDATE())";
      $date_driver_export = "WEEK(date) = WEEK(CURDATE())";
      break;
    case "month":
      $date_dispatch_hwb = "MONTH(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = MONTH(CURDATE())";
      $date_dispatch_due = "MONTH(STR_TO_DATE(dueDate, '%c/%e/%Y')) = MONTH(CURDATE())";
      $date_driver_export = "MONTH(date) = MONTH(CURDATE())";
      break;
    case "quarter":
      $date_dispatch_hwb = "QUARTER(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = QUARTER(CURDATE())";
      $date_dispatch_due = "QUARTER(STR_TO_DATE(dueDate, '%c/%e/%Y')) = QUARTER(CURDATE())";
      $date_driver_export = "QUARTER(date) = QUARTER(CURDATE())";
      break;
    case "year":
      $date_dispatch_hwb = "YEAR(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = YEAR(CURDATE())";
      $date_dispatch_due = "YEAR(STR_TO_DATE(dueDate, '%c/%e/%Y')) = YEAR(CURDATE())";
      $date_driver_export = "YEAR(date) = YEAR(CURDATE())";
      break;
    case "all":
      $date_dispatch_hwb = "";
      $date_dispatch_due = "";
      $date_driver_export = "";
      break;
}

$sql = "select 
        _a.count as 'as_puagent',
        _b.count as 'as_delagent',
        _c.count as 'as_pu_and_delagent',
        _a.count + _b.count +  _c.count as 'sum_count',
        _a.count * 2 as 'puagent_required_updates',
        _b.count * 2 as 'delagent_required_updates',
        _c.count * 4 as 'puagent_and_delagent_required_updates',
        _d.count as 'core_updates_sum',
        _e.count as 'misc_updates_sum',
        _f.count as 'picked_up',
        _g.count as 'arrived_to_shipper',
        _h.count as 'delivered',
        _i.count as 'arrived_to_consignee',
        _j.count as 'accessorial_count'
FROM 
(
SELECT 
        COUNT(*) AS count
    FROM
        dispatch
    WHERE
        (puAgentDriverPhone = (SELECT 
                driverid
            FROM
                users
            WHERE
                username = \"$username\")
            
        ) AND
        (delAgentDriverPhone != (SELECT 
                driverid
            FROM
                users
            WHERE
                username = \"$username\")
            
        ) AND $date_dispatch_hwb
) _a,
(
SELECT 
        COUNT(*) AS count
    FROM
        dispatch
    WHERE
        (delAgentDriverPhone = (SELECT 
                driverid
            FROM
                users
            WHERE
                username = \"$username\")
        ) AND
        (puAgentDriverPhone != (SELECT 
                driverid
            FROM
                users
            WHERE
                username = \"$username\")
        ) AND $date_dispatch_due
) _b,
(
SELECT 
        COUNT(*) AS count
    FROM
        dispatch
    WHERE
        (delAgentDriverPhone = (SELECT 
                driverid
            FROM
                users
            WHERE
                username = \"$username\")
        ) AND
        (puAgentDriverPhone = (SELECT 
                driverid
            FROM
                users
            WHERE
                username = \"$username\")
        ) AND $date_dispatch_due
        AND $date_dispatch_hwb
) _c,
(
SELECT 
        COUNT(*) AS count
    FROM
        driverexport
    WHERE
    updated_by = (SELECT drivername from users where username = \"$username\")
    and status in ('Picked Up','Arrived to Shipper','Delivered','Arrived To Consignee')
    AND $date_driver_export
) _d,
(
SELECT 
        COUNT(*) AS count
    FROM
        driverexport
    WHERE
    updated_by = (SELECT drivername from users where username = \"$username\")
    and status not in ('Picked Up','Arrived to Shipper','Delivered','Arrived To Consignee')
    AND $date_driver_export
) _e,
(
SELECT 
        COUNT(*) AS count
    FROM
        driverexport
    WHERE
    updated_by = (SELECT drivername from users where username = \"$username\")
    and status = 'Picked Up'
    AND $date_driver_export
) _f,
(
SELECT 
        COUNT(*) AS count
    FROM
        driverexport
    WHERE
    updated_by = (SELECT drivername from users where username = \"$username\")
    and status = 'Arrived to Shipper'
    AND $date_driver_export
) _g,
(
SELECT 
        COUNT(*) AS count
    FROM
        driverexport
    WHERE
    updated_by = (SELECT drivername from users where username = \"$username\")
    and status = 'Delivered'
    AND $date_driver_export
) _h,
(
SELECT 
        COUNT(*) AS count
    FROM
        driverexport
    WHERE
    updated_by = (SELECT drivername from users where username = \"$username\")
    and status = 'Arrived To Consignee'
    AND $date_driver_export
) _i,
(
select count(*) as count 
    from driverexport WHERE    
        employee_id = (SELECT 
                employee_id
            FROM
                users
            WHERE
                username = \"$username\")
                AND accessorials <> status
                AND date = CURDATE()
) _j
";

try {
   if ($result = $mysqli->query($sql))
    {
        while ($row = $result->fetch_assoc()) {
            $emparray[] = $row;
        }
    }
        $result->close();
    }else{
        throw new Exception("Unable to retrieve drivers email for notification: ". $mysqli->error);
    }
  } catch (Exception $e) {
    // An exception has been thrown
    $data = array('type' => 'error', 'message' => $e->getMessage);
    header('HTTP/1.1 400 Bad Request');
    header('Content-Type: application/json; charset=UTF-8');
    $mysqli->close();
    echo json_encode($data);
    exit;
  }

print json_encode($emparray);
?>
