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
    $statement       = "select fname, lname, employee_id, email, vtext, username, status
                        from users
                        order by fname";

    $counter = 0;
    if ($result = $mysqli->query($statement)) {
        while ($obj = $result->fetch_object()) {
            $all_users_array[$counter]['employee_id'] = $obj->employee_id;
            $all_users_array[$counter]['name']        = $obj->fname . " " . $obj->lname;
            $all_users_array[$counter]['email']       = $obj->email;
            $all_users_array[$counter]['vtext']       = $obj->vtext;
            $all_users_array[$counter]['username']    = $obj->username;
            $all_users_array[$counter]['status']      = $obj->status;
            $counter++;
        }
    }
    return $all_users_array;
}

function get_user_status($mysqli)
{
  $user_status = [];
  $statement = "select distinct status from users where status is not null";

  $counter = 0;
    if ($result = $mysqli->query($statement)) {
        while ($obj = $result->fetch_object()) {
            $user_status[$counter]['status'] = $obj->status;
            $counter++;
        }
    }
    return $user_status;
}

function get_aggregate_compliance($sd,$ed,$mysqli)
{
    $sql = "CALL compliance_productivity_stats('$sd', '$ed', TRUE);";
    $output = get_multi_sql_results($sql, $mysqli);
    return $output;
}

function generate_clockin_sql($emp_id, $sd, $ed)
{
    $sql = "select count(*) from days_worked
        where `DATE WORKED` between STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
        and `EMPLOYEE NUMBER` = '$emp_id'
        and worked = 1";
    return $sql;
}

function get_aggregate_vir($sd, $ed, $mysqli)
{
    $sql = "CALL vir_productivity_stats('$sd', '$ed', TRUE);";
    $output = get_multi_sql_results($sql, $mysqli);
    return $output;
}

function get_shipment_aggregate($sd, $ed, $mysqli)
{
  $sql = "CALL new_shipment_productivity_stats(STR_TO_DATE('$sd', '%Y-%m-%d'), STR_TO_DATE('$ed', '%Y-%m-%d'), TRUE);";  
  $output = get_multi_sql_results($sql, $mysqli);
  return $output;
}

