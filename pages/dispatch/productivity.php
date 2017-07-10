<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
$mysqli = new mysqli($db_hostname, $db_username, $db_password, $db_name);

# Get the driver names and employee_id
$driver_array = get_drivers($mysqli);
# Get all users
$all_users_array = get_all_users($mysqli);
$user_status_array = get_user_status($mysqli);

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];

// Get the stats user
if ($_SESSION['login'] == 1) {
  if ($_GET['trip_search_driver'] == 'none' || !isset($_GET['trip_search_driver'])) {
    $stats_user = 'ALL';
  }else{
    foreach ($all_users_array as $key => $value) {
      if ($all_users_array[$key]['employee_id'] == $_GET['trip_search_driver']) {
        $stats_user = $all_users_array[$key]['name'];
      }
    }
  }
}else{
  $stats_user = $_SESSION['employee_id'];
}

// Get the user image
if ($_SESSION['login'] == 1) {
  if ($_GET['trip_search_driver'] == 'none' || !isset($_GET['trip_search_driver'])) {
    $stats_image = HTTP."/pages/dispatch/images/allusers.JPG";
  }else{
    $stats_image = HTTP."/dist/img/usernophoto.jpg";
    foreach ($all_users_array as $key => $value) {      
      if ($all_users_array[$key]['employee_id'] == $_GET['trip_search_driver']) {        
        if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar")) {          
          $stats_image = HTTP."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar";          
        }
      }
    }
  }
}else{
  $stats_image = HTTP."/dist/img/usernophoto.jpg";
  if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) {
    $stats_image = HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";
  }
}

// Set the default status
if (!isset($_GET['productivity_user_status'])) {
  $_GET['productivity_user_status'] = 'Active';
}

// Query the cp_csa table to figure out the values we need for the compliance data
$cp_csa_values = get_sql_results("select * from cp_csa",$mysqli);

// Get the current quarter if no date range was specified.
$current_month = date('m');
$current_year = date('Y');
if($current_month>=1 && $current_month<=3)
{
  $default_start_date = strtotime('1-January-'.$current_year);  // timestamp or 1-Januray 12:00:00 AM
  $default_end_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM means end of 31 March
}
else  if($current_month>=4 && $current_month<=6)
{
  $default_start_date = strtotime('1-April-'.$current_year);  // timestamp or 1-April 12:00:00 AM
  $default_end_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM means end of 30 June
}
else  if($current_month>=7 && $current_month<=9)
{
  $default_start_date = strtotime('1-July-'.$current_year);  // timestamp or 1-July 12:00:00 AM
  $default_end_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM means end of 30 September
}
else  if($current_month>=10 && $current_month<=12)
{
  $default_start_date = strtotime('1-October-'.$current_year);  // timestamp or 1-October 12:00:00 AM
  $default_end_date = strtotime('1-January-'.($current_year+1));  // timestamp or 1-January Next year 12:00:00 AM means end of 31 December this year
}
if (empty($_GET['start'])) { $start_date = $default_start_date; }else{ $start_date = strtotime($_GET['start']); }
if (empty($_GET['end'])) { $end_date = $default_end_date; }else{ $end_date = strtotime($_GET['end']); }

