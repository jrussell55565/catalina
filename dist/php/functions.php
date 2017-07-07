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

function generate_aggregate_compliance_sql($sd,$ed)
{
    $sql = "select
  total_points
  ,(points_cash_value * cp_csa.cash_apoint) * cp_csa.cash_cpoint as points_cash_value
  ,(vehicle_maint_points * cp_csa.vehicle_maint_apoint) * cp_csa.vehicle_maint_cpoint as vehicle_maint_points
  ,vehicle_maint_cash
  ,(hos_compliance_points * cp_csa.hos_compliance_apoint) * cp_csa.hos_compliance_cpoint as hos_compliance_points
  ,hos_compliance_cash
  ,(no_violation_points * cp_csa.no_violation_apoint) * cp_csa.no_violation_cpoint as no_violation_points
  ,no_violation_cash
  ,(unsafe_driving_points * cp_csa.unsafe_driving_apoint) * cp_csa.unsafe_driving_cpoint as unsafe_driving_points
  ,unsafe_driving_cash
  ,(driver_fitness_points * cp_csa.driver_fitness_apoint) * cp_csa.driver_fitness_cpoint as driver_fitness_points
  ,driver_fitness_cash
  ,(controlled_sub_points * cp_csa.controlled_substances_apoint) * cp_csa.controlled_substances_cpoint as controlled_sub_points
  ,controlled_sub_cash
  ,(hazard_points * cp_csa.hazmat_compliance_apoint) * cp_csa.hazmat_compliance_cpoint as hazard_points
  ,hazard_cash
  ,(crash_points * cp_csa.crash_indicator_apoint) * crash_indicator_cpoint as crash_points
  ,crash_cash
  , employee_id
  ,real_name
  FROM
  (
  select coalesce(total_points,0) as total_points
  ,coalesce(points_cash_value,0) as points_cash_value
  ,coalesce(vehicle_maint_points,0) as vehicle_maint_points
  ,coalesce(vehicle_maint_cash,0) as vehicle_maint_cash
  ,coalesce(hos_compliance_points,0) as hos_compliance_points
  ,coalesce(hos_compliance_cash,0) AS hos_compliance_cash
  ,coalesce(no_violation_points,0) as no_violation_points
  ,coalesce(no_violation_cash,0) as no_violation_cash
  ,coalesce(unsafe_driving_points,0) as unsafe_driving_points
  ,coalesce(unsafe_driving_cash,0) as unsafe_driving_cash
  ,coalesce(driver_fitness_points,0) as driver_fitness_points
  ,coalesce(driver_fitness_cash,0) as driver_fitness_cash
  ,coalesce(controlled_sub_points,0) as controlled_sub_points
  ,coalesce(controlled_sub_cash,0) as controlled_sub_cash
  ,coalesce(hazard_points,0) as hazard_points
  ,coalesce(hazard_cash,0) as hazard_cash
  ,coalesce(crash_points,0) as crash_points
  ,coalesce(crash_cash,0) as crash_cash
  , users.employee_id
  ,concat_ws(' ',users.fname,users.lname) as real_name
   FROM
  (
select
  SUM(total_points)      AS total_points
  , SUM(points_cash_value) AS points_cash_value
  ,  sum(case when basic = 'Vehicle Maint.' then total_points else 0 end) as vehicle_maint_points
  ,  sum(case when basic = 'Vehicle Maint.' then points_cash_value else 0 end) as vehicle_maint_cash
  ,  sum(case when basic = 'HOS Compliance' then total_points else 0 end) as hos_compliance_points
  ,  sum(case when basic = 'HOS Compliance' then points_cash_value else 0 end) as hos_compliance_cash
  ,  sum(case when basic = 'No Violation' then total_points else 0 end) as no_violation_points
  ,  sum(case when basic = 'No Violation' then points_cash_value else 0 end) as no_violation_cash
  ,  sum(case when basic = 'Unsafe Driving' then total_points else 0 end) as unsafe_driving_points
  ,  sum(case when basic = 'Unsafe Driving' then points_cash_value else 0 end) as unsafe_driving_cash
  ,  sum(case when basic = 'Driver Fitness' then total_points else 0 end) as driver_fitness_points
  ,  sum(case when basic = 'Driver Fitness' then points_cash_value else 0 end) as driver_fitness_cash
  ,  sum(case when basic = 'Controlled Substances' then total_points else 0 end) as controlled_sub_points
  ,  sum(case when basic = 'Controlled Substances' then points_cash_value else 0 end) as controlled_sub_cash
  ,  sum(case when basic = 'Hazmat Compliance' then total_points else 0 end) as hazard_points
  ,  sum(case when basic = 'Hazmat Compliance' then points_cash_value else 0 end) as hazard_cash
  ,  sum(case when basic = 'Crash Indicator' then total_points else 0 end) as crash_points
  ,  sum(case when basic = 'Crash Indicator' then points_cash_value else 0 end) as crash_cash
, employee_id
from csadata
where date BETWEEN str_to_date('$sd','%Y-%m-%d') and str_to_date('$ed','%Y-%m-%d')
  and basic in ('Vehicle Maint.','HOS Compliance','No Violation','Unsafe Driving','Driver Fitness','Controlled Substances','Hazmat Compliance','Crash Indicator')
group by employee_id ) csa
RIGHT JOIN users on users.employee_id = csa.employee_id) whole_shebang,
  (select * from cp_csa) cp_csa";
  
    return $sql;
}

