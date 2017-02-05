<?php
function accessorials($accessorialType, $srcPage, $username)
{
    if ($accessorialType == 'Truck') {
        $ckPrefix = 'truck_';
    } elseif ($accessorialType == 'Trailer') {
        $ckPrefix = 'trailer_';
    }

    $sql = mysql_query("select * FROM accessorials WHERE acc_type = \"$accessorialType\" ORDER BY revenue_charge");
    while ($row = mysql_fetch_array($sql, MYSQL_BOTH)) {
        echo "<tr>\n";
        $input_type  = '';
        $input_value = '';
        if (preg_match('/^ck_/', $row['input_type'])) {
            $visibility = '';
            $input_type = "type=\"checkbox\" name=\"" . $ckPrefix . "ck_accessorials[]\" id=\"" . $ckPrefix . "ck_accessorials[]\" value=\"$row[revenue_charge]\" autocomplete=\"off\"";
        } elseif (preg_match('/^txt_/', $row['input_type'])) {
            $visibility = '';
            $input_type = "type=\"text\" name=\"bx_accessorials[$row[revenue_charge]]\" id=\"bx_accessorials[$row[revenue_charge]]\"\" value=\"\"";
        } else {
            # If the input is hidden and the page matches then
            # we set the input type html.  Otherwise
            # we just skip this part.
            if ($row['src_page'] == $srcPage) {
                $visibility = 'hidden';
                $input_type = "type=\"checkbox\" name=\"ck_accessorials[]\" id=\"ck_accessorials[]\" value=\"$row[revenue_charge]\" checked";
            } else {
                $visibility = 'hidden';
                $input_type = "type=\"checkbox\" name=\"NULL\" id=\"NULL\"";
            }
        }
        echo "<td $visibility>\n";
        if (preg_match('/checkbox/', $input_type)) {
            echo "<div class=\"btn-group\" data-toggle=\"buttons\">";
            echo "<label class=\"btn btn-primary btn-sm\">";
        }
        echo "<input $input_type/>$row[revenue_charge]\n";
        if (preg_match('/checkbox/', $input_type)) {
            echo "</label>";
            echo "</div>";
        }
        echo "</td>\n";
        echo "</tr>\n";
    }
    # Send in a hidden field with username
    echo "<tr><td><input type=hidden name=username value=$username ></td></tr>\n";
}

function sendEmail($to, $subject, $body, $cc, $from, $bcc)
{
    if ($from === null) {
        $from = 'drivers@catalinacartage.com';
    }
    $headers = "From: $from" . "\r\n" .
    'X-Mailer: PHP/' . phpversion() . "\r\n";
    if (isset($cc)) {
        $headers .= "CC: $cc\r\n";
    }
    if (isset($bcc)) {
        $headers .= "BCC: $bcc\r\n";
    }
    mail($to, $subject, $body, $headers);
}

function pageErrors($error)
{
    $array = array(
        "podName" => "POD Name must not be empty",
        "pieces"  => "Pieces must be greater than zero",
    );

    return ($array["$error"]);
}

function rtrim_limit($str, $delim, $count = 0)
{
    if ($count == 0) {
        return rtrim($str, $delim);
    }

    $l = strlen($delim);
    $k = 0;

    while (substr($str, -$l) == $delim && ($count == 0 || ($count > 0 && $k++ < $count))) {
        $str = substr($str, 0, strlen($str) - $l);
    }

    return $str;
}

function get_drivers($mysqli)
{
    # Get the driver names and employee_id
    $driver_array = [];
    $statement    = "select * from
   (
   select fname, lname, employee_id, email from users where title = 'Driver' AND status in ('Active','Disabled')
   union
   select 'Unknown' as fname, 'Driver' as lname, 'unknown' as employee_id, 'unknown' as email from DUAL
   union
   select 'Multiple' as fname, 'Drivers' as lname, 'multiple' as employee_id, 'multiple' as email from DUAL
   ) a order by fname";

    $counter = 0;
    if ($result = $mysqli->query($statement)) {
        while ($obj = $result->fetch_object()) {
            $driver_array[$counter]['employee_id'] = $obj->employee_id;
            $driver_array[$counter]['name']        = $obj->fname . " " . $obj->lname;
            $driver_array[$counter]['email']       = $obj->email;
            $counter++;
        }
    }
    return $driver_array;
}

function get_all_users($mysqli)
{
    $all_users_array = [];
    $statement       = "select fname, lname, employee_id, email, vtext from users
                 where status in ('Active') order by fname";

    $counter = 0;
    if ($result = $mysqli->query($statement)) {
        while ($obj = $result->fetch_object()) {
            $all_users_array[$counter]['employee_id'] = $obj->employee_id;
            $all_users_array[$counter]['name']        = $obj->fname . " " . $obj->lname;
            $all_users_array[$counter]['email']       = $obj->email;
            $all_users_array[$counter]['vtext']       = $obj->vtext;
            $counter++;
        }
    }
    return $all_users_array;
}

