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

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];

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

if ($_SESSION['login'] == 1)
{
    if(isset($_GET['trip_search_driver'])) {
        $emp_id = $_GET['trip_search_driver'];
    }else{
        $emp_id = $_SESSION['employee_id'];
    }
    $ship_sql = generate_ship_sql($emp_id,date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $shp_aggregate = get_sql_results($ship_sql,$mysqli);
  
    $vir_sql = generate_vir_sql(date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $vir_aggregate = get_sql_results($vir_sql,$mysqli);

    $vir_clockin_sql = generate_clockin_sql($emp_id,date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $vir_clockin_aggregate = get_sql_results($vir_clockin_sql,$mysqli);

    $csa_compliance_sql = generate_compliance_sql($emp_id);
    $csa_compliance_aggregate = get_sql_results($csa_compliance_sql,$mysqli);
}else{
    $emp_id = $_SESSION['employee_id'];
    $ship_sql = generate_ship_sql($emp_id,date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $shp_aggregate = get_sql_results($ship_sql,$mysqli);
  
    $vir_sql = generate_vir_sql(date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $vir_aggregate = get_sql_results($vir_sql,$mysqli);

    $vir_clockin_sql = generate_clockin_sql($emp_id,date('Y-m-d',$start_date),date('Y-m-d',$end_date));
    $vir_clockin_aggregate = get_sql_results($vir_clockin_sql,$mysqli);

    $csa_compliance_sql = generate_compliance_sql($emp_id);
    $csa_compliance_aggregate = get_sql_results($csa_compliance_sql,$mysqli);
}

// We'll generate another shp_aggregate array.  This time we'll loop
// through each employee.  This is for the stats section below.
// Truncate the existing table
$complete_ship_sql = "call company_shp_stats('".date('Y-m-d',$start_date)."','".date('Y-m-d',$end_date)."')";
run_sql($complete_ship_sql,$mysqli);
$complete_ship_sql = "select a.*, users.username from productivity_shipments a, users where a.date_start <= STR_TO_DATE('".date('Y-m-d',$start_date)."','%Y-%m-%d') 
                      and a.date_end >= STR_TO_DATE('".date('Y-m-d',$end_date)."','%Y-%m-%d')
                      and a.employee_id = users.employee_id ORDER BY percentage_earned desc";
$complete_ship_aggregate = get_sql_results($complete_ship_sql,$mysqli);

// Generate the sql for tasks
$task_sql = generate_task_sql(date('Y-m-d',$start_date),date('Y-m-d',$end_date));
$task_aggregate = get_sql_results($task_sql,$mysqli);

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

    <div class="col-sm-<?php if ($_SESSION['login'] == 1) { echo 10; }else{ echo 6; }?> pull-left well">
           <form name="frm_productivity" method="GET" action="" role="form" enctype="multipart/form-data">
              <div class="box-body">
               <div class="input-daterange input-group" id="datepicker">
                <input type="text" class="input-sm form-control datepicker" name="start" id="dt_start" data-date-format="mm/dd/yyyy"/ required>
                <span class="input-group-addon">to</span>
                <input type="text" class="input-sm form-control datepicker" name="end" id="dt_end" data-date-format="mm/dd/yyyy"/ required>
               </div>
               <?php if ($_SESSION['login'] == 1) { ?>
               <div class="input-group" id="driver">
                 <select class="input-sm form-control" name="trip_search_driver" id="trip_search_driver" value="" style="margin-top: 5px;">
                  <option value="null">All Drivers<option>
                    <?php for ($i=0; $i<sizeof($driver_array); $i++) { ?>
                      <option value=<?php echo $driver_array[$i]['employee_id'];
                       if ($driver_array[$i]['employee_id'] == $_GET['trip_search_driver']) { echo " selected "; } ?>
                      ><?php echo $driver_array[$i]['name'];?></option>
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

           <div class="col-md-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-blue">
                  <div class="widget-user-image">
                   <img src="
                    <?php 
                     if ($_SESSION['login'] == 1) { echo HTTP."/pages/dispatch/images/allusers.JPG"; }else{
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) { 
                        echo HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";}else{ echo HTTP . "dist/img/usernophoto.jpg"; 
                      }
                     }?>" 
                   alt="User Image" width="100" height="100" class="img-circle" />
                  <span class="fa-2x">Shipment</span></div>
                  <!-- Add text below Image Removed....
                  <span class="info-box-text">Shipments</span>
                  --> 
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Arrived Shipper <span class="pull-right badge bg-blue" id="shp_arrived_shipper">
                     <?php echo round($shp_aggregate[0]['arrived_to_shipper'],0) ." of ".round($shp_aggregate[0]['as_puagent'],0) + round($shp_aggregate[0]['as_pu_and_delagent'],0);?></span></a>
                    </li>
                    <li><a href="#">Arrived Shipper Points<span class="pull-right badge bg-blue" id="shp_arrived_shipper_points">
                     <?php echo round($shp_aggregate[0]['arrived_to_shipper_points'],0) ." of ".round($shp_aggregate[0]['max_arrived_to_shipper_points'],0);?></span></a>
                    </li>
                    <li><a href="#">Picked Up <span class="pull-right badge bg-blue" id="shp_picked_up">
                     <?php echo round($shp_aggregate[0]['picked_up'],0) ." of ".round($shp_aggregate[0]['as_puagent'],0) + round($shp_aggregate[0]['as_pu_and_delagent'],0);?></span></a>
                    </li>
                    <li><a href="#">Picked Up Points<span class="pull-right badge bg-blue" id="shp_picked_up_points">
                     <?php echo round($shp_aggregate[0]['picked_up_points'],0) ." of ".round($shp_aggregate[0]['max_picked_up_points'],0);?></span></a>
                    </li>
                    <li><a href="#">Arrived Consignee <span class="pull-right badge bg-blue" id="shp_arrived_consignee">
                     <?php echo round($shp_aggregate[0]['arrived_to_consignee'],0) ." of ".round($shp_aggregate[0]['as_delagent'],0) + round($shp_aggregate[0]['as_pu_and_delagent'],0);?></span></a>
                    </li>
                    <li><a href="#">Arrived Consignee Points<span class="pull-right badge bg-blue" id="shp_arrived_consignee_points">
                     <?php echo round($shp_aggregate[0]['arrived_to_consignee_points'],0) ." of ".round($shp_aggregate[0]['max_arrived_to_consignee_points'],0);?></span></a>
                    </li>
                    <li><a href="#">Delivered <span class="pull-right badge bg-blue" id="shp_delivered">
                     <?php echo round($shp_aggregate[0]['delivered'],0) ." of ".round($shp_aggregate[0]['as_delagent'],0) + round($shp_aggregate[0]['as_pu_and_delagent'],0);?></span></a>
                    </li>
                    <li><a href="#">Delivered Points<span class="pull-right badge bg-blue" id="shp_delivered_points">
                     <?php echo round($shp_aggregate[0]['delivered_points'],0) ." of ".round($shp_aggregate[0]['max_delivered_points'],0);?></span></a>
                    </li>
                    <li><a href="#">Accessorials Added <span class="pull-right badge bg-blue" id="shp_accessorials">
                     <?php echo round($shp_aggregate[0]['accessorial_count'],0) ." of ".round($shp_aggregate[0]['as_puagent'],0) + round($shp_aggregate[0]['as_delagent'],0) + round($shp_aggregate[0]['as_pu_and_delagent'],0);?></span></a>
                    </li>
                    <li><a href="#">Accessorials Added Points<span class="pull-right badge bg-blue" id="shp_accessorials_points">
                     <?php echo round($shp_aggregate[0]['accessorial_points'],0);?></span></a>
                    </li>
                    <li><a href="#">Other Status Change <span class="pull-right badge bg-blue" id="shp_other_status">
                     <?php echo round($shp_aggregate[0]['misc_updates_sum'],0) ." of ".round($shp_aggregate[0]['as_puagent'],0) + round($shp_aggregate[0]['as_delagent'],0) + round($shp_aggregate[0]['as_pu_and_delagent'],0);?></span></a>
                    </li>
                    <li><a href="#">Other Status Change Points<span class="pull-right badge bg-blue" id="shp_other_status_points">
                     <?php echo round($shp_aggregate[0]['misc_updates_sum'],0);?></span></a>
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
                    <img src="
                    <?php
                     if ($_SESSION['login'] == 1) { echo HTTP."/pages/dispatch/images/allusers.JPG"; }else{
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) {
                        echo HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";}else{ echo HTTP . "dist/img/usernophoto.jpg";
                      }
                     }?>"
                    alt="User Avatar" width="100" height="100" class="img-circle">
                  <span class="fa-2x">VIR'S</span></div>
                  <!-- Add text below Image Removed.... 
                  <span class="info-box-text"> VIRS</span>
                  -->
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Days Worked <span class="pull-right badge bg-blue" id="vir_days_worked">
                     <?php
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                            echo $vir_aggregate[$vir_i]['days_worked'];
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Pre-Trips <span class="pull-right badge bg-blue" id="vir_pretrip">
                       <?php
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                            echo $vir_aggregate[$vir_i]['vir_pretrip'];
                       }
                     }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Pre-Trip Points<span class="pull-right badge bg-blue" id="vir_pretrip_points">
                     <?php
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           if ($vir_aggregate[$vir_i]['vir_pretrip'] > $vir_aggregate[$vir_i]['days_worked']) {
                             echo $vir_aggregate[$vir_i]['days_worked'];
                           }else{
                             echo $vir_aggregate[$vir_i]['vir_pretrip'];
                           }
                          }
                        } 
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Post-Trips <span class="pull-right badge bg-blue" id="vir_posttrip">
                     <?php
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           echo $vir_aggregate[$vir_i]['vir_posttrip'];
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Post-Trip Points<span class="pull-right badge bg-blue" id="vir_posttrip_points">
                     <?php
                       for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           if ($vir_aggregate[$vir_i]['vir_posttrip'] > $vir_aggregate[$vir_i]['days_worked']) {
                             echo $vir_aggregate[$vir_i]['days_worked'];
                           }else{
                             echo $vir_aggregate[$vir_i]['vir_posttrip'];
                           }
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Breakdowns <span class="pull-right badge bg-blue" id="vir_breakdown">
                     <?php
                        for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                          if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                           echo $vir_aggregate[$vir_i]['vir_breakdown'];
                          }
                        }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Breakdown Points<span class="pull-right badge bg-blue" id="vir_breakdown_points">
                     <?php
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
                  <div class="widget-user-image"><span class="fa-2x"><img src="
                    <?php
                     if ($_SESSION['login'] == 1) { echo HTTP."/pages/dispatch/images/allusers.JPG"; }else{
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) {
                        echo HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";}else{ echo HTTP . "dist/img/usernophoto.jpg";
                      }
                     }?>"
                     alt="User Avatar" width="100" height="100" class="img-circle">Productivity</span></div>
                  <!-- Add text below Image Removed....
                  <span class="info-box-text"> Productivity</span>
                  -->
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Tasks<span class="pull-right badge bg-blue" id="prod_task">
                    <?php for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {?>
                     <?php if ($task_aggregate[$task_i]['assign_to'] == $emp_id) { ?>
                     <?php echo $task_aggregate[$task_i]['tasks']; ?>
                     <?php } ?>
                    <?php } ?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Task Points<span class="pull-right badge bg-blue" id="prod_task_points">
                    <?php for($task_i=0;$task_i<(count($task_aggregate));$task_i++) {?>
                    <?php if ($task_aggregate[$task_i]['assign_to'] == $emp_id) { ?>
                     <?php echo $task_aggregate[$task_i]['tasks']; ?>
                     <?php } ?>
                    <?php } ?>
                    </span>
                     </a>
                    </li>
                    <li><a href="#">Quiz<span class="pull-right badge bg-blue" id="prod_quiz"></span></a></li>
                    <li><a href="#">Quiz Points<span class="pull-right badge bg-blue" id="prod_quit_points"></span></a></li>
                    <li><a href="#">Idle Time<span class="pull-right badge bg-blue" id="prod_idletime"></span></a></li>
                    <li><a href="#">Idle Time Score<span class="pull-right badge bg-blue" id="prod_idletime_points"></span></a></li>
                    <li><a href="#">Company Compliance <span class="pull-right badge bg-blue" id="prod_company_compliance"></span></a></li>
                    <li><a href="#">Compnay Compliance Points<span class="pull-right badge bg-blue" id="prod_company_compliance_points"></span></a></li>
                  </ul>
                </div>
              </div><!-- /.widget-user -->
            </div>
           <div class="col-md-3">
              <!-- Widget: user widget style 1 -->
              <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-orange">
                  <div class="widget-user-image"><img src="
                    <?php
                     if ($_SESSION['login'] == 1) { echo HTTP."/pages/dispatch/images/allusers.JPG"; }else{
                      if (file_exists($_SERVER['DOCUMENT_ROOT']."/dist/img/userimages/" . $_SESSION['username'] . "_avatar")) {
                        echo HTTP."/dist/img/userimages/" . $_SESSION['username'] . "_avatar";}else{ echo HTTP . "dist/img/usernophoto.jpg";
                      }
                     }?>"
                    alt="User Avatar" width="100" height="100" class="img-circle"><span class="fa-2x">Compliance</span></div>
                </div>
                <div class="box-footer no-padding">
                  <ul class="nav nav-stacked">
                    <li><a href="#">Total Compliance Points<span class="pull-right badge bg-blue" id="csa_total_points">
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Total Points') {
                        if (empty($csa_compliance_aggregate[$i]['total_points'])) { echo 0; }else{ echo $csa_compliance_aggregate[$i]['total_points']; }
                       }
                     }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Compliance Cash<span class="pull-right badge bg-blue" id="csa_cash">
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Total Points') {
                        if (empty($csa_compliance_aggregate[$i]['points_cash_value'])){ echo 0; }else{ echo $csa_compliance_aggregate[$i]['points_cash_value']; }
                       }
                     }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">HOS Compliance<span class="pull-right badge bg-blue" id="csa_hos">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'HOS Compliance') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if ($found != 1 ) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Unsafe Driving<span class="pull-right badge bg-blue" id="csa_unsafe">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Unsafe Driving') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if (empty($found)) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Vehicle Maint.<span class="pull-right badge bg-blue" id="csa_maint">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Vehicle Maint.') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if (empty($found)) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Driver Fitness<span class="pull-right badge bg-blue" id="csa_fitness">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Driver Fitness') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if (empty($found)) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Controlled Substances/Alcohol<span class="pull-right badge bg-blue" id="csa_substance">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Controlled Substances/Alcohol') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if (empty($found)) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Hazardous Materials (HM)<span class="pull-right badge bg-blue" id="csa_hazardous">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Hazardous Materials (HM)') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if (empty($found)) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">Crash Indicator<span class="pull-right badge bg-blue" id="csa_crash">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'Crash Indicator') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if (empty($found)) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                    <li><a href="#">No Violation<span class="pull-right badge bg-blue" id="csa_no_violation">
                     <?php $found = 0; ?>
                     <?php for($i=0;$i<count($csa_compliance_aggregate);$i++) { ?>
                       <?php if ($csa_compliance_aggregate[$i]['basic'] == 'No Violation') {
                        echo $csa_compliance_aggregate[$i]['total_points'];
                        $found = 1;
                       }
                     }
                       if (empty($found)) { echo 0; }
                     ?>
                     </span></a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.widget-user -->
            </div>

        </div>

