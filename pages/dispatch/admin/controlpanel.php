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
            Control Panel / <span class="box-title"><?php echo "$_SESSION[drivername]"; ?></span> <a href="#">
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

<?php
// This will echo Hello World 1 line comment out
/* Long Line Comment out 
For multiple lines
*/
echo $var = 'Hello World';
?>  



<!-- =========================================================== -->
          <div class="row">
 <!-- ====================box 1 in section 2========================== -->            
 
       
            <div class="col-md-3">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Shipments Calculations</h3>
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
                      <td>Actual Points</td>
                      <td width="127">Cat %</td>
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
                      <td>Category Header</td>
                      <td colspan="2"><label for="textfield2"></label>
                        <label for="textarea2">
                          <input type="text" class="" value="" name='cat_head_shipments' id='cat_head_shipments' placeholder="updates DB cp_virs" style="margin-top: 5px; width: 10em;"/>
                        </label></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><label for="shipments_score_export_type">
                        <input type="submit" name="Submit5" id="Submit5" value="Update DB">
                      </label></td>
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
                      <td>Actual Points</td>
                      <td width="127">Cat %</td>
                    </tr>
                    <tr>
                      <td width="116">Days Worked</td>
                      <td><input name="arrived_shipper_apoint2" type="arrived_shipper_apoint" id="arrived_shipper_apoint2" value="1" size="1"></td>
                      <td><input name="arrived_shipper_cpoint2" type="arrived_shipper_cpoint" id="arrived_shipper_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Pre-Trips</td>
                      <td width="114"><input name="picked_up_apoint2" type="picked_up_apoint" id="picked_up_apoint2" value="1" size="1"></td>
                      <td><input name="picked_up_cpoint2" type="picked_up_cpoint" id="picked_up_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td width="116">Post Trips</td>
                      <td><input name="arrived_consignee_apoint2" type="arrived_consignee_apoint" id="arrived_consignee_apoint2" value="1" size="1"></td>
                      <td><input name="arrived_consignee_cpoint2" type="arrived_consignee_cpoint" id="arrived_consignee_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Breakdowns</td>
                      <td><input name="delivered_apoint2" type="delivered_apoint" id="delivered_apoint2" value="1" size="1"></td>
                      <td><input name="delivered_cpoint2" type="delivered_cpoint" id="delivered_cpoint2" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td height="22">Empty Slot</td>
                      <td><input name="delivered_apoint5" type="delivered_apoint" id="delivered_apoint5" value="1" size="1"></td>
                      <td><input name="delivered_apoint8" type="delivered_apoint" id="delivered_apoint8" value="1" size="1"></td>
                    </tr>
                    <tr>
                      <td>Empty Slot</td>
                      <td><input name="delivered_apoint6" type="delivered_apoint" id="delivered_apoint6" value="1" size="1"></td>
                      <td><input name="delivered_apoint9" type="delivered_apoint" id="delivered_apoint9" value="1" size="1"></td>
                    </tr>
                    <tr>
                      <td>Category Header</td>
                      <td colspan="2"><label for="textfield3"></label>
                        <label for="textarea3"></label>
                        <input type="text" class="" value="" name='cat_head_shipments2' id='cat_head_shipments2' placeholder="updates DB cp_vir" style="margin-top: 5px; width: 10em;"/></td>
                    </tr>
                    <tr>
                      <td height="26">&nbsp;</td>
                      <td colspan="2"><label for="shipments_score_export_type"></label>                        <label for="shipments_points_freequency_select_days">
                        <input type="submit" name="Submit" id="Submit" value="Update DB">
                      </label></td>
                    </tr>
                  </table>
                </div>
                <!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            
            
