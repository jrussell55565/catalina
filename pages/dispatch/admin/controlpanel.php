<?php
session_start();

if (($_SESSION['login'] != 2) && ($_SESSION['login'] != 1))
{
        header('Location: /pages/login/driverlogin.php');
}

include("$_SERVER[DOCUMENT_ROOT]/dist/php/global.php");
mysql_connect($db_hostname, $db_username, $db_password) or DIE('Connection to host is failed, perhaps the service is down!');
mysql_select_db($db_name) or DIE('Database name is not available!');

$username = $_SESSION['userid'];
$drivername = $_SESSION['drivername'];
$role = $_SESSION['role'];

?>

<?php
                     $sql = "SELECT
                      total_today.counts   AS total_today_count,
                      pu_today.counts      AS pu_today_count,
                      del_today.counts     AS del_today_count,
                      total_alltime.counts AS total_alltime_count,
                      pu_alltime.counts    AS pu_alltime_count,
                      del_alltime.counts   AS del_alltime_count,
                      archived.counts      AS archived_count,
                      virs_daily.count     AS virs_daily_count,
                      virs_weekly.count    AS virs_weekly_count
                    FROM
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          AND str_to_date(hawbDate,'%c/%e/%Y') = CURDATE()
                          AND deleted                          =\"F\"
                          AND archived                         =\"F\"
                          )
                        OR
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username =\"$username\"
                            )
                          AND str_to_date(dueDate,'%c/%e/%Y') = CURDATE()
                          AND deleted                         =\"F\"
                          AND archived                        =\"F\"
                          )
                      )
                      total_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          puAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND str_to_date(hawbDate,'%c/%e/%Y') = DATE(now())
                        AND deleted                          =\"F\"
                        AND archived                         =\"F\"
                        AND deleted                          =\"F\"
                        AND archived                         =\"F\"
                      )
                      pu_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          delAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND str_to_date(dueDate,'%c/%e/%Y') = DATE(now())
                        AND deleted                         =\"F\"
                        AND archived                        =\"F\"
                        AND deleted                         =\"F\"
                        AND archived                        =\"F\"
                      )
                      del_today,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          OR puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      total_alltime,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          puAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      pu_alltime,
                      (
                        SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          delAgentDriverPhone=
                          (
                            SELECT
                              driverid
                            FROM
                              users
                            WHERE
                              username=\"$username\"
                          )
                        AND deleted =\"F\"
                        AND archived=\"F\"
                      )
                      del_alltime,
                      (
                      SELECT
                          COUNT(*) AS counts
                        FROM
                          dispatch
                        WHERE
                          (
                            delAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          OR puAgentDriverPhone=
                            (
                              SELECT
                                driverid
                              FROM
                                users
                              WHERE
                                username=\"$username\"
                            )
                          )
                        AND deleted =\"F\"
                        AND archived=\"T\"
                      )
                      archived,
                     (
                      SELECT
                          COUNT(*) AS count
                        FROM
                          virs
                        WHERE
                        driver_name=\"$username\"
                        AND insp_date = date(now())
                      ) virs_daily,
                      (
                      SELECT
                          COUNT(*) AS count
                        FROM
                          virs
                        WHERE
                        driver_name=\"$username\"
                        AND insp_date BETWEEN date(now()) AND date(now()) - INTERVAL 8 DAY
                      ) virs_weekly";

                      $sql = mysql_query($sql);
                      while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
                      {
                        $total_today_count   = $row['total_today_count'];
                        $pu_today_count      = $row['pu_today_count'];
                        $del_today_count     = $row['del_today_count'];
                        $total_alltime_count = $row['total_alltime_count'];
                        $pu_alltime_count    = $row['pu_alltime_count'];
                        $del_alltime_count   = $row['del_alltime_count'];
                        $archived_count      = $row['archived_count'];
                        $virs_daily_count    = $row['virs_daily_count'];
                        $virs_weekly_count   = $row['virs_weekly_count'];
                      }
                      mysql_free_result($sql);