// If we're an admin...
if ($_SESSION['login'] == 1)
{
    // If we selected a user...
    if (isset($_GET['trip_search_driver'])) {
        // If it was 'All' users or a specific one.
        if ($_GET['trip_search_driver'] == 'none') {
          $emp_id = 'none';
        }else{
          $emp_id = $_GET['trip_search_driver'];  
        }
        // If the account status was 'All' or a specific one.
        if (isset($_GET['productivity_user_status'])) {          
          if ($_GET['productivity_user_status'] == 'none') {
            $account_status = 'none';    
          }else{
            $account_status = $_GET['productivity_user_status'];
          }
        }else{
            $account_status = $_GET['productivity_user_status'];
        } 
    }else{
        // This is probably a fresh load of the page.  Get the defaults for admins.
        $emp_id = 'none';
        $account_status = 'none';
    }
  
    $vir_clockin_sql = generate_clockin_sql($emp_id,date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $vir_clockin_aggregate = get_sql_results($vir_clockin_sql,$mysqli);
}else{
    $emp_id = $_SESSION['employee_id'];
  
    $vir_clockin_sql = generate_clockin_sql($emp_id,date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $vir_clockin_aggregate = get_sql_results($vir_clockin_sql,$mysqli);
}

// Generate the sql for virs
$vir_aggregate = get_aggregate_vir(date('Y-m-d',$start_date),date('Y-m-d',$end_date), $mysqli);

// Generate the sql for tasks
$task_aggregate = get_task_aggregate(date('Y-m-d',$start_date),date('Y-m-d',$end_date), $mysqli);

// Generate the sql for quizzes
$quiz_aggregate = get_quiz_aggregate(date('Y-m-d',$start_date),date('Y-m-d',$end_date), $mysqli);

// Generate the sql for compliance
$csa_compliance_aggregate = get_aggregate_compliance(date('Y-m-d',$start_date),date('Y-m-d',$end_date), $mysqli);

// Generate the sql for shipments
$shipment_aggregate = get_shipment_aggregate(date('Y-m-d',$start_date),date('Y-m-d',$end_date), $mysqli);
$shipment_aggregate_all_users = [];

// Create an array of top performers
$leader_array = [];
foreach ($all_users_array as $key => $value) {
  for($m=0;$m<count($shipment_aggregate);$m++) {
    $leader_array[$shipment_aggregate[$m]['employee_id']] = ["shipment_aggregate",["percentage_earned",$shipment_aggregate[$m]['percentage_earned']]];
    $leader_array[$shipment_aggregate[$m]['employee_id']] = ["shipment_aggregate",["earned_points",$shipment_aggregate[$m]['earned_points']]];
  }
  #$leader_array[$all_users_array[$key]['employee_id']] = ["shipment",1];
}

 # if ($all_users_array[$key]['employee_id']

// Do some array manipulation if we're looking at all users
if ($_SESSION['login'] == 1)
{ 
  if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
    $shipment_aggregate['all_users']['employee_id'] = 'all_employees';
    $shipment_aggregate['all_users']['name'] = 'all_employees';
    $vir_aggregate['all_users']['username'] = 'all_employees';
    $vir_aggregate['all_users']['real_name'] = 'all_employees';

    $task_aggregate['all_users']['real_name'] = 'all_employees';
    $csa_compliance_aggregate['all_users']['real_name'] = 'all_employees';

    for($m=0;$m<count($shipment_aggregate);$m++) {
      // Check to see If we're looking for a specific status.
      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
        if ($shipment_aggregate[$m]['status'] != $_GET['productivity_user_status']) {
          continue;
        }
      }
      $shipment_aggregate['all_users']['as_puagent'] = $shipment_aggregate['all_users']['as_puagent'] + $shipment_aggregate[$m]['as_puagent'];
      $shipment_aggregate['all_users']['as_delagent'] = $shipment_aggregate['all_users']['as_delagent'] + $shipment_aggregate[$m]['as_delagent'];    
      $shipment_aggregate['all_users']['as_pu_and_delagent'] = $shipment_aggregate['all_users']['as_pu_and_delagent'] + $shipment_aggregate[$m]['as_pu_and_delagent'];    
      $shipment_aggregate['all_users']['total_hwb'] = $shipment_aggregate['all_users']['total_hwb'] + $shipment_aggregate[$m]['total_hwb'];    
      $shipment_aggregate['all_users']['sum_count'] = $shipment_aggregate['all_users']['sum_count'] + $shipment_aggregate[$m]['sum_count'];    
      $shipment_aggregate['all_users']['puagent_required_updates'] = $shipment_aggregate['all_users']['puagent_required_updates'] + $shipment_aggregate[$m]['puagent_required_updates'];    
      $shipment_aggregate['all_users']['delagent_required_updates'] = $shipment_aggregate['all_users']['delagent_required_updates'] + $shipment_aggregate[$m]['delagent_required_updates'];    
      $shipment_aggregate['all_users']['puagent_and_delagent_required_updates'] = $shipment_aggregate['all_users']['puagent_and_delagent_required_updates'] + $shipment_aggregate[$m]['puagent_and_delagent_required_updates'];    
      $shipment_aggregate['all_users']['core_updates_sum'] = $shipment_aggregate['all_users']['core_updates_sum'] + $shipment_aggregate[$m]['core_updates_sum'];    
      $shipment_aggregate['all_users']['misc_updates_sum'] = $shipment_aggregate['all_users']['misc_updates_sum'] + $shipment_aggregate[$m]['misc_updates_sum'];    
      $shipment_aggregate['all_users']['picked_up'] = $shipment_aggregate['all_users']['picked_up'] + $shipment_aggregate[$m]['picked_up'];
      $shipment_aggregate['all_users']['arrived_to_shipper'] = $shipment_aggregate['all_users']['arrived_to_shipper'] + $shipment_aggregate[$m]['arrived_to_shipper'];    
      $shipment_aggregate['all_users']['delivered'] = $shipment_aggregate['all_users']['delivered'] + $shipment_aggregate[$m]['delivered'];    
      $shipment_aggregate['all_users']['arrived_to_consignee'] = $shipment_aggregate['all_users']['arrived_to_consignee'] + $shipment_aggregate[$m]['arrived_to_consignee'];    
      $shipment_aggregate['all_users']['accessorial_count'] = $shipment_aggregate['all_users']['accessorial_count'] + $shipment_aggregate[$m]['accessorial_count'];    
      $shipment_aggregate['all_users']['arrived_to_shipper_points'] = $shipment_aggregate['all_users']['arrived_to_shipper_points'] + $shipment_aggregate[$m]['arrived_to_shipper_points'];    
      $shipment_aggregate['all_users']['max_arrived_to_shipper_points'] = $shipment_aggregate['all_users']['max_arrived_to_shipper_points'] + $shipment_aggregate[$m]['max_arrived_to_shipper_points'];    
      $shipment_aggregate['all_users']['picked_up_points'] = $shipment_aggregate['all_users']['picked_up_points'] + $shipment_aggregate[$m]['picked_up_points'];    
      $shipment_aggregate['all_users']['max_picked_up_points'] = $shipment_aggregate['all_users']['max_picked_up_points'] + $shipment_aggregate[$m]['max_picked_up_points'];    
      $shipment_aggregate['all_users']['arrived_to_consignee_points'] = $shipment_aggregate['all_users']['arrived_to_consignee_points'] + $shipment_aggregate[$m]['arrived_to_consignee_points'];    
      $shipment_aggregate['all_users']['max_arrived_to_consignee_points'] = $shipment_aggregate['all_users']['max_arrived_to_consignee_points'] + $shipment_aggregate[$m]['max_arrived_to_consignee_points'];    
      $shipment_aggregate['all_users']['delivered_points'] = $shipment_aggregate['all_users']['delivered_points'] + $shipment_aggregate[$m]['delivered_points'];    
      $shipment_aggregate['all_users']['max_delivered_points'] = $shipment_aggregate['all_users']['max_delivered_points'] + $shipment_aggregate[$m]['max_delivered_points'];    
      $shipment_aggregate['all_users']['accessorial_points'] = $shipment_aggregate['all_users']['accessorial_points'] + $shipment_aggregate[$m]['accessorial_points'];    
      $shipment_aggregate['all_users']['max_accessorial_points'] = $shipment_aggregate['all_users']['max_accessorial_points'] + $shipment_aggregate[$m]['max_accessorial_points'];    
      $shipment_aggregate['all_users']['noncore_points'] = $shipment_aggregate['all_users']['noncore_points'] + $shipment_aggregate[$m]['noncore_points'];    
      $shipment_aggregate['all_users']['max_noncore_points'] = $shipment_aggregate['all_users']['max_noncore_points'] + $shipment_aggregate[$m]['max_noncore_points'];    
      $shipment_aggregate['all_users']['earned_points'] = $shipment_aggregate['all_users']['earned_points'] + $shipment_aggregate[$m]['earned_points'];    
      $shipment_aggregate['all_users']['max_points'] = $shipment_aggregate['all_users']['max_points'] + $shipment_aggregate[$m]['max_points'];    
      $shipment_aggregate['all_users']['percentage_earned'] = $shipment_aggregate['all_users']['percentage_earned'] + $shipment_aggregate[$m]['percentage_earned'];    
    }
    for($m=0;$m<count($vir_aggregate);$m++) {
      // Check to see If we're looking for a specific status.
      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
        if ($vir_aggregate[$m]['status'] != $_GET['productivity_user_status']) {
          continue;
        }
      }
      $vir_aggregate['all_users']['vir_pretrip'] = $vir_aggregate['all_users']['vir_pretrip'] + $vir_aggregate[$m]['vir_pretrip'];    
      $vir_aggregate['all_users']['vir_posttrip'] = $vir_aggregate['all_users']['vir_posttrip'] + $vir_aggregate[$m]['vir_posttrip'];    
      $vir_aggregate['all_users']['vir_breakdown'] = $vir_aggregate['all_users']['vir_breakdown'] + $vir_aggregate[$m]['vir_breakdown'];    
      $vir_aggregate['all_users']['days_worked'] = $vir_aggregate['all_users']['days_worked'] + $vir_aggregate[$m]['days_worked'];    
      $vir_aggregate['all_users']['vir_additional_trailer'] = $vir_aggregate['all_users']['vir_additional_trailer'] + $vir_aggregate[$m]['vir_additional_trailer'];    
      $vir_aggregate['all_users']['max_total_vir_points'] = $vir_aggregate['all_users']['max_total_vir_points'] + $vir_aggregate[$m]['max_total_vir_points'];    
      $vir_aggregate['all_users']['vir_pretrip_percent'] = $vir_aggregate['all_users']['vir_pretrip_percent'] + $vir_aggregate[$m]['vir_pretrip_percent'];    
      $vir_aggregate['all_users']['vir_posttrip_percent'] = $vir_aggregate['all_users']['vir_posttrip_percent'] + $vir_aggregate[$m]['vir_posttrip_percent'];    
      $vir_aggregate['all_users']['vir_breakdown_percent'] = $vir_aggregate['all_users']['vir_breakdown_percent'] + $vir_aggregate[$m]['vir_breakdown_percent'];    
      $vir_aggregate['all_users']['username'] = $vir_aggregate['all_users']['username'] + $vir_aggregate[$m]['username'];    
      $vir_aggregate['all_users']['status'] = $vir_aggregate['all_users']['status'] + $vir_aggregate[$m]['status'];    
      $vir_aggregate['all_users']['miles'] = $vir_aggregate['all_users']['miles'] + $vir_aggregate[$m]['miles'];
      $vir_aggregate['all_users']['vir_pretrip_points'] = $vir_aggregate['all_users']['vir_pretrip_points'] + $vir_aggregate[$m]['vir_pretrip_points'];    
      $vir_aggregate['all_users']['vir_posttrip_points'] = $vir_aggregate['all_users']['vir_posttrip_points'] + $vir_aggregate[$m]['vir_posttrip_points'];    
      $vir_aggregate['all_users']['vir_additional_trailer_points'] = $vir_aggregate['all_users']['vir_additional_trailer_points'] + $vir_aggregate[$m]['vir_additional_trailer_points'];    
      $vir_aggregate['all_users']['vir_total_points'] = $vir_aggregate['all_users']['vir_total_points'] + $vir_aggregate[$m]['vir_total_points'];    
      $vir_aggregate['all_users']['vir_total_percent'] = $vir_aggregate['all_users']['vir_total_percent'] + $vir_aggregate[$m]['vir_total_percent'];
      $vir_aggregate['all_users']['days_shoulda_worked'] = $vir_aggregate['all_users']['days_shoulda_worked'] + $vir_aggregate[$m]['days_shoulda_worked'];
    }
    for($m=0;$m<count($task_aggregate);$m++) {
      // Check to see If we're looking for a specific status.
      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
        if ($task_aggregate[$m]['status'] != $_GET['productivity_user_status']) {
          continue;
        }
      }
      $task_aggregate['all_users']['tasks_completed_by_user'] = $task_aggregate['all_users']['tasks_completed_by_user'] + $task_aggregate[$m]['tasks_completed_by_user'];
      $task_aggregate['all_users']['tasks_all_user'] = $task_aggregate['all_users']['tasks_all_user'] + $task_aggregate[$m]['tasks_all_user'];
      $task_aggregate['all_users']['category'] = $task_aggregate['all_users']['category'] + $task_aggregate[$m]['category'];
      $task_aggregate['all_users']['passed_quizzes'] = $task_aggregate['all_users']['passed_quizzes'] + $task_aggregate[$m]['passed_quizzes'];
      $task_aggregate['all_users']['all_quizzes'] = $task_aggregate['all_users']['all_quizzes'] + $task_aggregate[$m]['all_quizzes'];
      $task_aggregate['all_users']['days_worked'] = $task_aggregate['all_users']['days_worked'] + $task_aggregate[$m]['days_worked'];
      $task_aggregate['all_users']['miles'] = $task_aggregate['all_users']['miles'] + $task_aggregate[$m]['miles'];
      $task_aggregate['all_users']['idle_time'] = $task_aggregate['all_users']['idle_time'] + $task_aggregate[$m]['idle_time'];
      $task_aggregate['all_users']['aprox_idle_costs'] = $task_aggregate['all_users']['aprox_idle_costs'] + $task_aggregate[$m]['aprox_idle_costs'];
      $task_aggregate['all_users']['days_worked_points'] = $task_aggregate['all_users']['days_worked_points'] + $task_aggregate[$m]['days_worked_points'];
      $task_aggregate['all_users']['miles_points'] = $task_aggregate['all_users']['miles_points'] + $task_aggregate[$m]['miles_points'];
      $task_aggregate['all_users']['task_points'] = $task_aggregate['all_users']['task_points'] + $task_aggregate[$m]['task_points'];
      $task_aggregate['all_users']['quiz_points'] = $task_aggregate['all_users']['quiz_points'] + $task_aggregate[$m]['quiz_points'];
      $task_aggregate['all_users']['idle_time_points'] = $task_aggregate['all_users']['idle_time_points'] + $task_aggregate[$m]['idle_time_points'];
      $task_aggregate['all_users']['days_shoulda_worked'] = $task_aggregate['all_users']['days_shoulda_worked'] + $task_aggregate[$m]['days_shoulda_worked'];
      $task_aggregate['all_users']['employee_id'] = $task_aggregate['all_users']['employee_id'] + $task_aggregate[$m]['employee_id'];
      $task_aggregate['all_users']['activity_total_points'] = $task_aggregate['all_users']['activity_total_points'] + $task_aggregate[$m]['activity_total_points'];
      $task_aggregate['all_users']['activity_max_points'] = $task_aggregate['all_users']['activity_max_points'] + $task_aggregate[$m]['activity_max_points'];
      $task_aggregate['all_users']['total_percent'] = $task_aggregate['all_users']['total_percent'] + $task_aggregate[$m]['total_percent'];
    }
    for($m=0;$m<count($csa_compliance_aggregate);$m++) {
      // Check to see If we're looking for a specific status.
      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
        if ($csa_compliance_aggregate[$m]['status'] != $_GET['productivity_user_status']) {
          continue;
        }
      }
      $csa_compliance_aggregate['all_users']['current_violation_points'] = $csa_compliance_aggregate['all_users']['current_violation_points'] + $csa_compliance_aggregate[$m]['current_violation_points'];
      $csa_compliance_aggregate['all_users']['past_24m_violation_points'] = $csa_compliance_aggregate['all_users']['past_24m_violation_points'] + $csa_compliance_aggregate[$m]['past_24m_violation_points'];
      $csa_compliance_aggregate['all_users']['current_points_cash_value'] = $csa_compliance_aggregate['all_users']['current_points_cash_value'] + $csa_compliance_aggregate[$m]['current_points_cash_value'];
      $csa_compliance_aggregate['all_users']['current_vehicle_maint_points'] = $csa_compliance_aggregate['all_users']['current_vehicle_maint_points'] + $csa_compliance_aggregate[$m]['current_vehicle_maint_points'];
      $csa_compliance_aggregate['all_users']['current_vehicle_maint_cash'] = $csa_compliance_aggregate['all_users']['current_vehicle_maint_cash'] + $csa_compliance_aggregate[$m]['current_vehicle_maint_cash'];
      $csa_compliance_aggregate['all_users']['current_hos_compliance_points'] = $csa_compliance_aggregate['all_users']['current_hos_compliance_points'] + $csa_compliance_aggregate[$m]['current_hos_compliance_points'];
      $csa_compliance_aggregate['all_users']['current_hos_compliance_cash'] = $csa_compliance_aggregate['all_users']['current_hos_compliance_cash'] + $csa_compliance_aggregate[$m]['current_hos_compliance_cash'];
      $csa_compliance_aggregate['all_users']['current_no_violation_points'] = $csa_compliance_aggregate['all_users']['current_no_violation_points'] + $csa_compliance_aggregate[$m]['current_no_violation_points'];
      $csa_compliance_aggregate['all_users']['current_no_violation_cash'] = $csa_compliance_aggregate['all_users']['current_no_violation_cash'] + $csa_compliance_aggregate[$m]['current_no_violation_cash'];
      $csa_compliance_aggregate['all_users']['current_unsafe_driving_points'] = $csa_compliance_aggregate['all_users']['current_unsafe_driving_points'] + $csa_compliance_aggregate[$m]['current_unsafe_driving_points'];
      $csa_compliance_aggregate['all_users']['current_unsafe_driving_cash'] = $csa_compliance_aggregate['all_users']['current_unsafe_driving_cash'] + $csa_compliance_aggregate[$m]['current_unsafe_driving_cash'];
      $csa_compliance_aggregate['all_users']['current_driver_fitness_points'] = $csa_compliance_aggregate['all_users']['current_driver_fitness_points'] + $csa_compliance_aggregate[$m]['current_driver_fitness_points'];
      $csa_compliance_aggregate['all_users']['current_driver_fitness_cash'] = $csa_compliance_aggregate['all_users']['current_driver_fitness_cash'] + $csa_compliance_aggregate[$m]['current_driver_fitness_cash'];
      $csa_compliance_aggregate['all_users']['current_controlled_sub_points'] = $csa_compliance_aggregate['all_users']['current_controlled_sub_points'] + $csa_compliance_aggregate[$m]['current_controlled_sub_points'];
      $csa_compliance_aggregate['all_users']['current_controlled_sub_cash'] = $csa_compliance_aggregate['all_users']['current_controlled_sub_cash'] + $csa_compliance_aggregate[$m]['current_controlled_sub_cash'];
      $csa_compliance_aggregate['all_users']['current_hazard_points'] = $csa_compliance_aggregate['all_users']['current_hazard_points'] + $csa_compliance_aggregate[$m]['current_hazard_points'];
      $csa_compliance_aggregate['all_users']['current_hazard_cash'] = $csa_compliance_aggregate['all_users']['current_hazard_cash'] + $csa_compliance_aggregate[$m]['current_hazard_cash'];
      $csa_compliance_aggregate['all_users']['current_crash_points'] = $csa_compliance_aggregate['all_users']['current_crash_points'] + $csa_compliance_aggregate[$m]['current_crash_points'];
      $csa_compliance_aggregate['all_users']['current_crash_cash'] = $csa_compliance_aggregate['all_users']['current_crash_cash'] + $csa_compliance_aggregate[$m]['current_crash_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_points_cash_value'] = $csa_compliance_aggregate['all_users']['past_24m_points_cash_value'] + $csa_compliance_aggregate[$m]['past_24m_points_cash_value'];
      $csa_compliance_aggregate['all_users']['past_24m_vehicle_maint_points'] = $csa_compliance_aggregate['all_users']['past_24m_vehicle_maint_points'] + $csa_compliance_aggregate[$m]['past_24m_vehicle_maint_points'];
      $csa_compliance_aggregate['all_users']['past_24m_vehicle_maint_cash'] = $csa_compliance_aggregate['all_users']['past_24m_vehicle_maint_cash'] + $csa_compliance_aggregate[$m]['past_24m_vehicle_maint_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_hos_compliance_points'] = $csa_compliance_aggregate['all_users']['past_24m_hos_compliance_points'] + $csa_compliance_aggregate[$m]['past_24m_hos_compliance_points'];
      $csa_compliance_aggregate['all_users']['past_24m_hos_compliance_cash'] = $csa_compliance_aggregate['all_users']['past_24m_hos_compliance_cash'] + $csa_compliance_aggregate[$m]['past_24m_hos_compliance_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_no_violation_points'] = $csa_compliance_aggregate['all_users']['past_24m_no_violation_points'] + $csa_compliance_aggregate[$m]['past_24m_no_violation_points'];
      $csa_compliance_aggregate['all_users']['past_24m_no_violation_cash'] = $csa_compliance_aggregate['all_users']['past_24m_no_violation_cash'] + $csa_compliance_aggregate[$m]['past_24m_no_violation_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_unsafe_driving_points'] = $csa_compliance_aggregate['all_users']['past_24m_unsafe_driving_points'] + $csa_compliance_aggregate[$m]['past_24m_unsafe_driving_points'];
      $csa_compliance_aggregate['all_users']['past_24m_unsafe_driving_cash'] = $csa_compliance_aggregate['all_users']['past_24m_unsafe_driving_cash'] + $csa_compliance_aggregate[$m]['past_24m_unsafe_driving_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_driver_fitness_points'] = $csa_compliance_aggregate['all_users']['past_24m_driver_fitness_points'] + $csa_compliance_aggregate[$m]['past_24m_driver_fitness_points'];
      $csa_compliance_aggregate['all_users']['past_24m_driver_fitness_cash'] = $csa_compliance_aggregate['all_users']['past_24m_driver_fitness_cash'] + $csa_compliance_aggregate[$m]['past_24m_driver_fitness_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_controlled_sub_points'] = $csa_compliance_aggregate['all_users']['past_24m_controlled_sub_points'] + $csa_compliance_aggregate[$m]['past_24m_controlled_sub_points'];
      $csa_compliance_aggregate['all_users']['past_24m_controlled_sub_cash'] = $csa_compliance_aggregate['all_users']['past_24m_controlled_sub_cash'] + $csa_compliance_aggregate[$m]['past_24m_controlled_sub_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_hazard_points'] = $csa_compliance_aggregate['all_users']['past_24m_hazard_points'] + $csa_compliance_aggregate[$m]['past_24m_hazard_points'];
      $csa_compliance_aggregate['all_users']['past_24m_hazard_cash'] = $csa_compliance_aggregate['all_users']['past_24m_hazard_cash'] + $csa_compliance_aggregate[$m]['past_24m_hazard_cash'];
      $csa_compliance_aggregate['all_users']['past_24m_crash_points'] = $csa_compliance_aggregate['all_users']['past_24m_crash_points'] + $csa_compliance_aggregate[$m]['past_24m_crash_points'];
    }
  }
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Productivity</title>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/favicon/favicon.php');?>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<!-- Bootstrap 3.3.4 -->
<link href="<?php echo HTTP;?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo HTTP;?>/bootstrap/css/custom.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome Icons -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="<?php echo HTTP;?>/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
<link href="<?php echo HTTP;?>/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
<!-- Date Picker -->
<link href="<?php echo HTTP;?>/dist/css/bootstrap-datepicker3.css" rel="stylesheet">
<!-- Catalina -->
<link href="<?php echo HTTP;?>/dist/css/catalina.css" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link rel="stylesheet" href="/dist/css/animate.css">
<style>
.chart-legend li span{
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-right: 5px;
}
</style>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/sidebar.php');?> 

    <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Productivity
          </h1>
                  <div class="box-body"></div>           
          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Productivity</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->  
