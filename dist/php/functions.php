<?php 
function accessorials($accessorialType,$srcPage,$username)
{
   if ($accessorialType == 'Truck')
   {
      $ckPrefix = 'truck_';
   }elseif($accessorialType == 'Trailer'){
      $ckPrefix = 'trailer_';
   }

	$sql = mysql_query("select * FROM accessorials WHERE acc_type = \"$accessorialType\" ORDER BY revenue_charge");
        while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
        {
        	echo "<tr>\n";
                $input_type = '';
                $input_value = '';
                if (preg_match('/^ck_/',$row['input_type']))
                {
                	$visibility = '';
                	$input_type = "type=\"checkbox\" name=\"".$ckPrefix."ck_accessorials[]\" id=\"".$ckPrefix."ck_accessorials[]\" value=\"$row[revenue_charge]\" autocomplete=\"off\"";
                }elseif (preg_match('/^txt_/',$row['input_type'])){
                        $visibility = '';
                        $input_type = "type=\"text\" name=\"bx_accessorials[$row[revenue_charge]]\" id=\"bx_accessorials[$row[revenue_charge]]\"\" value=\"\"";
                }else{
			# If the input is hidden and the page matches then
			# we set the input type html.  Otherwise
			# we just skip this part.
			if ($row['src_page'] == $srcPage)
			{
                $visibility = 'hidden';
                $input_type = "type=\"checkbox\" name=\"ck_accessorials[]\" id=\"ck_accessorials[]\" value=\"$row[revenue_charge]\" checked";
			}else{
				$visibility = 'hidden';
				$input_type = "type=\"checkbox\" name=\"NULL\" id=\"NULL\"";
			}
                }
                echo "<td $visibility>\n";
                if (preg_match('/checkbox/', $input_type))
                {
                    echo "<div class=\"btn-group\" data-toggle=\"buttons\">";
                    echo "<label class=\"btn btn-primary btn-sm\" $colorOverride>";
                }
                echo "<input $input_type/>$row[revenue_charge]\n";
                if (preg_match('/checkbox/', $input_type))
                {
                    echo "</label>";
                    echo "</div>";
                }
		echo "</td>\n";
        	echo "</tr>\n";
	}
	# Send in a hidden field with username
	echo "<tr><td><input type=hidden name=username value=$username ></td></tr>\n";
}

function sendEmail($to, $subject, $body, $cc)
{
  $headers = "From: drivers@catalinacartage.com" . "\r\n" .
             'X-Mailer: PHP/' . phpversion() . "\r\n";
  if (isset($cc))
  {
    $headers .= "CC: $cc\r\n";
  }
	mail($to, $subject, $body, $headers);
}

function pageErrors($error)
{
    $array = array(
      "podName" => "POD Name must not be empty",
      "pieces" => "Pieces must be greater than zero",
    );

    return ($array["$error"]);
}

function rtrim_limit($str, $delim, $count = 0)
{
    if ($count == 0) return rtrim($str, $delim);

    $l = strlen($delim);
    $k = 0;

    while (substr($str, -$l) == $delim && ($count == 0 || ($count > 0 && $k++ < $count))) {
        $str = substr($str, 0, strlen($str) - $l);
    }

    return $str;
}

function get_drivers($mysqli) {
   # Get the driver names and employee_id
   $driver_array = [];
   $statement = "select * from
   (
   select fname, lname, employee_id from users where title = 'Driver'
   union
   select 'Unknown' as fname, 'Driver' as lname, 'null' as employee_id from DUAL
   union
   select 'Multiple' as fname, 'Drivers' as lname, 'null' as employee_id from DUAL
   ) a order by fname";

   $counter = 0;
   if ($result = $mysqli->query($statement)) {
     while($obj = $result->fetch_object()){
       $driver_array[$counter]['employee_id'] = $obj->employee_id;
       $driver_array[$counter]['name'] = $obj->fname. " ". $obj->lname;
       $counter++;
     }
   }
   return $driver_array;
}