if (isset($_POST['submit']) && $_POST['submit'] == 'share')
{
  $audience = $_POST['audience'];
  if ($audience == 'PHX')
  {
    $predicate = "AND office='PHX'";
  }
  if ($audience == 'TUS')
  {
    $predicate = "AND office='TUS'";
  }
    if ($audience == 'PHL')
  {
    $predicate = "AND office='PHL'";
  }
    if ($audience == 'DEN')
  {
    $predicate = "AND office='DEN'";
  }
    if ($audience == 'LAX')
  {
    $predicate = "AND office='LAX'";
  }
    if ($audience == 'MIA')
  {
    $predicate = "AND office='MIA'";
  }
    if ($audience == 'ORD')
  {
    $predicate = "AND office='ORD'";
  }
  $message = $_POST['message'];
  $sql = "SELECT 1";
  if (isset($_POST['sendEmail']))
  {
    $sql .= ",email";
  } 
  if (isset($_POST['sendText']))
  {
    $sql .= ",vtext";
  } 
  $sql .= " FROM users WHERE 1=1 $predicate AND status='Active'";

  $sql = mysql_query($sql);
  while ($row = mysql_fetch_array($sql, MYSQL_BOTH))
  {
    if (isset($_POST['sendEmail']))
    {
      sendEmail($row['email'],'Broadcast Message',$message); 
    } 
    if (isset($_POST['sendText']))
    {
      sendEmail($row['vtext'],'Broadcast Message',$message); 
    }
  }
  mysql_free_result($sql);

}
?>


<!DOCTYPE html>
<html>
<head>
<BASE href="http://dispatch.catalinacartage.com">
<meta charset="UTF-8">
<title>Control Panel</title>
<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/favicon/favicon.php');?>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<!-- Bootstrap 3.3.4 -->
<link href="<?php echo HTTP;?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome Icons -->
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="<?php echo HTTP;?>/dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
<link href="<?php echo HTTP;?>/dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />

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
            Admin Control Panel / <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
            <?php if ($_SESSION['login'] == 1) { echo "(Admin)"; }?>
            </a></h1>

          <ol class="breadcrumb">
            <li><a href="/pages/main/index.php"><i class="fa fa-home"></i> Home</a></li>
            <li class="active">Admin Controll Panel</li>
          </ol>
        </section>

<!-- Animated Top Menu Insert PHP Reference to /wwwlive/dist/menus_sidebars_elements  -->

<?php require($_SERVER['DOCUMENT_ROOT'].'/dist/menus_sidebars_elements/topmenuanimation.php');?>

<!-- End Animated Top Menu -->  


 
 
        <!-- Main content -->
        

<h2 class="page-header">Driver Score Calcs</h2>