function get_top_performers($sd,$ed,$mysqli)
{
    $sql = "CALL compliance_productivity_stats('$sd', '$ed', FALSE);";
    $sql .= "CALL new_shipment_productivity_stats(STR_TO_DATE('$sd', '%Y-%m-%d'), STR_TO_DATE('$ed', '%Y-%m-%d'), FALSE);";
    $sql .= "CALL vir_productivity_stats('$sd', '$ed', FALSE);";
    $sql .= "CALL task_productivity_stats('$sd', '$ed', FALSE);";
    $sql .= "CALL quiz_productivity_stats('$sd', '$ed', FALSE);";

    $sql .= "SELECT
            outer_results.employee_id,
            coalesce(outer_results.vir_total_points, 0) AS vir_total_points,
            outer_results.compliance_total_points,
            outer_results.shipment_earned_points,
            outer_results.shipment_percentage_earned,
            outer_results.task_activity_total_points,
            outer_results.task_total_percent,
            coalesce(outer_results.total_points, 0)     AS total_points,
            outer_results.total_percent,
            outer_results.real_name,
            outer_results.username,
            outer_results.status
          FROM (SELECT
                  all_results.*,
                  concat_ws(' ', users.fname, users.lname) AS real_name,
                  users.username,
                  users.status
                FROM (SELECT
                        a.*,
                        IF(a.vir_total_points + a.compliance_total_points + a.shipment_earned_points +
                           a.task_activity_total_points = 0, NULL,
                           a.vir_total_points + a.compliance_total_points + a.shipment_earned_points +
                           a.task_activity_total_points)                                                   AS total_points,
                        CASE WHEN a.vir_total_percent + a.shipment_percentage_earned + a.task_total_percent > 100
                          THEN 100
                        ELSE a.vir_total_percent + a.shipment_percentage_earned + a.task_total_percent END AS total_percent
                      FROM (SELECT
                              vir.employee_id,
                              coalesce(vir.vir_total_points, 0)                AS vir_total_points,
                              coalesce(vir_total_percent, 0)                   AS vir_total_percent,
                              coalesce(compliance.current_violation_points, 0) AS compliance_total_points,
                              coalesce(shipment.earned_points, 0)              AS shipment_earned_points,
                              coalesce(shipment.percentage_earned, 0)          AS shipment_percentage_earned,
                              coalesce(task.activity_total_points, 0)          AS task_activity_total_points,
                              coalesce(task.total_percent, 0)                  AS task_total_percent
                            FROM vir_productivity_tmp vir, compliance_productivity_tmp compliance,
                              shipment_productivity_tmp shipment, task_productivity_tmp task
                            WHERE vir.employee_id = compliance.employee_id AND vir.employee_id = shipment.employee_id AND
                                  vir.employee_id = task.employee_id) a) all_results
                  JOIN users ON all_results.employee_id = users.employee_id
                ORDER BY total_points DESC, real_name) outer_results;";

    $output = get_multi_sql_results($sql, $mysqli);
    return $output;
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

function get_multi_sql_results($sql, $mysqli)
{
    try {
          if ($mysqli->multi_query($sql)) {
            do {
            /* store first result set */
            if ($result = $mysqli->store_result()) {
              while ($row = $result->fetch_assoc()) {
                  $emparray[] = $row;
              }
              $result->free();
            }
            /* print divider */
            if ($mysqli->more_results()) {
              printf("<!-- ----------------- -->\n");
            }
          } while ($mysqli->next_result());
            return $emparray;
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
function get_task_aggregate($sd, $ed, $mysqli)
{
    $sql = "CALL task_productivity_stats('$sd', '$ed', TRUE);";
    $output = get_multi_sql_results($sql, $mysqli);
    return $output;
}

function get_task_nonaggregate($driver_predicate, $task_status_predicate) 
{
  $sql = "select tasks.id,
            date_format(tasks.submit_date,'%m/%d/%Y') as submit_date
            ,tasks.assign_to
            ,tasks.assigned_by
            ,tasks.category
            ,tasks.item
            ,tasks.subitem
            ,tasks.pos_neg
            ,date_format(tasks.due_date,'%m/%d/%Y') as due_date
            ,date_format(tasks.completed_date,'%m/%d/%Y') as completed_date
            ,tasks.points
            ,tasks.complete_user
            ,tasks.complete_approved
            ,tasks.internal_only
            ,concat_ws(' ', users.fname, users.lname) as real_name
            ,concat_ws(' ', u.fname, u.lname) as assigned_by
            ,users.username
           from tasks
        JOIN users on users.employee_id = tasks.assign_to
        JOIN users u on u.employee_id = tasks.assigned_by
          where 1=1 and  $driver_predicate and $task_status_predicate 
          order by submit_date, id asc";
  return $sql;
}

function get_quiz_aggregate($sd, $ed, $mysqli)
{
    $sql = "CALL quiz_productivity_stats('$sd', '$ed', TRUE);";
    $output = get_multi_sql_results($sql, $mysqli);
    return $output;
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
            throw new Exception("File is larger than the allowed ". $file_size . " bytes", 1);
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
function sort_array($my_array,$orderby){
  $sortArray = array(); 
  foreach($my_array as $i){ 
    foreach($i as $key=>$value){ 
        if(!isset($sortArray[$key])){ 
            $sortArray[$key] = array(); 
        } 
        $sortArray[$key][] = $value; 
    } 
  }
  $orderby = $orderby;
  array_multisort($sortArray[$orderby],SORT_DESC,$my_array);
  return $my_array;
}
// These two should be deprecated at some point
function generate_compliance_sql($emp_id,$time) {

    $predicates = generate_compliance_predicate($emp_id, $time);
    $predicate = $predicates[0];
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

function generate_compliance_predicate($emp_id,$time) {
    // Do some mangling if the $emp_id = 'all'.  This specific user is from the csa.php page.
    if ($emp_id == 'all') {
        $predicate = '1=1';
    }else{
        $predicate = "EMPLOYEE_ID='$emp_id'";
    }

   // Set some time ranges
   if (isset($time)) {
       if ($time == "24") {
           // We only want the last 24 months
           $time_predicate = "date BETWEEN curdate() - INTERVAL 24 MONTH AND curdate()";
       }elseif($time == "all") {
           // Get all time
           $time_predicate = "1=1";
       }elseif($time == "24+1") {
          // Get month 25 only
          $time_predicate = "date_format(date,'%Y-%m') = date_format(curdate() - INTERVAL 25 MONTH,'%Y-%m')";
       }
   }else{
       // Default to 24 months
       $time_predicate = "date BETWEEN curdate() - INTERVAL 24 MONTH AND curdate()";
   }

   return array ($predicate,$time_predicate);
}
?>