<div class="container">

    <div class="col-sm-<?php if ($_SESSION['login'] == 1) { echo 10; }else{ echo 6; }?> pull-left well" style="width: 50%;">
           <form name="frm_productivity" method="GET" action="" role="form" enctype="multipart/form-data">
              <div class="box-body">
               <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control datepicker" name="start" id="dt_start" data-date-format="mm/dd/yyyy"/ required>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm form-control datepicker" name="end" id="dt_end" data-date-format="mm/dd/yyyy"/ required>
               </div>
               <?php if ($_SESSION['login'] == 1) { ?>
               <div class="input-group" id="driver">
                 <select class="input-sm form-control" name="trip_search_driver" id="trip_search_driver" value="" style="margin-top: 5px;" onchange="display_status_div()">
                  <option value="none">All Users</option>
                    <?php for ($i=0; $i<sizeof($all_users_array); $i++) { ?>
                      <option value=<?php echo '"' . $all_users_array[$i]['employee_id'] . '"';
                       if ($all_users_array[$i]['employee_id'] == $_GET['trip_search_driver']) { echo " selected "; } ?>
                      ><?php echo $all_users_array[$i]['name'];?></option>
                    <?php } ?>
                </select>
               </div>
               <div class="input-group" id="user_status">
                 <select class="input-sm form-control" name="productivity_user_status" id="productivity_user_status" value="" style="margin-top: 5px;">
                    <option value='none'>All Status</option>
                    <?php for ($i=0; $i<sizeof($user_status_array); $i++) { ?>
                      <option value=<?php echo '"' . $user_status_array[$i]['status'] . '"';                       
                      if ($user_status_array[$i]['status'] == $_GET['productivity_user_status']) { echo " selected "; } ?>><?php echo $user_status_array[$i]['status'];?></option>
                    <?php } ?>
                </select>
               </div>
               <?php } ?>
            <button type="submit" class="btn btn-danger" name="btn_submit" value='true' style="margin-top: 5px;">Submit</button>
           </form>
    </div>
  </div>