<!-- =========================================================== -->
          <div class="row">
 <!-- ====================box 1 in section 2========================== -->            
          
            <div class="col-md-3">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Shipment Calculations</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table width="371" border="0">
                    <tr>
                      <td>Info</td>
                      <td>A Point Multi</td>
                      <td width="127">C Point Weight</td>
                    </tr>
                    <tr>
                      <td width="116">Arrived Shipper</td>
                      <td><input name="arrived_shipper_apoint" type="arrived_shipper_apoint" id="arrived_shipper_apoint" value="1" size="1"></td>
                      <td><input name="arrived_shipper_cpoint" type="arrived_shipper_cpoint" id="arrived_shipper_cpoint" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Picked Up</td>
                      <td width="114"><input name="picked_up_apoint" type="picked_up_apoint" id="picked_up_apoint" value="1" size="1"></td>
                      <td><input name="picked_up_cpoint" type="picked_up_cpoint" id="picked_up_cpoint" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td width="116">Arrived Consignee</td>
                      <td><input name="arrived_consignee_apoint" type="arrived_consignee_apoint" id="arrived_consignee_apoint" value="1" size="1"></td>
                      <td><input name="arrived_consignee_cpoint" type="arrived_consignee_cpoint" id="arrived_consignee_cpoint" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Delivered</td>
                      <td><input name="delivered_apoint" type="delivered_apoint" id="delivered_apoint" value="1" size="1"></td>
                      <td><input name="delivered_cpoint" type="delivered_cpoint" id="delivered_cpoint" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td height="22">Non-Core Update</td>
                      <td><input name="noncore_apoint" type="noncore_apoint" id="noncore_apoint" value="1" size="1"></td>
                      <td><input name="noncore_cpoint" type="noncore_cpoint" id="noncore_cpoint" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Accessorials</td>
                      <td><input name="accessorials_apoint" type="accessorials_apoint" id="accessorials_apoint" value="1" size="1"></td>
                      <td><input name="accessorials_cpoint" type="accessorials_cpoint" id="accessorials_cpoint" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Message</td>
                      <td colspan="2"><label for="textfield2"></label>
                        <label for="textarea2"></label>
                        <textarea name="shipments_message" id="shipments_message" placeholder="Message to Drivers About Scores" cols="35" rows="5">Please see your "PHP daily,weekly,monthly," Stats</textarea></td>
                    </tr>
                    <tr>
                      <td>Notify</td>
                      <td colspan="2">
                        <input name="radio_shipment_score_notify" type="radio" id="radio_shipment_score_notify_yes" value="1" checked>Yes
						<input type="radio" name="radio_shipment_score_notify" id="radio_shipment_score_notify_no" value="0">No
                      </td>
                    </tr>
                    <tr>
                      <td>Export Type</td>
                      <td colspan="2"><label for="shipments_score_export_type"></label>
                        <select name="shipments_score_export_type" id="shipments_score_export_type">
                          <option>CSV</option>
                          <option>PDF</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Notify Via</td>
                     <td colspan="2"><label for="frequency_date">
                       <input name="shipments_notify_via_txt" type="checkbox" id="shipments_notify_via_txt" checked>
                       TXT
                       <input name="shipments_notify_via_email" type="checkbox" id="shipments_notify_via_email" checked>
                       Email
                       <input name="shipments_notify_via_task" type="checkbox" id="shipments_notify_via_task" checked>
                       Task
                       <input name="shipments_notify_via_project" type="checkbox" id="shipments_notify_via_project" checked>
                      Project</label></td>
                    </tr>
                    <tr>
                      <td>Freequency</td>
                      <td colspan="2"><input type="checkbox" name="sc_points_freequency_daily" id="sc_points_freequency_daily">
                      <label for="sc_points_freequency_daily">Daily
                        <input type="checkbox" name="sc_points_freequency_weekly" id="sc_points_freequency_weekly">
                      Weekly</label>
                      <input type="checkbox" name="sc_points_freequency_monthly" id="sc_points_freequency_monthly">
                      <label for="sc_points_freequency_monthly">Monthly</label></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><label for="shipments_points_freequency_select_days">Time to Send: <span style="padding: 5px">
                      <select name="shipment_points_time" id="shipment_points_time">
                        <option value="00:00" <?php if ($row['sc_points_freequency_time'] == '00:00') { echo " selected "; }?>>00:00</option>
                        <option value="01:00" <?php if ($row['sc_points_freequency_time'] == '01:00') { echo " selected "; }?>>01:00</option>
                        <option value="02:00" <?php if ($row['sc_points_freequency_time'] == '02:00') { echo " selected "; }?>>02:00</option>
                        <option value="03:00" <?php if ($row['sc_points_freequency_time'] == '03:00') { echo " selected "; }?>>03:00</option>
                        <option value="04:00" <?php if ($row['sc_points_freequency_time'] == '04:00') { echo " selected "; }?>>04:00</option>
                        <option value="05:00" <?php if ($row['sc_points_freequency_time'] == '05:00') { echo " selected "; }?>>05:00</option>
                        <option value="06:00" <?php if ($row['sc_points_freequency_time'] == '06:00') { echo " selected "; }?>>06:00</option>
                        <option value="07:00" <?php if ($row['sc_points_freequency_time'] == '07:00') { echo " selected "; }?>>07:00</option>
                        <option value="08:00" selected <?php if ($row['sc_points_freequency_time'] == '08:00') { echo " selected "; }?>>08:00</option>
                        <option value="09:00" <?php if ($row['sc_points_freequency_time'] == '09:00') { echo " selected "; }?>>09:00</option>
                        <option value="10:00" <?php if ($row['sc_points_freequency_time'] == '10:00') { echo " selected "; }?>>10:00</option>
                        <option value="11:00" <?php if ($row['sc_points_freequency_time'] == '11:00') { echo " selected "; }?>>11:00</option>
                        <option value="12:00" <?php if ($row['sc_points_freequency_time'] == '12:00') { echo " selected "; }?>>12:00</option>
                        <option value="13:00" <?php if ($row['sc_points_freequency_time'] == '13:00') { echo " selected "; }?>>13:00</option>
                        <option value="14:00" <?php if ($row['sc_points_freequency_time'] == '14:00') { echo " selected "; }?>>14:00</option>
                        <option value="15:00" <?php if ($row['sc_points_freequency_time'] == '15:00') { echo " selected "; }?>>15:00</option>
                        <option value="16:00" <?php if ($row['sc_points_freequency_time'] == '16:00') { echo " selected "; }?>>16:00</option>
                        <option value="17:00" <?php if ($row['sc_points_freequency_time'] == '17:00') { echo " selected "; }?>>17:00</option>
                        <option value="18:00" <?php if ($row['sc_points_freequency_time'] == '18:00') { echo " selected "; }?>>18:00</option>
                        <option value="19:00" <?php if ($row['sc_points_freequency_time'] == '19:00') { echo " selected "; }?>>19:00</option>
                        <option value="20:00" <?php if ($row['sc_points_freequency_time'] == '20:00') { echo " selected "; }?>>20:00</option>
                        <option value="21:00" <?php if ($row['sc_points_freequency_time'] == '21:00') { echo " selected "; }?>>21:00</option>
                        <option value="22:00" <?php if ($row['sc_points_freequency_time'] == '22:00') { echo " selected "; }?>>22:00</option>
                        <option value="23:00" <?php if ($row['sc_points_freequency_time'] == '23:00') { echo " selected "; }?>>23:00</option>
                      </select>
                      </span></label></td>
                    </tr>
                    <tr>
                      <td height="47">Select Days</td>
                      <td colspan="2"><input type="checkbox" name="sc_points_freequency_day_sunday" id="sc_points_freequency_day_sunday">
                      <label for="sc_points_freequency_day_sunday">Sunday
                        <input type="checkbox" name="sc_points_freequency_day_monday" id="sc_points_freequency_day_monday">
                      Monday
                      <input type="checkbox" name="sc_points_freequency_day_tuesday" id="sc_points_freequency_day_tuesday">
                      Tuesday
                      <input type="checkbox" name="sc_points_freequency_day_wednesday" id="sc_points_freequency_day_wednesday">
                      Wednesday
                      <input type="checkbox" name="sc_points_freequency_day_thursday" id="sc_points_freequency_day_thursday">
                      Thursday</label>
                      <input type="checkbox" name="sc_points_freequency_day_friday" id="sc_points_freequency_day_friday">
                      <label for="sc_points_freequency_day_friday">Friday</label>
                      <input type="checkbox" name="sc_points_freequency_day_saturday" id="sc_points_freequency_day_saturday">
                      <label for="sc_points_freequency_day_saturday">Saturday</label></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><input type="submit" name="Submit5" id="Submit5" value="Update DB"></td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                  </table>
                </div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->          
          