function generate_clockin_sql($emp_id, $sd, $ed)
{
    $sql = "select count(*) from days_worked
        where `DATE WORKED` between STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
        and `EMPLOYEE NUMBER` = '$emp_id'
        and worked = 1";
    return $sql;
}

function generate_vir_sql($sd, $ed, $account_status)
{
    if ($account_status == 'none') {
      $predicate = '1 = 1';
    }else{
      $predicate = "users.status = '$account_status'";
    }

    $sql = "select mo_details.*, u.employee_id, concat_ws(' ',u.fname,u.lname) as real_name,
            coalesce(round((vir_total_points / max_total_vir_points) * 100),0) as vir_total_percent
            from        (
            select *,
            (CASE WHEN vir_pretrip_points > days_worked then days_worked else vir_pretrip_points END) +
              (CASE WHEN vir_posttrip_points > days_worked then days_worked else vir_posttrip_points END) +
              (CASE WHEN vir_breakdown > days_worked then days_worked else vir_breakdown END)  AS vir_total_points
            FROM (
            select virs.employee_id, virs.vir_pretrip, virs.vir_posttrip, virs.vir_breakdown, worked.days_worked,vir_additional_trailer,
            days_worked * 2 AS max_total_vir_points,
            coalesce(round((virs.vir_pretrip / worked.days_worked) * 100,0),0) as vir_pretrip_percent,
            coalesce(round((virs.vir_posttrip / worked.days_worked) * 100,0),0) as vir_posttrip_percent,
            coalesce(round((virs.vir_breakdown / worked.days_worked) * 100,0),0) as vir_breakdown_percent,
            users.username,
            users.status,
            concat_ws(' ',users.fname,users.lname) as real_name,
            round(miles,0) as miles,
            coalesce((virs.vir_pretrip * cp_virs.pre_trip_apoint) * cp_virs.pre_trip_cpoint,0) as vir_pretrip_points,
            coalesce((virs.vir_posttrip * cp_virs.post_trip_apoint) * cp_virs.post_trip_cpoint,0) as vir_posttrip_points,
            coalesce((virs.vir_additional_trailer * cp_virs.add_trailer_insp_apoint) * cp_virs.add_trailer_insp_cpoint,0) as vir_additional_trailer_points
            from
            (
            select
            virs.employee_id,
            coalesce(sum(case when virs.insp_type = 'vir_pretrip' then 1 else 0 end),0) as vir_pretrip,
            coalesce(sum(case when virs.insp_type = 'vir_posttrip' then 1 else 0 end),0) as vir_posttrip,
            coalesce(sum(case when virs.insp_type = 'vir_breakdown' then 1 else 0 end),0) as vir_breakdown,
            coalesce(sum(CASE WHEN virs.trucktype = 'trailer'  THEN 1 else 0 end),0) as vir_additional_trailer
            from virs where insp_date between str_to_date('$sd','%Y-%m-%d') and str_to_date('$ed','%Y-%m-%d')
            group by employee_id
            ) virs ,
            (
            select count(*) as days_worked,`employee number` from days_worked where worked = 1 and `date worked` between str_to_date('$sd','%Y-%m-%d') and str_to_date('$ed','%Y-%m-%d')
            group by `employee number`
            ) worked ,
            (
            select username,employee_id,status,fname,lname from users
            ) users,
            (SELECT
             sum(miles) as miles, employee_id
             FROM import_gps_trips
             GROUP BY employee_id) import_gps_trips,
             (select * from cp_virs) cp_virs
            where virs.employee_id = worked.`employee number`
            and virs.employee_id = users.employee_id
            and $predicate
            AND import_gps_trips.employee_id = users.employee_id ) details) mo_details
          right OUTER JOIN users u on u.employee_id = mo_details.employee_id";
    return $sql;
}