<!-- ======================New Section Colored Boxes============ -->
          <!-- Boxes with Icon on Right side (Status box) -->
          <div class="row">
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-blue">
                <div class="inner">
                  <h4 id="shp_points" style="text-align: center; font-size: 2em;">Points: <?php echo round($shp_aggregate[0]['earned_points'],2);?> of <?php echo round($shp_aggregate[0]['max_points'],2);?></h4>
                  <h4 id="shp_percent" style="text-align: center; font-size: 3em;"><?php echo round($shp_aggregate[0]['percentage_earned'],2) . "%";?></h4>
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
                    for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++) {
                      if ($vir_aggregate[$vir_i]['employee_id'] == $emp_id) {
                        $total_vir_percent = $vir_aggregate[$vir_i]['vir_total_percent'];
                        $total_vir_points = $vir_aggregate[$vir_i]['vir_pretrip'] + $vir_aggregate[$vir_i]['vir_posttrip'] + $vir_aggregate[$vir_i]['vir_breakdown'];
                        $days_worked = $vir_aggregate[$vir_i]['days_worked'];
                      }
                    }
                 ?> 
                  <h4 id="shp_points" style="text-align: center; font-size: 2em;">Points: <?php echo $total_vir_points;?> of <?php echo $days_worked;?></h4>
                  <h4 id="shp_percent" style="text-align: center; font-size: 3em;"><?php echo round($total_vir_percent,2) . "%";?></h4>
                </div>
               </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-purple">
                <div class="inner">
                  <h3>Score <?php echo "$pu_today_count";?> 85%</h3>
                  <p>As of PHP Select Year, Quarter, Month</p>
                </div>
                <div class="icon"> <i class="ion ion-person-add"></i> </div>
                <a href="#" class="small-box-footer">More info (go to below item current page)<i class="fa fa-arrow-circle-right"></i> <i class="fa fa-arrow-circle-right"></i> </a> </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-orange">
                <div class="inner">
                  <h4 id="shp_points" style="text-align: center; font-size: 2em;">Points:
                       <?php
                       for($i=0;$i<count($csa_compliance_aggregate);$i++) {
                         if ($csa_compliance_aggregate[$i]['basic'] == 'Total Points') {
                            $my_total_points = $csa_compliance_aggregate[$i]['total_points'];
                         }
                         if ($csa_compliance_aggregate[$i]['basic'] == 'Total Company Points') {
                            $company_total_points = $csa_compliance_aggregate[$i]['total_points'];
                         }
                       }
                       ?>
                      <?php echo $my_total_points;?> of <?php echo $company_total_points; ?>
                  </h4>
                  <h4 id="shp_percent" style="text-align: center; font-size: 3em;"><?php echo round($company_total_points / $my_total_points,2) . "%";?></h4>
                </div>
                <div class="icon"> <i class="fa fa-cog fa-spin"></i> </div>
              </div>
            </div>
            <!-- ./col -->
          </div>
          <!-- /.row -->



          <!-- Boxes with Icon on Right side (Status box) -->
        <div class="row">
          <div class="col-lg-12 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <center>
                    <h3>Combined Score <?php echo "$pu_today_count";?> 75% </h3></center>
                  <center><p>Total Points All Categories 150 of 200 as of Current Selection Year, Quarter, Month</p></center>
                </div>
                <div class="icon"> <i class="fa fa-cog fa-spin"></i> </div>
                <!--<a href="#" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a> </div>-->
          </div>
          <!-- ./col --><!-- ./col --><!-- ./col --><!-- ./col -->
          </div>
          <!-- /.row -->









          <div class="row">
            <div class="col-md-12">
              <div class="box"><!-- /.box-header --><!-- ./box-body -->


 





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
                    <tr>
                      <td height="30">1</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 100%"></div>
                      </div></td>
                      <td><span class="badge bg-green">100%</span></td>
                      <td><span class="badge bg-green">1800</span></td>
                      <td><span class="badge bg-black">10</span></td>
                      <td><span class="badge bg-purple">1790</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>2</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 90%"></div>
                      </div></td>
                      <td><span class="badge bg-green">90%</span></td>
                      <td><span class="badge bg-green">1655</span></td>
                      <td><span class="badge bg-black">15</span></td>
                      <td><span class="badge bg-purple">1640</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>3</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 89%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">89%</span></td>
                      <td><span class="badge bg-green">1501</span></td>
                      <td><span class="badge bg-black">32</span></td>
                      <td><span class="badge bg-purple">1469</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>4</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 70%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">70%</span></td>
                      <td><span class="badge bg-green">1312</span></td>
                      <td><span class="badge bg-black">56</span></td>
                      <td><span class="badge bg-purple">1256</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>5</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 69%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">69%</span></td>
                      <td><span class="badge bg-green">1122</span></td>
                      <td><span class="badge bg-black">98</span></td>
                      <td><span class="badge bg-purple">1024</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>6</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 50%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">50%</span></td>
                      <td><span class="badge bg-green">938</span></td>
                      <td><span class="badge bg-black">122</span></td>
                      <td><span class="badge bg-purple">816</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>7</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 49%"></div>
                      </div></td>
                      <td><span class="badge bg-red">49%</span></td>
                      <td><span class="badge bg-green">743</span></td>
                      <td><span class="badge bg-black">155</span></td>
                      <td><span class="badge bg-purple">588</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>8</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 26%"></div>
                      </div></td>
                      <td><span class="badge bg-red">26%</span></td>
                      <td><span class="badge bg-green">422</span></td>
                      <td><span class="badge bg-black">187</span></td>
                      <td><span class="badge bg-purple">235</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>9</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td>
                      <!-- Changed CSS to Custom CSS Sytel for Grey Bar.... -->
                      <div class="progress progress-striped active">
                      <div class="progress-bar progress-bar-black" style="width: 25%"></div>
                      </div>
                      <!-- Original CSS Danger Style removed for Grey Bar
                      <div class="progress progress-xs progress-striped active">
                      <div class="progress-bar progress-bar-danger" style="width: 25%"></div>
                      </div> --> 
                      </td>
                      <td><span class="badge bg-black">25%</span></td>
                      <td><span class="badge bg-green">375</span></td>
                      <td><span class="badge bg-black">234</span></td>
                      <td><span class="badge bg-purple">141</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                    <tr>
                      <td>10</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-striped active">
                        <div class="progress-bar progress-bar-black" style="width: 1%"></div>
                      </div></td>
                      <td><span class="badge bg-black">1%</span></td>
                      <td><span class="badge bg-green">303</span></td>
                      <td><span class="badge bg-black">255</span></td>
                      <td><span class="badge bg-purple">48</span></td>
                      <td><span class="label label-success">Boards</span></td>
                      <td><span class="label label-danger">VIR</span></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div>
          </div>         
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        
          <div class="row">
            <div class="col-md-6">
              <div class="box">
                <div class="box-header">

                    <h3 class="box-title"> Shipment Updates</h3><br><h5><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></h5>
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
for ($shp_i=0;$shp_i<count($complete_ship_aggregate);$shp_i++)
{
// Get our colors and width
if ($complete_ship_aggregate[$shp_i]['percentage_earned'] >= 90) { $color = 'green'; $percent = $complete_ship_aggregate[$shp_i]['percentage_earned'];}
if ($complete_ship_aggregate[$shp_i]['percentage_earned'] >= 70 && $complete_ship_aggregate[$shp_i]['percentage_earned'] < 90) { $color = 'blue'; $percent = $complete_ship_aggregate[$shp_i]['percentage_earned']; }
if ($complete_ship_aggregate[$shp_i]['percentage_earned'] > 50 && $complete_ship_aggregate[$shp_i]['percentage_earned'] < 70) { $color = 'yellow'; $percent = $complete_ship_aggregate[$shp_i]['percentage_earned']; }
if ($complete_ship_aggregate[$shp_i]['percentage_earned'] > 25 && $complete_ship_aggregate[$shp_i]['percentage_earned'] < 50) { $color = 'red'; $percent = $complete_ship_aggregate[$shp_i]['percentage_earned']; }
if ($complete_ship_aggregate[$shp_i]['percentage_earned'] <= 25) { $color = 'black'; $percent = $complete_ship_aggregate[$shp_i]['percentage_earned']; }

// If the user is over 100% then drop it to 100
if ($complete_ship_aggregate[$shp_i]['percentage_earned'] > 100) { $percent = 100; }
?>
<tr>
                 <td><?php echo $shp_i+1;?></td>
                 <td><img src="../../dist/img/dash.jpg" width="24" height="24" class="img-circle"><?php echo $complete_ship_aggregate[$shp_i]['username'];?></td>

                 <td><div class="progress progress-xs progress-striped active">
                 <div class="progress-bar progress-bar-<?php echo "$color";?>" style="width: <?php echo $percent;?>%"></div>

                 <td><span class="badge bg-<?php echo $color;?>"><?php echo $percent;?></span></td>
                 </tr>
<?php                 
}
?>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