<!-- ====================box 2 in section 2========================== -->  

            <div class="col-md-3">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">VIR Calculations</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table width="371" border="0">
                    <tr>
                      <td>Info</td>
                      <td>A Point Multi</td>
                      <td width="127">C Point Weight</td>
                    </tr>
                    <tr>
                      <td width="116">Arrived Shipper</td>
                      <td><input name="arrived_shipper_apoint2" type="arrived_shipper_apoint" id="arrived_shipper_apoint2" value="1" size="1"></td>
                      <td><input name="arrived_shipper_cpoint2" type="arrived_shipper_cpoint" id="arrived_shipper_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Picked Up</td>
                      <td width="114"><input name="picked_up_apoint2" type="picked_up_apoint" id="picked_up_apoint2" value="1" size="1"></td>
                      <td><input name="picked_up_cpoint2" type="picked_up_cpoint" id="picked_up_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td width="116">Arrived Consignee</td>
                      <td><input name="arrived_consignee_apoint2" type="arrived_consignee_apoint" id="arrived_consignee_apoint2" value="1" size="1"></td>
                      <td><input name="arrived_consignee_cpoint2" type="arrived_consignee_cpoint" id="arrived_consignee_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Delivered</td>
                      <td><input name="delivered_apoint2" type="delivered_apoint" id="delivered_apoint2" value="1" size="1"></td>
                      <td><input name="delivered_cpoint2" type="delivered_cpoint" id="delivered_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td height="22">Non-Core Update</td>
                      <td><input name="noncore_update_apoint2" type="noncore_update_apoint" id="noncore_update_apoint2" value="1" size="1"></td>
                      <td><input name="noncore_cpoint2" type="noncore_cpoint" id="noncore_cpoint2" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Accessorials</td>
                      <td><input name="accessorials_apoint2" type="accessorials_apoint" id="accessorials_apoint2" value="1" size="1"></td>
                      <td><input name="accessorials_cpoint2" type="accessorials_cpoint" id="accessorials_cpoint2" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Message</td>
                      <td colspan="2"><label for="textfield3"></label>
                        <label for="textarea3"></label>
                        <textarea name="shipments_message2" id="shipments_message2" placeholder="Message to Drivers About Scores" cols="35" rows="5">Please see your "PHP daily,weekly,monthly," Stats</textarea></td>
                    </tr>
                    <tr>
                      <td>Notify</td>
                      <td colspan="2"><input name="vir" type="radio" id="radio_shipment_score_notify_yes2" value="radio_shipment_score_notify_yes" checked>
                        Yes
                        <input type="radio" name="vir" id="radio_shipment_score_notify_no2" value="radio_shipment_score_notify_no">
                        No </td>
                    </tr>
                    <tr>
                      <td>Export Type</td>
                      <td colspan="2"><label for="shipments_score_export_type"></label>
                        <select name="shipments_score_export_type2" id="shipments_score_export_type">
                          <option>CSV</option>
                          <option>PDF</option>
                        </select></td>
                    </tr>
                    <tr>
                      <td>Notify Via</td>
                      <td colspan="2"><label for="frequency_date">
                        <input name="shipments_notify_via_txt2" type="checkbox" id="shipments_notify_via_txt2" checked>
                        TXT
                        <input name="shipments_notify_via_email2" type="checkbox" id="shipments_notify_via_email2" checked>
                        Email
                        <input name="shipments_notify_via_task2" type="checkbox" id="shipments_notify_via_task2" checked>
                        Task
                        <input name="shipments_notify_via_project2" type="checkbox" id="shipments_notify_via_project2" checked>
                        Project</label></td>
                    </tr>
                    <tr>
                      <td>Freequency</td>
                      <td colspan="2"><select name="frequency_date2" id="frequency_date">
                        <option value="daily">daily</option>
                        <option value="weekly">weekly</option>
                        <option value="monthly" selected>monthly</option>
                        <option value="quarterly">quarterly</option>
                      </select>
                        Time:
                        <label for="sc_points_freequency_time"></label>
                        <span style="padding: 5px">
                          <select name="shipment_points_time2" id="shipment_points_time2">
                            <option value="00:00" <?php if ($row['sc_points_freequency_time'] == '00:00') { echo " selected "; }?>>00:00</option>
                            <option value="01:00" <?php if ($row['sc_points_freequency_time'] == '01:00') { echo " selected "; }?>>01:00</option>
                            <option value="02:00" <?php if ($row['sc_points_freequency_time'] == '02:00') { echo " selected "; }?>>02:00</option>
                            <option value="03:00" <?php if ($row['sc_points_freequency_time'] == '03:00') { echo " selected "; }?>>03:00</option>
                            <option value="04:00" <?php if ($row['sc_points_freequency_time'] == '04:00') { echo " selected "; }?>>04:00</option>
                            <option value="05:00" <?php if ($row['sc_points_freequency_time'] == '05:00') { echo " selected "; }?>>05:00</option>
                            <option value="06:00" <?php if ($row['sc_points_freequency_time'] == '06:00') { echo " selected "; }?>>06:00</option>
                            <option value="07:00" <?php if ($row['sc_points_freequency_time'] == '07:00') { echo " selected "; }?>>07:00</option>
                            <option value="08:00" selected <?php if ($row['sc_points_freequency_time'] == '08:00') { echo " selected "; }?>>08:00</option>
                            <option value="09:00" <?php if ($row['sc_points_freequency_time'] == '09:00') { echo " selected "; }?>>09:00</option>
                            <option value="10:00" <?php if ($row['sc_points_freequency_time'] == '10:00') { echo " selected "; }?>>10:00</option>
                            <option value="11:00" <?php if ($row['sc_points_freequency_time'] == '11:00') { echo " selected "; }?>>11:00</option>
                            <option value="12:00" <?php if ($row['sc_points_freequency_time'] == '12:00') { echo " selected "; }?>>12:00</option>
                            <option value="13:00" <?php if ($row['sc_points_freequency_time'] == '13:00') { echo " selected "; }?>>13:00</option>
                            <option value="14:00" <?php if ($row['sc_points_freequency_time'] == '14:00') { echo " selected "; }?>>14:00</option>
                            <option value="15:00" <?php if ($row['sc_points_freequency_time'] == '15:00') { echo " selected "; }?>>15:00</option>
                            <option value="16:00" <?php if ($row['sc_points_freequency_time'] == '16:00') { echo " selected "; }?>>16:00</option>
                            <option value="17:00" <?php if ($row['sc_points_freequency_time'] == '17:00') { echo " selected "; }?>>17:00</option>
                            <option value="18:00" <?php if ($row['sc_points_freequency_time'] == '18:00') { echo " selected "; }?>>18:00</option>
                            <option value="19:00" <?php if ($row['sc_points_freequency_time'] == '19:00') { echo " selected "; }?>>19:00</option>
                            <option value="20:00" <?php if ($row['sc_points_freequency_time'] == '20:00') { echo " selected "; }?>>20:00</option>
                            <option value="21:00" <?php if ($row['sc_points_freequency_time'] == '21:00') { echo " selected "; }?>>21:00</option>
                            <option value="22:00" <?php if ($row['sc_points_freequency_time'] == '22:00') { echo " selected "; }?>>22:00</option>
                            <option value="23:00" <?php if ($row['sc_points_freequency_time'] == '23:00') { echo " selected "; }?>>23:00</option>
                          </select>
                        </span></td>
                    </tr>
                    <tr>
                      <td>Select Days</td>
                      <td colspan="2"><label for="shipments_points_freequency_select_days"></label></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><select name="shipments_points_freequency_select_days2" id="shipments_points_freequency_select_days" multiple>
                        <option value="Sunday" selected>Sunday</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td><input type="submit" name="Submit" id="Submit" value="Update DB"></td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                  </table>
                </div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
