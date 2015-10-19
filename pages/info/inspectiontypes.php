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

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
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

</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/header.php');?>
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/sidebar.php');?>
   
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper"> 
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>INSPECTION TYPES</h1>
        <ol class="breadcrumb">
          <li><a href="orders.php"><i class="fa fa-home"></i> Home</a></li>
          <li class="active">INSPECTION TYPES</li>
        </ol>
      </section>
      
      <!-- Main content -->
      <section class="content">
      <div class="row">
        <div class="col-md-8">
          <div class="box">
            <div class="box-header with-border">
              <h1>LEVEL I<br>
                North American Standard Inspection – An inspection that includes examination of driver’s license; medical examiner’s certificate and Skill Performance Evaluation (SPE) Certificate (if applicable); alcohol and drugs; driver’s record of duty status as required; hours of service; seat belt; vehicle inspection report(s) (if applicable); brake systems; coupling devices; exhaust systems; frames; fuel systems; lighting devices (headlamps, tail lamps, stop lamps, turn signals and lamps/flags on projecting loads); securement of cargo; steering mechanisms; suspensions; tires; van and open-top trailer bodies; wheels, rims and hubs; windshield wipers; emergency exits and/or electrical cables and systems in engine and battery compartments (buses), and HM/DG requirements as applicable. HM/DG required inspection items will be inspected by certified HM/DG inspectors.<br>
                <br>
                <br>
                Back to top<br>
                <br>
                <br>
                <br>
                <br>
                LEVEL II<br>
                Walk-Around Driver/Vehicle Inspection – An examination that includes each of the items specified under the North American Standard Level II Walk-Around Driver/Vehicle Inspection Procedure. As a minimum, Level II inspections must include examination of: driver’s license; medical examiner’s certificate and Skill Performance Evaluation (SPE) Certificate (if applicable); alcohol and drugs; driver’s record of duty status as required; hours of service; seat belt; vehicle inspection report(s) (if applicable); brake systems; coupling devices; exhaust systems; frames; fuel systems; lighting devices (headlamps, tail lamps, stop lamps, turn signals and lamps/flags on projecting loads); securement of cargo; steering mechanisms; suspensions; tires; van and open-top trailer bodies; wheels, rims and hubs; windshield wipers; emergency exits and/or electrical cables and systems in engine and battery compartments (buses), and HM/DG requirements as applicable. HM/DG required inspection items will be inspected by certified HM/DG inspectors. It is contemplated that the walk-around driver/vehicle inspection will include only those items, which can be inspected without physically getting under the vehicle.<br>
                <br>
                <br>
                Back to top<br>
                <br>
                <br>
                <br>
                <br>
                LEVEL III<br>
                Driver/Credential Inspection – An examination that includes those items specified under the North American Standard Level III Driver/Credential Inspection Procedure. As a minimum, Level III inspections must include, where required and/or applicable, examination of the driver’s license; medical examiner’s certificate and Skill Performance Evaluation (SPE) Certificate; driver’s record of duty status; hours of service; seat belt; vehicle inspection report(s); and HM/DG requirements. Those items not indicated in the North American Standard Level III Driver/Credential Inspection Procedure shall not be included on a Level III inspection.<br>
                <br>
                <br>
                Back to top<br>
                <br>
                <br>
                <br>
                <br>
                LEVEL IV<br>
                Special Inspections – Inspections under this heading typically include a one-time examination of a particular item. These examinations are normally made in support of a study or to verify or refute a suspected trend.<br>
                <br>
                <br>
                Back to top<br>
                <br>
                <br>
                <br>
                <br>
                LEVEL V<br>
                Vehicle-Only Inspection – An inspection that includes each of the vehicle inspection items specified under the North American Standard Inspection (Level I), without a driver present, conducted at any location.<br>
                <br>
                <br>
                Back to top<br>
                <br>
                <br>
                <br>
                <br>
                LEVEL VI<br>
                North American Standard Inspection for Transuranic Waste and Highway Route Controlled Quantities (HRCQ) of Radioactive Material – An inspection for select radiological shipments, which include inspection procedures, enhancements to the North American Standard Level I inspection, radiological requirements, and the North American Standard Out-of-Service Criteria for Transuranic Waste and Highway Route Controlled Quantities (HRCQ) of Radioactive Material.</h1>
              <h4 class="box-title"></h4>
              <div class="box-tools">
                <ul class="pagination pagination-sm no-margin pull-right">
                  <li>
                   <a href="orders.php?gather=pu">Page1</a></li>
                  <li>
                   <a href="orders.php?gather=del">Page2</a></li>
                </ul>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">


            <!-- PAGE CONTENT HERE -->

            <!-- END PAGE CONTENT HERE -->


            </div>
            <!-- /.box --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.col --> 
      </div>
      <!-- /.row --> 
  </section>
    </div>

  <!-- /.content --> 
</div>
<!-- /.content-wrapper -->
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/footer.php');?>

<!-- Control Sidebar -->
<?php require($_SERVER[DOCUMENT_ROOT].'/dist/menus_sidebars_elements/r_sidebar.php');?>
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

<!-- Demo -->
<script src="<?php echo HTTP;?>/dist/js/demo.js" type="text/javascript"></script>
</body>
</html>