<!-- ====================box 3 in section 2========================== -->              
            
            
            <div class="col-md-3">
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Productivity Calculations</h3>
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
                      <td>Actual Points</td>
                      <td width="127">Cat %</td>
                    </tr>
                    <tr>
                      <td width="116">Tasks</td>
                      <td><input name="arrived_shipper_apoint4" type="arrived_shipper_apoint" id="arrived_shipper_apoint4" value="1" size="1"></td>
                      <td><input name="arrived_shipper_cpoint4" type="arrived_shipper_cpoint" id="arrived_shipper_cpoint4" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Quiz's</td>
                      <td width="114"><input name="picked_up_apoint4" type="picked_up_apoint" id="picked_up_apoint4" value="1" size="1"></td>
                      <td><input name="picked_up_cpoint4" type="picked_up_cpoint" id="picked_up_cpoint4" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td width="116">Idle Time</td>
                      <td><input name="arrived_consignee_apoint4" type="arrived_consignee_apoint" id="arrived_consignee_apoint4" value="1" size="1"></td>
                      <td><input name="arrived_consignee_cpoint4" type="arrived_consignee_cpoint" id="arrived_consignee_cpoint4" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Compliance</td>
                      <td><input name="delivered_apoint4" type="delivered_apoint" id="delivered_apoint4" value="1" size="1"></td>
                      <td><input name="delivered_cpoint4" type="delivered_cpoint" id="delivered_cpoint4" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td height="22">Empty Slot</td>
                      <td><input name="noncore_update_apoint" type="noncore_update_apoint" id="noncore_update_apoint" value="1" size="1"></td>
                      <td><input name="noncore_cpoint4" type="noncore_cpoint" id="noncore_cpoint4" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Empty Slot</td>
                      <td><input name="accessorials_apoint4" type="accessorials_apoint" id="accessorials_apoint4" value="1" size="1"></td>
                      <td><input name="accessorials_cpoint4" type="accessorials_cpoint" id="accessorials_cpoint4" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Category Header</td>
                      <td colspan="2"><label for="textfield8"></label>
                        <label for="textarea8"></label>
                        <input type="text" class="" value="" name='cat_head_shipments3' id='cat_head_shipments3' placeholder="updates DB cp_productivity" style="margin-top: 5px; width: 10em;"/></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><label for="shipments_score_export_type6"></label>
                        <label for="shipments_points_freequency_select_days6">
                          <input type="submit" name="Submit8" id="Submit8" value="Update DB">
                        </label></td>
                    </tr>
                  </table>
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
                  <table width="371" border="0">
                    <tr>
                      <td>Info</td>
                      <td>Actual Points</td>
                      <td width="127">Cat %</td>
                    </tr>
                    <tr>
                      <td width="116">Tasks</td>
                      <td><input name="arrived_shipper_apoint5" type="arrived_shipper_apoint" id="arrived_shipper_apoint5" value="1" size="1"></td>
                      <td><input name="arrived_shipper_cpoint5" type="arrived_shipper_cpoint" id="arrived_shipper_cpoint5" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Quiz's</td>
                      <td width="114"><input name="picked_up_apoint5" type="picked_up_apoint" id="picked_up_apoint5" value="1" size="1"></td>
                      <td><input name="picked_up_cpoint5" type="picked_up_cpoint" id="picked_up_cpoint5" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td width="116">Idle Time</td>
                      <td><input name="arrived_consignee_apoint5" type="arrived_consignee_apoint" id="arrived_consignee_apoint5" value="1" size="1"></td>
                      <td><input name="arrived_consignee_cpoint5" type="arrived_consignee_cpoint" id="arrived_consignee_cpoint5" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td>Compliance</td>
                      <td><input name="delivered_apoint7" type="delivered_apoint" id="delivered_apoint7" value="1" size="1"></td>
                      <td><input name="delivered_cpoint5" type="delivered_cpoint" id="delivered_cpoint5" value="20" size="1"></td>
                    </tr>
                    <tr>
                      <td height="22">Empty Slot</td>
                      <td><input name="noncore_update_apoint2" type="noncore_update_apoint" id="noncore_update_apoint2" value="1" size="1"></td>
                      <td><input name="noncore_cpoint2" type="noncore_cpoint" id="noncore_cpoint2" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Empty Slot</td>
                      <td><input name="accessorials_apoint2" type="accessorials_apoint" id="accessorials_apoint2" value="1" size="1"></td>
                      <td><input name="accessorials_cpoint2" type="accessorials_cpoint" id="accessorials_cpoint2" value="10" size="1"></td>
                    </tr>
                    <tr>
                      <td>Category Header</td>
                      <td colspan="2"><label for="textfield4"></label>
                        <label for="textarea4"></label>
                        <input type="text" class="" value="" name='cat_head_shipments4' id='cat_head_shipments4' placeholder="updates DB cp_csa" style="margin-top: 5px; width: 10em;"/></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2"><label for="shipments_score_export_type2"></label>
                        <label for="shipments_points_freequency_select_days2">
                          <input type="submit" name="Submit3" id="Submit3" value="Update DB">
                        </label></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->