</div>
<!-- =============Productivity Menu================================ -->


          <div class="row">
          
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="headingOne">
              <h4 class="panel-title">
                  <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Productivity Review
                  </a>
                </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse <?php echo ($_SESSION['login'] == 1 ? 'in' : null);?>" role="tabpanel" aria-labelledby="headingOne">
                <div class="panel-body">

           <div class="col-md-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-blue">
                  <div class="widget-user-image">
                    <p><img src="<?php echo $stats_image;?>" 
                   alt="User Image" width="100" height="100" class="img-circle" />
                      <span class="fa-2x">Shipments</span></p>
                    <div style="margin-left: 5px;"><span class="fa-2x" style="font-size:1em;"> <div><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></div><div> <strong><?php echo $stats_user;?></strong></div></div></span>
                  </div>
                  <!-- Add text below Image Removed....
                  <span class="info-box-text">Shipments</span>
                  --> 
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">

                    <li><a href="#">Total HWB <span class="pull-right badge bg-blue" id="shp_arrived_shipper">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $shipment_aggregate['all_users']['total_hwb'];
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo $shipment_aggregate[$ship_i]['total_hwb'];
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                    <li><a href="#">Assigned as PU <span class="pull-right badge bg-blue" id="shp_assigned_pu">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $shipment_aggregate['all_users']['as_puagent'];
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {                          
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo $shipment_aggregate[$ship_i]['as_puagent'];                                                   
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                    <li><a href="#">Assigned as DEL <span class="pull-right badge bg-blue" id="shp_assigned_del">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $shipment_aggregate['all_users']['as_delagent'];
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {                          
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo $shipment_aggregate[$ship_i]['as_delagent'];                                                   
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                  
                    <li><a href="#">Assigned as BOTH <span class="pull-right badge bg-blue" id="shp_assigned_both">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $shipment_aggregate['all_users']['as_pu_and_delagent'];
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {                          
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo $shipment_aggregate[$ship_i]['as_pu_and_delagent'];                                                   
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                  <li><a href="#">Arrived Shipper <span class="pull-right badge bg-blue" id="shp_arrived_shipper">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo $shipment_aggregate['all_users']['arrived_to_shipper'];                      
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {                          
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo $shipment_aggregate[$ship_i]['arrived_to_shipper'];                                                   
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                    <li><a href="#">Arrived Shipper Points <span class="pull-right badge bg-green" id="shp_arrived_shipper">
                     <?php                     
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          $cp = round($shipment_aggregate['all_users']['arrived_to_shipper_points'],0); 
                          $mp = round($shipment_aggregate['all_users']['max_arrived_to_shipper_points'],0); 
                          echo ($cp <= $mp ? $cp : $mp) .' of ' . round($shipment_aggregate['all_users']['max_arrived_to_shipper_points'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            $cp = round($shipment_aggregate[$ship_i]['arrived_to_shipper_points'],0); 
                            $mp = round($shipment_aggregate[$ship_i]['max_arrived_to_shipper_points'],0); 
                            echo ($cp <= $mp ? $cp : $mp) .' of ' . round($shipment_aggregate[$ship_i]['max_arrived_to_shipper_points'],0);
                          }
                        }
                     ?>
                     </span></a>
                    </li>


                  <li><a href="#">Picked Up <span class="pull-right badge bg-blue" id="shp_picked_up">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['picked_up'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['picked_up'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Picked Up Points<span class="pull-right badge bg-blue productivity-pts" id="shp_picked_up_points">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['picked_up_points'],0) . ' of ' . round($shipment_aggregate['all_users']['max_picked_up_points'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['picked_up_points'],0) . ' of ' . round($shipment_aggregate[$ship_i]['max_picked_up_points'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Arrived Consignee <span class="pull-right badge bg-blue" id="shp_arrived_consignee">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['arrived_to_consignee'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['arrived_to_consignee'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Arrived Consignee Points<span class="pull-right badge bg-blue productivity-pts" id="shp_arrived_consignee_points">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['arrived_to_consignee_points'],0) . ' of ' . round($shipment_aggregate['all_users']['max_arrived_to_consignee_points'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['arrived_to_consignee_points'],0) . ' of ' . round($shipment_aggregate[$ship_i]['max_arrived_to_consignee_points'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Delivered <span class="pull-right badge bg-blue" id="shp_delivered">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['delivered'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['delivered'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Delivered Points<span class="pull-right badge bg-blue productivity-pts" id="shp_delivered_points">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['delivered_points'],0) . ' of ' . round($shipment_aggregate['all_users']['max_delivered_points'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['delivered_points'],0) . ' of ' . round($shipment_aggregate[$ship_i]['max_delivered_points'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Accessorials Added<span class="pull-right badge bg-blue" id="shp_accessorials">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['accessorial_count'],0) . ' of ' .round($shipment_aggregate['all_users']['as_puagent'],0) + round($shipment_aggregate['all_users']['as_delagent'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['accessorial_count'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Accessorials Added Points<span class="pull-right badge bg-blue productivity-pts" id="shp_accessorials_points">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['accessorial_points'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['accessorial_points'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Other Status Change <span class="pull-right badge bg-blue" id="shp_other_status">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['misc_updates_sum'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['misc_updates_sum'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>

                  <li><a href="#">Other Status Change Points<span class="pull-right badge bg-blue productivity-pts" id="shp_other_status_points">
                  <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($shipment_aggregate['all_users']['noncore_points'],0);
                        }
                        for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                          if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                            echo round($shipment_aggregate[$ship_i]['noncore_points'],0);
                          }
                        }
                  ?>
                  </span></a>
                  </li>
                  </ul>
                </div>
              </div><!-- /.widget-user -->
            </div>
           <div class="col-md-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-red">
                  <div class="widget-user-image">
                    <p><img src="<?php echo $stats_image;?>"
                    alt="User Avatar" width="100" height="100" class="img-circle">
                      <span class="fa-2x">VIR'S</span></p>
                    <div style="margin-left: 5px;"><span class="fa-2x" style="font-size:1em;"> <div><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></div><div> <strong><?php echo $stats_user;?></strong></div></div></span>
                  </div>
                  <!-- Add text below Image Removed.... 
                  <span class="info-box-text"> VIRS</span>
                  -->
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Days Worked <span class="pull-right badge bg-green" id="vir_days_worked">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $vir_aggregate['all_users']['days_worked'] . " of " . $vir_aggregate['all_users']['days_shoulda_worked'];
                        }
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                            echo $vir_aggregate[$vir_i]['days_worked']  . " of " . $vir_aggregate[$vir_i]['days_shoulda_worked'];
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Miles <span class="pull-right badge bg-blue" id="vir_days_worked">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($vir_aggregate['all_users']['miles'],0);
                        }
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                            echo round($vir_aggregate[$vir_i]['miles'],0);
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Pre-Trips <span class="pull-right badge bg-blue" id="vir_pretrip">
                       <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $vir_aggregate['all_users']['vir_pretrip'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                        }
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                            echo $vir_aggregate[$vir_i]['vir_pretrip'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                       }
                     }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Pre-Trip Points<span class="pull-right badge bg-blue productivity-pts" id="vir_pretrip_points">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          if ($vir_aggregate['all_users']['vir_pretrip_points'] > $vir_aggregate['all_users']['days_worked']) {
                           echo $vir_aggregate['all_users']['days_worked'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                          }else{
                           echo $vir_aggregate['all_users']['vir_pretrip_points'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                          }
                        }
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           if ($vir_aggregate[$vir_i]['vir_pretrip_points'] > $vir_aggregate[$vir_i]['days_worked']) {
                             echo $vir_aggregate[$vir_i]['days_worked'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                           }else{
                             echo $vir_aggregate[$vir_i]['vir_pretrip_points'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                           }
                          }
                        } 
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Post-Trips <span class="pull-right badge bg-blue" id="vir_posttrip">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $vir_aggregate['all_users']['vir_posttrip'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                        }
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           echo $vir_aggregate[$vir_i]['vir_posttrip'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                    <li><a href="#">Post-Trip Points<span class="pull-right badge bg-blue productivity-pts" id="vir_posttrip_points">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          if ($vir_aggregate['all_users']['vir_posttrip_points'] > $vir_aggregate['all_users']['days_worked']) {
                           echo $vir_aggregate['all_users']['days_worked'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                          }else{
                           echo $vir_aggregate['all_users']['vir_posttrip_points'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                          }
                        }
                       for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           if ($vir_aggregate[$vir_i]['vir_posttrip_points'] > $vir_aggregate[$vir_i]['days_worked']) {
                             echo $vir_aggregate[$vir_i]['days_worked'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                           }else{
                             echo $vir_aggregate[$vir_i]['vir_posttrip_points'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                           }
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                    <li><a href="#">Add. Trailer Insp.<span class="pull-right badge bg-blue" id="vir_posttrip_points">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          if ($vir_aggregate['all_users']['vir_additional_trailer'] > $vir_aggregate['all_users']['days_worked']) {
                           echo $vir_aggregate['all_users']['days_worked'];
                          }else{
                           echo $vir_aggregate['all_users']['vir_additional_trailer'];
                          }
                        }
                       for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           if ($vir_aggregate[$vir_i]['vir_additional_trailer'] > $vir_aggregate[$vir_i]['days_worked']) {
                             echo $vir_aggregate[$vir_i]['days_worked'];
                           }else{
                             echo $vir_aggregate[$vir_i]['vir_additional_trailer'];
                           }
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                    <li><a href="#">Add. Trailer Insp. Points<span class="pull-right badge bg-blue productivity-pts" id="vir_posttrip_points">
                     <?php
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        if ($vir_aggregate['all_users']['vir_additional_trailer_points'] > $vir_aggregate['all_users']['days_worked']) {
                           echo $vir_aggregate['all_users']['days_worked'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                         }else{
                           echo $vir_aggregate['all_users']['vir_additional_trailer_points'] . ' of ' . $vir_aggregate['all_users']['days_worked'];
                         }
                      }
                       for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           if ($vir_aggregate[$vir_i]['vir_additional_trailer_points'] > $vir_aggregate[$vir_i]['days_worked']) {
                             echo $vir_aggregate[$vir_i]['days_worked'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                           }else{
                             echo $vir_aggregate[$vir_i]['vir_additional_trailer_points'] . ' of ' . $vir_aggregate[$vir_i]['days_worked'];
                           }
                          }
                        }
                     ?>
                     </span></a>
                    </li>

                    <li><a href="#">Reported Breakdowns <span class="pull-right badge bg-blue" id="vir_breakdown">
                     <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $vir_aggregate['all_users']['vir_breakdown'];
                        }
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           echo $vir_aggregate[$vir_i]['vir_breakdown'];
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Breakdown Points<span class="pull-right badge bg-blue productivity-pts" id="vir_breakdown_points">
                     <?php
                     if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                       if ($vir_aggregate['all_users']['vir_breakdown'] > $vir_aggregate['all_users']['days_worked']) {
                         echo $vir_aggregate['all_users']['days_worked'];
                       }else{
                         echo $vir_aggregate['all_users']['vir_breakdown'];
                       }
                     }
                    for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           if ($vir_aggregate[$vir_i]['vir_breakdown'] > $vir_aggregate[$vir_i]['days_worked']) {
                             echo $vir_aggregate[$vir_i]['days_worked'];
                           }else{
                             echo $vir_aggregate[$vir_i]['vir_breakdown'];
                           }
                          }
                        } 
                     ?>
                     </span></a>
                    </li>
                    
                  </ul>
                </div>
              </div><!-- /.widget-user -->
            </div>
           <div class="col-md-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-purple">
                  <div class="widget-user-image">
                    <p class="fa-2x"><img src="<?php echo $stats_image;?>"
                     alt="User Avatar" width="100" height="100" class="img-circle">Activity</p>
<p class="fa-2x"></p><div style="margin-left: 5px;"><span class="fa-2x" style="font-size:1em;"> <div><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></div><div> <strong><?php echo $stats_user;?></strong></div></div></span>
                  </div>
                  <!-- Add text below Image Removed....
                  <span class="info-box-text"> Productivity</span>
                  -->
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                  <li><a href="#">Days Worked <span class="pull-right badge bg-blue" id="vir_days_worked">
                      <?php                      
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo $task_aggregate['all_users']['days_worked'] .' of ' . $task_aggregate['all_users']['days_shoulda_worked'];
                        }
                        for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                          if ($task_aggregate[$task_i]['employee_id'] == $emp_id) {                            
                            echo $task_aggregate[$task_i]['days_worked'] .' of ' . $task_aggregate[$task_i]['days_shoulda_worked'];
                          }
                        }?>
                     </span></a>
                    </li>
                    <li><a href="#">Days Worked Points <span class="pull-right badge bg-blue productivity-pts" id="vir_days_worked">
                      <?php
                        if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                          echo round($task_aggregate['all_users']['days_worked_points'],0) .' of ' . $task_aggregate['all_users']['days_shoulda_worked'];
                        }
                        for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                          if ($task_aggregate[$task_i]['employee_id'] == $emp_id) {
                            echo round($task_aggregate[$task_i]['days_worked_points'],0) .' of ' . $task_aggregate[$task_i]['days_shoulda_worked'];
                          }
                        }?>
                     </span></a>
                    </li>
                    <li><a href="#">Miles <span class="pull-right badge bg-blue" id="vir_days_worked">
                        <?php
                          if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                            echo round($task_aggregate['all_users']['miles'],0); 
                          }
                          for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                          if ($task_aggregate[$task_i]['employee_id'] == $emp_id) {
                            echo round($task_aggregate[$task_i]['miles'],0);
                          }
                        }?>
                     </span></a>
                    </li>
                    <li><a href="#">Miles Points<span class="pull-right badge bg-blue productivity-pts" id="vir_days_worked">
                        <?php 
                          if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                            echo $task_aggregate['all_users']['miles_points'] . " of " . $task_aggregate['all_users']['days_worked'] * $cp_csa_values[0]['miles_daily_multiplier'];
                          }
                          for($task_i=0;$task_i<(count($task_aggregate));$task_i++) { 
                            if ($task_aggregate[$task_i]['employee_id'] == $emp_id) {
                              echo $task_aggregate[$task_i]['miles_points']. " of " . $task_aggregate[$task_i]['days_worked'] * $cp_csa_values[0]['miles_daily_multiplier'];
                          }
                        }?>
                     </span></a>
                    </li>
                    <li><a href="#">Tasks<span class="pull-right badge bg-blue" id="prod_task">
                    <?php 
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo $task_aggregate['all_users']['tasks_completed_by_user'] . ' of ' . $task_aggregate['all_users']['tasks_all_user'];
                      }
                      for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                        if ($task_aggregate[$task_i]['employee_id'] == $emp_id) { 
                          echo $task_aggregate[$task_i]['tasks_completed_by_user'] . ' of ' . $task_aggregate[$task_i]['tasks_all_user'];
                        } 
                      }?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Task Points<span class="pull-right badge bg-blue productivity-pts" id="prod_task_points">
                    <?php 
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo $task_aggregate['all_users']['task_points'] . ' of '. $task_aggregate['all_users']['tasks_completed_by_user'];
                      }
                      for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                        if ($task_aggregate[$task_i]['employee_id'] == $emp_id) { 
                          echo $task_aggregate[$task_i]['task_points'] . ' of '. $task_aggregate[$task_i]['tasks_completed_by_user'];
                        }
                      } ?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Quiz<span class="pull-right badge bg-blue" id="prod_task_points">
                    <?php 
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo $task_aggregate['all_users']['passed_quizzes'] . ' of ' . $task_aggregate['all_users']['all_quizzes'];
                      }
                      for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                        if ($task_aggregate[$task_i]['employee_id'] == $emp_id) {
                          echo $task_aggregate[$task_i]['passed_quizzes'] . ' of ' . $task_aggregate[$task_i]['all_quizzes'];
                        }
                      } ?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Quiz Points<span class="pull-right badge bg-blue productivity-pts" id="prod_task_points">
                    <?php 
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo $task_aggregate['all_users']['quiz_points'] . ' of ' . $task_aggregate['all_users']['all_quizzes']; 
                      }
                      for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                        if ($task_aggregate[$task_i]['employee_id'] == $emp_id) { 
                          echo $task_aggregate[$task_i]['quiz_points'] . ' of ' . $task_aggregate[$task_i]['all_quizzes']; 
                        } 
                      } ?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Idle Time<span class="pull-right badge bg-blue" id="prod_task_points">
                    <?php 
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo $task_aggregate['all_users']['idle_time']; 
                      }
                      for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                        if ($task_aggregate[$task_i]['employee_id'] == $emp_id) { 
                          echo $task_aggregate[$task_i]['idle_time']; 
                        } 
                      } ?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Idle Time Points<span class="pull-right badge bg-blue productivity-pts" id="prod_task_points">
                    <?php 
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo round($task_aggregate['all_users']['idle_time_points'],0); 
                      }
                      for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                        if ($task_aggregate[$task_i]['employee_id'] == $emp_id) { 
                          echo round($task_aggregate[$task_i]['idle_time_points'],0); 
                        } 
                      } ?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Idle Time Cost<span class="pull-right badge bg-blue" id="prod_task_points">
                    <?php
                      if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                        echo $task_aggregate['all_users']['aprox_idle_costs'];  
                      }
                      for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {
                        if ($task_aggregate[$task_i]['employee_id'] == $emp_id) { 
                          echo $task_aggregate[$task_i]['aprox_idle_costs']; 
                        } 
                      } ?>
                    </span>
                     </a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.widget-user -->
            </div>
           <div class="col-md-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-orange">
                  <div class="widget-user-image">
                    <p><img src="<?php echo $stats_image;?>"
                    alt="User Avatar" width="100" height="100" class="img-circle"><span class="fa-2x">Compliance</span></p>
                    <div style="margin-left: 5px;"><span class="fa-2x" style="font-size:1em;"> <div><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></div><div> <strong><?php echo $stats_user;?></strong></div></div></span>
                  </div>
                </div>
                <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                  
                  <li><a href="#">CSA Compliance<span class="pull-right badge bg-blue" id="csa_total_points">
                  <?php                   
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_violation_points']
                      . " | t " .
                      $csa_compliance_aggregate['all_users']['past_24m_violation_points'];
                    }                                        
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) {                         
                        echo "c " . $csa_compliance_aggregate[$compliance_i]['current_violation_points']
                        . " | t " .
                        $csa_compliance_aggregate[$compliance_i]['past_24m_violation_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">HOS Compliance<span class="pull-right badge bg-blue" id="csa_hos">
                  <?php
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') { 
                      echo "c " . $csa_compliance_aggregate['all_users']['current_hos_compliance_points']
                      . " | t " .
                      $csa_compliance_aggregate['all_users']['past_24m_hos_compliance_points'];
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) { 
                        echo "c " . $csa_compliance_aggregate[$compliance_i]['current_hos_compliance_points']
                        . " | t " .
                        $csa_compliance_aggregate[$compliance_i]['past_24m_hos_compliance_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">Unsafe Driving<span class="pull-right badge bg-blue" id="csa_unsafe">
                  <?php 
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_unsafe_driving_points']
                      . " | t " .
                      $csa_compliance_aggregate['all_users']['past_24m_unsafe_driving_points'];
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) { 
                        echo "c " . $csa_compliance_aggregate[$compliance_i]['current_unsafe_driving_points']
                        . " | t " .
                        $csa_compliance_aggregate[$compliance_i]['past_24m_unsafe_driving_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">Vehicle Maint.<span class="pull-right badge bg-blue" id="csa_maint">
                  <?php 
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_vehicle_maint_points']
                      . " | t " .
                      $csa_compliance_aggregate['all_users']['past_24m_vehicle_maint_points'];
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) { 
                        echo "c " . $csa_compliance_aggregate[$compliance_i]['current_vehicle_maint_points']
                        . " | t " .
                        $csa_compliance_aggregate[$compliance_i]['past_24m_vehicle_maint_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">Driver Fitness<span class="pull-right badge bg-blue" id="csa_fitness">
                  <?php 
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_driver_fitness_points']
                      . " | t " .
                      $csa_compliance_aggregate['all_users']['past_24m_driver_fitness_points'];
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) { 
                        echo "c " . $csa_compliance_aggregate[$compliance_i]['current_driver_fitness_points']
                        . " | t " .
                        $csa_compliance_aggregate[$compliance_i]['past_24m_driver_fitness_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">Controlled Substances<span class="pull-right badge bg-blue" id="csa_substance">
                  <?php  
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_controlled_sub_points']
                      . " | t " .
                      $csa_compliance_aggregate['all_users']['past_24m_controlled_sub_points'];
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) { 
                        echo "c " . $csa_compliance_aggregate[$compliance_i]['current_controlled_sub_points']
                        . " | t " .
                        $csa_compliance_aggregate[$compliance_i]['past_24m_controlled_sub_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">HazMat<span class="pull-right badge bg-blue" id="csa_hazardous">
                  <?php 
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_hazard_points']
                      . " | t " .
                      $csa_compliance_aggregate['all_users']['past_24m_hazard_points'];
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) {
                        echo "c " . $csa_compliance_aggregate[$compliance_i]['current_hazard_points']
                        . " | t " .
                        $csa_compliance_aggregate[$compliance_i]['past_24m_hazard_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">Crash Indicator<span class="pull-right badge bg-blue" id="csa_crash">
                  <?php 
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_crash_points']
                      . " | t " .
                       $csa_compliance_aggregate['all_users']['past_24m_crash_points'];
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) { 
                        echo "c " .  $csa_compliance_aggregate[$compliance_i]['current_crash_points']
                        . " | t " .
                         $csa_compliance_aggregate[$compliance_i]['past_24m_crash_points'];
                      }
                    }?>
                  </span></a>
                    </li>

                  <li><a href="#">No Violation<span class="pull-right badge bg-blue" id="csa_no_violation">
                  <?php 
                    if (!isset($_GET['trip_search_driver']) || $_GET['trip_search_driver'] == 'none') {
                      echo "c " . $csa_compliance_aggregate['all_users']['current_points_cash_value'] 
                      . " | t " . 
                      $csa_compliance_aggregate['all_users']['past_24m_points_cash_value']; 
                    }
                    for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                      if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) {
                      echo "c " . $csa_compliance_aggregate[$compliance_i]['current_points_cash_value']  
                      . " | t " . 
                      $csa_compliance_aggregate[$compliance_i]['past_24m_points_cash_value']; 
                      }
                    }?>
                  </span></a>
                    </li>
                </ul>
                </div>
              </div><!-- /.widget-user -->
            </div>
            </div>
          </div>
        </div>
        </div>

<!-- ======================New Section Colored Boxes============ -->
          <!-- Boxes with Icon on Right side (Status box) -->
          <div class="row">
          <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-blue">
                <div class="inner">
                <?php
                    $total_shipment_points = $shipment_aggregate['all_users']['earned_points'];
                    $possible_shipment_points = $shipment_aggregate['all_users']['max_points'];
                    // override the total percent for all_users                    
                    $total_shipment_percent = round(($shipment_aggregate['all_users']['earned_points'] / $shipment_aggregate['all_users']['max_points']) * 100,0);
                    for ($ship_i=0;$ship_i<count($shipment_aggregate);$ship_i++) {
                      if ($shipment_aggregate[$ship_i]['employee_id'] == $emp_id) {
                        $total_shipment_percent = $shipment_aggregate[$ship_i]['percentage_earned'];
                        $total_shipment_points = $shipment_aggregate[$ship_i]['earned_points'];
                        $possible_shipment_points = $shipment_aggregate[$ship_i]['max_points'];
                      }
                    }
                 ?> 
                  <h4 id="shp_points" style="text-align: center; font-size: 2em;">Points: <?php echo round($total_shipment_points,2);?> of <?php echo round($possible_shipment_points,2);?></h4>
                  <h4 id="shp_percent" style="text-align: center; font-size: 3em;"><?php $pe = round($total_shipment_percent,2); echo ($pe > 100 ? 100 : $pe);?>%</h4>
                </div>
                <div class="icon"> <i class="fa fa-cog fa-spin"></i> </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-red">
                <div class="inner">
                <?php                    
                    $total_vir_points = $vir_aggregate['all_users']['vir_total_points'];
                    $days_worked = $vir_aggregate['all_users']['days_worked'];
                    $possible_vir_points = $vir_aggregate['all_users']['max_total_vir_points'];
                    // override the total percent for all_users
                    $total_vir_percent = round(($vir_aggregate['all_users']['vir_total_points'] / $vir_aggregate['all_users']['max_total_vir_points']) * 100,0);
                    for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                      if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                        $total_vir_percent = $vir_aggregate[$vir_i]['vir_total_percent'];
                        $total_vir_points = $vir_aggregate[$vir_i]['vir_total_points'];
                        $days_worked = $vir_aggregate[$vir_i]['days_worked'];
                        $possible_vir_points = $vir_aggregate[$vir_i]['max_total_vir_points'];
                      }
                    }
                 ?> 
                  <h4 id="vir_points" style="text-align: center; font-size: 2em;">Points:<?php echo $total_vir_points;?> of <?php echo $possible_vir_points;?></h4>
                  <h4 id="vir_percent" style="text-align: center; font-size: 3em;"><?php $pe = round($total_vir_percent,2); echo ($pe > 100 ? 100 : $pe) . "%";?></h4>
                </div>
               </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                <?php
                     $total_activity_points = $task_aggregate['all_users']['activity_total_points'];
                    $possible_activity_points = $task_aggregate['all_users']['activity_max_points'] + $task_aggregate['all_users']['days_worked'] * $cp_csa_values[0]['miles_daily_multiplier'];
                    $task_total_percent = round(($total_activity_points / $possible_activity_points) * 100,0);
                    for($task_i=0;$task_i<count($task_aggregate);$task_i++) {
                      if ($task_aggregate[$task_i]['employee_id'] == $emp_id) {
                        $total_activity_points = $task_aggregate[$task_i]['activity_total_points'];
                        $possible_activity_points = $task_aggregate[$task_i]['activity_max_points'] + $task_aggregate[$task_i]['days_worked'] * $cp_csa_values[0]['miles_daily_multiplier'];
                        $task_total_percent = round(($total_activity_points / $possible_activity_points) * 100 ,0);
                      }
                    }
                 ?> 
                  <h4 id="task_points" style="text-align: center; font-size: 2em;">Points: <?php echo $total_activity_points;?> of <?php echo $possible_activity_points;?></h4>
                  <h4 id="task_percent" style="text-align: center; font-size: 3em;"><?php echo $task_total_percent . "%";?></h4>
                </div>
                <div class="icon"> <i class="ion ion-person-add"></i> </div>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-orange">
                <div class="inner">
                  <h4 id="csa_points" style="text-align: center; font-size: 2em;">
                       <?php
                        $total_compliance_points_24 =
                        $csa_compliance_aggregate['all_users']['past_24m_violation_points'];

                        $total_compliance_percent_24 = round(($total_compliance_points_24 / $cp_csa_values[0]['csa_24m_all']) * 100,0);
                        
                        $total_compliance_points_current =
                        $csa_compliance_aggregate['all_users']['current_violation_points'];

                        $total_compliance_percent_current = round(($total_compliance_points_current / $cp_csa_values[0]['csa_q_all']) * 100,0);

                        $possible_compliance_points = 0;
                        $total_percent = ($total_compliance_points > 0 ? 0 : 100);
                       for($compliance_i=0;$compliance_i<count($csa_compliance_aggregate);$compliance_i++) {
                        if ($csa_compliance_aggregate[$compliance_i]['employee_id'] == $emp_id) {
                          $total_compliance_points_24 =
                          $csa_compliance_aggregate[$compliance_i]['past_24m_violation_points'];

                          $total_compliance_percent_24 = round(($total_compliance_points_24 / $cp_csa_values[0]['csa_24m_individual']) * 100,0);                          

                          $total_compliance_points_current =
                          $csa_compliance_aggregate[$compliance_i]['current_violation_points'];                        

                          $total_compliance_percent_current = round(($total_compliance_points_current / $cp_csa_values[0]['csa_q_individual']) * 100,0);

                          $possible_compliance_points = 0;
                          $total_percent = ($total_compliance_points > 0 ? 0 : 100);
                      }
                    }
                       ?>
                      <?php echo  $total_compliance_percent_current."% | ". $total_compliance_percent_24."%";?>
                  </h4>
                  <?php $total_compliance_percent_both = ($total_compliance_percent_current + $total_compliance_percent_24 / 2);?>
                  <h4 id="shp_percent" style="text-align: center; font-size: 3em;"><?php echo  round($total_compliance_percent_both,0) . "%";?></h4>
                </div>
                <div class="icon"> <i class="fa fa-cog fa-spin"></i> </div>
              </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->
          </div>
          </div>



          <!-- Boxes with Icon on Right side (Status box) -->
        <div class="row">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
          <div class="col-lg-12 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <center>
                    <?php                      
                      $sum_earned_percent = ($total_shipment_percent + $total_vir_percent + $task_total_percent + $total_compliance_percent_both);
                      $sum_possible_points = ($possible_shipment_points + $possible_vir_points + $possible_activity_points + $possible_compliance_points);
                    ?>
                    <h3>Combined Score <?php echo round(($sum_earned_percent / 4),0);?>%</h3></center>
                  <center><p>Total Points All Categories 150 of 200 as of Current Selection Year, Quarter, Month</p></center>
                </div>
                <div class="icon"> <i class="fa fa-cog fa-spin"></i> </div>
                <!--<a href="#" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a> </div>-->
          </div>
          <!-- ./col --><!-- ./col --><!-- ./col --><!-- ./col -->
          </div>
          <!-- /.row -->
          </div>
          </div>








          <div class="row">
            <div class="col-md-12" style="margin-left: 10px; width:98%;">
              <div class="box"><!-- /.box-header --><!-- ./box-body -->

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
            <div>
 





      <!-- Top Box Full sized window -->
          <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                  
                  <h3 class="box-title">Top Performers</h3>

                  <!-- Insert Plus Minus tool -->
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>                
                
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table width="100%" class="table table-hover">
                    <tr>
                      <th width="3%">#</th>
                      <th width="15%">Name</th>
                      <th width="14%">Graph Score</th>
                      <th width="10%">Total Score</th>
                      <th width="6%">+ Points</th>
                      <th width="7%"> - Points</th>
                      <th width="9%">Total Points</th>
                      <th width="11%">Best Category</th>
                      <th width="25%">Worst Category</th>
                    </tr>

                <?php
                // Get the top performers
                $top_performers = get_top_performers(date('Y-m-d',$start_date),date('Y-m-d',$end_date), $mysqli);

                $counter = 0;                                
                for($z=0;$z<count($top_performers);$z++){                  
                  // Check to see If we're looking for a specific status.
                  if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
                    if ($top_performers[$z]['status'] != $_GET['productivity_user_status']) {
                      continue;
                    }
                  }
                  $counter++;
                  if ($top_performers[$z]['combined_percent'] >= 90) { $color = 'green'; $percent = $top_performers[$z]['combined_percent'];}
                  if ($top_performers[$z]['combined_percent'] >= 70 && $top_performers[$z]['combined_percent'] < 90) { $color = 'blue'; $percent = $top_performers[$z]['combined_percent']; }
                  if ($top_performers[$z]['combined_percent'] > 50 && $top_performers[$z]['combined_percent'] < 70) { $color = 'yellow'; $percent = $top_performers[$z]['combined_percent']; }
                  if ($top_performers[$z]['combined_percent'] > 25 && $top_performers[$z]['combined_percent'] < 50) { $color = 'red'; $percent = $top_performers[$z]['combined_percent']; }
                  if ($top_performers[$z]['combined_percent'] <= 25) { $color = 'black'; $percent = $top_performers[$z]['combined_percent']; }

                  // Total all positive points.
                  $positive_points = 0;
                  $negative_points = 0;
                  
                  ($top_performers[$z]['vir_total_points'] > 0 ? $positive_points += $top_performers[$z]['vir_total_points'] : $negative_points += $top_performers[$z]['vir_total_points']);
                  ($top_performers[$z]['compliance_total_points'] > 0 ? $positive_points += $top_performers[$z]['compliance_total_points'] : $negative_points += $top_performers[$z]['compliance_total_points']);
                  ($top_performers[$z]['shipment_earned_points'] > 0 ? $positive_points += $top_performers[$z]['shipment_earned_points'] : $negative_points += $top_performers[$z]['shipment_earned_points']);
                  ($top_performers[$z]['task_activity_total_points'] > 0 ? $positive_points += $top_performers[$z]['task_activity_total_points'] : $negative_points += $top_performers[$z]['task_activity_total_points']);

                  // Which category is the best category?
                  
                  $sorted_array = [];
                  foreach ($top_performers[$z] as $key => $val) {
                    if ( (preg_match('/vir_total_points/', $key))
                        || (preg_match('/compliance_total_points/', $key))
                        || (preg_match('/shipment_earned_points/', $key))
                        || (preg_match('/task_activity_total_points/', $key))
                        || (preg_match('/quit_points/', $key)) ) {
                      array_push($sorted_array, $val);
                    }
                  }
                  arsort($sorted_array);         
                  $best_category = reset($sorted_array);                                           
                  $worst_category = end($sorted_array);
                  foreach ($top_performers[$z] as $key => $val) {                    
                    if ($val == $best_category) {                      
                      $best_category = $key;
                      if ($val == 0) { $best_category = 'N/A'; }
                    }
                    if ($val == $worst_category) {
                      $worst_category = $key;
                      if ($val == 0) { $worst_category = 'N/A'; }
                    }
                    if (preg_match('/vir/', $best_category)) { $best_category = 'VIR'; }
                    if (preg_match('/ship/', $best_category)) { $best_category = 'Boards'; }
                    if (preg_match('/task/', $best_category)) { $best_category = 'Activity'; }
                    if (preg_match('/compliance/', $best_category)) { $best_category = 'Compliance'; }
                    if (preg_match('/quiz/', $best_category)) { $best_category = 'Quiz'; }

                    if (preg_match('/vir/', $worst_category)) { $worst_category = 'VIR'; }
                    if (preg_match('/ship/', $worst_category)) { $worst_category = 'Boards'; }
                    if (preg_match('/task/', $worst_category)) { $worst_category = 'Activity'; }
                    if (preg_match('/compliance/', $worst_category)) { $worst_category = 'Compliance'; }
                    if (preg_match('/quiz/', $worst_category)) { $worst_category = 'Quiz'; }
                  }         
                  $all_stats_image = HTTP."/dist/img/usernophoto.jpg";                  
                  if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $top_performers[$z]['username'] . "_avatar")) {
                    $all_stats_image = HTTP."/dist/img/userimages/" . $top_performers[$z]['username'] . "_avatar";
                  }                  
                ?>
                  <tr>
                      <td height="30"><?php echo $counter; ?></td>
                      <td><img src="<?php echo $all_stats_image; ?>" alt="" width="24" height="24" class="img-circle"> <?php echo $top_performers[$z]['real_name'];?></td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-<?php echo $color; ?>" style="width: <?php echo $top_performers[$z]['combined_percent'];?>%"></div>
                      </div></td>
                      <td><span class="badge bg-<?php echo $color; ?>"><?php echo $top_performers[$z]['combined_percent'];?>%</span></td>
                      <td><span class="badge bg-green"><?php echo $positive_points; ?></span></td>
                      <td><span class="badge bg-black"><?php echo $negative_points; ?></span></td>
                      <td><span class="badge bg-purple"><?php echo $positive_points + $negative_points; ?></span></td>
                      <td><span class="label label-success"><?php echo $best_category; ?></span></td>
                      <td><span class="label label-danger"><?php echo $worst_category; ?></span></td>
                    </tr>
                <?php } ?>
                    

                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>         
        
        
          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header">

                    <h3 class="box-title">Shipment Updates</h3><br><h5><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></h5>
                       <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                       </div>
                  </blockquote>
                </div><!-- /.box-header -->
                <div class="box-body" style="overflow: auto; height: 500px;">
                  <table class="table table-bordered" id="shp_admin_stats">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <?php
                    $shipment_array = sort_array($shipment_aggregate,'percentage_earned');
                    $counter = 0;
                    for ($ship_i=0;$ship_i<count($shipment_array);$ship_i++) {
                      // Check to see If we're looking for a specific status.
                      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
                        if ($shipment_array[$ship_i]['status'] != $_GET['productivity_user_status']) {
                          continue;
                        }
                      }
                      $counter++;
                      echo($shipment_array[$compliance_i]['percentage_earned']);
                      if ($shipment_array[$ship_i]['percentage_earned'] >= 90) { $color = 'green'; $percent = $shipment_array[$ship_i]['percentage_earned'];}
                      if ($shipment_array[$ship_i]['percentage_earned'] >= 70 && $shipment_array[$ship_i]['percentage_earned'] < 90) { $color = 'blue'; $percent = $shipment_array[$ship_i]['percentage_earned']; }
                      if ($shipment_array[$ship_i]['percentage_earned'] > 50 && $shipment_array[$ship_i]['percentage_earned'] < 70) { $color = 'yellow'; $percent = $shipment_array[$ship_i]['percentage_earned']; }
                      if ($shipment_array[$ship_i]['percentage_earned'] > 25 && $shipment_array[$ship_i]['percentage_earned'] < 50) { $color = 'red'; $percent = $shipment_array[$ship_i]['percentage_earned']; }
                      if ($shipment_array[$ship_i]['percentage_earned'] <= 25) { $color = 'black'; $percent = $shipment_array[$ship_i]['percentage_earned']; }

                      // If the user is over 100% then drop it to 100
                      if ($shipment_array[$ship_i]['percentage_earned'] > 100) { $percent = 100; }
                    ?>
                    <tr>
                 <td><?php echo $counter;?></td>
                 <td>
                 <?php
                  $all_stats_image = HTTP."/dist/img/usernophoto.jpg";
                  foreach ($all_users_array as $key => $value) {
                    if ($all_users_array[$key]['employee_id'] == $shipment_array[$ship_i]['employee_id']) {
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar")) {
                        $all_stats_image = HTTP."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar";
                      }
                    }
                  }
                 ?>
                 <img src="<?php echo $all_stats_image;?>" width="24" height="24" class="img-circle">
                 <?php echo $shipment_array[$ship_i]['name'];?></td>

                 <td><div class="progress progress-xs progress-striped active">
                 <div class="progress-bar progress-bar-<?php echo "$color";?>" style="width: <?php echo $percent;?>%"></div>

                 <td><span class="badge bg-<?php echo $color;?>"><?php echo $shipment_array[$ship_i]['percentage_earned'] . "%";?></span></td>
                 </tr>
                    <?php } ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