function validate_vir($array) {
    foreach(array("vir_pretrip","vir_posttrip","vir_breakdown") as $val) {
        $found = 0;
        for ($i=0;$i<count($array);$i++) {
          if ($array[$i]['insp_type'] == "$val") {
              $found = 1;
              break;
          }
        }
      if ($found == 0) {
        $new_count = count($array);
        $array[$new_count]['insp_type'] = "$val";
        $array[$new_count]['count(*)'] = 0;
      }
    }
    return $array;
}

function generate_compliance_sql($emp_id) {
    $sql = "SELECT 'Total Company Points' AS basic, sum(total_points) AS total_points, sum(points_cash_value) AS points_cash_value FROM csadata
         WHERE DATE BETWEEN curdate() - INTERVAL 24 MONTH AND curdate()
         union
         select 'Total Points' as basic, SUM(total_points) as total_points, SUM(points_cash_value) as points_cash_value from CSADATA
         where EMPLOYEE_ID='$emp_id'
         and date between CURDATE() - interval 24 month and CURDATE()
         union
         select basic, SUM(total_points) as total_points, SUM(points_cash_value) as points_cash_value from CSADATA
         where basic in ('Vehicle Maint.','HOS Compliance','No Violation','Unsafe Driving','Driver Fitness','Controlled Substances/Alcohol','Hazardous Materials (HM)','Crash Indicator')
         and EMPLOYEE_ID='$emp_id'
         and date between CURDATE() - interval 24 month and CURDATE()
         group by basic";
    return $sql;
}

function generate_clockin_sql($emp_id,$sd,$ed) {
    $sql = "select count(*) from DAYS_WORKED
        where `DATE WORKED` between STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
        and `EMPLOYEE NUMBER` = '$emp_id'
        and worked = 1";
    return $sql;
}