<!-- ====================End Colapse Section 2 in section========================== -->  


          </div><!-- /.row -->


<!-- =========================================================== -->

         <!-- Left Side Box 1 Start-->         
          <div class="row">
            <!-- Div Class-md-6 will give the seperation of columns...-->  <div class="col-md-12">
            <!--<div class="box box-default collapsed-box"> -->
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Notification Settings For Productivity Categories</h3>
                       <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                       </div>

                </div><!-- /.box-header -->
                <div class="box-body">
                  <table width="810" border="1">
                    <tr>
                      <td width="155">Info</td>
                      <td width="200">&nbsp;</td>
                      <td width="234">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Email Title</td>
                      <td colspan="2"><label for="textfield6"></label>
                        <label for="textarea6">
                          <input type="text" class="" value="" name='cat_head_shipments5' id='cat_head_shipments5' placeholder="Productivity Report" style="margin-top: 5px; width: 10em;"/>
                        </label></td>
                    </tr>
                    <tr>
                      <td rowspan="2">Email Message in body / Text Notification Message</td>
                      <td>Email Body Msg</td>
                      <td>Text Notification Msg</td>
                    </tr>
                    <tr>
                      <td><textarea name="shipments_message3" id="shipments_message3" placeholder="Message to Drivers About Scores" cols="35" rows="5">Please see below for  your "PHP daily,weekly,monthly," Stats</textarea></td>
                      <td><textarea name="shipments_message" id="shipments_message" placeholder="Message to Drivers About Scores" cols="35" rows="5">Productivity Report has been sent to your email Insert Mail sent to PHP code here</textarea></td>
                    </tr>
                    <tr>
                      <td>Text Notification</td>
                      <td colspan="2"><input name="vir" type="radio" id="radio_shipment_score_notify_yes3" value="radio_shipment_score_notify_yes" checked>
                        Yes
                        <input type="radio" name="vir" id="radio_shipment_score_notify_no3" value="radio_shipment_score_notify_no">
                        No </td>
                    </tr>
                    <tr>
                      <td>What Are We Sending to Users</td>
                      <td colspan="2"><input name="shipments_notify_via_txt" type="checkbox" id="shipments_notify_via_txt" checked>
Shipments
  <input name="shipments_notify_via_email" type="checkbox" id="shipments_notify_via_email" checked>