function generate_compliance_sql($emp_id, $time)
{

    $predicates     = generate_compliance_predicate($emp_id, $time);
    $predicate      = $predicates[0];
    $time_predicate = $predicates[1];

    $sql = "SELECT 'Total Company Points' AS basic, sum(total_points) AS total_points, sum(points_cash_value) AS points_cash_value FROM csadata
         WHERE $time_predicate
         union
         select 'Total Points' as basic, SUM(total_points) as total_points, SUM(points_cash_value) as points_cash_value from csadata
         where $predicate
         and $time_predicate
         union
         select basic, SUM(total_points) as total_points, SUM(points_cash_value) as points_cash_value from csadata
         where $predicate
         and basic in ('Vehicle Maint.','HOS Compliance','No Violation','Unsafe Driving','Driver Fitness','Controlled Substances/Alcohol','Hazardous Materials (HM)','Crash Indicator')
         and $time_predicate
         group by basic";
    return $sql;
}

function generate_compliance_predicate($emp_id, $time)
{
    // Do some mangling if the $emp_id = 'all'.  This specific user is from the csa.php page.
    if ($emp_id == 'all') {
        $predicate = '1=1';
    } else {
        $predicate = "EMPLOYEE_ID='$emp_id'";
    }

    // Set some time ranges
    if (isset($time)) {
        if ($time == "24") {
            // We only want the last 24 months
            $time_predicate = "date BETWEEN curdate() - INTERVAL 24 MONTH AND curdate()";
        } elseif ($time == "all") {
            // Get all time
            $time_predicate = "1=1";
        } elseif ($time == "24+1") {
            // Get month 25 only
            $time_predicate = "date_format(date,'%Y-%m') = date_format(curdate() - INTERVAL 25 MONTH,'%Y-%m')";
        }
    } else {
        // Default to 24 months
        $time_predicate = "date BETWEEN curdate() - INTERVAL 24 MONTH AND curdate()";
    }

    return array($predicate, $time_predicate);
}
function generate_clockin_sql($emp_id, $sd, $ed)
{
    $sql = "select count(*) from days_worked
        where `DATE WORKED` between STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
        and `EMPLOYEE NUMBER` = '$emp_id'
        and worked = 1";
    return $sql;
}