<!-- End this box left side -->






              <div class="box">
                <div class="box-header">
              
                 <h3 class="box-title">Compliance</h3>
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
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
                    </tr>
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
                  <h3 class="box-title"> VIR</h3><br><h5><?php echo date('m/d/y',$start_date) . " - " . date('m/d/y',$end_date);?></h5>
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
for ($vir_i=0;$vir_i<count($vir_aggregate);$vir_i++)
{
// Get our colors and width
if ($vir_aggregate[$vir_i]['vir_total_percent'] >= 90) { $color = 'green'; $percent = $vir_aggregate[$vir_i]['vir_total_percent'];}
if ($vir_aggregate[$vir_i]['vir_total_percent'] >= 70 && $vir_aggregate[$vir_i]['vir_total_percent'] < 90) { $color = 'blue'; $percent = $vir_aggregate[$vir_i]['vir_total_percent']; }
if ($vir_aggregate[$vir_i]['vir_total_percent'] > 50 && $vir_aggregate[$vir_i]['vir_total_percent'] < 70) { $color = 'yellow'; $percent = $vir_aggregate[$vir_i]['vir_total_percent']; }
if ($vir_aggregate[$vir_i]['vir_total_percent'] > 25 && $vir_aggregate[$vir_i]['vir_total_percent'] < 50) { $color = 'red'; $percent = $vir_aggregate[$vir_i]['vir_total_percent']; }
if ($vir_aggregate[$vir_i]['vir_total_percent'] <= 25) { $color = 'black'; $percent = $vir_aggregate[$vir_i]['vir_total_percent']; }

// If the user is over 100% then drop it to 100
if ($vir_aggregate[$vir_i]['vir_total_percent'] > 100) { $percent = 100; }
?>
<tr>
                 <td><?php echo $vir_i+1;?></td>
                 <td><img src="../../dist/img/dash.jpg" width="24" height="24" class="img-circle"><?php echo $vir_aggregate[$vir_i]['real_name'];?></td>

                 <td><div class="progress progress-xs progress-striped active">
                 <div class="progress-bar progress-bar-<?php echo "$color";?>" style="width: <?php echo $percent;?>%"></div>

                 <td><span class="badge bg-<?php echo $color;?>"><?php echo $percent;?></span></td>
                 </tr>
<?php
}
?>
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
                    <tr>
                      <td>1.</td>
                      <td><img src="../../dist/img/dash.jpg" alt="" width="24" height="24" class="img-circle"> Dash</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 99%"></div>
                      </div></td>
                      <td><span class="badge bg-green">99%</span></td>
                    </tr>
                    <tr>
                      <td>2.</td>
                      <td><img src="../../dist/img/violet.jpg" alt="" width="24" height="24" class="img-circle"> Violote</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-success" style="width: 85%"></div>
                      </div></td>
                      <td><span class="badge bg-green">80%</span></td>
                    </tr>
                    <tr>
                      <td>3.</td>
                      <td><img src="../../dist/img/jack.jpg" alt="" width="24" height="24" class="img-circle">Jack Jack</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 79%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">79%</span></td>
                    </tr>
                    <tr>
                      <td>4.</td>
                      <td><img src="../../dist/img/edna.jpg" alt="" width="24" height="24" class="img-circle">Edna Mode</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-primary" style="width: 66%"></div>
                      </div></td>
                      <td><span class="badge bg-light-blue">60%</span></td>
                    </tr>
                    <tr>
                      <td>5.</td>
                      <td><img src="../../dist/img/Gilbert Huph.jpg" alt="" width="24" height="24" class="img-circle">Gilbert Huph</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 58%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">59%</span></td>
                    </tr>
                    <tr>
                      <td>6.</td>
                      <td><img src="../../dist/img/syndrome.jpg" alt="" width="24" height="24" class="img-circle">Syndrome</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-yellow" style="width: 40%"></div>
                      </div></td>
                      <td><span class="badge bg-yellow">40%</span></td>
                    </tr>
                    <tr>
                      <td>7.</td>
                      <td><img src="../../dist/img/bernie kropp.jpg" alt="" width="24" height="24" class="img-circle"> Burnie Kropp</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">39%</span></td>
                    </tr>
                    <tr>
                      <td>8.</td>
                      <td><img src="../../dist/img/frank.jpg" alt="" width="24" height="24" class="img-circle"> Frank</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">20%</span></td>
                    </tr>
                    <tr>
                      <td>9.</td>
                      <td><img src="../../dist/img/h2tyd 3.jpg" alt="" width="24" height="24" class="img-circle"> Hector Axe</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 19%"></div>
                      </div></td>
                      <td><span class="badge bg-red">19%</span></td>
                    </tr>
                    <tr>
                      <td>10.</td>
                      <td><img src="../../dist/img/tony rydinger.jpg" alt="" width="24" height="24" class="img-circle"> Troy Hydinger</td>
                      <td><div class="progress progress-xs progress-striped active">
                        <div class="progress-bar progress-bar-danger" style="width: 01%"></div>
                      </div></td>
                      <td><span class="badge bg-red">01%</span></td>
                    </tr>
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

  get_productivity_report("<?php echo $username;?>","day");

   $("#productivity_time").change(function() {
    $("#productivity_time option:selected").each(function() {
      var productivity = $( this ).val();
      // Now, set the HTML based on 'productivity'
      if (productivity == 'day') {
        get_productivity_report("<?php echo $username;?>","day");
      }
      if (productivity == 'week') {
        get_productivity_report("<?php echo $username;?>","week");
      }
      if (productivity == 'month') {
        get_productivity_report("<?php echo $username;?>","month");
      }
      if (productivity == 'quarter') {
        get_productivity_report("<?php echo $username;?>","quarter");
      }
      if (productivity == 'year') {
        get_productivity_report("<?php echo $username;?>","year");
      }
      if (productivity == 'all') {
        get_productivity_report("<?php echo $username;?>","all");
      }
    });
  });

// Set the default values for the datepicker
$("#dt_start").val('<?php echo date('m/d/y',$start_date);?>');
$("#dt_end").val('<?php echo date('m/d/y',$end_date);?>');
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