VIRS
<input name="shipments_notify_via_task" type="checkbox" id="shipments_notify_via_task" checked>
Productivity
<input name="shipments_notify_via_project" type="checkbox" id="shipments_notify_via_project" checked>
CSA 
<input name="shipments_notify_via_project2" type="checkbox" id="shipments_notify_via_project2" checked>
Company Stats</td>
                    </tr>
                    <tr>
                      <td>Which Users are we sending this to</td>
                      <td colspan="2"><select class="form-control"  value="" name="driver2" required style="width:150px;">
                        <option value="null">Select Employee</option>
                        <option value="all" selected>-All-</option>
                        <?php for ($i=0; $i<sizeof($all_users_array); $i++) { ?>
                        <option value=<?php echo $all_users_array[$i]['employee_id'];?> <?php if ($all_users_array[$i]['employee_id'] == $_GET['driver']) { echo " selected "; }?>> <?php echo $all_users_array[$i]['name'];?></option>
                        <?php } ?>
                      </select> 
                        When selecting All (only for Active Users)</td>
                    </tr>
                    <tr>
                      <td>Daily Frequency
                      <input name="shipments_notify_via_project4" type="checkbox" id="shipments_notify_via_project4"></td>
                      <td><select name="shipments_points_freequency_select_days" id="shipments_points_freequency_select_days4" multiple>
                        <option value="Sunday" selected>Sunday</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                      </select></td>
                      <td>Time to Send: <span style="padding: 5px">
                      <select name="shipment_points_time3" id="shipment_points_time3">
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
                      <td>Date Range Daily</td>
                      <td colspan="2"><select name="shipments_points_freequency_select_days3" id="shipments_points_freequency_select_days2" multiple>
                        <option value="Saturday" selected>Previous Day</option>
                        <option>Previous Week</option>
                        <option>Previous Month</option>
                        <option>Previous Quarter</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Weekly Frequency
                      <input name="shipments_notify_via_project5" type="checkbox" id="shipments_notify_via_project5"></td>
                      <td><select name="shipments_points_freequency_select_days2" id="shipments_points_freequency_select_days" multiple>
                        <option value="Sunday" selected>Sunday</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                      </select></td>
                      <td>Time to Send: <span style="padding: 5px">
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
                      </span></td>
                    </tr>
                    <tr>
                      <td>Date Range Weekly</td>
                      <td colspan="2"><select name="shipments_points_freequency_select_days4" id="shipments_points_freequency_select_days3" multiple>
                        <option value="Saturday" selected>Previous Day</option>
                        <option>Previous Week</option>
                        <option>Previous Month</option>
                        <option>Previous Quarter</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Monthly Frequency
                      <input name="shipments_notify_via_project6" type="checkbox" id="shipments_notify_via_project6"></td>
                      <td><input type="checkbox" name="sc_points_freequency_monthly" id="sc_points_freequency_monthly">
                        <label for="sc_points_freequency_monthly">1st Day
                          <input type="checkbox" name="sc_points_freequency_monthly3" id="sc_points_freequency_monthly3">
                      Last Day </label></td>
                      <td>Time to Send: <span style="padding: 5px">
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
                      <td>Date Range</td>
                      <td colspan="2"><select name="shipments_points_freequency_select_days5" id="shipments_points_freequency_select_days5" multiple>
                        <option value="Saturday" selected>Previous Day</option>
                        <option>Previous Week</option>
                        <option>Previous Month</option>
                        <option>Previous Quarter</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Quarter Frequency
                        <input name="shipments_notify_via_project3" type="checkbox" id="shipments_notify_via_project3"></td>
                      <td><select name="shipments_points_freequency_select_days7" id="shipments_points_freequency_select_days7" multiple>
                        <option value="Sunday" selected>Sunday</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                      </select></td>
                      <td>Time to Send: <span style="padding: 5px">
                      <select name="shipment_points_time4" id="shipment_points_time4">
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
                      <td>&nbsp;</td>
                      <td colspan="2"><select name="shipments_points_freequency_select_days6" id="shipments_points_freequency_select_days6" multiple>
                        <option value="Saturday" selected>Previous Day</option>
                        <option>Previous Week</option>
                        <option>Previous Month</option>
                        <option>Previous Quarter</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>Email Attachment + HTML</td>
                      <td colspan="2"><select name="shipments_score_export_type3" id="shipments_score_export_type4">
                        <option>CSV</option>
                        <option selected>PDF</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><input type="submit" name="Submit2" id="Submit2" value="Update DB">
Will Update new Options</td>
                      <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><input type="submit" name="Submit4" id="Submit4" value="Manual Export"></td>
                      <td colspan="2"><select class="form-control"  value="" name="driver" required style="width:150px;">
                        <option value="null">Select Employee</option>
                        <option value="all">-All-</option>
                        <?php for ($i=0; $i<sizeof($all_users_array); $i++) { ?>
                        <option value=<?php echo $all_users_array[$i]['employee_id'];?> <?php if ($all_users_array[$i]['employee_id'] == $_GET['driver']) { echo " selected "; }?>> <?php echo $all_users_array[$i]['name'];?></option>
                        <?php } ?>
                      </select>
                        <span class="input-daterange input-group">
                        <input type="text" class="input-sm form-control datepicker" name="start" id="dt_start" data-date-format="mm/dd/yyyy"/ required>
                        <span class="input-group-addon">to</span>
                        <input type="text" class="input-sm form-control datepicker" name="end" id="dt_end" data-date-format="mm/dd/yyyy"/ required>
                      </span></td>
                    </tr>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
             </div><!-- /.row --> 
			
<!-- Left Side Box 1 End--> 




<!-- ======================New Section Colored Boxes============ -->
          <!-- Boxes with Icon on Right side (Status box) -->
        <div class="row">
          <div class="col-lg-12 col-xs-6">
              <!-- small box -->
              <div class="small-box bg-green">
                <div class="inner">
                  <!-- =========================================================== -->
                  <center>
                    <h3>Alert Messages / Event Notifications </h3></center>
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