<!-- ====================box 3 in section 2========================== -->              
            
            
            <div class="col-md-3">
              <div class="box box-navy">
                <div class="box-header with-border">
                  <h3 class="box-title">Productivity Calculations</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table width="308" border="0">
                    <tr>
                      <td width="233">Tasks</td>
                      <td width="65">34</td>
                    </tr>
                    <tr>
                      <td>Projects</td>
                      <td>33</td>
                    </tr>
                    <tr>
                      <td>Paperwork</td>
                      <td>33</td>
                    </tr>
                    <tr>
                      <td>Category Score</td>
                      <td>25</td>
                    </tr>
                  </table>
                  <input type="submit" name="Submit3" id="Submit3" value="Update DB">
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
            
<!-- ====================box 4 in section 2========================== -->            
            
            
            
            <div class="col-md-3">
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">CSA Calculations</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table width="308" border="0">
                    <tr>
                      <td width="233">CSA Score</td>
                      <td width="65">34</td>
                    </tr>
                    <tr>
                      <td>Company Compliance</td>
                      <td>33</td>
                    </tr>
                    <tr>
                      <td>Attendance</td>
                      <td>33</td>
                    </tr>
                    <tr>
                      <td>Category Score</td>
                      <td>25</td>
                    </tr>
                  </table>
                  <input type="submit" name="Submit4" id="Submit4" value="Update DB">
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->