function generate_vir_sql($emp_id,$sd,$ed) {
    $sql="select count(*),insp_type from VIRS WHERE
                employee_id ='$emp_id'
                and INSP_DATE between STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
                group by insp_type";
    return $sql;
}
function generate_ship_sql($emp_id,$sd,$ed) {
    $sql = "SELECT '$emp_id'    AS 'employee_id',
             b.*,
             Round((Coalesce(b.earned_points / b.max_points, 0) * 100), 1) AS 'percentage_earned'
      FROM   (
                    SELECT a.*,
                           Round(a.arrived_to_shipper_points     + a.picked_up_points + a.arrived_to_consignee_points + a.delivered_points + a.accessorial_points + a.noncore_points, 1)                     AS 'earned_points',
                           Round(a.max_arrived_to_shipper_points + a.max_picked_up_points + a.max_arrived_to_consignee_points + a.max_delivered_points + a.max_accessorial_points + a.max_noncore_points, 1) AS 'max_points'
                    FROM   (
                                  SELECT _a.count                                                                                                  AS 'as_puagent',
                                         _b.count                                                                                                  AS 'as_delagent',
                                         _c.count                                                                                                  AS 'as_pu_and_delagent',
                                         _a.count + _b.count + _c.count                                                                            AS 'sum_count',
                                         _a.count * 2                                                                                              AS 'puagent_required_updates',
                                         _b.count * 2                                                                                              AS 'delagent_required_updates',
                                         _c.count * 4                                                                                              AS 'puagent_and_delagent_required_updates',
                                         _d.count                                                                                                  AS 'core_updates_sum',
                                         _e.count                                                                                                  AS 'misc_updates_sum',
                                         _f.count                                                                                                  AS 'picked_up',
                                         _g.count                                                                                                  AS 'arrived_to_shipper',
                                         _h.count                                                                                                  AS 'delivered',
                                         _i.count                                                                                                  AS 'arrived_to_consignee',
                                         _j.count                                                                                                  AS 'accessorial_count',
                                         (_cp_shipments.arrived_shipper_apoint   * _g.count) * _cp_shipments.arrived_shipper_cpoint                AS 'arrived_to_shipper_points',
                                         (_cp_shipments.arrived_shipper_apoint   * (_a.count + _c.count)) * _cp_shipments.arrived_shipper_cpoint   AS 'max_arrived_to_shipper_points',
                                         (_cp_shipments.picked_up_apoint         * _f.count) * _cp_shipments.picked_up_cpoint                      AS 'picked_up_points',
                                         (_cp_shipments.picked_up_apoint         * (_a.count + _c.count)) * _cp_shipments.picked_up_cpoint         AS 'max_picked_up_points',
                                         (_cp_shipments.arrived_consignee_apoint * _i.count) * _cp_shipments.arrived_consignee_cpoint              AS 'arrived_to_consignee_points',
                                         (_cp_shipments.arrived_consignee_apoint * (_b.count + _c.count)) * _cp_shipments.arrived_consignee_cpoint AS 'max_arrived_to_consignee_points',
                                         (_cp_shipments.delivered_apoint         * _h.count) * _cp_shipments.delivered_cpoint                      AS 'delivered_points',
                                         (_cp_shipments.delivered_apoint         * (_b.count + _c.count)) * _cp_shipments.delivered_cpoint         AS 'max_delivered_points',
                                         (_cp_shipments.accessorials_apoint      * _j.count) * _cp_shipments.accessorials_cpoint                   AS 'accessorial_points',
                                         0                                                                                                         AS 'max_accessorial_points',
                                         (_cp_shipments.noncore_apoint * _e.count) * _cp_shipments.noncore_cpoint                                  AS 'noncore_points',
                                         0                                                                                                         AS 'max_noncore_points'
                                  FROM   (
                                                SELECT Count(*) AS count
                                                FROM   dispatch
                                                WHERE  (
                                                              puagentdriverphone =
                                                              (
                                                                     SELECT driverid
                                                                     FROM   users
                                                                     WHERE  username =
                                                                            (
                                                                                   SELECT username
                                                                                   FROM   users
                                                                                   WHERE  employee_id = '$emp_id')))
                                                AND    (
                                                              delagentdriverphone !=
                                                              (
                                                                     SELECT driverid
                                                                     FROM   users
                                                                     WHERE  username =
                                                                            (
                                                                                   SELECT username
                                                                                   FROM   users
                                                                                   where  EMPLOYEE_ID = '$emp_id')) )
                                                AND    Str_to_date(hawbdate, '%c/%e/%Y') BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _a,
                                         (
                                                SELECT Count(*) AS count
                                                FROM   dispatch
                                                WHERE  (
                                                              delagentdriverphone =
                                                              (
                                                                     SELECT driverid
                                                                     FROM   users
                                                                     WHERE  username =
                                                                            (
                                                                                   SELECT username
                                                                                   FROM   users
                                                                                   WHERE  employee_id = '$emp_id')))
                                                AND    (
                                                              puagentdriverphone !=
                                                              (
                                                                     SELECT driverid
                                                                     FROM   users
                                                                     WHERE  username =
                                                                            (
                                                                                   SELECT username
                                                                                   FROM   users
                                                                                   WHERE  employee_id = '$emp_id')))
                                                AND    Str_to_date(duedate, '%c/%e/%Y') BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _b,
                                         (
                                                SELECT Count(*) AS count
                                                FROM   dispatch
                                                WHERE  (
                                                              delagentdriverphone =
                                                              (
                                                                     SELECT driverid
                                                                     FROM   users
                                                                     WHERE  username =
                                                                            (
                                                                                   SELECT username
                                                                                   FROM   users
                                                                                   WHERE  employee_id = '$emp_id')))
                                                AND    (
                                                              puagentdriverphone =
                                                              (
                                                                     SELECT driverid
                                                                     FROM   users
                                                                     WHERE  username =
                                                                            (
                                                                                   SELECT username
                                                                                   FROM   users
                                                                                   WHERE  employee_id = '$emp_id')))
                                                AND    Str_to_date(hawbdate, '%c/%e/%Y') BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
                                  and    str_to_date(duedate, '%c/%e/%Y') BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _c,
                           (
                                  SELECT count(*) AS count
                                  FROM   driverexport
                                  WHERE  updated_by =
                                         (
                                                SELECT drivername
                                                FROM   users
                                                WHERE  username =
                                                       (
                                                              SELECT username
                                                              FROM   users
                                                              WHERE  employee_id = '$emp_id'))
                                  AND    status IN ('Picked Up' ,
                                                    'Arrived to Shipper',
                                                    'Delivered',
                                                    'Arrived To Consignee')
                                  AND    date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _d,
                           (
                                  SELECT count(*) AS count
                                  FROM   driverexport
                                  WHERE  updated_by =
                                         (
                                                SELECT drivername
                                                FROM   users
                                                WHERE  username =
                                                       (
                                                              SELECT username
                                                              FROM   users
                                                              WHERE  employee_id = '$emp_id'))
                                  AND    status NOT IN ('Picked Up' ,
                                                        'Arrived to Shipper',
                                                        'Delivered',
                                                        'Arrived To Consignee')
                                  AND    date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _e,
                           (
                                  SELECT count(*) AS count
                                  FROM   driverexport
                                  WHERE  updated_by =
                                         (
                                                SELECT drivername
                                                FROM   users
                                                WHERE  username =
                                                       (
                                                              SELECT username
                                                              FROM   users
                                                              WHERE  employee_id = '$emp_id'))
                                  AND    status = 'Picked Up'
                                  AND    date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _f,
                           (
                                  SELECT count(*) AS count
                                  FROM   driverexport
                                  WHERE  updated_by =
                                         (
                                                SELECT drivername
                                                FROM   users
                                                WHERE  username =
                                                       (
                                                              SELECT username
                                                              FROM   users
                                                              WHERE  employee_id = '$emp_id'))
                                  AND    status = 'Arrived to Shipper'
                                  AND    date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _g,
                           (
                                  SELECT count(*) AS count
                                  FROM   driverexport
                                  WHERE  updated_by =
                                         (
                                                SELECT drivername
                                                FROM   users
                                                WHERE  username =
                                                       (
                                                              SELECT username
                                                              FROM   users
                                                              WHERE  employee_id = '$emp_id'))
                                  AND    status = 'Delivered'
                                  AND    date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _h,
                           (
                                  SELECT count(*) AS count
                                  FROM   driverexport
                                  WHERE  updated_by =
                                         (
                                                SELECT drivername
                                                FROM   users
                                                WHERE  username =
                                                       (
                                                              SELECT username
                                                              FROM   users
                                                              WHERE  employee_id = '$emp_id'))
                                  AND    status = 'Arrived To Consignee'
                                  AND    date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _i,
                           (
                                  SELECT count(*) AS count
                                  FROM   driverexport
                                  WHERE  employee_id =
                                         (
                                                SELECT employee_id
                                                FROM   users
                                                WHERE  username =
                                                       (
                                                              SELECT username
                                                              FROM   users
                                                              WHERE  employee_id = '$emp_id'))
                                  AND    accessorials <> status
                                  AND    date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')) _j,
                           (
                                  select *
                                  FROM   cp_shipments) _cp_shipments) a) b";
    return $sql;
}
function get_sql_results($sql,$mysqli) {
       try {
          if ($result = $mysqli->query($sql))
           {
               while ($row = $result->fetch_assoc()) {
                   $emparray[] = $row;
               }
               $result->close();
           }else{
               throw new Exception("Query error: ". $mysqli->error);
           }
         } catch (Exception $e) {
           // An exception has been thrown
           $data = array('type' => 'error', 'message' => $e->getMessage());
           print $e->getMessage();
           $mysqli->close();
           exit;
         }
        return $emparray;
}
?>