<!-- End this box left side -->






              <div class="box">
                <div class="box-header">
              
                 <h3 class="box-title">Activity</h3>
<div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                  <?php                  
                    $task_array = sort_array($task_aggregate,'activity_total_points');
                    $counter = 0;
                    for ($task_i=0;$task_i<count($task_array);$task_i++) {
                      // Check to see If we're looking for a specific status.
                      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
                        if ($task_array[$task_i]['status'] != $_GET['productivity_user_status']) {
                          continue;
                        }
                      }
                      if ($task_array[$task_i]['activity_total_points'] >= 1) { $color = 'black'; $percent = 100; }
                      if ($task_array[$task_i]['activity_total_points'] < 1) { $color = 'green'; $percent = 0; }
                      $counter++;
                    ?>
                    <tr>
                 <td><?php echo $counter;?></td>
                 <td>
                 <?php
                  $all_stats_image = HTTP."/dist/img/usernophoto.jpg";
                  foreach ($all_users_array as $key => $value) {
                    if ($all_users_array[$key]['employee_id'] == $task_array[$task_i]['employee_id']) {
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar")) {
                        $all_stats_image = HTTP."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar";
                      }
                    }
                  }
                 ?>
                 <img src="<?php echo $all_stats_image;?>" width="24" height="24" class="img-circle">
                 <?php echo $task_array[$task_i]['real_name'];?></td>

                 <td><div class="progress progress-xs progress-striped active">
                 <div class="progress-bar progress-bar-<?php echo "$color";?>" style="width: <?php echo $percent;?>%"></div>

                 <td><span class="badge bg-<?php echo $color;?>"><?php echo $task_array[$task_i]['activity_total_points'];?></span></td>
                 </tr>
                    <?php } ?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box --><!-- /.box --><!-- /.box -->              
              
              
              
              
              
              
              
              
              
              
              
            </div><!-- /.col -->
            
            
         
            
            



            
            <!-- End Right Side Box Menus -->
            <div class="col-md-6">           
            </div><!-- /.col -->
            <div class="col-md-6">












            <!-- Start Left Side Box Menus -->
              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">VIR</h3><br><h5><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></h5>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                  <!-- /.Removing the Box Tools side element -->
                 <div class="box-tools">

                  </div>
                 
                </div><!-- /.box-header -->
                <div class="box-body" style="overflow: auto; height: 500px;">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                <?php
                    $vir_array = sort_array($vir_aggregate,'vir_total_percent');
                    $counter = 0;
                    for ($vir_i=0;$vir_i<count($vir_array);$vir_i++) {
                      // Check to see If we're looking for a specific status.
                      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
                        if ($vir_array[$vir_i]['status'] != $_GET['productivity_user_status']) {
                          continue;
                        }
                      }
                      $counter++;                      
                      if ($vir_array[$vir_i]['vir_total_percent'] >= 90) { $color = 'green'; $percent = $vir_array[$vir_i]['vir_total_percent'];}
                      if ($vir_array[$vir_i]['vir_total_percent'] >= 70 && $vir_array[$vir_i]['vir_total_percent'] < 90) { $color = 'blue'; $percent = $vir_array[$vir_i]['vir_total_percent']; }
                      if ($vir_array[$vir_i]['vir_total_percent'] > 50 && $vir_array[$vir_i]['vir_total_percent'] < 70) { $color = 'yellow'; $percent = $vir_array[$vir_i]['vir_total_percent']; }
                      if ($vir_array[$vir_i]['vir_total_percent'] > 25 && $vir_array[$vir_i]['vir_total_percent'] < 50) { $color = 'red'; $percent = $vir_array[$vir_i]['vir_total_percent']; }
                      if ($vir_array[$vir_i]['vir_total_percent'] <= 25) { $color = 'black'; $percent = $vir_array[$vir_i]['vir_total_percent']; }

                      // If the user is over 100% then drop it to 100
                      if ($vir_array[$vir_i]['vir_total_percent'] > 100) { $percent = 100; }
                    ?>
                    <tr>
                 <td><?php echo $counter;?></td>
                 <td><?php
                  $all_stats_image = HTTP."/dist/img/usernophoto.jpg";
                  foreach ($all_users_array as $key => $value) {
                    if ($all_users_array[$key]['employee_id'] == $vir_array[$vir_i]['employee_id']) {
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar")) {
                        $all_stats_image = HTTP."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar";
                      }
                    }
                  }
                 ?>
                 <img src="<?php echo $all_stats_image;?>" width="24" height="24" class="img-circle">
                 <?php echo $vir_array[$vir_i]['real_name'];?></td>

                 <td><div class="progress progress-xs progress-striped active">
                 <div class="progress-bar progress-bar-<?php echo "$color";?>" style="width: <?php echo $percent;?>%"></div>

                 <td><span class="badge bg-<?php echo $color;?>"><?php echo $vir_array[$vir_i]['vir_total_percent'];?></span></td>
                 </tr>
                    <?php } ?>

                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->











              <div class="box">
                <div class="box-header">
                  <h3 class="box-title">CSA Compliance</h3>
                  <div class="box-tools pull-right">
                  <!--          <div class="box"> -->
                  <!--Remove the div Class "box" above and add ?? to below primary collapsed -->
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>                  
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <table class="table table-bordered">
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>
                    <?php                    
                    $compliance_array = sort_array($csa_compliance_aggregate,'total_points');
                    $counter = 0;
                    for ($compliance_i=0;$compliance_i<count($compliance_array);$compliance_i++) {
                      // Check to see If we're looking for a specific status.
                      if (isset($_GET['productivity_user_status']) && $_GET['productivity_user_status'] != 'none' ) {                    
                        if ($compliance_array[$compliance_i]['status'] != $_GET['productivity_user_status']) {
                          continue;
                        }
                      }
                      $counter++;
                      if ($compliance_array[$compliance_i]['current_violation_points'] <= 0) 
                        { 
                          $color = 'green'; 
                          $percent = 100;
                        }else{
                          $color = 'black'; 
                          $percent = 0;
                        }                      
                    ?>
                    <tr>
                 <td><?php echo $counter;?></td>
                 <td><?php
                  $all_stats_image = HTTP."/dist/img/usernophoto.jpg";
                  foreach ($all_users_array as $key => $value) {
                    if ($all_users_array[$key]['employee_id'] == $compliance_array[$compliance_i]['employee_id']) {
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar")) {
                        $all_stats_image = HTTP."/dist/img/userimages/" . $all_users_array[$key]['username'] . "_avatar";
                      }
                    }
                  }
                 ?>
                 <img src="<?php echo $all_stats_image;?>" width="24" height="24" class="img-circle">
                 <?php echo $compliance_array[$compliance_i]['real_name'];?></td>

                 <td><div class="progress progress-xs progress-striped active">
                 <div class="progress-bar progress-bar-<?php echo "$color";?>" style="width: <?php echo $percent;?>%"></div>

                 <td><span class="badge bg-<?php echo $color;?>"><?php echo $compliance_array[$compliance_i]['current_violation_points'];?></span></td>
                 </tr>
                    <?php } ?>
                  </table>
                </div><!-- /.box-body -->
              </div>
            </div><!-- /.col -->
          </div><!-- /.row -->
          
          
          <!-- Bottom Box Full sized window -->

          </section>
          <!-- /.content -->
              </div><!-- /.content-wrapper -->