<!-- ====================End Colapse Section 2 in section========================== -->  


          </div><!-- /.row -->


<!-- =========================================================== -->




<!-- ======================New Section Colored Boxes============ -->
          <!-- Boxes with Icon on Right side (Status box) -->
        <div class="row">
          <div class="col-lg-12 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <!-- =========================================================== -->
                  <center>
                    <h3>Combined Driver Category Score Must = 100% </h3></center>
                  <center>
                    <p>A Point = Actual Points for Driver updating on Dashboard C Point is the Percentage applied for that specific Category All C Points should add up to 100% for each category. Each Category will have a % assigned for the total Points to Equal the Drivers Score</p>
                    <p>Example: Shipment Boards Category has 5 different Items. So each item would be assigned a point per update. </p>
                  </center>
                </div>
                <div class="icon"> <i class="fa fa-cog fa-spin"></i> </div>
                <!--<a href="#" class="small-box-footer"> More info <i class="fa fa-arrow-circle-right"></i> </a> </div>-->
          </div>
          <!-- ./col --><!-- ./col --><!-- ./col --><!-- ./col -->
          </div>
          <!-- /.row -->
          </div>

<!-- =========================================================== -->























<h2 class="page-header">Security Testing (Removing Boxes)</h2>

<!-- =========================================================== -->
          <div class="row">
 <!-- ====================box 1 in section 2========================== -->            
          
            <div class="col-md-3">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Grey All Users</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                Security Setting Grey (non Admin)</div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->          
          


