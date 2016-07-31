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
      $date_dispatch_hwb = "YEARWEEK(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = YEARWEEK(CURDATE())";
      $date_dispatch_due = "YEARWEEK(STR_TO_DATE(dueDate, '%c/%e/%Y')) = YEARWEEK(CURDATE())";
      $date_driver_export = "YEARWEEK(date) = YEARWEEK(CURDATE())";
      break;
    case "month":
      $date_dispatch_hwb = "YEAR(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = YEAR(CURDATE()) AND MONTH(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = MONTH(CURDATE())";
      $date_dispatch_due = "YEAR(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = YEAR(CURDATE()) AND MONTH(STR_TO_DATE(dueDate, '%c/%e/%Y')) = MONTH(CURDATE())";
      $date_driver_export = "YEAR(date) = YEAR(CURDATE()) AND MONTH(date) = MONTH(CURDATE())";
      break;
    case "quarter":
      $date_dispatch_hwb = "YEAR(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = YEAR(CURDATE()) AND QUARTER(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = QUARTER(CURDATE())";
      $date_dispatch_due = "YEAR(STR_TO_DATE(hawbDate, '%c/%e/%Y')) = YEAR(CURDATE()) AND QUARTER(STR_TO_DATE(dueDate, '%c/%e/%Y')) = QUARTER(CURDATE())";
      $date_driver_export = "YEAR(date) = YEAR(CURDATE()) AND QUARTER(date) = QUARTER(CURDATE())";
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

$sql = "SELECT 
    a.*,
    round(a.arrived_to_shipper_points + a.picked_up_points + a.arrived_to_consignee_points + a.delivered_points + a.accessorial_points,1) as 'earned_points',
    round(a.max_arrived_to_shipper_points + a.max_picked_up_points + a.max_arrived_to_consignee_points + a.max_delivered_points + a.max_accessorial_points,1) as 'max_points'
FROM
    (SELECT 
        _a.count AS 'as_puagent',
            _b.count AS 'as_delagent',
            _c.count AS 'as_pu_and_delagent',
            _a.count + _b.count + _c.count AS 'sum_count',
            _a.count * 2 AS 'puagent_required_updates',
            _b.count * 2 AS 'delagent_required_updates',
            _c.count * 4 AS 'puagent_and_delagent_required_updates',
            _d.count AS 'core_updates_sum',
            _e.count AS 'misc_updates_sum',
            _f.count AS 'picked_up',
            _g.count AS 'arrived_to_shipper',
            _h.count AS 'delivered',
            _i.count AS 'arrived_to_consignee',
            _j.count AS 'accessorial_count',
            (_cp_shipments.arrived_shipper_apoint * _g.count) * _cp_shipments.arrived_shipper_cpoint AS 'arrived_to_shipper_points',
            (_cp_shipments.arrived_shipper_apoint * (_a.count + _c.count)) * _cp_shipments.arrived_shipper_cpoint AS 'max_arrived_to_shipper_points',
            (_cp_shipments.picked_up_apoint * _f.count) * _cp_shipments.picked_up_cpoint AS 'picked_up_points',
            (_cp_shipments.picked_up_apoint *(_a.count + _c.count)) * _cp_shipments.picked_up_cpoint AS 'max_picked_up_points',
            (_cp_shipments.arrived_consignee_apoint * _i.count) * _cp_shipments.arrived_consignee_cpoint AS 'arrived_to_consignee_points',
            (_cp_shipments.arrived_consignee_apoint * (_b.count + _c.count)) * _cp_shipments.arrived_consignee_cpoint AS 'max_arrived_to_consignee_points',
            (_cp_shipments.delivered_apoint * _h.count) * _cp_shipments.delivered_cpoint AS 'delivered_points',
            (_cp_shipments.delivered_apoint * (_b.count + _c.count)) * _cp_shipments.delivered_cpoint AS 'max_delivered_points',
            (_cp_shipments.accessorials_apoint * _j.count) * _cp_shipments.accessorials_cpoint AS 'accessorial_points',
             _a.count + _b.count + _c.count AS 'max_accessorial_points'
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
                AND $date_driver_export
) _j,
(
select * from cp_shipments
) _cp_shipments
) a
";

try {
   if ($result = $mysqli->query($sql))
    {
        while ($row = $result->fetch_assoc()) {
            $emparray[] = $row;
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
//echo $sql; exit;
print json_encode($emparray);
?>