<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/footer.php');?>

<!-- Control Sidebar -->
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/r_sidebar.php');?>
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
           immediately after the control sidebar -->
<div class='control-sidebar-bg'></div>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.1.4 -->
<script src="<?php echo HTTP;?>/plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.2 JS -->
<script src="<?php echo HTTP;?>/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- Slimscroll -->
<script src="<?php echo HTTP;?>/plugins/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- FastClick -->
<script src='<?php echo HTTP;?>/plugins/fastclick/fastclick.min.js'></script>
<!-- AdminLTE App -->
<script src="<?php echo HTTP;?>/dist/js/app.min.js" type="text/javascript"></script>
<!-- ChartJS -->
<script src="<?php echo HTTP;?>/dist/js/Chart.min.js"></script>
<script>
// Get context with jQuery - using jQuery's .get() method.
var ctx = $("#dispatchChart").get(0).getContext("2d");
// This will get the first returned node in the jQuery collection.
<?php
# Create array with months.
$sql = "select monthname(str_to_date(pu_month,'%m-%y')),sum(pickups) from
(
SELECT
date_format(str_to_date(hawbDate,'%c/%e/%Y'),'%m-%y') pu_month,
sum(CASE monthname(str_to_date(hawbDate,'%c/%e/%Y'))
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'Octover' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    dispatch
WHERE

    puAgentDriverPhone = (SELECT 
            driverid
        FROM
            users
        WHERE
            username = \"$username\")           
AND 
str_to_date(hawbDate,'%c/%e/%Y') > DATE(now()) - INTERVAL 12 MONTH
group by pu_month
UNION ALL
SELECT
date_format(str_to_date(dueDate,'%c/%e/%Y'),'%m-%y') pu_month,
sum(CASE monthname(str_to_date(dueDate,'%c/%e/%Y'))
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'October' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    dispatch
WHERE

    delAgentDriverPhone = (SELECT 
            driverid
        FROM
            users
        WHERE
            username = \"$username\")           
AND 
str_to_date(dueDate,'%c/%e/%Y') > DATE(now()) - INTERVAL 12 MONTH
group by pu_month
) foo
group by pu_month
order by pu_month DESC";

$months = array();
$dispatch_number = array();
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result,MYSQL_BOTH))
{
  array_push($months,"'$row[0]'");
  array_push($dispatch_number,$row[1]);
}
mysql_free_result($result);

$months =  rtrim(implode(',',$months),',');
$dispatch_number =  rtrim(implode(',',$dispatch_number),',');
?>
var data = {
    labels: [<?php echo $months;?>],
    datasets: [
        {
            label: "Dispatched",
            fillColor: "rgba(220,220,220,0.2)",
            strokeColor: "rgba(220,220,220,1)",
            pointColor: "rgba(220,220,220,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: [<?php echo $dispatch_number;?>]
        },
<?php
$sql = "SELECT
monthname(date) pu_month,
SUM(CASE monthname(date)
WHEN 'January' THEN 1
WHEN 'February' THEN 1
WHEN 'March' THEN 1
WHEN 'April' THEN 1
WHEN 'May' THEN 1
WHEN 'June' THEN 1
WHEN 'July' THEN 1
WHEN 'August' THEN 1
WHEN 'September' THEN 1
WHEN 'October' THEN 1
WHEN 'November' THEN 1
WHEN 'December' THEN 1
ELSE 0
END) AS pickups
FROM
    driverexport
WHERE employee_id =
(select employee_id from users where username = \"$username\")
AND
date > DATE(now()) - INTERVAL 12 MONTH
AND
(status = 'Picked Up' OR status = 'Delivered')
group by pu_month
order by date DESC";

$months = array();
$dispatch_number = array();
$result = mysql_query($sql);
while ($row = mysql_fetch_array($result,MYSQL_BOTH))
{
  array_push($months,"'$row[0]'");
  array_push($dispatch_number,$row[1]);
}
mysql_free_result($result);

$months =  rtrim(implode(',',$months),',');
$dispatch_number =  rtrim(implode(',',$dispatch_number),',');
?>
       {
            label: "Updated",
            fillColor: "rgba(151,187,205,0.2)",
            strokeColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(151,187,205,1)",
            data: [<?php echo $dispatch_number;?>]
        },
    ]
};
var myLineChart = new Chart(ctx).Line(data, {
});
x = myLineChart.generateLegend();
$("#js-legend").html(x);
</script>

<script>
$(document).ready(function(){
  // Set some default values after load
  <?php // If we're an admin then change the username to 'all' ?>
  <?php if ($_SESSION['login'] == 1) { $username = "all"; } ?>

// Set the default values for the datepicker
$("#dt_start").val('<?php echo date('m/d/y',$start_date);?>');
$("#dt_end").val('<?php echo date('m/d/y',$end_date - 1);?>');

// Hide the user status div on startup 
if ($("#trip_search_driver").val() != 'none') {
      $("#productivity_user_status").hide();
}

});
</script>

<?php if ($_SESSION['login'] == 1) { ?>
<script>
function update_admin_shipment_info(json)
{
      var counter = 1;
      $("#shp_admin_stats tr").remove();
      var j = `                   <tr>
                      <th style="width: 10px">#</th>
                      <th>Name</th>
                      <th>Graph Score</th>
                      <th style="width: 40px">Score</th>
                    </tr>`;
      $("#shp_admin_stats").append(j);

      $.each(json, function() {
        var color = '';
        var percentage_earned = parseInt(this.percentage_earned,10);
        if (percentage_earned > 90) {
            color = 'green';
        }
        if (percentage_earned > 50 && percentage_earned <= 90) {
            color = 'yellow';
        }
        if (percentage_earned > 0 && percentage_earned <= 50) {
            color = 'red';
        }
        var i = `<tr>
                 <td>`+counter+`.</td>
                 <td><img src="../../dist/img/dash.jpg" width="24" height="24" class="img-circle"> `+this.username+`</td>

                 <td><div class="progress progress-xs progress-striped active">
                 <div class="progress-bar progress-bar-`+color+`" style="width: `+this.percentage_earned+`%"></div>

                 <td><span class="badge bg-`+color+`">`+this.percentage_earned+`%</span></td>
                 </tr>`;
        $("#shp_admin_stats").append(i);
        counter = counter + 1;
      });
/*    <tr>
      <td>1.</td>
      <td><img src="../../dist/img/dash.jpg" width="24" height="24" class="img-circle"> Dash</td>
     
      <td><div class="progress progress-xs progress-striped active">
        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
  
      <td><span class="badge bg-green">99%</span></td>
    </tr>
*/
}
</script>
<?php } ?>

<script>
function display_status_div()
{
    if ($("#trip_search_driver").val() == 'none') {
      $("#productivity_user_status").show();
    }else{
      $("#productivity_user_status").hide();
    }
}

function get_productivity_report(username,frequency)
{
    $.ajax({
     method: "GET",
     url: "<?php echo HTTP;?>/pages/dispatch/productivity_calculator.php",
     data: {
            username: username,
            frequency: frequency
          },
     success: function(data, textStatus, xhr) {
          var json = jQuery.parseJSON( data );
          <?php
          if ($_SESSION['login'] == 2)
          {
              // Update the shipment board if non-admin
          ?>
              //update_shipment_info(json);
          <?php
          }elseif($_SESSION['login'] == 1){
          ?>
              update_admin_shipment_info(json);
          <?php
          }
          ?>
      },
      error: function(xhr, textStatus, errorThrown) {
          console.log("error getting productivity report for "+username)
      }
    });
}
</script>
    <!-- Date Picker -->
    <script src="<?php echo HTTP;?>/dist/js/bootstrap-datepicker.js"></script>
    <script>
    $('.datepicker').datepicker({
    startDate: "2015-01-01",
    todayBtn: "linked",
    autoclose: true,
    datesDisabled: '0',
    todayHighlight: true,
    });
</script>

</body>
</html>