<!-- ====================box 2 in section 2========================== -->  

            <div class="col-md-3">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Green All Users</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">Security Setting Grey (non Admin)</div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
<!-- ====================box 3 in section 2========================== -->              
            
            
            <div class="col-md-3">
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Yellow Supervisor</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">Security Setting Yellow (Limited Admin)</div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
            
<!-- ====================box 4 in section 2========================== -->            
            
            
            
            <div class="col-md-3">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Red Admin</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">Security Setting Yellow (Full Admin Rights)</div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->


<!-- ====================End Colapse Section 2 in section========================== -->  


          </div><!-- /.row -->







<h2 class="page-header">Security Testing (Content In Boxes)</h2>

<!-- =========================================================== -->
          <div class="row">
 <!-- ====================box 1 in section 2========================== -->            
          
            <div class="col-md-3">
              <div class="box box-default">
                <div class="box-header with-border">
                  <h3 class="box-title">Grey All Users</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                  <p>Security Setting Grey (non Admin)</p>
                  <p>&nbsp;</p>
                </div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->          
          


<!-- ====================box 2 in section 2========================== -->  

            <div class="col-md-3">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Green All Users</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">Security Setting Grey (non Admin)</div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
<!-- ====================box 3 in section 2========================== -->              
            
            
            <div class="col-md-3">
              <div class="box box-warning">
                <div class="box-header with-border">
                  <h3 class="box-title">Yellow Supervisor</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">Security Setting Yellow (Limited Admin)</div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
            
<!-- ====================box 4 in section 2========================== -->            
            
            
            
            <div class="col-md-3">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Red Admin</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">Security Setting Yellow (Full Admin Rights)</div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->


<!-- ====================End Colapse Section 2 in section========================== -->  


          </div><!-- /.row -->


<!-- =========================================================== -->
           

</div><!-- /.row -->

<!-- ======================End Section 4===================================== -->

          <div class="row">
            <div class="col-xs-12">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li></li>
                </ul><!-- /.content-wrapper -->







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

<!-- Demo -->
</body>
</html>