function generate_vir_sql($sd, $ed)
{
    $sql = "select virs.employee_id, virs.vir_pretrip, virs.vir_posttrip, virs.vir_breakdown, worked.days_worked,
            coalesce(round((virs.vir_pretrip / worked.days_worked) * 100,0),0) as vir_pretrip_percent,
            coalesce(round((virs.vir_posttrip / worked.days_worked) * 100,0),0) as vir_posttrip_percent,
            coalesce(round((virs.vir_breakdown / worked.days_worked) * 100,0),0) as vir_breakdown_percent,
            coalesce(round(((virs.vir_pretrip + virs.vir_posttrip) / (worked.days_worked * 2)) * 100,0),0) as vir_total_percent,
            users.username,
            users.status,
            concat_ws(' ',users.fname,users.lname) as real_name
            from
            (
            select
            virs.employee_id,
            sum(case when virs.insp_type = 'vir_pretrip' then 1 else 0 end) as vir_pretrip,
            sum(case when virs.insp_type = 'vir_posttrip' then 1 else 0 end) as vir_posttrip,
            sum(case when virs.insp_type = 'vir_breakdown' then 1 else 0 end) as vir_breakdown
            from virs where insp_date between str_to_date('$sd','%Y-%m-%d') and str_to_date('$ed','%Y-%m-%d')
            group by employee_id
            ) virs ,
            (
            select count(*) as days_worked,`employee number` from days_worked where worked = 1 and `date worked` between str_to_date('$sd','%Y-%m-%d') and str_to_date('$ed','%Y-%m-%d')
            group by `employee number`
            ) worked ,
            (
            select username,employee_id,status,fname,lname from users
            ) users
            where virs.employee_id = worked.`employee number`
            and virs.employee_id = users.employee_id
            and users.status = 'Active'
            order by vir_total_percent desc";
    return $sql;
}
function generate_ship_sql($emp_id, $sd, $ed)
{
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
function get_sql_results($sql, $mysqli)
{
    try {
        if ($result = $mysqli->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $emparray[] = $row;
            }
            $result->close();
        } else {
            throw new Exception("Query error: " . $mysqli->error);
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

function run_sql($sql, $mysqli)
{
    try {
        if ($result = $mysqli->query($sql)) {
            return;
        } else {
            throw new Exception("Query error: " . $mysqli->error);
        }
    } catch (Exception $e) {
        // An exception has been thrown
        $data = array('type' => 'error', 'message' => $e->getMessage());
        print $e->getMessage();
        $mysqli->close();
        exit;
    }
}
function generate_user_csa_sql($emp_id, $time, $basic)
{
    // If the BASIC type wasn't specified then get them all
    if (empty($basic)) {
        $basic_predicate = "1=1";
    } else {
        $basic_predicate = "basic = '$basic'";
    }
    $sql = "SELECT date,
        basic,
        violation_group,
        code,
        violation_weight,
        time_weight,description,
        co_driver_first_name,
        co_driver_last_name,
        total_points,
        CONCAT_WS(' ',first_name,last_name) as name,
        points_cash_value
        from csadata where $emp_id
        and $time and $basic_predicate";
    return $sql;
}
function generate_task_sql($sd, $ed)
{
    $sql = "select assign_to,sum(number_of_tasks) tasks ,sum(number_of_points) points from
       (
       select assign_to,count(*) as number_of_tasks, 0 as number_of_points from tasks
       where submit_date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
       and complete_approved = 0
       group by assign_to
       union
       select assign_to, 0 as number_of_tasks, sum(points) as number_of_points from tasks
       where submit_date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
       and complete_approved = 0
       group by assign_to
       union
       select employee_id as assign_to, 0 as number_of_tasks, 0 as number_of_points from users
       )a group by assign_to";
    return $sql;
}
function generate_quiz_sql($sd, $ed)
{
    $sql = "SELECT cu.username,cu.employee_id,q.user_id,q.assignment_id,MAX(q.pass_score_point) AS max_score
      FROM assignments.user_quizzes q, catalina.users cu, assignments.users au
      WHERE cu.employee_id = au.comments
      AND au.userid = q.user_id
      AND q.added_date BETWEEN str_to_date('$sd','%Y-%m-%d') AND str_to_date('$ed','%Y-%m-%d')
      GROUP BY username, employee_id, user_id, assignment_id
      ORDER BY max_score DESC";
    return $sql;
}
function upload_image($input_file, $target_dir, $target_name, $return_page, $sql, $file_size, $mysqli)
{
    // Check that the input variables are set
    if (! (isset($input_file) || isset($target_dir) || isset($target_name) || isset($return_page) || isset($sql) || isset($file_size) || isset($mysqli))) {
      error_log('Required input variables are not set!');
      return;
    }

    # Image Uploads
    if (!empty($input_file["name"])) {
        # File upload logic
        $target_dir  = $_SERVER['DOCUMENT_ROOT'] . $target_dir;
        $target_file = $target_dir . $target_name;
        $uploadOk    = 0;
        $mysqli->autocommit(FALSE);
        $mysqli->begin_transaction(MYSQLI_TRANS_START_READ_WRITE);

        try {
          // Check if image file is a actual image or fake image
          if (! getimagesize($input_file["tmp_name"])) {
             throw new Exception("Unable to determine if this upload is an image.", 1);   
          }

          // Check file size
          if ($input_file["size"] > $file_size) {
            throw new Exception("File is larger than the allowed ". $file_size, 1);
          }

          // Allow certain file formats
          if (exif_imagetype($input_file["tmp_name"]) != IMAGETYPE_GIF
              && exif_imagetype($input_file["tmp_name"]) != IMAGETYPE_JPEG
              && exif_imagetype($input_file["tmp_name"]) != IMAGETYPE_PNG
          ) {
              throw new Exception("Uploaded file is not an allowed type (GIF, JPEG, PNG)", 1);
          }

          
          # resize the image
          $resizedImage = new Imagick($input_file["tmp_name"]);
          $resizedImage->resizeImage(160, 0, Imagick::FILTER_LANCZOS, 1);
          $resizedImage->writeImage($input_file["tmp_name"]);
          $resizedImage->destroy();
          if (!move_uploaded_file($input_file["tmp_name"], $target_file)) {
              throw new Exception("Unable to move the file into ". $target_file, 1);
          }

          if ($mysqli->query($sql) === false)
          {
              throw new Exception($mysqli->error);
          }        

        }catch (Exception $e){
         // An exception has been thrown
            // We must rollback the transaction
            error_log($e->getMessage());
            $url_error = urlencode($e->getMessage());
            $mysqli->rollback();
            http_response_code(500);
            header("location: ".$return_page."?return=false&error=".$url_error);
            $mysqli->autocommit(TRUE);
            $mysqli->close();
            exit;
        }
        $mysqli->commit();
    }
}
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