function get_shipment_aggregate($sd, $ed, $emp_id, $emp_status, $mysqli)
{
  // Override for emp_id
  $emp_id = 'none';
  if ($emp_id == 'none') {
    if ($emp_status == 'none') {

      $sql = "CALL new_shipment_productivity_stats(STR_TO_DATE('$sd', '%Y-%m-%d'), STR_TO_DATE('$ed', '%Y-%m-%d'),'NULL','NULL');";  
    }else{
      $sql = "CALL new_shipment_productivity_stats(STR_TO_DATE('$sd', '%Y-%m-%d'), STR_TO_DATE('$ed', '%Y-%m-%d'),'NULL','$emp_status');";
    } 
  }else{   
    $sql = "CALL new_shipment_productivity_stats(STR_TO_DATE('$sd', '%Y-%m-%d'), STR_TO_DATE('$ed', '%Y-%m-%d'),'$emp_id','NULL');";
  }
  $sql .= "select * from shipment_productivity_tmp";

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
function generate_task_sql($sd, $ed)
{
    $sql = "select mo_data.*
    , coalesce(round((activity_total_points / activity_max_points) * 100,0),0) as total_percent
    FROM
      (
    select a.*, users.employee_id ,
coalesce(
  days_worked_points + miles_points + task_points + quiz_points + idle_time_points,0) as activity_total_points,
 (
    tasks_all_user + days_shoulda_worked + all_quizzes
  ) as activity_max_points
  ,concat_ws(' ',users.fname,users.lname) as real_name
FROM (
SELECT
  whole_shebang.*
  , coalesce((days_worked * cp_activity.daysworked_apoint) * cp_activity.daysworked_cpoint,0)   AS days_worked_points
  , round(coalesce((miles * cp_activity.miles_apoint) * cp_activity.miles_cpoint,0),0)          AS miles_points
  , coalesce((tasks_completed_by_user * cp_activity.tasks_apoint) * cp_activity.tasks_cpoint,0) AS task_points
  , coalesce((passed_quizzes * cp_activity.quiz_apoint) * cp_activity.quiz_cpoint,0)            AS quiz_points
  , coalesce((idle_time * cp_activity.idle_apoint) * cp_activity.idle_cpoint,0)            AS idle_time_points
  , round(TIMESTAMPDIFF(DAY,str_to_date('$sd','%Y-%m-%d'),str_to_date('$ed','%Y-%m-%d')) * .675,0) as days_shoulda_worked
FROM (
       SELECT
         tasks.employee_id as emp_id
         , tasks_completed_by_user
         , tasks_all_user
         , category
         , coalesce(passed_quizzes,0) as passed_quizzes
         , coalesce(all_quizzes,0) as all_quizzes
         , days_worked
         , round(miles,0) AS miles
         , idle_time
         , aprox_idle_costs
       FROM ((
               SELECT
                   assign_to                  AS employee_id
                 , sum(CASE WHEN tasks.complete_user = 1
                 THEN 1
                       ELSE 0 END)            AS tasks_completed_by_user
                 , count(tasks.complete_user) AS tasks_all_user
                 , category
               FROM tasks
               WHERE submit_date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
               GROUP BY assign_to) tasks LEFT OUTER JOIN (
                                                           SELECT
                                                             employee_id
                                                             , sum(CASE WHEN success = 1
                                                             THEN 1
                                                                   ELSE 0 END) AS passed_quizzes
                                                             , count(success)  AS all_quizzes
                                                           FROM assignments.user_quizzes uq
                                                             JOIN assignments.v_imported_users viu
                                                               ON uq.user_id = viu.UserID
                                                             JOIN catalina.users ON users.username = viu.UserName
                                                           WHERE uq.added_date BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
                                                           GROUP BY employee_id) quiz
           ON quiz.employee_id = tasks.employee_id
         LEFT OUTER JOIN (
                           SELECT
                               COUNT(*)          AS days_worked
                             , `employee number` AS employee_id
                           FROM days_worked
                           WHERE worked = 1 AND
                                 `date worked` BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
                           GROUP BY `employee number`) worked ON worked.employee_id = tasks.employee_id
         LEFT OUTER JOIN (
                           SELECT
                              sum(miles)       AS miles
                             , employee_id
                             ,sum(`Idle Time`) AS idle_time
                           FROM import_gps_trips
                           WHERE (
                             began BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d')
                             AND Ended BETWEEN STR_TO_DATE('$sd','%Y-%m-%d') and STR_TO_DATE('$ed','%Y-%m-%d'))
                           GROUP BY employee_id) miles ON miles.employee_id = tasks.employee_id) JOIN (
                                                                                                        SELECT
                                                                                                          *
                                                                                                        FROM
                                                                                                          idle_calcs) idle_cals
           ON (idle_time / 60) BETWEEN idle_cals.idle_from_hrs AND idle_cals.idle_to_hrs) whole_shebang,(
                                                                                                          SELECT
                                                                                                            *
                                                                                                          FROM
                                                                                                            cp_activity) cp_activity ) a
          right outer join users on users.employee_id = a.emp_id ) mo_data";
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
